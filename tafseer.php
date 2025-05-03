<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفسير القرآن الكريم - موقع قراء</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
       :root {
    --primary-color: #1F4B3F;
    --secondary-color: #2C6B5B;
    --gold-color: #D4AF37;
    --text-color: #222;
    --border-color: #dee2e6;
    --background-light: #f4f6f8;
    --background-white: #ffffff;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    direction: rtl;
    font-family: 'Cairo', sans-serif;
}

body {
    background-color: var(--background-light);
    margin: 0;
    padding: 0;
    min-height: 100vh;
    color: var(--text-color);
    background-image: url('https://static.vecteezy.com/system/resources/previews/041/034/119/non_2x/islamic-background-ramadan-with-arabic-frame-eid-mubarak-design-vector.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
}

.site-header {
    background-color: var(--primary-color);
    color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1000;
}

.main-nav {
    background-color: var(--primary-color);
    padding: 15px 0;
    border-bottom: 3px solid var(--gold-color);
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
}

.logo {
    color: white;
    text-decoration: none;
    font-size: 28px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo i {
    color: var(--gold-color);
    font-size: 32px;
}

.nav-links {
    display: flex;
    gap: 25px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-links a {
    color: white;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-size: 16px;
    font-weight: 500;
}

.nav-links a:hover {
    background-color: var(--secondary-color);
    color: white;
}

.nav-links a.active {
    background-color: var(--secondary-color);
    color: var(--gold-color);
}

.main-container {
    max-width: 1200px;
    margin: 116px auto 40px;
    padding: 0 20px;
}

.search-section {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.search-title {
    color: var(--primary-color);
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
}

.search-form {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 20px;
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    color: var(--text-color);
    font-weight: 500;
}

.form-group select,
.form-group input {
    padding: 12px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 16px;
    background: var(--background-white);
}

.search-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: var(--secondary-color);
}

.tafseer-content {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-top: 30px;
}

.surah-info {
    text-align: center;
    margin-bottom: 30px;
    padding: 25px;
    background: var(--background-light);
    border-radius: 15px;
    border: 2px solid var(--primary-color);
}

.surah-info h2 {
    color: var(--primary-color);
    font-size: 32px;
    margin-bottom: 15px;
    font-weight: bold;
}

.surah-info p {
    color: var(--text-color);
    font-size: 20px;
}

.ayah-section {
    margin-bottom: 30px;
    padding: 25px;
    background: var(--background-white);
    border-radius: 15px;
    border: 1px solid var(--border-color);
}

.ayah-text {
    font-family: 'UthmanicHafs', 'Traditional Arabic', Arial, sans-serif;
    font-size: 32px;
    line-height: 2;
    text-align: center;
    color: var(--primary-color);
    margin-bottom: 25px;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    border: 1px solid var(--border-color);
}

.tafseer-text {
    font-size: 20px;
    line-height: 1.8;
    color: var(--text-color);
    text-align: justify;
    padding: 25px;
    background: var(--background-white);
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    border: 1px solid var(--border-color);
}

.tafseer-text strong {
    color: var(--primary-color);
    font-size: 22px;
    display: block;
    margin-bottom: 15px;
}

.error {
    color: #dc3545;
    text-align: center;
    padding: 25px;
    background: #fff;
    border-radius: 10px;
    margin: 20px 0;
    font-size: 18px;
    border: 1px solid #dc3545;
}

.site-footer {
    background-color: var(--primary-color);
    color: white;
    padding: 40px 0;
    margin-top: 40px;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.footer-section h3 {
    color: var(--gold-color);
    margin-bottom: 20px;
    font-size: 20px;
}

.footer-section p {
    line-height: 1.6;
    margin-bottom: 15px;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section ul li a {
    color: white;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: var(--gold-color);
}

.copyright {
    text-align: center;
    padding-top: 20px;
    margin-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.1);
}

@media (max-width: 768px) {
    .search-form {
        grid-template-columns: 1fr;
    }

    .nav-container {
        flex-direction: column;
        gap: 15px;
    }

    .nav-links {
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    .nav-links a {
        padding: 6px 12px;
        font-size: 14px;
    }

    .footer-container {
        grid-template-columns: 1fr;
    }
}

    </style>
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <nav class="main-nav">
            <div class="nav-container">
                <a href="index.php" class="logo">
                    <i class="fas fa-quran"></i>
                    قراء
                </a>
                <ul class="nav-links">
                    <li><a href="index.php">الرئيسية</a></li>
                    <li><a href="quran.php">المصحف</a></li>
                    <li><a href="azkar.php">الأذكار</a></li>
                    <li><a href="tafseer.php" class="active">التفسير</a></li>
                    <li><a href="recitations.html">القراء</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        <section class="search-section">
            <h2 class="search-title">البحث في التفسير</h2>
            <form class="search-form" id="tafseerForm">
                <div class="form-group">
                    <label for="surah">السورة</label>
                    <select id="surah" name="surah" required>
                        <?php
                        $surahNames = array(
                            "1" => "الفاتحة", "2" => "البقرة", "3" => "آل عمران", "4" => "النساء",
                            "5" => "المائدة", "6" => "الأنعام", "7" => "الأعراف", "8" => "الأنفال",
                            "9" => "التوبة", "10" => "يونس", "11" => "هود", "12" => "يوسف",
                            "13" => "الرعد", "14" => "إبراهيم", "15" => "الحجر", "16" => "النحل",
                            "17" => "الإسراء", "18" => "الكهف", "19" => "مريم", "20" => "طه",
                            "21" => "الأنبياء", "22" => "الحج", "23" => "المؤمنون", "24" => "النور",
                            "25" => "الفرقان", "26" => "الشعراء", "27" => "النمل", "28" => "القصص",
                            "29" => "العنكبوت", "30" => "الروم", "31" => "لقمان", "32" => "السجدة",
                            "33" => "الأحزاب", "34" => "سبأ", "35" => "فاطر", "36" => "يس",
                            "37" => "الصافات", "38" => "ص", "39" => "الزمر", "40" => "غافر",
                            "41" => "فصلت", "42" => "الشورى", "43" => "الزخرف", "44" => "الدخان",
                            "45" => "الجاثية", "46" => "الأحقاف", "47" => "محمد", "48" => "الفتح",
                            "49" => "الحجرات", "50" => "ق", "51" => "الذاريات", "52" => "الطور",
                            "53" => "النجم", "54" => "القمر", "55" => "الرحمن", "56" => "الواقعة",
                            "57" => "الحديد", "58" => "المجادلة", "59" => "الحشر", "60" => "الممتحنة",
                            "61" => "الصف", "62" => "الجمعة", "63" => "المنافقون", "64" => "التغابن",
                            "65" => "الطلاق", "66" => "التحريم", "67" => "الملك", "68" => "القلم",
                            "69" => "الحاقة", "70" => "المعارج", "71" => "نوح", "72" => "الجن",
                            "73" => "المزمل", "74" => "المدثر", "75" => "القيامة", "76" => "الإنسان",
                            "77" => "المرسلات", "78" => "النبأ", "79" => "النازعات", "80" => "عبس",
                            "81" => "التكوير", "82" => "الانفطار", "83" => "المطففين", "84" => "الانشقاق",
                            "85" => "البروج", "86" => "الطارق", "87" => "الأعلى", "88" => "الغاشية",
                            "89" => "الفجر", "90" => "البلد", "91" => "الشمس", "92" => "الليل",
                            "93" => "الضحى", "94" => "الشرح", "95" => "التين", "96" => "العلق",
                            "97" => "القدر", "98" => "البينة", "99" => "الزلزلة", "100" => "العاديات",
                            "101" => "القارعة", "102" => "التكاثر", "103" => "العصر", "104" => "الهمزة",
                            "105" => "الفيل", "106" => "قريش", "107" => "الماعون", "108" => "الكوثر",
                            "109" => "الكافرون", "110" => "النصر", "111" => "المسد", "112" => "الإخلاص",
                            "113" => "الفلق", "114" => "الناس"
                        );
                        foreach ($surahNames as $number => $name) {
                            echo "<option value=\"$number\">$number - سورة $name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ayah">رقم الآية</label>
                    <input type="number" id="ayah" name="ayah" min="1" required>
                </div>
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                    عرض التفسير
                </button>
            </form>
        </section>

        <section class="tafseer-content" id="tafseerResult">
            <!-- التفسير سيظهر هنا -->
        </section>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>عن الموقع</h3>
                <p>موقع قراء هو منصة إلكترونية متخصصة في القرآن الكريم وتفسيره، تهدف إلى تسهيل الوصول إلى المصحف الشريف وتفسيره.</p>
            </div>
            <div class="footer-section">
                <h3>روابط سريعة</h3>
                <ul>
                    <li><a href="index.php">الرئيسية</a></li>
                    <li><a href="quran.php">المصحف</a></li>
                    <li><a href="azkar.php">الأذكار</a></li>
                    <li><a href="tafseer.php">التفسير</a></li>
                    <li><a href="recitations.html">القراء</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>اتصل بنا</h3>
                <p>للاستفسارات والاقتراحات، يمكنك التواصل معنا عبر البريد الإلكتروني:</p>
                <p>info@quraa.com</p>
            </div>
        </div>
        <div class="copyright">
            <p>جميع الحقوق محفوظة &copy; <?php echo date('Y'); ?> موقع قراء</p>
        </div>
    </footer>

    <script>
        const ayahCounts = {
            1: 7, 2: 286, 3: 200, 4: 176, 5: 120, 6: 165, 7: 206, 8: 75,
            9: 129, 10: 109, 11: 123, 12: 111, 13: 43, 14: 52, 15: 99, 16: 128,
            17: 111, 18: 110, 19: 98, 20: 135, 21: 112, 22: 78, 23: 118, 24: 64,
            25: 77, 26: 227, 27: 93, 28: 88, 29: 69, 30: 60, 31: 34, 32: 30,
            33: 73, 34: 54, 35: 45, 36: 83, 37: 182, 38: 88, 39: 75, 40: 85,
            41: 54, 42: 53, 43: 89, 44: 59, 45: 37, 46: 35, 47: 38, 48: 29,
            49: 18, 50: 45, 51: 60, 52: 49, 53: 62, 54: 55, 55: 78, 56: 96,
            57: 29, 58: 22, 59: 24, 60: 13, 61: 14, 62: 11, 63: 11, 64: 18,
            65: 12, 66: 12, 67: 30, 68: 52, 69: 52, 70: 44, 71: 28, 72: 28,
            73: 20, 74: 56, 75: 40, 76: 31, 77: 50, 78: 40, 79: 46, 80: 42,
            81: 29, 82: 19, 83: 36, 84: 25, 85: 22, 86: 17, 87: 19, 88: 26,
            89: 30, 90: 20, 91: 15, 92: 21, 93: 11, 94: 8, 95: 8, 96: 19,
            97: 5, 98: 8, 99: 8, 100: 11, 101: 11, 102: 8, 103: 3, 104: 9,
            105: 5, 106: 4, 107: 7, 108: 3, 109: 6, 110: 3, 111: 5, 112: 4,
            113: 5, 114: 6
        };

        document.getElementById('surah').addEventListener('change', function() {
            const surah = this.value;
            const ayahInput = document.getElementById('ayah');
            ayahInput.max = ayahCounts[surah];
            ayahInput.placeholder = `1 - ${ayahCounts[surah]}`;
        });

        document.getElementById('tafseerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const surah = document.getElementById('surah').value;
            const ayah = document.getElementById('ayah').value;
            const resultDiv = document.getElementById('tafseerResult');
            
            try {
                resultDiv.innerHTML = '<div style="text-align: center; padding: 20px; font-size: 18px;"><i class="fas fa-spinner fa-spin"></i> جاري تحميل التفسير...</div>';
                
                const response = await fetch(`get_tafseer.php?surah_number=${surah}&ayah=${ayah}`);
                const data = await response.json();
                
                if (data && data.length > 0) {
                    if (data[0].error) {
                        resultDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-circle"></i> ${data[0].error}</div>`;
                    } else {
                        const surahName = document.getElementById('surah').options[document.getElementById('surah').selectedIndex].text;
                        resultDiv.innerHTML = `
                            <div class="surah-info">
                                <h2>${surahName}</h2>
                                <p>الآية رقم: ${data[0].ayah_number}</p>
                            </div>
                            <div class="ayah-section">
                                <div class="ayah-text">
                                    ${data[0].ayah_text}
                                </div>
                                <div class="tafseer-text">
                                    <strong>التفسير:</strong>
                                    ${data[0].tafseer_text}
                                </div>
                            </div>
                        `;
                    }
                } else {
                    resultDiv.innerHTML = '<div class="error"><i class="fas fa-exclamation-circle"></i> لم يتم العثور على تفسير لهذه الآية</div>';
                }
            } catch (error) {
                console.error('Error:', error);
                resultDiv.innerHTML = '<div class="error"><i class="fas fa-exclamation-circle"></i> حدث خطأ أثناء تحميل التفسير</div>';
            }
        });

        // تحديث الحد الأقصى للآية عند تحميل الصفحة
        window.addEventListener('load', function() {
            const surah = document.getElementById('surah').value;
            const ayahInput = document.getElementById('ayah');
            ayahInput.max = ayahCounts[surah];
            ayahInput.placeholder = `1 - ${ayahCounts[surah]}`;
        });
    </script>
</body>
</html>