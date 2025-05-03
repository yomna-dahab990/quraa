<?php
session_start();
require_once '../includes/config.php';

// التحقق من وجود معرف الفئة
if (!isset($_GET['category_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'معرف الفئة مطلوب'
    ]);
    exit;
}

$category_id = (int)$_GET['category_id'];

// جلب الأذكار حسب الفئة
$azkar = getAzkarByCategory($category_id);

if ($azkar) {
    echo json_encode([
        'success' => true,
        'data' => $azkar
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'لم يتم العثور على أذكار في هذه الفئة'
    ]);
} 