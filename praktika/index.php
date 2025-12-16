<?php
require_once 'db.php';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$categories = getCategories($pdo);
$products = getProducts($pdo, $category_id);
$current_category = getCategoryName($pdo, $category_id);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Столовая - Меню</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #4CAF50;
        }
        
        header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #666;
            font-size: 1.1em;
        }
        
        .category-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .category-info h2 {
            color: #333;
        }
        
        .product-count {
            background: #4CAF50;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .categories {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }
        
        .category-btn {
            padding: 12px 20px;
            background: white;
            border: 2px solid #ddd;
            border-radius: 5px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .category-btn:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
        }
        
        .category-btn.active {
            background: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: white;
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .product-category {
            display: inline-block;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.8em;
            margin-bottom: 10px;
        }
        
        .product-name {
            font-size: 1.2em;
            margin-bottom: 8px;
            color: #333;
        }
        
        .product-description {
            color: #666;
            margin-bottom: 10px;
            font-size: 0.9em;
        }
        
        .product-price {
            color: #4CAF50;
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .availability {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.8em;
            font-weight: bold;
        }
        
        .available {
            background: #d4edda;
            color: #155724;
        }
        
        .not-available {
            background: #f8d7da;
            color: #721c24;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            margin-top: 30px;
            border-top: 1px solid #ddd;
            color: #666;
        }
        
        .no-products {
            text-align: center;
            padding: 40px;
            grid-column: 1 / -1;
        }
        
        .no-products i {
            font-size: 3em;
            color: #ccc;
            margin-bottom: 15px;
        }
        
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .categories {
                flex-direction: column;
                align-items: center;
            }
            
            .category-btn {
                width: 100%;
                max-width: 300px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🍽️ Меню столовой</h1>
            <p class="subtitle">Выберите категорию для просмотра блюд</p>
        </header>
        
        <div class="category-info">
            <h2>Категория: <?php echo htmlspecialchars($current_category); ?></h2>
            <div class="product-count">Найдено: <?php echo count($products); ?> блюд</div>
        </div>
        
        <div class="categories">
            <a href="?category=0" class="category-btn <?php echo $category_id == 0 ? 'active' : ''; ?>">
                📋 Все блюда
            </a>
            
            <?php foreach ($categories as $category): ?>
            <a href="?category=<?php echo $category['id']; ?>" 
               class="category-btn <?php echo $category_id == $category['id'] ? 'active' : ''; ?>">
                <?php 
                $icons = [
                    'Напитки' => '🥤',
                    'Супы' => '🍲',
                    'Основные блюда' => '🍛',
                    'Салаты' => '🥗',
                    'Десерты' => '🍰',
                    'Выпечка' => '🥐'
                ];
                echo $icons[$category['name']] ?? '📦';
                ?>
                <?php echo htmlspecialchars($category['name']); ?>
            </a>
            <?php endforeach; ?>
        </div>
        
        <?php if (count($products) > 0): ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-category">
                    <?php echo htmlspecialchars($product['category_name']); ?>
                </div>
                
                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                
                <p class="product-description">
                    <?php echo htmlspecialchars($product['description']); ?>
                </p>
                
                <div class="product-price">
                    <?php echo number_format($product['price'], 2, '.', ' '); ?> ₽
                </div>
                
                <div class="product-footer">
                    
                    <?php if ($product['is_available']): ?>
                    <form method="POST" action="cart.php" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" style="
                            background: #4CAF50;
                            color: white;
                            border: none;
                            padding: 8px 15px;
                            border-radius: 4px;
                            cursor: pointer;
                            font-weight: bold;
                        ">
                            🛒 В корзину
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="no-products">
            <div>🍽️</div>
            <h3>Блюда не найдены</h3>
            <p>В выбранной категории пока нет доступных блюд</p>
            <a href="?category=0" class="back-btn">Вернуться ко всем блюдам</a>
        </div>
        <?php endif; ?>
        
    </div>
</body>
</html>