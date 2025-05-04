<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
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
        :root {
            --primary-color: rgb(14, 95, 57);
            --secondary-color: rgb(16, 98, 59);
            --text-color: #333;
            --background-color: #f8f9fa;
            --border-color: #dee2e6;
            --hover-color: #19875420;
        }
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
        }
        header {
            background: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        .header-container h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .nav-links a:hover {
            background: var(--secondary-color);
        }
        .khatma-section {
            padding: 3rem 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .section-title {
            text-align: center;
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 2rem;
            font-weight: 700;
        }
        .khatma-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        .khatma-item {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .khatma-item a {
            display: block;
            padding: 2rem;
            text-decoration: none;
            color: inherit;
            text-align: center;
        }
        .khatma-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .khatma-item i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        .khatma-item h3 {
            font-size: 1.5rem;
            margin: 0.5rem 0;
            color: var(--text-color);
        }
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            .khatma-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>قراء</h1>
            <div class="nav-links">
                <a href="index.php">الرئيسية</a>
                <a href="logout.php">تسجيل الخروج</a>
            </div>
        </div>
    </header>
    <section class="khatma-section">
        <div class="container">
            <h1 class="section-title">إدارة الختمات</h1>
            <div class="khatma-options">
                <div class="khatma-item">
                    <a href="start_new_khatma.php">
                        <i class="fas fa-book-open"></i>
                        <h3>ختمة جديدة</h3>
                    </a>
                </div>
                <div class="khatma-item">
                    <a href="previous_khatma.php">
                        <i class="fas fa-history"></i>
                        <h3>ختمة سابقة</h3>
                    </a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>


