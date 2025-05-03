<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$public_khatmat = mysqli_query($conn, "SELECT * FROM khatmat WHERE status='public' ORDER BY created_at DESC");

// انضمام لختمة
if (isset($_GET['join'])) {
    $khatma_id = intval($_GET['join']);
    $user_id = $_SESSION['user_id'] ?? 1; // لو مافيش user_id، استخدمي قيمة افتراضية أو user_name
    $query = "UPDATE khatmat SET participants = participants + 1 WHERE id = $khatma_id";
    mysqli_query($conn, $query);
    // سجل المستخدم في khatma_parts لو عايزة تتبعي
    $query = "INSERT INTO khatma_parts (khatma_id, user_id) VALUES ($khatma_id, $user_id)";
    mysqli_query($conn, $query);
    header("Location: public_khatma.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الختمات العامة - قراء</title>
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
            margin-left: 10px;
        }
        .khatma-item a:hover {
            background: rgb(16, 98, 59);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>الختمات العامة</h1>
        <div class="khatma-list">
            <?php
            if (mysqli_num_rows($public_khatmat) > 0) {
                while ($khatma = mysqli_fetch_assoc($public_khatmat)) {
                    echo '<div class="khatma-item">';
                    echo '<h3>' . $khatma['name'] . '</h3>';
                    echo '<p>عدد المشاركين: ' . $khatma['participants'] . '</p>';
                    echo '<a href="public_khatma.php?join=' . $khatma['id'] . '">انضم</a>';
                    if ($khatma['surah_number']) {
                        echo '<a href="quran.php?surah=' . $khatma['surah_number'] . '">اقرأ السورة</a>';
                    } elseif ($khatma['juz_number']) {
                        echo '<a href="quran.php?juz=' . $khatma['juz_number'] . '">اقرأ الجزء</a>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<p>لا توجد ختمات عامة حاليًا</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>