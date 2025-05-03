<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // التحقق من وجود الملف
    if (!file_exists('recitation.json')) {
        throw new Exception('ملف البيانات غير موجود');
    }

    // قراءة ملف JSON
    $jsonContent = file_get_contents('recitation.json');
    if ($jsonContent === false) {
        throw new Exception('فشل في قراءة ملف البيانات');
    }

    // تحويل JSON إلى مصفوفة
    $surahs = json_decode($jsonContent, true);
    if ($surahs === null) {
        throw new Exception('خطأ في تنسيق ملف JSON');
    }

    // ترتيب السور حسب الرقم
    usort($surahs, function($a, $b) {
        return $a['number'] - $b['number'];
    });

    // تنظيف البيانات وإرجاع المعلومات المطلوبة فقط
    $cleanedSurahs = array_map(function($surah) {
        return [
            'number' => (int)$surah['number'],
            'name' => $surah['name'],
            'ayahs' => (int)$surah['ayahs']
        ];
    }, $surahs);

    // إرجاع البيانات
    echo json_encode($cleanedSurahs, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?> 