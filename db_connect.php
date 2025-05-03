<?php
$host = 'localhost'; // اسم السيرفر (عادةً localhost)
$dbname = 'quraa_db'; // اسم قاعدة البيانات اللي عملتيها
$username = 'root'; // اسم المستخدم (افتراضي في XAMPP هو root)
$password = ''; // كلمة السر (افتراضي في XAMPP فاضية)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET CHARACTER SET utf8");
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>

