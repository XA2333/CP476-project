<?php
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ProductID'];

    try {
        $sql = "DELETE FROM InventoryTable WHERE ProductID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

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
