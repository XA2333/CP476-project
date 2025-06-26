<?php
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ProductID'];
    $qty = $_POST['Quantity'];
    $price = $_POST['Price'];
    $status = $_POST['Status'];

    try {
        $sql = "UPDATE InventoryTable 
                SET Quantity = :qty, Price = :price, Status = :status 
                WHERE ProductID = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':qty' => $qty,
            ':price' => $price,
            ':status' => $status,
            ':id' => $id
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
