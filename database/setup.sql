DROP DATABASE IF EXISTS quraa_db;
CREATE DATABASE quraa_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quraa_db;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE azkar_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    name_ar VARCHAR(100),
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE favorites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    type VARCHAR(20),
    item_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدخال بيانات التصنيفات
INSERT INTO azkar_categories (name, name_ar, description, icon) VALUES
('Morning', 'أذكار الصباح', 'أذكار الصباح من السنة النبوية', 'fa-sun'),
('Evening', 'أذكار المساء', 'أذكار المساء من السنة النبوية', 'fa-moon'),
('Sleep', 'أذكار النوم', 'أذكار النوم من السنة النبوية', 'fa-bed'),
('Prayer', 'أذكار الصلاة', 'أذكار الصلاة من السنة النبوية', 'fa-pray'),
('Food', 'أذكار الطعام', 'أذكار الطعام من السنة النبوية', 'fa-utensils'),
('Travel', 'أذكار السفر', 'أذكار السفر من السنة النبوية', 'fa-plane'),
('General', 'أذكار عامة', 'أذكار عامة من السنة النبوية', 'fa-heart');

-- إدخال بيانات الأذكار
INSERT INTO azkar (category_id, text, text_with_tashkeel, source, count, fadl) VALUES
(1, 'أصبحنا وأصبح الملك لله، والحمد لله، لا إله إلا الله وحده لا شريك له، له الملك وله الحمد وهو على كل شيء قدير', 'أَصْبَحْنَا وَأَصْبَحَ الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٌ', 'رواه مسلم', 1, 'من قالها حين يصبح أدى شكر يومه'),
(1, 'اللهم بك أصبحنا، وبك أمسينا، وبك نحيا، وبك نموت، وإليك النشور', 'اللَّهُمَّ بِكَ أَصْبَحْنَا، وَبِكَ أَمْسَيْنَا، وَبِكَ نَحْيَا، وَبِكَ نَمُوتُ، وَإِلَيْكَ النُّشُورُ', 'رواه الترمذي', 1, 'من قالها حين يصبح فقد أدى شكر يومه'),
(2, 'أمسينا وأمسى الملك لله، والحمد لله، لا إله إلا الله وحده لا شريك له، له الملك وله الحمد وهو على كل شيء قدير', 'أَمْسَيْنَا وَأَمْسَى الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٌ', 'رواه مسلم', 1, 'من قالها حين يمسي أدى شكر ليلته'),
(3, 'باسمك اللهم أموت وأحيا', 'بِاسْمِكَ اللَّهُمَّ أَمُوتُ وَأَحْيَا', 'رواه البخاري', 1, 'من قالها عند النوم مات على الفطرة'),
(4, 'سبحان ربي العظيم', 'سُبْحَانَ رَبِّيَ الْعَظِيمِ', 'متفق عليه', 3, 'تقال في الركوع'),
(5, 'اللهم بارك لنا فيما رزقتنا وقنا عذاب النار', 'اللَّهُمَّ بَارِكْ لَنَا فِيمَا رَزَقْتَنَا وَقِنَا عَذَابَ النَّارِ', 'رواه مسلم', 1, 'دعاء الطعام'),
(6, 'اللهم إنا نسألك في سفرنا هذا البر والتقوى، ومن العمل ما ترضى', 'اللَّهُمَّ إِنَّا نَسْأَلُكَ فِي سَفَرِنَا هَذَا الْبِرَّ وَالتَّقْوَى، وَمِنَ الْعَمَلِ مَا تَرْضَى', 'رواه مسلم', 1, 'دعاء السفر'),
(7, 'سبحان الله وبحمده', 'سُبْحَانَ اللَّهِ وَبِحَمْدِهِ', 'متفق عليه', 100, 'من قالها مائة مرة حُطت خطاياه وإن كانت مثل زبد البحر'); 