<?php
// إعدادات الموقع
define('SITE_NAME', 'موقع قراء');
define('SITE_DESCRIPTION', 'موقع قراء للقرآن الكريم، تلاوات، تفسير، وأذكار');
define('BASE_URL', 'http://localhost/quraa');

// إعدادات قاعدة البيانات
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'quraa_db');

// إنشاء اتصال بقاعدة البيانات
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS);

// التحقق من وجود أخطاء في الاتصال
if ($mysqli->connect_error) {
    die('خطأ في الاتصال بقاعدة البيانات: ' . $mysqli->connect_error);
}

// تعيين ترميز الاتصال
$mysqli->set_charset("utf8mb4");

// إنشاء قاعدة البيانات إذا لم تكن موجودة
$mysqli->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// اختيار قاعدة البيانات
$mysqli->select_db(DB_NAME);

/**
 * إضافة/إزالة من المفضلة
 */
function toggleFavorite($user_id, $item_id, $type) {
    global $mysqli;
    
    // التحقق من وجود العنصر في المفضلة
    $stmt = $mysqli->prepare("SELECT id FROM favorites WHERE user_id = ? AND item_id = ? AND type = ?");
    $stmt->bind_param("iis", $user_id, $item_id, $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $favorite = $result->fetch_assoc();
    
    if ($favorite) {
        // إزالة من المفضلة
        $stmt = $mysqli->prepare("DELETE FROM favorites WHERE id = ?");
        $stmt->bind_param("i", $favorite['id']);
        return $stmt->execute();
    } else {
        // إضافة إلى المفضلة
        $stmt = $mysqli->prepare("INSERT INTO favorites (user_id, item_id, type) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $item_id, $type);
        return $stmt->execute();
    }
}

/**
 * التحقق من وجود العنصر في المفضلة
 */
function isItemFavorited($user_id, $item_id, $type) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT id FROM favorites WHERE user_id = ? AND item_id = ? AND type = ?");
    $stmt->bind_param("iis", $user_id, $item_id, $type);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc() ? true : false;
}

/**
 * التحقق من تسجيل دخول المستخدم
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * الحصول على معلومات المستخدم الحالي
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * تنسيق التاريخ بالعربية
 */
function formatDateArabic($date) {
    $months = [
        'January' => 'يناير',
        'February' => 'فبراير',
        'March' => 'مارس',
        'April' => 'أبريل',
        'May' => 'مايو',
        'June' => 'يونيو',
        'July' => 'يوليو',
        'August' => 'أغسطس',
        'September' => 'سبتمبر',
        'October' => 'أكتوبر',
        'November' => 'نوفمبر',
        'December' => 'ديسمبر'
    ];
    
    $date = date('F d, Y', strtotime($date));
    foreach ($months as $en => $ar) {
        $date = str_replace($en, $ar, $date);
    }
    
    return $date;
}

// دالة للحصول على سور القرآن
function getQuranSurahs() {
    global $mysqli;
    $result = $mysqli->query("SELECT DISTINCT sura_number, sura_name, sura_name_ar FROM quran ORDER BY sura_number");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// دالة للحصول على آيات سورة معينة
function getSurahAyat($sura_number) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM quran WHERE sura_number = ? ORDER BY ayah_number");
    $stmt->bind_param("i", $sura_number);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// دالة للحصول على القراء
function getReaders() {
    global $mysqli;
    $result = $mysqli->query("SELECT * FROM readers ORDER BY name_ar");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// دالة للحصول على تلاوات قارئ معين
function getReaderRecitations($reader_id) {
    global $mysqli;
    $stmt = $mysqli->prepare("
        SELECT r.*, q.sura_name_ar, q.ayah_number 
        FROM recitations r 
        JOIN quran q ON r.quran_id = q.id 
        WHERE r.reader_id = ? 
        ORDER BY r.id DESC
    ");
    $stmt->bind_param("i", $reader_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// دالة للحصول على المفضلة للمستخدم
function getFavorites($user_id) {
    global $mysqli;
    $stmt = $mysqli->prepare("
        SELECT f.*, 
            CASE 
                WHEN f.type = 'quran' THEN q.sura_name_ar
                WHEN f.type = 'azkar' THEN a.text
                WHEN f.type = 'recitation' THEN r.audio_url
            END as content_title
        FROM favorites f
        LEFT JOIN quran q ON f.type = 'quran' AND f.item_id = q.id
        LEFT JOIN azkar a ON f.type = 'azkar' AND f.item_id = a.id
        LEFT JOIN recitations r ON f.type = 'recitation' AND f.item_id = r.id
        WHERE f.user_id = ?
        ORDER BY f.created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?> 