<?php
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

// التحقق من تسجيل دخول المستخدم
if (!isLoggedIn()) {
    echo json_encode(['error' => 'يجب تسجيل الدخول أولاً']);
    exit;
}

// التحقق من وجود البيانات المطلوبة
if (!isset($_GET['item_id']) || !isset($_GET['type'])) {
    echo json_encode(['error' => 'بيانات غير مكتملة']);
    exit;
}

$user_id = $_SESSION['user_id'];
$item_id = (int)$_GET['item_id'];
$type = $_GET['type'];

// التحقق من وجود العنصر في المفضلة
$stmt = $mysqli->prepare("SELECT id FROM favorites WHERE user_id = ? AND item_id = ? AND type = ?");
$stmt->bind_param("iis", $user_id, $item_id, $type);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode([
    'is_favorite' => $result->num_rows > 0
]); 