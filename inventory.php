<?php
require 'connect.php';

$stmt = $pdo->prepare("SELECT * FROM InventoryTable ORDER BY ProductID ASC");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1'>";
echo "<tr><th>ProductID</th><th>ProductName</th><th>Quantity</th><th>Price</th><th>Status</th><th>SupplierName</th></tr>";
foreach ($rows as $row) {
    echo "<tr>";
    foreach ($row as $cell) {
        echo "<td>".htmlspecialchars($cell)."</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>
