<?php
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['ProductID'];
    $supplierId = $_POST['SupplierID'];
    $qty = $_POST['Quantity'];
    $price = $_POST['Price'];
    $status = $_POST['Status'];

    try {
        // Use both ProductID and SupplierID for precise record identification
        $sql = "UPDATE InventoryTable 
                SET Quantity = :qty, Price = :price, Status = :status 
                WHERE ProductID = :productId AND SupplierID = :supplierId";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':qty' => $qty,
            ':price' => $price,
            ':status' => $status,
            ':productId' => $productId,
            ':supplierId' => $supplierId
        ]);

        // Redirect back to dashboard with success message
        header("Location: dashboard.php?message=Product updated successfully");
        exit();
    } catch (PDOException $e) {
        // Redirect back to dashboard with error message
        header("Location: dashboard.php?error=" . urlencode("Error updating product: " . $e->getMessage()));
        exit();
    }
} else {
    // If not POST request, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}
?>
