<?php
session_start();

// نظف الجلسة
session_unset();
session_destroy();

// رجع اليوزر للصفحة الرئيسية
header("Location: index.php");
exit();
?>