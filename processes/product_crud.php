<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'create':
            $name = sanitize($_POST['name']);
            $description = sanitize($_POST['description']);
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];
            $category = sanitize($_POST['category']);
            $sku = sanitize($_POST['sku']);
            
            $query = "INSERT INTO products (user_id, name, description, price, stock_quantity, category, sku) 
                      VALUES (:user_id, :name, :description, :price, :stock_quantity, :category, :sku)";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock_quantity', $stock_quantity);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':sku', $sku);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan produk']);
            }
            break;
            
        case 'delete':
            $product_id = $_POST['product_id'];
            
            $query = "DELETE FROM products WHERE id = :id AND user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $product_id);
            $stmt->bindParam(':user_id', $user_id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Produk berhasil dihapus']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menghapus produk']);
            }
            break;
    }
}
?>