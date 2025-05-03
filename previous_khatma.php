<?php
session_start();
require_once 'db_connect.php'; // المسار زي ما هو

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

// مصفوفة بأسماء الـ 114 سورة
$surahs = [
    1 => 'الفاتحة', 2 => 'البقرة', 3 => 'آل عمران', 4 => 'النساء', 5 => 'المائدة',
    6 => 'الأنعام', 7 => 'الأعراف', 8 => 'الأنفال', 9 => 'التوبة', 10 => 'يونس',
    11 => 'هود', 12 => 'يوسف', 13 => 'الرعد', 14 => 'إبراهيم', 15 => 'الحجر',
    16 => 'النحل', 17 => 'الإسراء', 18 => 'الكهف', 19 => 'مريم', 20 => 'طه',
    21 => 'الأنبياء', 22 => 'الحج', 23 => 'المؤمنون', 24 => 'النور', 25 => 'الفرقان',
    26 => 'الشعراء', 27 => 'النمل', 28 => 'القصص', 29 => 'العنكبوت', 30 => 'الروم',
    31 => 'لقمان', 32 => 'السجدة', 33 => 'الأحزاب', 34 => 'سبأ', 35 => 'فاطر',
    36 => 'يس', 37 => 'الصافات', 38 => 'ص', 39 => 'الزمر', 40 => 'غافر',
    41 => 'فصلت', 42 => 'الشورى', 43 => 'الزخرف', 44 => 'الدخان', 45 => 'الجاثية',
    46 => 'الأحقاف', 47 => 'محمد', 48 => 'الفتح', 49 => 'الحجرات', 50 => 'ق',
    51 => 'الذاريات', 52 => 'الطور', 53 => 'النجم', 54 => 'القمر', 55 => 'الرحمن',
    56 => 'الواقعة', 57 => 'الحديد', 58 => 'المجادلة', 59 => 'الحشر', 60 => 'الممتحنة',
    61 => 'الصف', 62 => 'الجمعة', 63 => 'المنافقون', 64 => 'التغابن', 65 => 'الطلاق',
    66 => 'التحريم', 67 => 'الملك', 68 => 'القلم', 69 => 'الحاقة', 70 => 'المعارج',
    71 => 'نوح', 72 => 'الجن', 73 => 'المزمل', 74 => 'المدثر', 75 => 'القيامة',
    76 => 'الإنسان', 77 => 'المرسلات', 78 => 'النبأ', 79 => 'النازعات', 80 => 'عبس',
    81 => 'التكوير', 82 => 'الانفطار', 83 => 'المطففين', 84 => 'الانشقاق', 85 => 'البروج',
    86 => 'الطارق', 87 => 'الأعلى', 88 => 'الغاشية', 89 => 'الفجر', 90 => 'البلد',
    91 => 'الشمس', 92 => 'الليل', 93 => 'الضحى', 94 => 'الشرح', 95 => 'التين',
    96 => 'العلق', 97 => 'القدر', 98 => 'البينة', 99 => 'الزلزلة', 100 => 'العاديات',
    101 => 'القارعة', 102 => 'التكاثر', 103 => 'العصر', 104 => 'الهمزة', 105 => 'الفيل',
    106 => 'قريش', 107 => 'الماعون', 108 => 'الكوثر', 109 => 'الكافرون', 110 => 'النصر',
    111 => 'المسد', 112 => 'الإخلاص', 113 => 'الفلق', 114 => 'الناس'
];

try {
    // جلب الختمات الخاصة بالمستخدم الحالي
    $user_id = $_SESSION['user_id']; // افتراض إن user_id مخزن في السيشن
    $query = "SELECT id, name, status, start_date, end_date, surah_id 
              FROM khatmat 
              WHERE user_id = :user_id 
              ORDER BY start_date DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $khatmat = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<p style="color: red;">خطأ: ' . $e->getMessage() . '</p>';
    $khatmat = [];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الختمات السابقة - قراء</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: rgb(14, 95, 57);
        }
        .khatma-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .khatma-item {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .khatma-item a {
            display: inline-block;
            margin-top: 10px;
            background: rgb(14, 95, 57);
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
        }
        .khatma-item a:hover {
            background: rgb(16, 98, 59);
        }
        .no-khatmat {
            text-align: center;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>الختمات السابقة</h1>
        <?php if (isset($_GET['success'])): ?>
            <p style="color: green; text-align: center;"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php endif; ?>
        <div class="khatma-list">
            <?php if (empty($khatmat)): ?>
                <p class="no-khatmat">لا توجد ختمات سابقة حاليًا</p>
            <?php else: ?>
                <?php foreach ($khatmat as $khatma): ?>
                    <div class="khatma-item">
                        <h3><?php echo htmlspecialchars($khatma['name']); ?></h3>
                        <p>الحالة: <?php echo $khatma['status'] === 'current' ? 'حالية' : 'عامة'; ?></p>
                        <p>تاريخ البداية: <?php echo htmlspecialchars($khatma['start_date']); ?></p>
                        <p>تاريخ النهاية: <?php echo $khatma['end_date'] ? htmlspecialchars($khatma['end_date']) : 'غير محدد'; ?></p>
                        <p>السورة: <?php echo $khatma['surah_id'] && isset($surahs[$khatma['surah_id']]) ? htmlspecialchars($surahs[$khatma['surah_id']]) : 'غير محدد'; ?></p>
                        <?php if ($khatma['surah_id']): ?>
                            <a href="quran.php?surah=<?php echo $khatma['surah_id']; ?>" class="read-btn">اقرأ السورة</a>
                        <?php else: ?>
                            <a href="quran.php" class="read-btn">اقرأ السورة</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <p style="text-align: center; margin-top: 20px;">
            <a href="start_new_khatma.php" style="background: rgb(14, 95, 57); color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px;">إضافة ختمة جديدة</a>
            <a href="new_khatma.php" style="background: rgb(14, 95, 57); color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; margin-right: 10px;">العودة لإدارة الختمات</a>
        </p>
    </div>
</body>
</html>