<?php
session_start(); // بدء الجلسة عشان نقدر نستخدم الـ sessions

// تكوين الموقع
$config = [
    'site_name' => 'موقع قراء',
    'site_description' => 'موقع قراء للقرآن الكريم، تلاوات، تفسير، وأذكار',
    'base_url' => 'http://localhost/quraa'
];

// التحقق من وجود اليوزر
$user_logged_in = isset($_SESSION['user_name']); // لو اليوزر سجل دخول، هيكون عندنا اسمه في الجلسة
$user_name = $user_logged_in ? $_SESSION['user_name'] : 'زائر'; // لو مش مسجل، هنقوله زائر

// التأكد من تحميل الملفات المطلوبة
$required_files = [
    'css/style.css',
    'stylehome.css',
    'css/quran.css',
    'js/main.js',
    'quran.json'
];

foreach ($required_files as $file) {
    if (!file_exists($file)) {
        die("Error: Required file $file not found!");
    }
}

// جيبي user_id بتاع اليوزر المسجل دخول
require_once 'db_connect.php'; // جيبي ملف الاتصال بقاعدة البيانات
$user_id = null;
if ($user_logged_in) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE name = ?");
    $stmt->execute([$user_name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $user_id = $user['id'];
    } else {
        // لو اليوزر مش موجود في قاعدة البيانات، يبقى فيه مشكلة في الجلسة
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// عرّفي المتغير $progress بشكل افتراضي
$progress = [
    'daily_prayers' => 0,
    'daily_10_minutes' => 0
];

// لو اليوزر مسجل دخول، جيبي تقدمه من قاعدة البيانات
if ($user_id) {
    $stmt = $pdo->prepare("SELECT activity_type, progress FROM user_progress WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($result as $row) {
        $progress[$row['activity_type']] = (int)$row['progress'];
    }
}

// حددي نوع المستوى بناءً على النسبة لكل نشاط
function getLevelType($percentage) {
    if ($percentage >= 75) {
        return "ممتاز";
    } elseif ($percentage >= 50) {
        return "متوسط";
    } elseif ($percentage > 0) {
        return "مبتدئ";
    } else {
        return "لم تبدأ بعد";
    }
}

$daily_10_minutes_level = getLevelType($progress['daily_10_minutes']);
$daily_prayers_level = getLevelType($progress['daily_prayers']);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['site_name']; ?></title>
    <meta name="description" content="<?php echo $config['site_description']; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="stylehome.css">
    <style>
        /* أنماط جديدة للكروت بس، من غير ما نغير أي حاجة في التصميم العام */
        .activity-list {
            display: flex;
            flex-wrap: wrap;
            gap: 25px; /* زيّدت المسافة بين الكروت */
            justify-content: center;
        }

        .activity-item {
            background: linear-gradient(135deg, #ffffff, #f0f8f5); /* خلفية متدرجة */
            border-radius: 15px; /* حدود دائرية أكتر */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); /* ظل أقوى */
            width: 300px;
            text-align: center;
            padding: 25px; /* زيّدت المسافات الداخلية */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* انتقال سلس */
            border: 1px solid #e0e0e0; /* حدود خفيفة */
        }

        .activity-item:hover {
            transform: translateY(-8px); /* الكارت يترفع لفوق لما نحط المؤشر */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); /* ظل أقوى */
        }

        .activity-item a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .activity-item i {
            font-size: 45px; /* كبّرت الأيقونة */
            color: #0e5f39; /* لون الأيقونة */
            margin-bottom: 20px;
            transition: transform 0.3s ease; /* انتقال سلس للأيقونة */
        }

        .activity-item:hover i {
            transform: scale(1.1); /* الأيقونة تكبر شوية لما نحط المؤشر */
        }

        .activity-item h3 {
            font-size: 22px; /* كبّرت حجم العنوان */
            margin-bottom: 12px;
            color: #333;
            font-weight: 700; /* وزن أثقل للعنوان */
        }

        .activity-item p {
            font-size: 16px; /* كبّرت حجم الوصف */
            color: #666;
            margin-bottom: 18px;
            font-weight: 500;
        }

        .activity-level {
            font-size: 15px;
            color: #0e5f39;
            font-weight: bold;
            display: block; /* عشان ياخد سطر لوحده */
        }

        .level-type {
            font-size: 14px;
            color: #888;
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <!-- الهيدر المعدل -->
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <i class="fas fa-quran"></i>
                    <span>قراء</span>
                </a>
                
                <button class="mobile-menu-toggle" aria-label="قائمة الموقع">
                    <i class="fas fa-bars"></i>
                </button>
                
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php" class="active">الرئيسية</a></li>
                        <li><a href="quran.php">المصحف</a></li>
                        <li><a href="azkar.php">الأذكار</a></li>
                        <li><a href="tafseer.php">التفسير</a></li>
                        <li><a href="recitations.html">القراء</a></li>
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <button class="search-btn" aria-label="بحث">
                        <i class="fas fa-search"></i>
                    </button>
                    <?php if ($user_logged_in) { ?>
                        <a href="logout.php" class="user-btn" aria-label="تسجيل الخروج">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    <?php } else { ?>
                        <a href="login.php" class="user-btn" aria-label="حساب المستخدم">
                            <i class="fas fa-user"></i>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </header>

    <!-- القسم الرئيسي -->
    <main>
        <!-- القسم 1: مقدمة الموقع -->
        <section class="intro-section">
            <div class="container">
                <div class="intro-content">
                    <h1>مرحباً بكم في موقع قراء</h1>
                    <p class="lead">منصة متكاملة لخدمة القرآن الكريم وعلومه</p>
                    <div class="intro-text">
                        <p>يقدم موقع قراء خدمات متعددة لكتاب الله تعالى بدءاً من المصحف الإلكتروني مع إمكانية البحث، وحتى التلاوات المسجلة لأشهر القراء، بالإضافة إلى تفسير الآيات والأذكار اليومية.</p>
                        <p>نسعى لتقديم تجربة مميزة وميسرة للتعامل مع القرآن الكريم وتعلمه.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 2: الترحيب ونشاط اليوزر -->
        <?php if ($user_logged_in) { ?>
            <section class="user-activity-section">
                <div class="container">
                    <div class="user-welcome">
                        <h2>مرحباً، <?php echo htmlspecialchars($user_name); ?>!</h2>
                        <p>إزيك النهاردة؟ اتفرجي على تقدمك في القرآن والأذكار</p>
                    </div>
                    <div class="activity-list">
                        <!-- كارت اقرأ 10 دقايق كل يوم -->
                        <div class="activity-item">
                            <a href="daily_10_minutes.php">
                                <i class="fas fa-clock"></i>
                                <h3>اقرأ 10 دقايق كل يوم</h3>
                                <p>هدف سهل وبسيط عشان تبدأي</p>
                                <span class="activity-level">مستواك: <?php echo $progress['daily_10_minutes']; ?>%</span>
                                <span class="level-type">نوع المستوى: <?php echo $daily_10_minutes_level; ?></span>
                            </a>
                        </div>

                        <!-- كارت نشاط الأذكار (مع أيقونة إيد بتدعي) -->
                        <div class="activity-item">
                            <a href="azkar.php">
                                <i class="fas fa-hands-praying"></i>
                                <h3>نشاط الأذكار</h3>
                                <p>تابعي تقدمك في الأذكار اليومية</p>
                                <span class="activity-level">مستواك: <?php echo $progress['daily_prayers']; ?>%</span>
                                <span class="level-type">نوع المستوى: <?php echo $daily_prayers_level; ?></span>
                            </a>
                        </div>

                        <!-- كارت إضافة ختمة جديدة -->
                        <div class="activity-item">
                            <a href="new_khatma.php">
                                <i class="fas fa-book"></i>
                                <h3>إضافة ختمة جديدة</h3>
                                <p>ابدأي ختمة جديدة أو تابعي ختماتك السابقة</p>
                            </a>
                        </div>
                    </div>
                    <!-- حذفت زر "التالي" من هنا -->
                </div>
            </section>
        <?php } else { ?>
            <section class="user-activity-section">
                <div class="container">
                    <div class="user-welcome">
                        <h2>مرحباً، زائر!</h2>
                        <p>سجلي دخول عشان تشوفي تقدمك في القرآن والأذكار</p>
                        <a href="login.php" class="login-btn">سجلي دخول دلوقتي</a>
                        <a href="signup.php" class="login-btn" style="background-color: #2c3e50; margin-top: 10px;">إنشاء حساب جديد</a>
                    </div>
                </div>
            </section>
        <?php } ?>
    </main>

    <!-- الفوتر -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section about-section">
                    <h3>عن موقع قراء</h3>
                    <p>موقع إسلامي متخصص في خدمة القرآن الكريم وعلومه، يقدم المصحف الإلكتروني، التلاوات، التفاسير، والأذكار اليومية.</p>
                    <div class="social-links">
                        <a href="#" aria-label="فيسبوك"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="تويتر"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="يوتيوب"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section links-section">
                    <h3>روابط سريعة</h3>
                    <ul>
                        <li><a href="index.php">الرئيسية</a></li>
                        <li><a href="quran.php">المصحف الشريف</a></li>
                        <li><a href="azkar.php">الأذكار اليومية</a></li>
                        <li><a href="tafseer.php">تفسير القرآن</a></li>
                    </ul>
                </div>
                <div class="footer-section contact-section">
                    <h3>تواصل معنا</h3>
                    <ul>
                        <li><i class="fas fa-envelope"></i> info@quraa.com</li>
                        <li><i class="fas fa-phone"></i> +20123456789</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© <?php echo date('Y'); ?> موقع قراء - جميع الحقوق محفوظة</p>
            </div>
        </div>
    </footer>

    <script defer src="java.js"></script>
    <script defer src="js/main.js"></script>
    <script>
        // تحديث سنة حقوق النشر (لو عايزاه بالـ JavaScript)
        document.getElementById('current-year').textContent = new Date().getFullYear();

        // JavaScript لتفعيل زر القائمة في الموبايل
        document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
            document.querySelector('.main-nav').classList.toggle('active');
        });
    </script>
</body>
</html>