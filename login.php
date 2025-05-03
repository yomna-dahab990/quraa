<?php
session_start();
require_once 'db_connect.php'; // جيبي ملف الاتصال بقاعدة البيانات

// لو اليوزر مسجل دخول بالفعل، يروح على الصفحة الرئيسية
if (isset($_SESSION['user_name']) && isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// لما اليوزر يضغط على زرار تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // التحقق من البيانات
    if (empty($email) || empty($password)) {
        $error = "كل الحقول مطلوبة!";
    } else {
        try {
            // جيبي بيانات اليوزر من قاعدة البيانات
            $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // لو البيانات صح، سجلي اليوزر دخول
                $_SESSION['user_id'] = $user['id']; // إضافة user_id للسيشن
                $_SESSION['user_name'] = $user['name'];
                // تحقق مؤقت للتأكد إن user_id اتخزن
                if (!isset($_SESSION['user_id'])) {
                    $error = "خطأ في تخزين بيانات السيشن!";
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                $error = "الإيميل أو كلمة السر غلط!";
            }
        } catch (PDOException $e) {
            $error = "خطأ في قاعدة البيانات: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - موقع قراء</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Amiri', serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
            background-image: url('https://img.freepik.com/premium-vector/elegant-islamic-modern-ornament-background-with-gold-accent_17812-334.jpg?semt=ais_hybrid&w=740');
            background-size: cover;
            background-position: center;
        }
        .login-container {
            background-color: #00000000;
            padding: 30px;
            border-radius: 50px;
            box-shadow: 0 10px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            border: 5px solid #ffd900;
            text-align: right;
        }
        .login-container h2 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #f5d100;
            text-align: right;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #FFD700;
            border: none;
            border-radius: 5px;
            color: #000;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-container button:hover {
            background-color: #FFC107;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .signup-link {
            display: block;
            margin-top: 15px;
            color: #dfc21b;
            text-decoration: none;
        }
        .signup-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>تسجيل الدخول</h2>
        <?php if (isset($error)) { ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php } ?>
        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit">تسجيل الدخول</button>
            <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                <a href="#" style="color: #ffd900d3;">نسيت كلمة المرور؟</a>
                <a href="signup.php" class="signup-link">إنشاء حساب جديد</a>
            </div>
        </form>
    </div>

    <footer style="position: absolute; bottom: 20px; width: 100%; text-align: center; color: #FFD700;">
    </footer>
</body>
</html>