<header class="site-header">
    <div class="container">
        <div class="header-content">
            <a href="index.php" class="logo">
                <i class="fas fa-quran"></i>
                <span>قراء</span>
            </a>
            
            <button class="mobile-menu-toggle" aria-label="قائمة الموقع">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>الرئيسية</a></li>
                    <li><a href="quran.php" <?php echo basename($_SERVER['PHP_SELF']) == 'quran.php' ? 'class="active"' : ''; ?>>المصحف</a></li>
                    <li><a href="azkar.php" <?php echo basename($_SERVER['PHP_SELF']) == 'azkar.php' ? 'class="active"' : ''; ?>>الأذكار</a></li>
                    <li><a href="tafseer.php" <?php echo basename($_SERVER['PHP_SELF']) == 'tafseer.php' ? 'class="active"' : ''; ?>>التفسير</a></li>
                    <li><a href="recitations.html">القراء</a></li>
                </ul>
            </nav>
            
            <div class="header-actions">
                <button class="search-btn" aria-label="بحث">
                    <i class="fas fa-search"></i>
                </button>
                <a href="login.php" class="user-btn" aria-label="حساب المستخدم">
                    <i class="fas fa-user"></i>
                </a>
            </div>
        </div>
    </div>
</header> 