<?php
header('Content-Type: application/json; charset=utf-8');

// تحقق من وجود المعلمات المطلوبة
$surah_number = isset($_GET['surah_number']) ? (int)$_GET['surah_number'] : 0;
$ayah = isset($_GET['ayah']) ? (int)$_GET['ayah'] : 0;

// التحقق من صحة المدخلات
if ($surah_number < 1 || $surah_number > 114 || $ayah < 1) {
    echo json_encode([
        ['error' => 'رقم السورة أو الآية غير صحيح']
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// مصفوفة تحتوي على عدد آيات كل سورة
$ayahCounts = [
    7, 286, 200, 176, 120, 165, 206, 75, 129, 109, 123, 111, 43, 52, 99, 128,
    111, 110, 98, 135, 112, 78, 118, 64, 77, 227, 93, 88, 69, 60, 34, 30,
    73, 54, 45, 83, 182, 88, 75, 85, 54, 53, 89, 59, 37, 35, 38, 29,
    18, 45, 60, 49, 62, 55, 78, 96, 29, 22, 24, 13, 14, 11, 11, 18,
    12, 12, 30, 52, 52, 44, 28, 28, 20, 56, 40, 31, 50, 40, 46, 42,
    29, 19, 36, 25, 22, 17, 19, 26, 30, 20, 15, 21, 11, 8, 8, 19,
    5, 8, 8, 11, 11, 8, 3, 9, 5, 4, 7, 3, 6, 3, 5, 4, 5, 6
];

// التحقق من أن رقم الآية لا يتجاوز عدد آيات السورة
if ($ayah > $ayahCounts[$surah_number - 1]) {
    echo json_encode([
        ['error' => 'رقم الآية غير صحيح']
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// مسارات الملفات
$json_file_tafseer = 'data/tafseer_muyassar.json';
$json_file_quran   = 'quran.json';

// تحميل بيانات التفسير والقرآن
$tafseer_data = json_decode(file_get_contents($json_file_tafseer), true);
$quran_data   = json_decode(file_get_contents($json_file_quran), true);

// متغيرات البحث
$tafseer_found = false;
$ayah_text     = '';

// البحث عن التفسير
foreach ($tafseer_data as $item) {
    if ((int)$item['number'] === $surah_number && (int)$item['aya'] === $ayah) {
        $tafseer_text  = $item['text'];
        $tafseer_found = true;
        break;
    }
}

// البحث عن نص الآية
if (isset($quran_data[$surah_number])) {
    foreach ($quran_data[$surah_number] as $verse) {
        if ((int)$verse['verse'] === $ayah) {
            $ayah_text = $verse['text'];
            break;
        }
    }
}

// بناء الاستجابة
if ($tafseer_found) {
    $response[] = [
        'surah_number' => $surah_number,
        'ayah_number'  => $ayah,
        'ayah_text'    => $ayah_text,    // هنا ربط نص الآية
        'tafseer_text' => $tafseer_text,
    ];
} else {
    $response[] = ['error' => 'لم يتم العثور على تفسير لهذه الآية'];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>
