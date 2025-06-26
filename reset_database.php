<?php
/**
 * reset_database.php
 * 
 * Script to reset and reinitialize the database with fresh data
 * Run this file to completely rebuild your database tables
 */

$host = 'localhost';
$db = 'inventorydb';
$user = 'root';
$pass = 'mawentao0806';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Resetting database...<br>";
    
    // Drop all tables
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("DROP TABLE IF EXISTS InventoryTable");
    $pdo->exec("DROP TABLE IF EXISTS ProductTable");
    $pdo->exec("DROP TABLE IF EXISTS SupplierTable");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "Dropped existing tables...<br>";
    
    // Create tables
    $create_tables = "
    CREATE TABLE SupplierTable (
      SupplierID INT PRIMARY KEY,
      SupplierName VARCHAR(255) NOT NULL,
      Address VARCHAR(255),
      Phone VARCHAR(50),
      Email VARCHAR(255)
    );

    CREATE TABLE ProductTable (
      ProductID INT,
      ProductName VARCHAR(255) NOT NULL,
      Description TEXT,
      Price DECIMAL(10,2) NOT NULL,
      Quantity INT NOT NULL,
      Status CHAR(1) NOT NULL,
      SupplierID INT NOT NULL,
      FOREIGN KEY (SupplierID) REFERENCES SupplierTable(SupplierID)
    );

    CREATE TABLE InventoryTable (
        ProductID INT,
        ProductName VARCHAR(255),
        Quantity INT,
        Price DECIMAL(10,2),
        Status CHAR(1),
        SupplierName VARCHAR(255)
    );
    ";
    
    $statements = explode(';', $create_tables);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "Created new tables...<br>";
    
    // Insert supplier data
    $supplier_data = "
    INSERT INTO SupplierTable (SupplierID, SupplierName, Address, Phone, Email) VALUES
    (9512, 'Acme Corporation', '123 Main St', '205-288-8591', 'info@acme-corp.com'),
    (8642, 'Xerox Inc.', '456 High St', '505-398-8414', 'info@xrx.com'),
    (3579, 'RedPark Ltd.', '789 Park Ave', '604-683-2555', 'info@redpark.ca'),
    (7890, 'Samsung', '456 Seoul St', '909-763-4442', 'support@samsung.com'),
    (7671, 'LG Electronics', '789 Busan St', '668-286-5378', 'support@lge.kr'),
    (9876, 'Toshiba', '246 Osaka St', '90-6378-0835', 'support@toshiba.co.jp'),
    (3456, 'Panasonic', '246 Osaka St', '443-887-9967', 'support@panasonic.co.jp'),
    (8765, 'Philips', '789 Amsterdam St', '61-483-898-670', 'support@philips.au'),
    (1357, 'Sharp', '123 Tokyo St', '80-4745-3107', 'support@sharp.co.jp'),
    (9144, 'Fujitsu', '456 Tokyo St', '03-3556-7890', 'support@fujitsu.co.jp'),
    (8655, 'Dell', '246 Austin St', '505-351-3181', 'support@dell.com'),
    (3592, 'IBM', '456 New York St', '201-335-9423', 'support@ibm.com'),
    (7084, 'Acer', '135 Taipei St', '905-926-031', 'support@acer.tw'),
    (2345, 'MSI', '789 Mofan St', '943-299-465', 'support@msi.tw'),
    (6954, 'Apple', '246 Cupertino St', '202-918-2132', 'support@apple.com'),
    (9794, 'Amazon', '246 Seattle St', '555-343-8950', 'support@amazon.com'),
    (8692, 'Microsoft', '123 Redmond St', '505-549-0420', 'support@microsoft.com'),
    (7807, 'Intel', '2200 Mission College Blvd', '408-646-7611', 'support@intel.com'),
    (8672, 'AMD', '246 Santa Clara St', '312-866-2043', 'support@amd.com'),
    (4567, 'Qualcomm', '456 San Diego St', '44-7700-087231', 'info@qualcomm.co.uk');
    ";
    $pdo->exec($supplier_data);
    
    echo "Inserted supplier data...<br>";
    
    // Insert product data
    $product_data = "
    INSERT INTO ProductTable (ProductID, ProductName, Description, Price, Quantity, Status, SupplierID) VALUES
    (2591, 'Camera', 'Camera', 799.9, 50, 'B', 7890),
    (3374, 'Laptop', 'MacBook Pro', 1799.9, 30, 'A', 9876),
    (3034, 'Telephone', 'Cordless Phone', 299.99, 40, 'A', 3456),
    (3034, 'Telephone', 'Home telephone', 99.9, 25, 'A', 8765),
    (1234, 'TV', 'Plate TV', 799.9, 20, 'C', 9144),
    (1234, 'TV', 'Plate TV', 1499.99, 5, 'A', 7671),
    (2591, 'Camera', 'Instant Camera', 179.5, 30, 'C', 8642),
    (1516, 'Mouse', 'Wireless Mouse', 99.5, 30, 'A', 3579),
    (3034, 'Telephone', 'Home Telephone', 169.99, 15, 'A', 8692),
    (2591, 'Camera', 'Digital Camera', 499.9, 10, 'B', 9512),
    (3034, 'Telephone', 'Home Telephone', 59.5, 20, 'A', 8655),
    (2591, 'Camera', 'Digital Camera', 449.4, 50, 'A', 3592),
    (1234, 'TV', 'Plate TV', 699.7, 5, 'B', 7084),
    (1516, 'Mouse', 'Wireless Mouse', 69.9, 25, 'C', 2345),
    (3374, 'Laptop', 'Laptop', 1399.2, 10, 'B', 1357),
    (3374, 'Laptop', 'Refurbished Laptop', 1099.1, 20, 'A', 6954),
    (1516, 'Mouse', 'Wireless Mouse', 49.4, 50, 'B', 9794),
    (1516, 'Mouse', 'Wireless Mouse', 69.5, 20, 'A', 7807),
    (1234, 'TV', 'Plate TV', 599.3, 5, 'B', 8672),
    (3374, 'Laptop', 'Laptop', 1369.9, 15, 'A', 4567);
    ";
    $pdo->exec($product_data);
    
    echo "Inserted product data...<br>";
    
    // Create inventory table
    $inventory_data = "
    INSERT INTO InventoryTable (ProductID, ProductName, Quantity, Price, Status, SupplierName)
    SELECT P.ProductID, P.ProductName, P.Quantity, P.Price, P.Status, S.SupplierName
    FROM ProductTable P
    JOIN SupplierTable S ON P.SupplierID = S.SupplierID
    ORDER BY P.ProductID;
    ";
    $pdo->exec($inventory_data);
    
    echo "Created inventory table...<br>";
    
    // Check results
    $stmt = $pdo->query("SELECT COUNT(*) FROM SupplierTable");
    $supplier_count = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM ProductTable");
    $product_count = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM InventoryTable");
    $inventory_count = $stmt->fetchColumn();
    
    echo "<h3>Database Reset Complete!</h3>";
    echo "Supplier records: $supplier_count<br>";
    echo "Product records: $product_count<br>";
    echo "Inventory records: $inventory_count<br>";
    echo "<br><a href='dashboard.php'>Go to Dashboard</a>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
