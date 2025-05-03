<?php
session_start();
require_once '../includes/config.php';

// التحقق من تسجيل دخول المستخدم
if (!isLoggedIn()) {
    echo json_encode([
        'success' => false,
        'message' => 'يجب تسجيل الدخول أولاً'
    ]);
    exit;
}

// التحقق من وجود البيانات المطلوبة
if (!isset($_POST['item_id']) || !isset($_POST['type'])) {
    echo json_encode([
        'success' => false,
        'message' => 'بيانات غير مكتملة'
    ]);
    exit;
}

$item_id = (int)$_POST['item_id'];
$type = $_POST['type'];
$user_id = $_SESSION['user_id'];

// إضافة/إزالة من المفضلة
$result = toggleFavorite($user_id, $item_id, $type);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'تم تحديث المفضلة بنجاح'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ أثناء تحديث المفضلة'
    ]);
} 