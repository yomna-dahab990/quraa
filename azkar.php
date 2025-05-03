<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/azkar_data.php';
require_once 'db_connect.php'; // جيبي ملف الاتصال بقاعدة البيانات

// تعيين ترميز المخرجات
header('Content-Type: text/html; charset=utf-8');

// تنظيم الأذكار حسب التصنيفات
$categorized_azkar = [];
foreach ($azkar_categories as $category) {
    foreach ($category['subcategories'] as $subcategory) {
        $subcategory_azkar = array_filter($azkar, function($zekr) use ($subcategory) {
            return $zekr['subcategory_id'] == $subcategory['id'];
        });
        if (!empty($subcategory_azkar)) {
            $categorized_azkar[$subcategory['id']] = [
                'name' => $subcategory['name_ar'],
                'icon' => $subcategory['icon'],
                'azkar' => array_values($subcategory_azkar),
                'category_name' => $category['name_ar']
            ];
        }
    }
}

// التحقق من المفضلة
$favorites = isset($_SESSION['favorites']) ? $_SESSION['favorites'] : [];

// لو اليوزر مش مسجل دخول، يروح لصفحة تسجيل الدخول
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

// جيبي user_id بتاع اليوزر المسجل دخول
$user_name = $_SESSION['user_name'];
$stmt = $pdo->prepare("SELECT id FROM users WHERE name = ?");
$stmt->execute([$user_name]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $user['id'];

// احسبي عدد الأذكار الكلي
$total_azkar = 0;
foreach ($categorized_azkar as $subcategory) {
    $total_azkar += count($subcategory['azkar']);
}

// لو اليوزر أكمل ذكر أو عمل Reset (هنستخدم AJAX عشان نحدّث التقدم بدون إعادة تحميل الصفحة)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['zekr_id'])) {
    $zekr_id = (int)$_POST['zekr_id'];
    
    // جيبي تقدم اليوزر النهاردة
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT progress, last_updated FROM user_progress WHERE user_id = ? AND activity_type = 'daily_prayers' AND DATE(last_updated) = ?");
    $stmt->execute([$user_id, $today]);
    $progress = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $current_progress = $progress ? (int)$progress['progress'] : 0;
    
    // احسبي النسبة لكل ذكر
    $progress_per_zikr = 100 / $total_azkar; // النسبة لكل ذكر
    
    if (isset($_POST['update_progress'])) {
        // لو اليوزر أكمل ذكر، زودي النسبة
        $new_progress = min($current_progress + $progress_per_zikr, 100);
        
        // لو السجل موجود، حدّثيه، لو مش موجود، أضيفيه
        if ($progress) {
            $stmt = $pdo->prepare("UPDATE user_progress SET progress = ?, last_updated = NOW() WHERE user_id = ? AND activity_type = 'daily_prayers' AND DATE(last_updated) = ?");
            $stmt->execute([$new_progress, $user_id, $today]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO user_progress (user_id, activity_type, progress) VALUES (?, 'daily_prayers', ?)");
            $stmt->execute([$user_id, $new_progress]);
        }
    } elseif (isset($_POST['reset_progress'])) {
        // لو اليوزر عمل Reset للذكر، قللي النسبة
        $new_progress = max($current_progress - $progress_per_zikr, 0);
        
        // حدّثي السجل في قاعدة البيانات
        if ($progress) {
            $stmt = $pdo->prepare("UPDATE user_progress SET progress = ?, last_updated = NOW() WHERE user_id = ? AND activity_type = 'daily_prayers' AND DATE(last_updated) = ?");
            $stmt->execute([$new_progress, $user_id, $today]);
        }
    }
    
    // إرجعي التقدم الجديد كـ JSON
    echo json_encode(['success' => true, 'new_progress' => $new_progress]);
    exit();
}

// جيبي تقدم اليوزر النهاردة عشان نعرضه في شريط التقدم
$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT progress, last_updated FROM user_progress WHERE user_id = ? AND activity_type = 'daily_prayers' AND DATE(last_updated) = ?");
$stmt->execute([$user_id, $today]);
$progress = $stmt->fetch(PDO::FETCH_ASSOC);
$current_progress = $progress ? (int)$progress['progress'] : 0;

// لو الساعة عدّت 12:00 صباحاً، نرجّع النسبة لـ 0
$last_updated = $progress ? $progress['last_updated'] : null;
if ($last_updated && date('Y-m-d', strtotime($last_updated)) != $today) {
    $current_progress = 0;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>الأذكار - <?php echo SITE_NAME; ?></title>
    <meta name="description" content="أذكار الصباح والمساء وأدعية متنوعة من السنة النبوية">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        :root {
            --primary-color: rgb(14, 95, 57);
            --secondary-color: rgb(16, 98, 59);
            --text-color: #333;
            --background-color: #f8f9fa;
            --border-color: #dee2e6;
            --hover-color: #19875420;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .section-title {
            text-align: center;
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 2rem;
            font-weight: 700;
        }

        .category-section {
            background: white;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .category-header {
            background: var(--primary-color);
            color: white;
            padding: 1.2rem 2rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .category-header:hover {
            background: var(--secondary-color);
        }

        .category-title {
            color: white;
            font-size: 1.8rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 700;
        }

        .category-content {
            display: none;
            padding: 2rem;
            background: white;
        }

        .category-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .category-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .reset-category {
            background: white;
            color: var(--primary-color);
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .reset-category:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        .toggle-icon {
            transition: transform 0.3s ease;
            font-size: 1.2rem;
        }

        .category-header.active .toggle-icon {
            transform: rotate(180deg);
        }

        .azkar-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .zekr-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            position: relative;
        }

        .zekr-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .zekr-text {
            font-size: 1.4rem;
            line-height: 1.8;
            margin: 1.5rem 0;
            color: var(--text-color);
            text-align: center;
            font-weight: 500;
        }

        .zekr-tashkeel {
            font-size: 1.1rem;
            color: #666;
            margin: 1rem 0;
            text-align: center;
            line-height: 1.8;
        }

        .zekr-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .zekr-source {
            color: #666;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .zekr-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0.6rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .action-btn:hover {
            background: var(--hover-color);
            color: var(--primary-color);
        }

        .zekr-fadl {
            background: var(--hover-color);
            padding: 1rem 1.2rem;
            border-radius: 10px;
            margin-top: 1rem;
            font-size: 1rem;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            line-height: 1.6;
        }

        .zekr-fadl i {
            color: var(--primary-color);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .counter {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }

        .counter-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
            min-width: 30px;
            text-align: center;
        }

        .counter-btn {
            background: var(--primary-color);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .counter-btn:hover {
            transform: scale(1.1);
            background: var(--secondary-color);
        }

        .counter-btn:disabled {
            background: var(--primary-color);
            opacity: 0.7;
            cursor: default;
            transform: none;
        }

        .completed {
            background: var(--hover-color);
            border: 1px solid var(--primary-color);
        }

        .completed .zekr-text {
            color: var(--primary-color);
        }

        .favorite-btn {
            background: none;
            border: none;
            color: #ddd;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 50%;
        }

        .favorite-btn:hover {
            color: #FFD700;
            transform: scale(1.1);
        }

        .favorite-btn.active {
            color: #FFD700;
            animation: star-pulse 0.3s ease;
        }

        @keyframes star-pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .reset-zekr-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 1rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .reset-zekr-btn:hover {
            color: #d32f2f;
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .azkar-list {
                grid-template-columns: 1fr;
            }

            .category-header {
                padding: 1rem;
            }

            .category-title {
                font-size: 1.4rem;
            }

            .zekr-text {
                font-size: 1.2rem;
            }

            .category-actions {
                gap: 0.5rem;
            }

            .reset-category {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
        }

        /* تحسين شكل الإشعارات */
        #toast-container > div {
            opacity: 1;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .toast-success {
            background-color: var(--primary-color) !important;
        }

        .toast-info {
            background-color: var(--secondary-color) !important;
        }

        .reset-btn {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.3s;
        }

        .reset-btn:hover {
            background-color: #d32f2f;
        }

        .reset-btn i {
            font-size: 12px;
        }

        .progress-bar {
            width: 100%;
            background-color: #ddd;
            border-radius: 5px;
            height: 20px;
            margin-bottom: 20px;
        }

        .progress-bar-fill {
            height: 100%;
            background-color: var(--primary-color);
            border-radius: 5px;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <div class="container">
            <h1 class="section-title">الأذكار</h1>
            
            <div class="progress-bar">
                <div class="progress-bar-fill" id="progress-bar-fill" style="width: <?php echo $current_progress; ?>%;"></div>
            </div>
            <p style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">
                تقدمك اليومي: <?php echo round($current_progress, 2); ?>% (<?php echo round($current_progress / (100 / $total_azkar)); ?> من <?php echo $total_azkar; ?> ذكر)
            </p>
            
            <?php foreach ($categorized_azkar as $subcategory_id => $data): ?>
                <section class="category-section" id="category-<?php echo $subcategory_id; ?>">
                    <div class="category-header" onclick="toggleCategory(<?php echo $subcategory_id; ?>)">
                        <h2 class="category-title">
                            <i class="fas <?php echo $data['icon']; ?>"></i>
                            <?php echo $data['name']; ?>
                            <small style="font-size: 0.7em; opacity: 0.9; margin-right: 1rem;">
                                (<?php echo count($data['azkar']); ?> ذكر)
                            </small>
                        </h2>
                        <div class="category-actions">
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </div>
                    </div>
                    
                    <div class="category-content" id="content-<?php echo $subcategory_id; ?>">
                        <div class="azkar-list">
                            <?php foreach ($data['azkar'] as $zekr): ?>
                                <div class="zekr-card" id="zekr-<?php echo $zekr['id']; ?>">
                                    <div class="zekr-actions">
                                        <div class="action-buttons">
                                            <button class="favorite-btn <?php echo in_array($zekr['id'], $favorites) ? 'active' : ''; ?>" 
                                                    onclick="toggleFavorite(<?php echo $zekr['id']; ?>)">
                                                <i class="fas fa-star"></i>
                                            </button>
                                            <button class="reset-zekr-btn" onclick="resetZekr(<?php echo $zekr['id']; ?>)" title="إعادة تعيين">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        </div>
                                        <div class="counter">
                                            <span class="counter-value">0</span>/<span class="counter-total"><?php echo $zekr['count']; ?></span>
                                            <button class="counter-btn" onclick="incrementCounter(this)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="zekr-text"><?php echo $zekr['text']; ?></div>
                                    <div class="zekr-tashkeel"><?php echo $zekr['text_with_tashkeel']; ?></div>

                                    <div class="zekr-info">
                                        <span class="zekr-source"><?php echo $zekr['source']; ?></span>
                                        <div class="zekr-actions">
                                            <button class="action-btn copy-btn" title="نسخ" data-text="<?php echo htmlspecialchars($zekr['text']); ?>">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button class="action-btn share-btn" title="مشاركة" data-text="<?php echo htmlspecialchars($zekr['text']); ?>">
                                                <i class="fas fa-share-alt"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <?php if (isset($zekr['fadl']) && $zekr['fadl']): ?>
                                        <div class="zekr-fadl">
                                            <i class="fas fa-info-circle"></i>
                                            <?php echo $zekr['fadl']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-left",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        function toggleCategory(categoryId) {
            console.log('Toggling category:', categoryId);
            const content = document.getElementById(`content-${categoryId}`);
            const header = document.querySelector(`#category-${categoryId} .category-header`);
            console.log('Content:', content, 'Header:', header);
            if (content && header) {
                if (content.style.display === 'block') {
                    content.style.display = 'none';
                    header.classList.remove('active');
                } else {
                    content.style.display = 'block';
                    header.classList.add('active');
                }
            } else {
                console.error('Element not found for category:', categoryId);
            }
        }

        function resetZekr(zekrId) {
            const zekrCard = document.getElementById(`zekr-${zekrId}`);
            const btn = zekrCard.querySelector('.counter-btn');
            const valueSpan = zekrCard.querySelector('.counter-value');
            
            // إعادة تعيين العداد في الواجهة
            localStorage.removeItem(`zekr_${zekrId}_completed`);
            valueSpan.textContent = '0';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus"></i>';
            zekrCard.classList.remove('completed');
            
            toastr.info('تم إعادة تعيين هذا الذكر');

            // إرسال طلب AJAX لتقليل التقدم في قاعدة البيانات
            $.ajax({
                url: 'azkar.php',
                method: 'POST',
                data: {
                    reset_progress: true,
                    zekr_id: zekrId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // تحديث شريط التقدم في الواجهة
                        const progressBarFill = document.getElementById('progress-bar-fill');
                        progressBarFill.style.width = response.new_progress + '%';
                        toastr.info('تم تحديث تقدمك بعد إعادة التعيين!');
                    } else {
                        toastr.error('فشل في تحديث التقدم');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('خطأ في طلب AJAX:', status, error);
                    toastr.error('حدث خطأ أثناء تحديث التقدم');
                }
            });
        }

        function toggleFavorite(zekrId) {
            const btn = document.querySelector(`#zekr-${zekrId} .favorite-btn`);
            btn.classList.toggle('active');
            
            if (btn.classList.contains('active')) {
                toastr.success('تم إضافة الذكر إلى المفضلة');
                const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                favorites.push(zekrId);
                localStorage.setItem('favorites', JSON.stringify(favorites));
            } else {
                toastr.info('تم إزالة الذكر من المفضلة');
                const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                const index = favorites.indexOf(zekrId);
                if (index > -1) {
                    favorites.splice(index, 1);
                }
                localStorage.setItem('favorites', JSON.stringify(favorites));
            }
        }

        function incrementCounter(btn) {
            const zekrItem = btn.closest('.zekr-card');
            const counter = btn.parentElement;
            const valueSpan = counter.querySelector('.counter-value');
            const totalSpan = counter.querySelector('.counter-total');
            let value = parseInt(valueSpan.textContent);
            const total = parseInt(totalSpan.textContent);
            
            if (value < total) {
                value++;
                valueSpan.textContent = value;
                
                if (value === total) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    zekrItem.classList.add('completed');
                    
                    const zekrText = zekrItem.querySelector('.zekr-text').textContent.substring(0, 50) + '...';
                    toastr.success(`أحسنت! لقد أكملت الذكر: ${zekrText}`);

                    const zekrId = zekrItem.id.replace('zekr-', '');
                    localStorage.setItem(`zekr_${zekrId}_completed`, 'true');

                    // إرسال طلب AJAX لتحديث التقدم في قاعدة البيانات
                    $.ajax({
                        url: 'azkar.php',
                        method: 'POST',
                        data: {
                            update_progress: true,
                            zekr_id: zekrId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                const progressBarFill = document.getElementById('progress-bar-fill');
                                progressBarFill.style.width = response.new_progress + '%';
                                toastr.info('تم تحديث تقدمك!');
                            } else {
                                toastr.error('فشل في تحديث التقدم');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('خطأ في طلب AJAX:', status, error);
                            toastr.error('حدث خطأ أثناء تحديث التقدم');
                        }
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            favorites.forEach(zekrId => {
                const btn = document.querySelector(`#zekr-${zekrId} .favorite-btn`);
                if (btn) {
                    btn.classList.add('active');
                }
            });
            
            document.querySelectorAll('.zekr-card').forEach(item => {
                const zekrId = item.id.replace('zekr-', '');
                if (localStorage.getItem(`zekr_${zekrId}_completed`) === 'true') {
                    const btn = item.querySelector('.counter-btn');
                    const valueSpan = item.querySelector('.counter-value');
                    const totalSpan = item.querySelector('.counter-total');
                    const total = parseInt(totalSpan.textContent);
                    
                    valueSpan.textContent = total;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    item.classList.add('completed');
                }
            });
        });

        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const text = this.dataset.text;
                navigator.clipboard.writeText(text).then(() => {
                    toastr.success('تم نسخ النص بنجاح');
                }).catch(() => {
                    toastr.error('حدث خطأ أثناء نسخ النص');
                });
            });
        });

        document.querySelectorAll('.share-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const text = this.dataset.text;
                if (navigator.share) {
                    navigator.share({
                        text: text
                    }).then(() => {
                        toastr.success('تمت المشاركة بنجاح');
                    }).catch(() => {
                        toastr.error('حدث خطأ أثناء المشاركة');
                    });
                } else {
                    navigator.clipboard.writeText(text).then(() => {
                        toastr.success('تم نسخ النص للمشاركة');
                    }).catch(() => {
                        toastr.error('حدث خطأ أثناء نسخ النص');
                    });
                }
            });
        });
    </script>
</body>
</html>