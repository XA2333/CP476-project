<?php
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['ProductID'];
    $productName = $_POST['ProductName'];
    $quantity = $_POST['Quantity'];
    $price = $_POST['Price'];
    $status = $_POST['Status'];
    $supplierId = $_POST['SupplierID'];
    $supplierName = $_POST['SupplierName'];

    try {
        // Insert new product into InventoryTable with SupplierID
        $sql = "INSERT INTO InventoryTable (ProductID, ProductName, Quantity, Price, Status, SupplierID, SupplierName) 
                VALUES (:productId, :productName, :quantity, :price, :status, :supplierId, :supplierName)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':productId' => $productId,
            ':productName' => $productName,
            ':quantity' => $quantity,
            ':price' => $price,
            ':status' => $status,
            ':supplierId' => $supplierId,
            ':supplierName' => $supplierName
        ]);

        // Redirect back to dashboard with success message
        header("Location: dashboard.php?message=Product added successfully");
        exit();
    } catch (PDOException $e) {
        // Redirect back to dashboard with error message
        header("Location: dashboard.php?error=" . urlencode("Error adding product: " . $e->getMessage()));
        exit();
    }
} else {
    // If not POST request, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}
?>
