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
    // First, check if search term looks like a ComboID (numbers only and longer than typical ProductID)
    $isComboID = preg_match('/^\d{8,}$/', $search_term);
    
    if ($isComboID) {
        // Search by ComboID - need to find ProductID and SupplierID combination
        $sql = "SELECT ProductID, ProductName, Quantity, Price, Status, SupplierID, SupplierName 
                FROM InventoryTable 
                WHERE CONCAT(ProductID, SupplierID) LIKE :term
                ORDER BY ProductID ASC";
    } else {
        // Regular search by ProductID, ProductName, or SupplierName
        $sql = "SELECT ProductID, ProductName, Quantity, Price, Status, SupplierID, SupplierName 
                FROM InventoryTable 
                WHERE ProductID LIKE :term 
                OR ProductName LIKE :term 
                OR SupplierName LIKE :term 
                OR CONCAT(ProductID, SupplierID) LIKE :term
                ORDER BY ProductID ASC";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':term' => "%$search_term%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Modify results to include combined Product ID without dash
    foreach ($results as &$result) {
        $result['CombinedProductID'] = $result['ProductID'] . $result['SupplierID'];
    }
    
    echo json_encode($results);
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
