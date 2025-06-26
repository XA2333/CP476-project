<?php
/**
 * search_products.php
 * 
 * AJAX endpoint for searching products in the inventory
 * Returns JSON data for product search functionality
 * 
 * @author Wentao Ma
 * @package CP476
 */

header('Content-Type: application/json');
require_once 'connect.php';

$search_term = isset($_GET['q']) ? $_GET['q'] : '';

if (empty($search_term)) {
    echo json_encode([]);
    exit();
}

try {
    $sql = "SELECT ProductID, ProductName, Quantity, Price, Status, SupplierName 
            FROM InventoryTable 
            WHERE ProductID LIKE :term 
            OR ProductName LIKE :term 
            OR SupplierName LIKE :term 
            ORDER BY ProductID ASC ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':term' => "%$search_term%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($results);
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
