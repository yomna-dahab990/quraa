<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/azkar_data.php';

// التأكد من تسجيل دخول المستخدم
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// الحصول على المفضلة
$stmt = $mysqli->prepare("
    SELECT f.*, a.text, a.text_with_tashkeel, a.source, a.count, a.fadl, a.category_id
    FROM favorites f
    JOIN azkar a ON f.item_id = a.id
    WHERE f.user_id = ? AND f.type = 'azkar'
    ORDER BY f.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$favorites = $result->fetch_all(MYSQLI_ASSOC);

// تجميع الأذكار حسب التصنيف
$categorized_favorites = [];
foreach ($favorites as $favorite) {
    $category_id = $favorite['category_id'];
    if (!isset($categorized_favorites[$category_id])) {
        $categorized_favorites[$category_id] = [];
    }
    $categorized_favorites[$category_id][] = $favorite;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المفضلة - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/sections.css">
    <style>
        .favorites-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .category-section {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .category-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .category-header h2 {
            margin: 0;
            font-size: 1.8rem;
        }

        .favorite-item {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .favorite-item:last-child {
            border-bottom: none;
        }

        .favorite-text {
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .favorite-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .favorite-source {
            font-style: italic;
        }

        .favorite-count {
            background-color: var(--primary-color);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
        }

        .favorite-fadl {
            margin-top: 0.5rem;
            color: var(--text-muted);
            font-style: italic;
        }

        .remove-favorite {
            color: var(--danger-color);
            cursor: pointer;
            border: none;
            background: none;
            padding: 0.5rem;
            transition: color 0.3s ease;
        }

        .remove-favorite:hover {
            color: var(--danger-color-dark);
        }

        .empty-message {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <div class="favorites-container">
            <h1 class="section-title">الأذكار المفضلة</h1>

            <?php if (empty($favorites)): ?>
            <div class="empty-message">
                <i class="fas fa-heart" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                <p>لا توجد أذكار في المفضلة</p>
                <a href="azkar.php" class="btn">تصفح الأذكار</a>
            </div>
            <?php else: ?>
                <?php foreach ($categorized_favorites as $category_id => $items): ?>
                    <div class="category-section">
                        <div class="category-header">
                            <h2>
                                <?php 
                                foreach ($azkar_categories as $category) {
                                    if ($category['id'] == $category_id) {
                                        echo '<i class="fas ' . $category['icon'] . '"></i> ' . $category['name_ar'];
                                        break;
                                    }
                                }
                                ?>
                            </h2>
                        </div>
                        <?php foreach ($items as $item): ?>
                            <div class="favorite-item">
                                <div class="favorite-text"><?php echo $item['text_with_tashkeel']; ?></div>
                                <div class="favorite-meta">
                                    <span class="favorite-source"><?php echo $item['source']; ?></span>
                                    <span class="favorite-count">
                                        <?php echo $item['count']; ?> <?php echo $item['count'] > 1 ? 'مرات' : 'مرة'; ?>
                                    </span>
                                </div>
                                <div class="favorite-fadl"><?php echo $item['fadl']; ?></div>
                                <button class="remove-favorite" data-id="<?php echo $item['item_id']; ?>" data-type="azkar">
                                    <i class="fas fa-trash"></i> إزالة من المفضلة
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
    document.querySelectorAll('.remove-favorite').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const type = this.dataset.type;
            const item = this.closest('.favorite-item');

            fetch('ajax/toggle_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `item_id=${id}&type=${type}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.remove();
                        // إذا كان هذا آخر عنصر في التصنيف، نزيل القسم بأكمله
                        const categorySection = item.closest('.category-section');
                        if (!categorySection.querySelector('.favorite-item')) {
                            categorySection.remove();
                        }
                        // إذا لم تعد هناك أي أقسام، نعرض رسالة "لا توجد أذكار"
                        if (!document.querySelector('.category-section')) {
                            const container = document.querySelector('.favorites-container');
                            container.innerHTML = `
                                <h1 class="section-title">الأذكار المفضلة</h1>
                                <div class="empty-message">
                                    <i class="fas fa-heart" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                    <p>لا توجد أذكار في المفضلة</p>
                                    <a href="azkar.php" class="btn">تصفح الأذكار</a>
                                </div>
                            `;
                        }
                    }, 300);
                }
            });
        });
    });
    </script>
</body>
</html> 