-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS quraa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quraa_db;

-- جدول المستخدمين
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول القرآن الكريم
CREATE TABLE quran (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sura_number INT,
    sura_name VARCHAR(100),
    sura_name_ar VARCHAR(100),
    ayah_number INT,
    ayah_text TEXT,
    ayah_text_with_tashkeel TEXT,
    page_number INT,
    juz_number INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول المفسرين
CREATE TABLE interpreters (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    name_ar VARCHAR(100),
    biography TEXT,
    death_year INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول التفسير
CREATE TABLE tafseer (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quran_id INT,
    interpreter_id INT,
    interpretation_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quran_id) REFERENCES quran(id),
    FOREIGN KEY (interpreter_id) REFERENCES interpreters(id)
);

-- جدول القراء
CREATE TABLE readers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    name_ar VARCHAR(100),
    biography TEXT,
    image_url VARCHAR(255),
    country VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول التلاوات
CREATE TABLE recitations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reader_id INT,
    quran_id INT,
    audio_url VARCHAR(255),
    duration INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reader_id) REFERENCES readers(id),
    FOREIGN KEY (quran_id) REFERENCES quran(id)
);

-- جدول تصنيفات الأذكار
CREATE TABLE azkar_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    name_ar VARCHAR(100),
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول الأذكار
CREATE TABLE azkar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    text TEXT NOT NULL,
    text_with_tashkeel TEXT,
    source VARCHAR(255),
    count INT DEFAULT 1,
    fadl TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES azkar_categories(id)
);

-- جدول المفضلة
CREATE TABLE favorites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    content_type ENUM('quran', 'azkar', 'recitation'),
    content_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- إضافة تصنيفات الأذكار الأساسية
INSERT INTO azkar_categories (name, name_ar, description, icon) VALUES
('Morning', 'أذكار الصباح', 'أذكار الصباح من السنة النبوية', 'fa-sun'),
('Evening', 'أذكار المساء', 'أذكار المساء من السنة النبوية', 'fa-moon'),
('Sleep', 'أذكار النوم', 'أذكار النوم من السنة النبوية', 'fa-bed'),
('Prayer', 'أذكار الصلاة', 'أذكار الصلاة من السنة النبوية', 'fa-pray'),
('Food', 'أذكار الطعام', 'أذكار الطعام من السنة النبوية', 'fa-utensils'),
('Travel', 'أذكار السفر', 'أذكار السفر من السنة النبوية', 'fa-plane'),
('Morning and Evening', 'أذكار الصباح والمساء', 'أذكار الصباح والمساء من السنة النبوية', 'fa-clock');

-- إضافة بعض الأذكار كمثال
INSERT INTO azkar (category_id, text, text_with_tashkeel, source, count, fadl) VALUES
(1, 'أَصْبَحْنَا وَأَصْبَحَ الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَٰهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَىٰ كُلِّ شَيْءٍ قَدِيرٌ', 'أَصْبَحْنَا وَأَصْبَحَ الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَٰهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَىٰ كُلِّ شَيْءٍ قَدِيرٌ', 'رواه مسلم', 1, 'من قالها حين يصبح أدى شكر يومه'),
(1, 'اللَّهُمَّ بِكَ أَصْبَحْنَا، وَبِكَ أَمْسَيْنَا، وَبِكَ نَحْيَا، وَبِكَ نَمُوتُ، وَإِلَيْكَ النُّشُورُ', 'اللَّهُمَّ بِكَ أَصْبَحْنَا، وَبِكَ أَمْسَيْنَا، وَبِكَ نَحْيَا، وَبِكَ نَمُوتُ، وَإِلَيْكَ النُّشُورُ', 'رواه الترمذي', 1, 'من قالها حين يصبح فقد أدى شكر يومه'),
(2, 'أَمْسَيْنَا وَأَمْسَى الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَٰهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَىٰ كُلِّ شَيْءٍ قَدِيرٌ', 'أَمْسَيْنَا وَأَمْسَى الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَٰهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَىٰ كُلِّ شَيْءٍ قَدِيرٌ', 'رواه مسلم', 1, 'من قالها حين يمسي أدى شكر ليلته'),
(2, 'اللَّهُمَّ بِكَ أَمْسَيْنَا، وَبِكَ أَصْبَحْنَا، وَبِكَ نَحْيَا، وَبِكَ نَمُوتُ، وَإِلَيْكَ الْمَصِيرُ', 'اللَّهُمَّ بِكَ أَمْسَيْنَا، وَبِكَ أَصْبَحْنَا، وَبِكَ نَحْيَا، وَبِكَ نَمُوتُ، وَإِلَيْكَ الْمَصِيرُ', 'رواه الترمذي', 1, 'من قالها حين يمسي فقد أدى شكر ليلته'),
(3, 'بِاسْمِكَ اللَّهُمَّ أَمُوتُ وَأَحْيَا', 'بِاسْمِكَ اللَّهُمَّ أَمُوتُ وَأَحْيَا', 'رواه البخاري', 1, 'من قالها عند النوم مات على الفطرة'),
(3, 'اللَّهُمَّ قِنِي عَذَابَكَ يَوْمَ تَبْعَثُ عِبَادَكَ', 'اللَّهُمَّ قِنِي عَذَابَكَ يَوْمَ تَبْعَثُ عِبَادَكَ', 'رواه أبو داود', 3, 'من قالها ثلاث مرات عند النوم حفظه الله من عذاب القبر');

-- إضافة بعض القراء كمثال
INSERT INTO readers (name, name_ar, biography, image_url, country) VALUES
('Abdul Basit Abdul Samad', 'عبد الباسط عبد الصمد', 'من أشهر قراء القرآن الكريم في العالم الإسلامي', 'images/readers/abdulbasit.jpg', 'مصر'),
('Muhammad Siddiq Al-Minshawi', 'محمد صديق المنشاوي', 'من أبرز قراء القرآن الكريم في القرن العشرين', 'images/readers/minshawi.jpg', 'مصر'),
('Maher Al-Muaiqly', 'ماهر المعيقلي', 'إمام الحرم المكي الشريف', 'images/readers/maher.jpg', 'السعودية'); 