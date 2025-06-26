<?php
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ProductID'];
    $qty = $_POST['Quantity'];
    $price = $_POST['Price'];
    $status = $_POST['Status'];

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

    echo "Update successful.";
}
?>
