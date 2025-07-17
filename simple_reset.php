<?php
/**
 * simple_reset.php
 * 
 * Simple and reliable database reset script
 */

require_once 'connect.php';

try {
    echo "<h2>Starting Database Reset...</h2>";
    
    // Drop all tables
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("DROP TABLE IF EXISTS InventoryTable");
    $pdo->exec("DROP TABLE IF EXISTS ProductTable");
    $pdo->exec("DROP TABLE IF EXISTS SupplierTable");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "✅ Dropped existing tables<br>";
    
    // Create SupplierTable
    $pdo->exec("
    CREATE TABLE SupplierTable (
      SupplierID INT PRIMARY KEY,
      SupplierName VARCHAR(255) NOT NULL,
      Address VARCHAR(255),
      Phone VARCHAR(50),
      Email VARCHAR(255)
    )");
    
    echo "✅ Created SupplierTable<br>";
    
    // Create InventoryTable (simplified, no ProductTable needed)
    $pdo->exec("
    CREATE TABLE InventoryTable (
        ProductID INT,
        ProductName VARCHAR(255),
        Quantity INT,
        Price DECIMAL(10,2),
        Status CHAR(1),
        SupplierID INT,
        SupplierName VARCHAR(255),
        INDEX idx_product_supplier (ProductID, SupplierID)
    )");
    
    echo "✅ Created InventoryTable<br>";
    
    // Insert supplier data
    $pdo->exec("
    INSERT INTO SupplierTable (SupplierID, SupplierName, Address, Phone, Email) VALUES
    (7890, 'Samsung', '456 Seoul St', '909-763-4442', 'support@samsung.com'),
    (9876, 'Toshiba', '246 Osaka St', '90-6378-0835', 'support@toshiba.co.jp'),
    (3456, 'Panasonic', '246 Osaka St', '443-887-9967', 'support@panasonic.co.jp'),
    (8765, 'Philips', '789 Amsterdam St', '61-483-898-670', 'support@philips.au'),
    (9144, 'Fujitsu', '456 Tokyo St', '03-3556-7890', 'support@fujitsu.co.jp')
    ");
    
    echo "✅ Inserted supplier data<br>";
    
    // Insert inventory data directly
    $pdo->exec("
    INSERT INTO InventoryTable (ProductID, ProductName, Quantity, Price, Status, SupplierID, SupplierName) VALUES
    (2591, 'Camera', 50, 799.90, 'B', 7890, 'Samsung'),
    (3374, 'Laptop', 30, 1799.90, 'A', 9876, 'Toshiba'),
    (3034, 'Telephone', 40, 299.99, 'A', 3456, 'Panasonic'),
    (1234, 'TV', 20, 799.90, 'C', 9144, 'Fujitsu'),
    (1516, 'Mouse', 30, 99.50, 'A', 8765, 'Philips')
    ");
    
    echo "✅ Inserted inventory data<br>";
    
    // Check results
    $stmt = $pdo->query("SELECT COUNT(*) FROM InventoryTable");
    $count = $stmt->fetchColumn();
    
    echo "<h3>✅ Database Reset Complete!</h3>";
    echo "<p>Inventory records: <strong>$count</strong></p>";
    
    // Show sample data
    $stmt = $pdo->query("SELECT ProductID, ProductName, SupplierID, SupplierName FROM InventoryTable ORDER BY ProductID");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h4>Sample Data:</h4>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ProductID</th><th>ProductName</th><th>SupplierID</th><th>SupplierName</th><th>ComboID</th></tr>";
    
    foreach($records as $record) {
        $comboId = $record['ProductID'] . $record['SupplierID'];
        echo "<tr>";
        echo "<td>" . $record['ProductID'] . "</td>";
        echo "<td>" . $record['ProductName'] . "</td>";
        echo "<td>" . $record['SupplierID'] . "</td>";
        echo "<td>" . $record['SupplierName'] . "</td>";
        echo "<td><strong>" . $comboId . "</strong></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><p><a href='dashboard.php'>Go to Dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "<h3>❌ Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<p>This usually means:</p>";
    echo "<ul>";
    echo "<li>MySQL server is not running</li>";
    echo "<li>Wrong password in connect.php</li>";
    echo "<li>Database permission issues</li>";
    echo "</ul>";
}
?>
