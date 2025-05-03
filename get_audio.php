<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // التحقق من وجود المعلمات المطلوبة
    if (!isset($_GET['surah']) || !isset($_GET['reciter'])) {
        throw new Exception('Missing parameters');
    }

    $surahNumber = intval($_GET['surah']);
    $reciter = $_GET['reciter'];

    // التحقق من صحة رقم السورة
    if ($surahNumber < 1 || $surahNumber > 114) {
        throw new Exception('Invalid surah number');
    }

    // تنسيق رقم السورة بإضافة أصفار في البداية
    $formattedSurahNumber = str_pad($surahNumber, 3, '0', STR_PAD_LEFT);

    // تعريف روابط القراء
    $reciterUrls = [
        'maher_almuaiqly' => [
            'base_url' => 'https://verses.quran.com/Alafasy/',
            'format' => '%d.mp3'
        ],
        'mishari_al_afasy' => [
            'base_url' => 'https://everyayah.com/data/Alafasy_128kbps/',
            'format' => '%03d.mp3'
        ],
        'abdul_baset_abdulsamad' => [
            'base_url' => 'https://server7.mp3quran.net/basit/',
            'format' => '%s.mp3'
        ],
        'ahmad_al_ajmy' => [
            'base_url' => 'https://server10.mp3quran.net/ajm/',
            'format' => '%03d.mp3'
        ],
        'khalil_al_husary' => [
            'base_url' => 'https://server13.mp3quran.net/husr/',
            'format' => '%s.mp3'
        ]
    ];

    // التحقق من وجود القارئ
    if (!isset($reciterUrls[$reciter])) {
        throw new Exception('Invalid reciter');
    }

    // بناء رابط الصوت
    $reciterInfo = $reciterUrls[$reciter];
    
    // تحديد تنسيق رقم السورة حسب القارئ
    if ($reciter === 'mishari_al_afasy') {
        $audioUrl = $reciterInfo['base_url'] . sprintf($reciterInfo['format'], $surahNumber);
    } else if ($reciter === 'maher_almuaiqly') {
        $audioUrl = $reciterInfo['base_url'] . sprintf($reciterInfo['format'], $surahNumber);
    } else {
        $audioUrl = $reciterInfo['base_url'] . sprintf($reciterInfo['format'], $formattedSurahNumber);
    }

    // محاولة التحقق من وجود الملف
    $headers = @get_headers($audioUrl);
    if (!$headers || strpos($headers[0], '200') === false) {
        // روابط بديلة لكل قارئ
        $alternativeUrls = [
            'maher_almuaiqly' => [
                'base_url' => 'https://server12.mp3quran.net/maher/',
                'format' => '%s.mp3'
            ],
            'mishari_al_afasy' => [
                'base_url' => 'https://server8.mp3quran.net/afs/',
                'format' => '%s.mp3'
            ],
            'abdul_baset_abdulsamad' => [
                'base_url' => 'https://server8.mp3quran.net/basit/',
                'format' => '%s.mp3'
            ],
            'ahmad_al_ajmy' => [
                'base_url' => 'https://server10.mp3quran.net/ajm/',
                'format' => '%03d.mp3'
            ],
            'khalil_al_husary' => [
                'base_url' => 'https://server7.mp3quran.net/husr/',
                'format' => '%s.mp3'
            ]
        ];

        if (isset($alternativeUrls[$reciter])) {
            $altInfo = $alternativeUrls[$reciter];
            $audioUrl = $altInfo['base_url'] . sprintf($altInfo['format'], $formattedSurahNumber);
        }
    }

    // قراءة ملف JSON للتحقق من وجود السورة
    if (!file_exists('recitation.json')) {
        throw new Exception('Recitation file not found');
    }

    $jsonContent = file_get_contents('recitation.json');
    if ($jsonContent === false) {
        throw new Exception('Failed to read recitation file');
    }

    $surahs = json_decode($jsonContent, true);
    if ($surahs === null) {
        throw new Exception('Invalid JSON format');
    }

    // التحقق من وجود السورة في ملف JSON
    $surahExists = false;
    $surahName = '';
    foreach ($surahs as $surah) {
        if ($surah['number'] === $surahNumber) {
            $surahExists = true;
            $surahName = $surah['name'];
            break;
        }
    }

    if (!$surahExists) {
        throw new Exception('Surah not found');
    }

    // إضافة معلومات التشخيص
    $debug_info = [
        'audio_url' => $audioUrl,
        'surah_number' => $surahNumber,
        'formatted_number' => $formattedSurahNumber,
        'surah_name' => $surahName,
        'reciter' => $reciter,
        'headers' => $headers
    ];

    // إرجاع رابط الصوت مع معلومات التشخيص
    echo json_encode($debug_info, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>