<?php
$host = 'localhost';
$db = 'inventorydb';
$user = 'root';
$pass = 'mawentao0806'; // Update with your MySQL password if set, otherwise leave empty
try {
    // First try to connect to MySQL server (without specifying database)
    $pdo_temp = new PDO("mysql:host=$host", $user, $pass);
    $pdo_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo_temp->exec("CREATE DATABASE IF NOT EXISTS $db");
    
    // Now connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if InventoryTable exists, if not create it
    $stmt = $pdo->query("SHOW TABLES LIKE 'InventoryTable'");
    if ($stmt->rowCount() == 0) {
        // Create table structure
        $sql = "
        CREATE TABLE SupplierTable (
          SupplierID INT PRIMARY KEY,
          SupplierName VARCHAR(255),
          Address VARCHAR(255),
          Phone VARCHAR(50),
          Email VARCHAR(255)
        );

        CREATE TABLE ProductTable (
          ProductID INT,
          ProductName VARCHAR(255),
          Description TEXT,
          Price DECIMAL(10,2),
          Quantity INT,
          Status CHAR(1),
          SupplierID INT,
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
        
        // Execute each CREATE statement separately
        $statements = explode(';', $sql);
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        
        // Insert some sample data
        $sample_data = "
        INSERT INTO InventoryTable (ProductID, ProductName, Quantity, Price, Status, SupplierName) VALUES
        (2591, 'Camera', 50, 799.90, 'B', 'Samsung'),
        (3374, 'Laptop', 30, 1799.90, 'A', 'Toshiba'),
        (3034, 'Telephone', 40, 299.99, 'A', 'Panasonic'),
        (1234, 'TV', 20, 799.90, 'C', 'Fujitsu'),
        (1516, 'Mouse', 30, 99.50, 'A', 'RedPark Ltd.');
        ";
        $pdo->exec($sample_data);
    }
    
} catch (PDOException $e) {
    // More detailed error information
    $error_details = $e->getMessage();
    if (strpos($error_details, 'Access denied') !== false) {
        die("Database connection failed: Username or password error. Please check database credentials in connect.php.<br>
            Error details: " . $error_details . "<br><br>
            <strong>Solutions:</strong><br>
            1. Make sure MySQL service is running<br>
            2. Check if username and password are correct<br>
            3. If using XAMPP/WAMP, usually root user password is empty<br>
            4. If password is set, please update \$pass variable in connect.php file");
    } else {
        die("Database connection failed: " . $error_details);
    }
}
?>
