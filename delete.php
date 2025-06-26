<?php
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ProductID'];

    $sql = "DELETE FROM InventoryTable WHERE ProductID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    echo "Delete successful.";
}
?>
