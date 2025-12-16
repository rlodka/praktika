<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "cafeteria_db";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
function getCategories($pdo) {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY id");
    return $stmt->fetchAll();
}

function getProducts($pdo, $category_id = 0) {
    if ($category_id > 0) {
        $stmt = $pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.category_id = ? 
            ORDER BY p.name
        ");
        $stmt->execute([$category_id]);
    } else {
        $stmt = $pdo->query("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            ORDER BY p.category_id, p.name
        ");
    }
    return $stmt->fetchAll();
}

function getCategoryName($pdo, $category_id) {
    if ($category_id == 0) return "Все блюда";
    
    $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch();
    
    return $category ? $category['name'] : "Все блюда";
}
?>