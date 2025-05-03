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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // جمع البيانات من الفورم
        $name = $_POST['khatma_name'];
        $status = $_POST['status'];
        $start_date = $_POST['start_date'];
        $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
        $surah_id = !empty($_POST['surah_id']) ? (int)$_POST['surah_id'] : null;
        $user_id = $_SESSION['user_id']; // جلب user_id من السيشن

        // إعداد الـ query باستخدام prepared statement
        $query = "INSERT INTO khatmat (user_id, name, status, start_date, end_date, surah_id)
                  VALUES (:user_id, :name, :status, :start_date, :end_date, :surah_id)";
        $stmt = $pdo->prepare($query);

        // ربط القيم
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $end_date, $end_date ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindParam(':surah_id', $surah_id, $surah_id ? PDO::PARAM_INT : PDO::PARAM_NULL);

        // تنفيذ الـ query
        if ($stmt->execute()) {
            // توجيه لصفحة الختمات السابقة بعد الإضافة
            header("Location: previous_khatma.php?success=تم إضافة الختمة بنجاح");
            exit();
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">خطأ: ' . $e->getMessage() . '</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة ختمة جديدة - قراء</title>
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
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: rgb(14, 95, 57);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        button {
            background: rgb(14, 95, 57);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: rgb(16, 98, 59);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>إضافة ختمة جديدة</h1>
        <form method="POST">
            <div class="form-group">
                <label>اسم الختمة</label>
                <input type="text" name="khatma_name" required>
            </div>
            <div class="form-group">
                <label>نوع الختمة</label>
                <select name="status" required>
                    <option value="current">ختمة حالية</option>
                    <option value="public">ختمة عامة</option>
                </select>
            </div>
            <div class="form-group">
                <label>تاريخ البداية</label>
                <input type="date" name="start_date" required>
            </div>
            <div class="form-group">
                <label>تاريخ النهاية (اختياري)</label>
                <input type="date" name="end_date">
            </div>
            <div class="form-group">
                <label>اختر السورة (اختياري)</label>
                <select name="surah_id">
                    <option value="">اختر سورة</option>
                    <?php foreach ($surahs as $id => $name): ?>
                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">إضافة الختمة</button>
        </form>
    </div>
</body>
</html>