<?php
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['ProductID'];
    $supplierId = $_POST['SupplierID'];

    try {
        // Use both ProductID and SupplierID for precise record identification
        $sql = "DELETE FROM InventoryTable WHERE ProductID = :productId AND SupplierID = :supplierId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':productId' => $productId, ':supplierId' => $supplierId]);

        // Redirect back to dashboard with success message
        header("Location: dashboard.php?message=Product deleted successfully");
        exit();
    } catch (PDOException $e) {
        // Redirect back to dashboard with error message
        header("Location: dashboard.php?error=" . urlencode("Error deleting product: " . $e->getMessage()));
        exit();
    }
} else {
    // If not POST request, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}
?>
