<?php
session_start();

// إعدادات الموقع
$config = [
    'site_name' => 'موقع قراء',
    'site_description' => 'موقع قراء للقرآن الكريم، تلاوات، تفسير، وأذكار',
    'base_url' => 'http://localhost/quraa'
];

// لازمتك مسجلة دخول عشان نقدر نكمل
$user_logged_in = isset($_SESSION['user_name']);
$user_name = $user_logged_in ? $_SESSION['user_name'] : 'زائر';

if (!$user_logged_in) {
    header("Location: login.php");
    exit();
}

// جيبي user_id بتاعك
require_once 'db_connect.php';
$stmt = $pdo->prepare("SELECT id FROM users WHERE name = ?");
$stmt->execute([$user_name]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    $user_id = $user['id'];
} else {
    session_destroy();
    header("Location: login.php");
    exit();
}

// قايمة السور (نفس القايمة اللي في quran.php)
$surahNames = array(
    "1" => "الفاتحة", "2" => "البقرة", "3" => "آل عمران", "4" => "النساء", "5" => "المائدة",
    "6" => "الأنعام", "7" => "الأعراف", "8" => "الأنفال", "9" => "التوبة", "10" => "يونس",
    "11" => "هود", "12" => "يوسف", "13" => "الرعد", "14" => "إبراهيم", "15" => "الحجر",
    "16" => "النحل", "17" => "الإسراء", "18" => "الكهف", "19" => "مريم", "20" => "طه",
    "21" => "الأنبياء", "22" => "الحج", "23" => "المؤمنون", "24" => "النور", "25" => "الفرقان",
    "26" => "الشعراء", "27" => "النمل", "28" => "القصص", "29" => "العنكبوت", "30" => "الروم",
    "31" => "لقمان", "32" => "السجدة", "33" => "الأحزاب", "34" => "سبأ", "35" => "فاطر",
    "36" => "يس", "37" => "الصافات", "38" => "ص", "39" => "الزمر", "40" => "غافر",
    "41" => "فصلت", "42" => "الشورى", "43" => "الزخرف", "44" => "الدخان", "45" => "الجاثية",
    "46" => "الأحقاف", "47" => "محمد", "48" => "الفتح", "49" => "الحجرات", "50" => "ق",
    "51" => "الذاريات", "52" => "الطور", "53" => "النجم", "54" => "القمر", "55" => "الرحمن",
    "56" => "الواقعة", "57" => "الحديد", "58" => "المجادلة", "59" => "الحشر", "60" => "الممتحنة",
    "61" => "الصف", "62" => "الجمعة", "63" => "المنافقون", "64" => "التحريم", "65" => "الملك",
    "66" => "القلم", "67" => "الحاقة", "68" => "المعارج", "69" => "نوح", "70" => "الجن",
    "71" => "المزمل", "72" => "المدثر", "73" => "القيامة", "74" => "الإنسان", "75" => "المرسلات",
    "76" => "النبأ", "77" => "النازعات", "78" => "عبس", "79" => "التكوير", "80" => "الانفطار",
    "81" => "المطففين", "82" => "الانشقاق", "83" => "البروج", "84" => "الطارق", "85" => "الأعلى",
    "86" => "الغاشية", "87" => "الفجر", "88" => "البلد", "89" => "الشمس", "90" => "الليل",
    "91" => "الضحى", "92" => "الشرح", "93" => "التين", "94" => "العلق", "95" => "القدر",
    "96" => "البينة", "97" => "الزلزلة", "98" => "العاديات", "99" => "القارعة", "100" => "التكاثر",
    "101" => "العصر", "102" => "الهمزة", "103" => "الفيل", "104" => "قريش", "105" => "الماعون",
    "106" => "الكوثر", "107" => "الكافرون", "108" => "النصر", "109" => "المسد", "110" => "الإخلاص",
    "111" => "الفلق", "112" => "الناس"
);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل القراءة - <?php echo $config['site_name']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="stylehome.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            text-align: center;
            direction: rtl;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .reading-section, .details {
            background: linear-gradient(135deg, #ffffff, #f0f8f5);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px 0;
        }
        .reading-section h2, .details h2 {
            color: #0e5f39;
            margin-bottom: 20px;
        }
        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 300px;
        }
        .timer-controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }
        .timer-btn {
            background-color: #0e5f39;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .timer-btn:hover {
            background-color: #0b4a2d;
        }
        .timer {
            font-size: 18px;
            color: #0e5f39;
        }
        .surah-title {
            font-size: 35px;
            text-align: center;
            margin-bottom: 20px;
            color: #0e5f39;
        }
        .bismillah {
            text-align: center;
            font-size: 30px;
            margin: 20px 0;
            color: #0e5f39;
        }
        .ayah {
            font-size: 28px;
            line-height: 2;
            margin-bottom: 15px;
            text-align: center;
            color: #333;
            padding: 15px;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        .ayah-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background-color: #0e5f39;
            color: #fff;
            border-radius: 50%;
            margin-right: 10px;
            font-size: 16px;
        }
        .details p {
            font-size: 18px;
            color: #333;
            margin: 10px 0;
        }
        .details p strong {
            color: #0e5f39;
        }
        a.back-btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #fff;
            background-color: #0e5f39;
            padding: 10px 20px;
            border-radius: 5px;
        }
        a.back-btn:hover {
            background-color: #0b4a2d;
        }
    </style>
</head>
<body>
    <!-- الهيدر -->
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
                        <li><a href="index.php" class="active">الرئيسية</a></li>
                        <li><a href="quran.php">المصحف</a></li>
                        <li><a href="azkar.php">الأذكار</a></li>
                        <li><a href="tafseer.php">التفسير</a></li>
                        <li><a href="recitations.html">القراء</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <button class="search-btn" aria-label="بحث">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="logout.php" class="user-btn" aria-label="تسجيل الخروج">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- القسم الرئيسي -->
    <main>
        <section class="reading-details-section">
            <div class="container">
                <!-- اختيار السورة وبدء القراية -->
                <div class="reading-section">
                    <h2>اقرأي 10 دقايق في اليوم</h2>
                    <label>اختاري السورة:</label><br>
                    <select id="surahSelect">
                        <?php
                        foreach ($surahNames as $number => $name) {
                            echo "<option value='$number'>سورة $name</option>";
                        }
                        ?>
                    </select>
                    <div class="timer-controls">
                        <button class="timer-btn" id="startTimer">ابدأي القراية</button>
                        <button class="timer-btn" id="stopTimer" style="display: none;">إنهاء القراية</button>
                        <span class="timer" id="timerDisplay">0:00</span>
                    </div>
                    <div id="surahContent">
                        <!-- الآيات هتظهر هنا -->
                    </div>
                </div>

                <!-- التفاصيل بعد ما تخلّصي -->
                <div class="details" id="readingDetails" style="display: none;">
                    <h2>تفاصيل قراءتك اليومية</h2>
                    <p><strong>اسم السورة:</strong> <span id="detailSurah"></span></p>
                    <p><strong>المدة الفعلية:</strong> <span id="detailDuration"></span> دقيقة</p>
                    <p><strong>التاريخ:</strong> <span id="detailDate"></span></p>
                    <p><strong>التقدم:</strong> <span id="detailProgress"></span></p>
                    <a href="index.php" class="back-btn">رجوع للرئيسية</a>
                </div>
            </div>
        </section>
    </main>

    <!-- الفوتر -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section about-section">
                    <h3>عن موقع قراء</h3>
                    <p>موقع إسلامي متخصص في خدمة القرآن الكريم وعلومه، يقدم المصحف الإلكتروني، التلاوات، التفاسير، والأذكار اليومية.</p>
                    <div class="social-links">
                        <a href="#" aria-label="فيسبوك"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="تويتر"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="يوتيوب"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section links-section">
                    <h3>روابط سريعة</h3>
                    <ul>
                        <li><a href="index.php">الرئيسية</a></li>
                        <li><a href="quran.php">المصحف الشريف</a></li>
                        <li><a href="azkar.php">الأذكار اليومية</a></li>
                        <li><a href="tafseer.php">تفسير القرآن</a></li>
                    </ul>
                </div>
                <div class="footer-section contact-section">
                    <h3>تواصل معنا</h3>
                    <ul>
                        <li><i class="fas fa-envelope"></i> info@quraa.com</li>
                        <li><i class="fas fa-phone"></i> +20123456789</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© <?php echo date('Y'); ?> موقع قراء - جميع الحقوق محفوظة</p>
            </div>
        </div>
    </footer>

    <script defer src="java.js"></script>
    <script defer src="js/main.js"></script>
    <script>
        // نفس أسماء السور اللي في الـ PHP
        const surahNames = <?php echo json_encode($surahNames); ?>;

        // متغيرات عشان نحسب الوقت
        let timerInterval;
        let seconds = 0;
        let isTimerRunning = false;
        const startTimerBtn = document.getElementById('startTimer');
        const stopTimerBtn = document.getElementById('stopTimer');
        const timerDisplay = document.getElementById('timerDisplay');
        const surahSelect = document.getElementById('surahSelect');
        const surahContent = document.getElementById('surahContent');
        const readingDetails = document.getElementById('readingDetails');

        // دالة عشان تجيب السورة من quran.json
        function loadSurah(surahNumber) {
            fetch('./quran.json')
                .then(response => response.json())
                .then(data => {
                    const surah = data[surahNumber];
                    if (surah) {
                        surahContent.innerHTML = '';
                        const surahTitle = document.createElement('h1');
                        surahTitle.className = 'surah-title';
                        surahTitle.textContent = `سورة ${surahNames[surahNumber]}`;
                        surahContent.appendChild(surahTitle);

                        if (surahNumber !== '9') {
                            const bismillah = document.createElement('div');
                            bismillah.className = 'bismillah';
                            bismillah.textContent = 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ';
                            surahContent.appendChild(bismillah);
                        }

                        surah.forEach(verse => {
                            const ayah = document.createElement('div');
                            ayah.className = 'ayah';
                            ayah.innerHTML = `${verse.text}<span class="ayah-number">${verse.verse}</span>`;
                            surahContent.appendChild(ayah);
                        });
                    } else {
                        surahContent.innerHTML = '<p>في مشكلة في تحميل السورة!</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    surahContent.innerHTML = '<p>في مشكلة في تحميل السورة!</p>';
                });
        }

        // لما تختاري سورة، جيبي الآيات على طول
        surahSelect.addEventListener('change', function() {
            const surahNumber = this.value;
            loadSurah(surahNumber);
        });

        // ابدأي الوقت لما تدوسي "ابدأي القراية"
        startTimerBtn.addEventListener('click', function() {
            if (!isTimerRunning) {
                isTimerRunning = true;
                startTimerBtn.style.display = 'none';
                stopTimerBtn.style.display = 'inline-block';
                timerInterval = setInterval(() => {
                    seconds++;
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    timerDisplay.textContent = `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
                }, 1000);
            }
        });

        // وقفي الوقت لما تدوسي "إنهاء القراية"
        stopTimerBtn.addEventListener('click', function() {
            if (isTimerRunning) {
                clearInterval(timerInterval);
                isTimerRunning = false;
                startTimerBtn.style.display = 'inline-block';
                stopTimerBtn.style.display = 'none';

                // احسبي المدة بالدقايق
                const duration = Math.floor(seconds / 60);
                const selectedSurah = surahSelect.value;
                const surahName = surahNames[selectedSurah];
                const today = new Date().toISOString().split('T')[0]; // التاريخ (2025-05-04)
                const progress = duration >= 10 ? 'هدف اليوم مكتمل!' : 'اقرأي المزيد عشان تكملي الهدف';

                // املي التفاصيل
                document.getElementById('detailSurah').textContent = surahName;
                document.getElementById('detailDuration').textContent = duration;
                document.getElementById('detailDate').textContent = today;
                document.getElementById('detailProgress').textContent = progress;

                // خلي التفاصيل تظهر
                readingDetails.style.display = 'block';
            }
        });

        // لما الصفحة تفتح، جيبي أول سورة على طول
        loadSurah('1');

        // الزر بتاع الموبايل في الهيدر
        document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
            document.querySelector('.main-nav').classList.toggle('active');
        });
    </script>
</body>
</html>