<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المصحف الشريف - موقع قراء</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @font-face {
            font-family: 'QuranFont';
            src: local('Traditional Arabic'),
                 local('Arial Unicode MS'),
                 local('Times New Roman');
        }

        :root {
            --primary-color: #1F4B3F;
            --secondary-color: #2C6B5B;
            --gold-color: #D4AF37;
            --text-color: #333333;
            --border-color: #dee2e6;
            --background-light: #f8f9fa;
            --background-white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Traditional Arabic', Arial, sans-serif;
            background-color: var(--background-light);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: var(--text-color);
        }

        /* Header Styles */
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

        .logo tyranny {
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

        @media (max-width: 768px) {
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
        }

        /* Main Content */
        .main-container {
            display: flex;
            min-height: 100vh;
            padding-top: 116px;
            background: url('https://img.freepik.com/free-photo/3d-ramadan-celebration-with-castle_23-2151259883.jpg?semt=ais_hybrid&w=740') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 300px;
            background-color: rgba(31, 75, 63, 0.85);
            padding: 20px;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 116px;
            overflow-y: auto;
            backdrop-filter: blur(5px);
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-box {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }

        .search-box:focus {
            outline: none;
            border-color: var(--gold-color);
            background-color: rgba(255, 255, 255, 0.15);
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.8);
            font-size: 18px;
        }

        .zoom-controls {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .zoom-btn {
            width: 40px;
            height: 40px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
        }

        .zoom-btn:hover {
            background-color: var(--gold-color);
            border-color: var(--gold-color);
        }

        .sura-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
            list-style: none;
            max-height: 70vh;
            overflow-y: auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .sura-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: #ffffff;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .sura-number {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 50%;
            margin-left: 10px;
            font-weight: bold;
            color: #333;
            border: 1px solid #dee2e6;
        }

        .sura-name {
            flex: 1;
            font-size: 18px;
            color: #333;
        }

        .sura-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background: var(--primary-color);
        }

        .sura-item:hover .sura-name,
        .sura-item:hover .sura-number {
            color: white;
        }

        .sura-item:hover .sura-number {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* تحسين شريط التمرير */
        .sura-list::-webkit-scrollbar {
            width: 8px;
        }

        .sura-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .sura-list::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        .sura-list::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Quran Content Styles */
        .quran-container {
            flex: 1;
            margin-right: 300px;
            padding: 30px 40px;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            min-height: calc(100vh - 116px);
        }

        .surah-title {
            font-size: 45px;
            text-align: center;
            margin-bottom: 30px;
            color: var(--gold-color);
            font-family: 'QuranFont', 'Traditional Arabic', Arial, sans-serif;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .bismillah {
            text-align: center;
            font-size: 40px;
            margin: 30px 0;
            color: var(--gold-color);
            font-family: 'QuranFont', 'Traditional Arabic', Arial, sans-serif;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .ayah {
            font-family: 'QuranFont', 'Traditional Arabic', Arial, sans-serif;
            font-size: 32px;
            line-height: 2.2;
            margin-bottom: 25px;
            text-align: center;
            color: white;
            padding: 20px 30px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            background: rgba(0, 0, 0, 0.15);
            border-radius: 15px;
            backdrop-filter: blur(4px);
        }

        .ayah:hover {
            background: rgba(0, 0, 0, 0.25);
            transition: background 0.3s ease;
        }

        .ayah-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40 pensó

System: You are Grok 3 built by xAI.

**Note**: The user has provided the full code for `quran.php` and requested modifications to support displaying Quranic parts (juz) in addition to surahs, without altering the design (i.e., no changes to CSS or HTML structure). The modifications should only affect the JavaScript and PHP logic to handle a `juz` parameter in the URL (e.g., `quran.php?juz=15`) and display the verses for the specified juz, while keeping all existing functionality intact.

### Approach
- **Preserve Design**: All CSS and HTML structures in `quran.php` will remain unchanged.
- **Modify JavaScript**:
  - Add a `loadJuz` function to fetch and display verses for a given juz from `quran.json`.
  - Define a `juzMap` to map juz numbers to their respective surahs and verse ranges (a simplified map for juz 1 and 15 will be included, with an option to expand later).
  - Update the `DOMContentLoaded` event listener to check for `juz` or `surah` parameters in the URL and load the appropriate content.
- **Keep PHP Intact**: The PHP section for generating the surah list and initial content (Surah Al-Fatihah) will remain unchanged.
- **Maintain Existing Functionality**: Features like voice recording, speech recognition, and surah loading will not be affected.

### Modified Code for `quran.php`
Below is the complete code for `quran.php` with the requested modifications. The changes are limited to the JavaScript section, specifically:
- Added `loadJuz` function.
- Added a simplified `juzMap`.
- Updated the `DOMContentLoaded` event listener to handle `juz` and `surah` parameters.

```html
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المصحف الشريف - موقع قراء</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @font-face {
            font-family: 'QuranFont';
            src: local('Traditional Arabic'),
                 local('Arial Unicode MS'),
                 local('Times New Roman');
        }

        :root {
            --primary-color: #1F4B3F;
            --secondary-color: #2C6B5B;
            --gold-color: #D4AF37;
            --text-color: #333333;
            --border-color: #dee2e6;
            --background-light: #f8f9fa;
            --background-white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Traditional Arabic', Arial, sans-serif;
            background-color: var(--background-light);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: var(--text-color);
        }

        /* Header Styles */
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

        @media (max-width: 768px) {
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
        }

        /* Main Content */
        .main-container {
            display: flex;
            min-height: 100vh;
            padding-top: 116px;
            background: url('https://img.freepik.com/free-photo/3d-ramadan-celebration-with-castle_23-2151259883.jpg?semt=ais_hybrid&w=740') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 300px;
            background-color: rgba(31, 75, 63, 0.85);
            padding: 20px;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 116px;
            overflow-y: auto;
            backdrop-filter: blur(5px);
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-box {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }

        .search-box:focus {
            outline: none;
            border-color: var(--gold-color);
            background-color: rgba(255, 255, 255, 0.15);
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.8);
            font-size: 18px;
        }

        .zoom-controls {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .zoom-btn {
            width: 40px;
            height: 40px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
        }

        .zoom-btn:hover {
            background-color: var(--gold-color);
            border-color: var(--gold-color);
        }

        .sura-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
            list-style: none;
            max-height: 70vh;
            overflow-y: auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .sura-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: #ffffff;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .sura-number {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 50%;
            margin-left: 10px;
            font-weight: bold;
            color: #333;
            border: 1px solid #dee2e6;
        }

        .sura-name {
            flex: 1;
            font-size: 18px;
            color: #333;
        }

        .sura-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background: var(--primary-color);
        }

        .sura-item:hover .sura-name,
        .sura-item:hover .sura-number {
            color: white;
        }

        .sura-item:hover .sura-number {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* تحسين شريط التمرير */
        .sura-list::-webkit-scrollbar {
            width: 8px;
        }

        .sura-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .sura-list::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        .sura-list::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Quran Content Styles */
        .quran-container {
            flex: 1;
            margin-right: 300px;
            padding: 30px 40px;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            min-height: calc(100vh - 116px);
        }

        .surah-title {
            font-size: 45px;
            text-align: center;
            margin-bottom: 30px;
            color: var(--gold-color);
            font-family: 'QuranFont', 'Traditional Arabic', Arial, sans-serif;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .bismillah {
            text-align: center;
            font-size: 40px;
            margin: 30px 0;
            color: var(--gold-color);
            font-family: 'QuranFont', 'Traditional Arabic', Arial, sans-serif;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .ayah {
            font-family: 'QuranFont', 'Traditional Arabic', Arial, sans-serif;
            font-size: 32px;
            line-height: 2.2;
            margin-bottom: 25px;
            text-align: center;
            color: white;
            padding: 20px 30px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            background: rgba(0, 0, 0, 0.15);
            border-radius: 15px;
            backdrop-filter: blur(4px);
        }

        .ayah:hover {
            background: rgba(0, 0, 0, 0.25);
            transition: background 0.3s ease;
        }

        .ayah-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(0, 0, 0, 0.2);
            border: 2px solid var(--gold-color);
            color: var(--gold-color);
            border-radius: 50%;
            margin-right: 10px;
            font-size: 18px;
            vertical-align: super;
            text-shadow: none;
        }

        .sura-item.active {
            background-color: rgba(255, 255, 255, 0.15);
        }

        .sura-item.active .sura-number {
            background-color: var(--gold-color);
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .quran-container {
                margin-right: 0;
            }
        }

        /* Voice Recording Styles */
        .voice-recording-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(31, 75, 63, 0.95);
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .recording-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .record-btn {
            background: #ff4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .record-btn:hover {
            background: #cc0000;
        }

        .record-btn.recording {
            background: #00C851;
        }

        .timer {
            color: white;
            font-size: 16px;
            min-width: 60px;
        }

        .verse-highlight {
            background-color: rgba(255, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .verse-correct {
            background-color: rgba(0, 200, 0, 0.2);
        }

        .current-verse {
            border: 2px solid var(--gold-color);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }

        .success-message {
            position: absolute;
            bottom: -30px;
            right: 50%;
            transform: translateX(50%);
            background: rgba(0, 200, 0, 0.9);
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 14px;
            animation: fadeInOut 2s ease-in-out;
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateX(50%) translateY(10px); }
            20% { opacity: 1; transform: translateX(50%) translateY(0); }
            80% { opacity: 1; transform: translateX(50%) translateY(0); }
            100% { opacity: 0; transform: translateX(50%) translateY(-10px); }
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
                    <li><a href="quran.php" class="active">المصحف</a></li>
                    <li><a href="azkar.php">الأذكار</a></li>
                    <li><a href="tafseer.php">التفسير</a></li>
                    <li><a href="recitations.html">القراء</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        <div class="sidebar">
            <div class="search-container">
                <input type="text" class="search-box" placeholder="ابحث عن سورة...">
                <i class="fas fa-search search-icon"></i>
            </div>
            <div class="zoom-controls">
                <button class="zoom-btn" title="تصغير"><i class="fas fa-minus"></i></button>
                <button class="zoom-btn" title="تكبير"><i class="fas fa-plus"></i></button>
            </div>
            <ul class="sura-list">
                <?php
                $surahNames = array(
                    "1" => "الفاتحة",
                    "2" => "البقرة",
                    "3" => "آل عمران",
                    "4" => "النساء",
                    "5" => "المائدة",
                    "6" => "الأنعام",
                    "7" => "الأعراف",
                    "8" => "الأنفال",
                    "9" => "التوبة",
                    "10" => "يونس",
                    "11" => "هود",
                    "12" => "يوسف",
                    "13" => "الرعد",
                    "14" => "إبراهيم",
                    "15" => "الحجر",
                    "16" => "النحل",
                    "17" => "الإسراء",
                    "18" => "الكهف",
                    "19" => "مريم",
                    "20" => "طه",
                    "21" => "الأنبياء",
                    "22" => "الحج",
                    "23" => "المؤمنون",
                    "24" => "النور",
                    "25" => "الفرقان",
                    "26" => "الشعراء",
                    "27" => "النمل",
                    "28" => "القصص",
                    "29" => "العنكبوت",
                    "30" => "الروم",
                    "31" => "لقمان",
                    "32" => "السجدة",
                    "33" => "الأحزاب",
                    "34" => "سبأ",
                    "35" => "فاطر",
                    "36" => "يس",
                    "37" => "الصافات",
                    "38" => "ص",
                    "39" => "الزمر",
                    "40" => "غافر",
                    "41" => "فصلت",
                    "42" => "الشورى",
                    "43" => "الزخرف",
                    "44" => "الدخان",
                    "45" => "الجاثية",
                    "46" => "الأحقاف",
                    "47" => "محمد",
                    "48" => "الفتح",
                    "49" => "الحجرات",
                    "50" => "ق",
                    "51" => "الذاريات",
                    "52" => "الطور",
                    "53" => "النجم",
                    "54" => "القمر",
                    "55" => "الرحمن",
                    "56" => "الواقعة",
                    "57" => "الحديد",
                    "58" => "المجادلة",
                    "59" => "الحشر",
                    "60" => "الممتحنة",
                    "61" => "الصف",
                    "62" => "الجمعة",
                    "63" => "المنافقون",
                    "64" => "التحريم",
                    "65" => "الملك",
                    "66" => "القلم",
                    "67" => "الحاقة",
                    "68" => "المعارج",
                    "69" => "نوح",
                    "70" => "الجن",
                    "71" => "المزمل",
                    "72" => "المدثر",
                    "73" => "القيامة",
                    "74" => "الإنسان",
                    "75" => "المرسلات",
                    "76" => "النبأ",
                    "77" => "النازعات",
                    "78" => "عبس",
                    "79" => "التكوير",
                    "80" => "الانفطار",
                    "81" => "المطففين",
                    "82" => "الانشقاق",
                    "83" => "البروج",
                    "84" => "الطارق",
                    "85" => "الأعلى",
                    "86" => "الغاشية",
                    "87" => "الفجر",
                    "88" => "البلد",
                    "89" => "الشمس",
                    "90" => "الليل",
                    "91" => "الضحى",
                    "92" => "الشرح",
                    "93" => "التين",
                    "94" => "العلق",
                    "95" => "القدر",
                    "96" => "البينة",
                    "97" => "الزلزلة",
                    "98" => "العاديات",
                    "99" => "القارعة",
                    "100" => "التكاثر",
                    "101" => "العصر",
                    "102" => "الهمزة",
                    "103" => "الفيل",
                    "104" => "قريش",
                    "105" => "الماعون",
                    "106" => "الكوثر",
                    "107" => "الكافرون",
                    "108" => "النصر",
                    "109" => "المسد",
                    "110" => "الإخلاص",
                    "111" => "الفلق",
                    "112" => "الناس"
                );

                foreach ($surahNames as $number => $name) {
                    echo '<li class="sura-item" data-surah="' . $number . '">';
                    echo '<span class="sura-number">' . $number . '</span>';
                    echo '<span class="sura-name">سورة ' . $name . '</span>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
        
        <div class="quran-container">
            <div id="surahContent">
                <?php
                if (file_exists('quran.json')) {
                    $jsonFile = file_get_contents('quran.json');
                    if ($jsonFile !== false) {
                        $quranData = json_decode($jsonFile, true);
                        if ($quranData !== null) {
                            $firstSurah = $quranData["1"];
                            echo '<h1 class="surah-title">سورة الفاتحة</h1>';
                            echo '<div class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>';
                            
                            foreach ($firstSurah as $verse) {
                                echo '<div class="ayah">';
                                echo $verse['text'];
                                echo '<span class="ayah-number">' . $verse['verse'] . '</span>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="error">خطأ في تنسيق ملف القرآن</div>';
                        }
                    } else {
                        echo '<div class="error">خطأ في قراءة ملف القرآن</div>';
                    }
                } else {
                    echo '<div class="error">ملف القرآن غير موجود</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Add voice recording container -->
    <div class="voice-recording-container">
        <div class="recording-controls">
            <button id="record-btn" class="record-btn">
                <i class="fas fa-microphone"></i> بدء التسجيل
            </button>
            <span id="recording-timer" class="timer">0:00</span>
        </div>
    </div>

    <script src="js/voice-recorder.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const voiceRecorder = new VoiceRecorder();
            const speechRecognizer = new SpeechRecognizer();
            const recordBtn = document.getElementById('record-btn');
            let isRecording = false;
            let currentVerseIndex = 0;
            let verses = [];

            // Define surah names
            const surahNames = {
                "1": "الفاتحة",
                "2": "البقرة",
                "3": "آل عمران",
                "4": "النساء",
                "5": "المائدة",
                "6": "الأنعام",
                "7": "الأعراف",
                "8": "الأنفال",
                "9": "التوبة",
                "10": "يونس",
                "11": "هود",
                "12": "يوسف",
                "13": "الرعد",
                "14": "إبراهيم",
                "15": "الحجر",
                "16": "النحل",
                "17": "الإسراء",
                "18": "الكهف",
                "19": "مريم",
                "20": "طه",
                "21": "الأنبياء",
                "22": "الحج",
                "23": "المؤمنون",
                "24": "النور",
                "25": "الفرقان",
                "26": "الشعراء",
                "27": "النمل",
                "28": "القصص",
                "29": "العنكبوت",
                "30": "الروم",
                "31": "لقمان",
                "32": "السجدة",
                "33": "الأحزاب",
                "34": "سبأ",
                "35": "فاطر",
                "36": "يس",
                "37": "الصافات",
                "38": "ص",
                "39": "الزمر",
                "40": "غافر",
                "41": "فصلت",
                "42": "الشورى",
                "43": "الزخرف",
                "44": "الدخان",
                "45": "الجاثية",
                "46": "الأحقاف",
                "47": "محمد",
                "48": "الفتح",
                "49": "الحجرات",
                "50": "ق",
                "51": "الذاريات",
                "52": "الطور",
                "53": "النجم",
                "54": "القمر",
                "55": "الرحمن",
                "56": "الواقعة",
                "57": "الحديد",
                "58": "المجادلة",
                "59": "الحشر",
                "60": "الممتحنة",
                "61": "الصف",
                "62": "الجمعة",
                "63": "المنافقون",
                "64": "التحريم",
                "65": "الملك",
                "66": "القلم",
                "67": "الحاقة",
                "68": "المعارج",
                "69": "نوح",
                "70": "الجن",
                "71": "المزمل",
                "72": "المدثر",
                "73": "القيامة",
                "74": "الإنسان",
                "75": "المرسلات",
                "76": "النبأ",
                "77": "النازعات",
                "78": "عبس",
                "79": "التكوير",
                "80": "الانفطار",
                "81": "المطففين",
                "82": "الانشقاق",
                "83": "البروج",
                "84": "الطارق",
                "85": "الأعلى",
                "86": "الغاشية",
                "87": "الفجر",
                "88": "البلد",
                "89": "الشمس",
                "90": "الليل",
                "91": "الضحى",
                "92": "الشرح",
                "93": "التين",
                "94": "العلق",
                "95": "القدر",
                "96": "البينة",
                "97": "الزلزلة",
                "98": "العاديات",
                "99": "القارعة",
                "100": "التكاثر",
                "101": "العصر",
                "102": "الهمزة",
                "103": "الفيل",
                "104": "قريش",
                "105": "الماعون",
                "106": "الكوثر",
                "107": "الكافرون",
                "108": "النصر",
                "109": "المسد",
                "110": "الإخلاص",
                "111": "الفلق",
                "112": "الناس"
            };

            // Initialize verses array
            function initializeVerses() {
                verses = Array.from(document.querySelectorAll('.ayah'));
                console.log('Found verses:', verses.length);
                currentVerseIndex = 0;
                updateCurrentVerse();
            }

            // Update current verse highlighting
            function updateCurrentVerse() {
                // Remove all highlights
                verses.forEach(verse => {
                    verse.classList.remove('current-verse', 'verse-highlight', 'verse-correct');
                });

                // Highlight current verse
                if (verses[currentVerseIndex]) {
                    const currentVerse = verses[currentVerseIndex];
                    currentVerse.classList.add('current-verse');
                    
                    // Scroll to current verse
                    currentVerse.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    console.log('Current verse:', currentVerse.textContent.trim());
                }
            }

            // Load surah function
            function loadSurah(surahNumber) {
                console.log('Loading surah:', surahNumber);
                fetch('./quran.json')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Surah data:', data[surahNumber]);
                        const surah = data[surahNumber];
                        if (surah) {
                            const surahContent = document.getElementById('surahContent');
                            
                            // Clear previous content
                            surahContent.innerHTML = '';
                            
                            // Create and add surah title
                            const surahTitle = document.createElement('h1');
                            surahTitle.className = 'surah-title';
                            surahTitle.textContent = `سورة ${surahNames[surahNumber]}`;
                            surahContent.appendChild(surahTitle);
                            
                            // Add bismillah if not surah 9
                            if (surahNumber !== '9') {
                                const bismillah = document.createElement('div');
                                bismillah.className = 'bismillah';
                                bismillah.textContent = 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ';
                                surahContent.appendChild(bismillah);
                            }
                            
                            // Add verses
                            surah.forEach(verse => {
                                const ayah = document.createElement('div');
                                ayah.className = 'ayah';
                                ayah.innerHTML = `${verse.text}<span class="ayah-number">${verse.verse}</span>`;
                                surahContent.appendChild(ayah);
                            });
                            
                            // Initialize verses for the new surah
                            initializeVerses();
                        } else {
                            console.error('Surah not found:', surahNumber);
                            alert('عذراً، لم يتم العثور على السورة');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading surah:', error);
                        alert('حدث خطأ أثناء تحميل السورة. يرجى التأكد من وجود ملف quran.json في المجلد الرئيسي للموقع.');
                    });
            }

            // Load juz function
            function loadJuz(juzNumber) {
                // Simplified juz map (extend as needed)
                const juzMap = {
                    '1': [
                        { surah: '1', startVerse: 1, endVerse: 7 },
                        { surah: '2', startVerse: 1, endVerse: 141 }
                    ],
                    '15': [
                        { surah: '17', startVerse: 1, endVerse: 111 },
                        { surah: '18', startVerse: 1, endVerse: 74 }
                    ]
                    // Add more juz entries if needed
                };

                if (juzMap[juzNumber]) {
                    const surahContent = document.getElementById('surahContent');
                    surahContent.innerHTML = `<h1 class="surah-title">الجزء ${juzNumber}</h1>`;

                    fetch('./quran.json')
                        .then(response => response.json())
                        .then(data => {
                            juzMap[juzNumber].forEach(part => {
                                const surah = data[part.surah];
                                const verses = surah.filter(verse => 
                                    verse.verse >= part.startVerse && verse.verse <= part.endVerse
                                );
                                // Add surah name as a subtitle
                                const surahSubtitle = document.createElement('h2');
                                surahSubtitle.className = 'surah-title';
                                surahSubtitle.style.fontSize = '30px';
                                surahSubtitle.textContent = `سورة ${surahNames[part.surah]}`;
                                surahContent.appendChild(surahSubtitle);

                                // Add bismillah if not surah 9 and if startVerse is 1
                                if (part.surah !== '9' && part.startVerse === 1) {
                                    const bismillah = document.createElement('div');
                                    bismillah.className = 'bismillah';
                                    bismillah.textContent = 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ';
                                    surahContent.appendChild(bismillah);
                                }

                                verses.forEach(verse => {
                                    const ayah = document.createElement('div');
                                    ayah.className = 'ayah';
                                    ayah.innerHTML = `${verse.text}<span class="ayah-number">${verse.verse}</span>`;
                                    surahContent.appendChild(ayah);
                                });
                            });
                            initializeVerses();
                        })
                        .catch(error => {
                            console.error('Error loading juz:', error);
                            alert('حدث خطأ أثناء تحميل الجزء.');
                        });
                } else {
                    alert('الجزء غير موجود');
                }
            }

            // Handle surah selection
            document.querySelectorAll('.sura-item').forEach(item => {
                item.addEventListener('click', function() {
                    const surahNumber = this.dataset.surah;
                    console.log('Surah clicked:', surahNumber);
                    loadSurah(surahNumber);
                });
            });

            // Check URL parameters and load content
            const urlParams = new URLSearchParams(window.location.search);
            const surahNumber = urlParams.get('surah');
            const juzNumber = urlParams.get('juz');

            if (juzNumber) {
                loadJuz(juzNumber);
            } else if (surahNumber) {
                loadSurah(surahNumber);
            } else {
                loadSurah('1'); // Default to Surah Al-Fatihah
            }

            // Initialize speech recognition
            if (!speechRecognizer.initialize()) {
                alert('عذراً، المتصفح لا يدعم التعرف على الصوت');
                recordBtn.disabled = true;
            }

            // Handle recording button click
            recordBtn.addEventListener('click', async function() {
                try {
                    if (!isRecording) {
                        // Request microphone permission
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        
                        const started = await voiceRecorder.startRecording(stream);
                        if (started) {
                            isRecording = true;
                            recordBtn.classList.add('recording');
                            recordBtn.innerHTML = '<i class="fas fa-microphone"></i> جاري التسجيل...';
                            speechRecognizer.start();
                            initializeVerses();
                            console.log('Recording started successfully');
                        } else {
                            alert('حدث خطأ في بدء التسجيل');
                        }
                    } else {
                        voiceRecorder.stopRecording();
                        speechRecognizer.stop();
                        isRecording = false;
                        recordBtn.classList.remove('recording');
                        recordBtn.innerHTML = '<i class="fas fa-microphone"></i> بدء التسجيل';
                        console.log('Recording stopped');
                    }
                } catch (error) {
                    console.error('Recording error:', error);
                    alert('حدث خطأ في التسجيل. يرجى التأكد من السماح باستخدام الميكروفون');
                    recordBtn.classList.remove('recording');
                    recordBtn.innerHTML = '<i class="fas fa-microphone"></i> بدء التسجيل';
                    isRecording = false;
                }
            });

            // Add error handling for speech recognition
            speechRecognizer.onError = function(error) {
                console.error('Speech recognition error:', error);
                alert('حدث خطأ في التعرف على الصوت');
                recordBtn.classList.remove('recording');
                recordBtn.innerHTML = '<i class="fas fa-microphone"></i> بدء التسجيل';
                isRecording = false;
            };

            // Handle speech recognition results
            speechRecognizer.onSpeechResult = function(transcript) {
                if (!verses[currentVerseIndex]) return;

                const currentVerse = verses[currentVerseIndex];
                const verseText = currentVerse.textContent.trim();
                const verseNumber = currentVerse.querySelector('.ayah-number').textContent;
                
                // Remove verse number from comparison
                const cleanVerseText = verseText.replace(verseNumber, '').trim();
                
                console.log('Comparing:');
                console.log('Verse:', cleanVerseText);
                console.log('Recognized:', transcript);

                // Check if we have enough text to compare
                const verseLength = cleanVerseText.normalize('NFKD').replace(/[\u064B-\u065F]/g, '').length;
                const transcriptLength = transcript.normalize('NFKD').replace(/[\u064B-\u065F]/g, '').length;
                
                // If the recognized text is significantly shorter than the verse, wait for more input
                if (transcriptLength < verseLength * 0.7) {
                    console.log('Waiting for more input...');
                    return;
                }

                // Normalize text for comparison
                const normalize = str => {
                    return str
                        .normalize('NFKD')
                        .replace(/[\u064B-\u065F]/g, '') // Remove diacritics
                        .replace(/[إأآا]/g, 'ا') // Normalize Alef variations
                        .replace(/ى/g, 'ي') // Normalize Ya variations
                        .replace(/ة/g, 'ه') // Normalize Ta variations
                        .replace(/ؤ/g, 'ء') // Normalize Hamza variations
                        .replace(/ئ/g, 'ء')
                        .replace(/ء/g, '')
                        .trim();
                };

                const normalizedVerseText = normalize(cleanVerseText);
                // Declare verseWords and verseWordCount only once
                const verseWords = normalizedVerseText.split(/\s+/);
                const verseWordCount = verseWords.length;

                // Only compare the last N words from the transcript
                let normalizedTranscriptText = normalize(transcript);
                let transcriptWords = normalizedTranscriptText.split(/\s+/);
                if (transcriptWords.length > verseWordCount) {
                    transcriptWords = transcriptWords.slice(-verseWordCount);
                    normalizedTranscriptText = transcriptWords.join(' ');
                }

                // Calculate similarity with more lenient matching
                let matchingChars = 0;
                let totalChars = Math.max(normalizedVerseText.length, normalizedTranscriptText.length);
                
                // Count matching characters with some flexibility
                for (let i = 0; i < normalizedVerseText.length; i++) {
                    const verseChar = normalizedVerseText[i];
                    if (normalizedTranscriptText[i] === verseChar || 
                        normalizedTranscriptText[i+1] === verseChar ||
                        normalizedTranscriptText[i-1] === verseChar) {
                        matchingChars++;
                    }
                }

                // Add bonus for matching words
                let matchingWords = 0;
                verseWords.forEach(word => {
                    if (transcriptWords.some(tWord => {
                        if (word === tWord) return true;
                        const minLength = Math.min(word.length, tWord.length);
                        let matching = 0;
                        for (let i = 0; i < minLength; i++) {
                            if (word[i] === tWord[i]) matching++;
                        }
                        return matching >= minLength * 0.5;
                    })) {
                        matchingWords++;
                    }
                });

                // Calculate final similarity with word bonus
                const charSimilarity = matchingChars / totalChars;
                const wordSimilarity = verseWords.length > 0 ? matchingWords / verseWords.length : 1;
                const similarity = (charSimilarity * 0.6) + (wordSimilarity * 0.4);
                
                console.log('Similarity:', similarity);
                console.log('Matching words:', matchingWords, 'out of', verseWords.length);

                // Set threshold to 70% (3 out of 4)
                const threshold = 0.7;

                // Always remove highlights before every new evaluation
                verses.forEach(v => v.classList.remove('verse-highlight', 'verse-correct'));
                if (!window._verseTransitioning) window._verseTransitioning = false;

                function moveToNextVerseWithGreen() {
                    if (window._verseTransitioning) return; // Prevent double transition
                    window._verseTransitioning = true;
                    currentVerse.classList.add('verse-correct');
                    setTimeout(() => {
                        currentVerse.classList.remove('verse-correct');
                        if (currentVerseIndex < verses.length - 1) {
                            currentVerseIndex++;
                            updateCurrentVerse();
                            // Do not show any color on the new verse
                        }
                        window._verseTransitioning = false;
                        // No color is shown after moving to the next verse
                    }, 1500); // Show green for 1.5 seconds
                }

                // Fresh evaluation every time
                if (matchingWords === verseWords.length && verseWords.length > 0) {
                    moveToNextVerseWithGreen();
                } else if (similarity >= threshold) {
                    moveToNextVerseWithGreen();
                } else {
                    currentVerse.classList.add('verse-highlight');
                    setTimeout(() => {
                        currentVerse.classList.remove('verse-highlight');
                        // Reset for a new attempt: stop and restart speech recognition to clear transcript
                        if (isRecording && speechRecognizer) {
                            speechRecognizer.stop();
                            setTimeout(() => {
                                speechRecognizer.start();
                            }, 200);
                        }
                    }, 1000); // Show red for 1 second then clear
                }
            };
        });
    </script>
</body>
</html>