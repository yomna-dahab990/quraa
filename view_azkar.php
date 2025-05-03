<?php
require_once 'includes/azkar_data.php';

$subcategory_id = isset($_GET['subcategory']) ? (int)$_GET['subcategory'] : null;

if (!$subcategory_id) {
    header('Location: azkar.php');
    exit;
}

$azkar_list = getAzkarBySubcategory($subcategory_id);
$categories = getAzkarCategories();

// Find the subcategory details
$subcategory_details = null;
foreach ($categories as $category) {
    foreach ($category['subcategories'] as $subcategory) {
        if ($subcategory['id'] == $subcategory_id) {
            $subcategory_details = $subcategory;
            $category_details = $category;
            break 2;
        }
    }
}

if (!$subcategory_details) {
    header('Location: azkar.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $subcategory_details['name']; ?> - الأذكار</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2196F3;
            --text-color: #333;
            --background-color: #f5f5f5;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .breadcrumb {
            background: white;
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb i {
            margin: 0 10px;
            color: #666;
        }

        .azkar-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .zekr-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .zekr-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .zekr-text {
            font-size: 1.2em;
            margin-bottom: 15px;
            line-height: 1.6;
            color: #333;
        }

        .zekr-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .zekr-count {
            background: var(--primary-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .zekr-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
            font-size: 1.2em;
        }

        .action-btn:hover {
            color: var(--primary-color);
        }

        .category-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .category-header h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .category-header p {
            color: #666;
            font-size: 1.1em;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .azkar-list {
                grid-template-columns: 1fr;
            }

            .zekr-text {
                font-size: 1.1em;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="breadcrumb">
            <a href="azkar.php">الرئيسية</a>
            <i class="fas fa-chevron-left"></i>
            <a href="azkar.php"><?php echo $category_details['name']; ?></a>
            <i class="fas fa-chevron-left"></i>
            <span><?php echo $subcategory_details['name']; ?></span>
        </div>

        <div class="category-header">
            <h1><?php echo $subcategory_details['name']; ?></h1>
            <p>عدد الأذكار: <?php echo count($azkar_list); ?></p>
        </div>

        <div class="azkar-list">
            <?php foreach ($azkar_list as $zekr): ?>
                <div class="zekr-card">
                    <div class="zekr-text"><?php echo $zekr['text']; ?></div>
                    <div class="zekr-info">
                        <div class="zekr-count">
                            التكرار: <?php echo isset($zekr['count']) ? $zekr['count'] : 1; ?>
                        </div>
                        <div class="zekr-actions">
                            <button class="action-btn copy-btn" title="نسخ" data-text="<?php echo htmlspecialchars($zekr['text']); ?>">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="action-btn share-btn" title="مشاركة" data-text="<?php echo htmlspecialchars($zekr['text']); ?>">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // Configure toastr
            toastr.options = {
                "closeButton": true,
                "positionClass": "toast-bottom-right",
                "timeOut": "2000"
            };

            // Copy functionality
            $('.copy-btn').click(function() {
                const text = $(this).data('text');
                navigator.clipboard.writeText(text).then(function() {
                    toastr.success('تم نسخ النص بنجاح');
                }).catch(function() {
                    toastr.error('حدث خطأ أثناء نسخ النص');
                });
            });

            // Share functionality
            $('.share-btn').click(function() {
                const text = $(this).data('text');
                if (navigator.share) {
                    navigator.share({
                        text: text
                    }).then(() => {
                        toastr.success('تمت المشاركة بنجاح');
                    }).catch((error) => {
                        toastr.error('حدث خطأ أثناء المشاركة');
                    });
                } else {
                    navigator.clipboard.writeText(text).then(function() {
                        toastr.success('تم نسخ النص للمشاركة');
                    }).catch(function() {
                        toastr.error('حدث خطأ أثناء نسخ النص');
                    });
                }
            });
        });
    </script>
</body>
</html> 