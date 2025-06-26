<?php
require 'connect.php';

$term = $_GET['q'] ?? '';
$sql = "SELECT * FROM InventoryTable 
        WHERE ProductID LIKE :term 
        OR ProductName LIKE :term 
        OR SupplierName LIKE :term 
        ORDER BY ProductID ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':term' => "%$term%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1'>";
foreach ($results as $row) {
    echo "<tr>";
    foreach ($row as $col) {
        echo "<td>".htmlspecialchars($col)."</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>
