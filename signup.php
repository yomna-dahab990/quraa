<?php
session_start();
require_once 'db_connect.php'; // جيبي ملف الاتصال بقاعدة البيانات

// لو اليوزر مسجل دخول بالفعل، يروح على الصفحة الرئيسية
if (isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

// لما اليوزر يضغط على زرار التسجيل
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    
    // التحقق من البيانات
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "كل الحقول مطلوبة!";
    } elseif ($password !== $confirmPassword) {
        $error = "كلمات المرور غير متطابقة!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "الإيميل مش صحيح!";
    } else {
        // تشفير كلمة السر
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            // التحقق إن الإيميل مش موجود بالفعل
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "الإيميل ده مسجل قبل كده!";
            } else {
                // إضافة المستخدم لقاعدة البيانات
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed_password]);
                
                // بعد التسجيل، نسجله دخول تلقائي
                $_SESSION['user_id'] = $pdo->lastInsertId(); // إضافة user_id للسيشن
                $_SESSION['user_name'] = $name;
                header("Location: index.php");
                exit();
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // خطأ إيميل مكرر
                $error = "الإيميل ده مسجل قبل كده!";
            } else {
                $error = "فيه مشكلة حصلت، جربي تاني!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - موقع قراء</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Amiri', serif;
            background-color: #f8f8f8;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('https://img.freepik.com/premium-vector/elegant-islamic-modern-ornament-background-with-gold-accent_17812-334.jpg');
            background-size: cover;
            background-position: center;
        }

        .register-container {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: right;
            color: #fff;
            border: 4px solid #FFD700;
        }

        .register-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #FFD700;
        }

        .register-container input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: none;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .register-container input:focus {
            outline: 2px solid #FFD700;
        }

        .register-container button {
            width: 100%;
            padding: 12px;
            background-color: #FFD700;
            border: none;
            border-radius: 6px;
            color: #000;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        .register-container button:hover {
            background-color: #ffc400;
        }

        .register-container a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #ffe066;
            text-decoration: none;
        }

        .register-container a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ff6666;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>إنشاء حساب جديد</h2>
        <?php if (isset($error)) { ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php } ?>
        <form method="POST" action="signup.php">
            <input type="text" name="fullName" placeholder="الاسم الكامل" required>
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <input type="password" name="confirmPassword" placeholder="تأكيد كلمة المرور" required>
            <button type="submit">تسجيل</button>
        </form>
        
        <a href="login.php">هل لديك حساب؟ تسجيل الدخول</a>
    </div>
</body>
</html>