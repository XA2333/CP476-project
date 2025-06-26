<?php
/*
 * dashboard.php
 * 
 * Handles user dashboard display after successful login.
 * This page will display database in sheet format. user will have the ability to add, edit, and delete records. (modify fucntionality)
 * The record will be implemented based on sql query given in MLS.
 * 
 * PHP version 7 or greater
 * 
 * @auther Wentao Ma
 * @package CP476
 */

session_start();
// if the user is not logged in, redirect to login page
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'connect.php';

// Handle search functionality
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch inventory data from database
try {
    if (!empty($search_term)) {
        $sql = "SELECT ProductID, ProductName, Quantity, Price, Status, SupplierName 
                FROM InventoryTable 
                WHERE ProductID LIKE :term 
                OR ProductName LIKE :term 
                OR SupplierName LIKE :term 
                ORDER BY ProductID ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':term' => "%$search_term%"]);
    } else {
        $sql = "SELECT ProductID, ProductName, Quantity, Price, Status, SupplierName 
                FROM InventoryTable 
                ORDER BY ProductID ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    $inventory_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $inventory_data = [];
    $error_message = "Error fetching data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="laurier-short.ico">
    <!-- CSS Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(230, 230, 230);
            margin: 0;
            padding: 0;
        }
        .container {
            width: 82%;
            margin: 3rem auto;
        }
        h2 {
            text-align: center;
            color:rgb(26, 26, 26);
            font-size: 45px;
            
        }
        .welcome {
            text-align: right;
            margin-bottom: 1rem;
            
        }
        .search-box, .update-form, .insert-form {
            background-color: #fff;
            padding: 1rem;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }
        th, td {
            padding: .8rem;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 18px;
        }
        th {
            background-color: #f1f1f1;
        }
        input[type="text"], input[type="number"], select {
            padding: .4rem;
            width: 200px;
        }
        input[type="submit"], button {
            padding: .5rem 1rem;
            margin-left: .5rem;
        }
        .form-row {
            margin-bottom: .8rem;
            display: flex;
            align-items: center;
        }
        .form-row label {
            display: flex;
            align-items: center;
            width: 100%;
            font-weight: bold;
        }
        .form-row label span {
            display: inline-block;
            width: 120px;
            margin-right: 10px;
            text-align: right;
        }
        .form-row input,
        .form-row select {
            flex: 1;
            max-width: 200px;
            padding: .5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .modal-content input[type="submit"] {
            width: 100%;
            padding: .8rem;
            margin-top: 1rem;
            background-color: #007cba;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .modal-content input[type="submit"]:hover {
            background-color: #005a87;
        }
        /* Modal Background Overlay */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4); /* Dark semi-transparent */
}

/* Modal Box */
.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 1rem 2rem;
    border: 1px solid #888;
    width: 500px;
    border-radius: 8px;
    position: relative;
    margin-bottom: 3rem;
}

/* Close Button */
.close {
    color: #aaa;
    position: absolute;
    top: 8px;
    right: 16px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
.close:hover {
    color: #000;
}

    </style>
</head>
<body>
    <div class="container">

        <!-- Top-right welcome message and logout link -->
        <div class="welcome">
            Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?> | 
            <a href="logout.php">Logout</a>
        </div>

        <!-- Page title -->
        <h2>Inventory Management System</h2>

        <!-- Search box to filter products by ID, name or supplier -->
        <div class="search-box">
            <form method="GET" action="dashboard.php">
                <label for="search">Search by Product ID, Name or Supplier:</label>
                <input type="text" name="search" placeholder="e.g. Camera or Samsung" value="<?php echo htmlspecialchars($search_term); ?>">
                <input type="submit" value="Search">
                <?php if (!empty($search_term)): ?>
                    <a href="dashboard.php" style="margin-left: 10px;">Clear Search</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Modal Trigger Buttons -->
        <div class="search-box">
            <button onclick="openModal('updateModal')">Update Product</button>
            <button onclick="openModal('insertModal')">Add New Product</button>
        </div>

        <!-- Display error message if any -->
        <?php if (isset($error_message)): ?>
            <div style="color: red; margin-bottom: 1rem; padding: 10px; background-color: #ffebee; border: 1px solid #f44336; border-radius: 4px;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Display success message if any -->
        <?php if (isset($_GET['message'])): ?>
            <div style="color: green; margin-bottom: 1rem; padding: 10px; background-color: #e8f5e8; border: 1px solid #4caf50; border-radius: 4px;">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Display error message from URL parameter -->
        <?php if (isset($_GET['error'])): ?>
            <div style="color: red; margin-bottom: 1rem; padding: 10px; background-color: #ffebee; border: 1px solid #f44336; border-radius: 4px;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Inventory table displaying records -->
        <table>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price ($)</th>
                <th>Status</th>
                <th>Supplier</th>
                <th>Actions</th>
            </tr>
            <?php if (!empty($inventory_data)): ?>
                <?php foreach ($inventory_data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ProductID']); ?></td>
                        <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['Price']); ?></td>
                        <td><?php echo htmlspecialchars($row['Status']); ?></td>
                        <td><?php echo htmlspecialchars($row['SupplierName']); ?></td>
                        <td>
                            <!-- Delete button form -->
                            <form method="POST" action="delete.php" style="display:inline;">
                                <input type="hidden" name="ProductID" value="<?php echo $row['ProductID']; ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                            <!-- Edit button -->
                            <button onclick="populateUpdateForm(
                                '<?php echo $row['ProductID']; ?>',
                                '<?php echo htmlspecialchars($row['Quantity']); ?>',
                                '<?php echo htmlspecialchars($row['Price']); ?>',
                                '<?php echo htmlspecialchars($row['Status']); ?>'
                            )">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No records found.</td>
                </tr>
            <?php endif; ?>
        </table>

        <!-- Update Modal -->
        <div id="updateModal" class="modal">
          <div class="modal-content">
            <span class="close" onclick="closeModal('updateModal')">&times;</span>
            <h3>Update Product</h3>
            <form method="POST" action="update.php">
                <div class="form-row">
                    <label><span>Product ID:</span><input type="text" name="ProductID" id="updateProductID" readonly style="background-color: #f0f0f0;"></label>
                </div>
                <div class="form-row">
                    <label><span>Quantity:</span><input type="number" name="Quantity" id="updateQuantity" required></label>
                </div>
                <div class="form-row">
                    <label><span>Price ($):</span><input type="number" step="0.01" name="Price" id="updatePrice" required></label>
                </div>
                <div class="form-row">
                    <label><span>Status:</span>
                        <select name="Status" id="updateStatus" required>
                            <option value="A">A - Available</option>
                            <option value="B">B - Back Order</option>
                            <option value="C">C - Discontinued</option>
                        </select>
                    </label>
                </div>
                <input type="submit" value="Update Product">
            </form>
          </div>
        </div>

        <!-- Insert Modal -->
        <div id="insertModal" class="modal">
          <div class="modal-content">
            <span class="close" onclick="closeModal('insertModal')">&times;</span>
            <h3>Add New Product</h3>
            <form method="POST" action="insert.php">
                <div class="form-row">
                    <label><span>Product ID:</span><input type="number" name="ProductID" required></label>
                </div>
                <div class="form-row">
                    <label><span>Product Name:</span><input type="text" name="ProductName" required></label>
                </div>
                <div class="form-row">
                    <label><span>Quantity:</span><input type="number" name="Quantity" required></label>
                </div>
                <div class="form-row">
                    <label><span>Price ($):</span><input type="number" step="0.01" name="Price" required></label>
                </div>
                <div class="form-row">
                    <label><span>Status:</span>
                        <select name="Status" required>
                            <option value="">Select Status</option>
                            <option value="A">A - Available</option>
                            <option value="B">B - Back Order</option>
                            <option value="C">C - Discontinued</option>
                        </select>
                    </label>
                </div>
                <div class="form-row">
                    <label><span>Supplier Name:</span><input type="text" name="SupplierName" required></label>
                </div>
                <input type="submit" value="Add Product">
            </form>
          </div>
        </div>

    <!-- JavaScript to toggle visibility of forms -->
    <script>
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = (form.style.display === "none") ? "block" : "none";
        }

        function openModal(id) {
            document.getElementById(id).style.display = "block";
        }

        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }

        function populateUpdateForm(productId, quantity, price, status) {
            document.getElementById('updateProductID').value = productId;
            document.getElementById('updateQuantity').value = quantity;
            document.getElementById('updatePrice').value = price;
            document.getElementById('updateStatus').value = status;
            openModal('updateModal');
        }

        // Close modal when clicking outside the box
        window.onclick = function(event) {
            const modals = ["updateModal", "insertModal"];
            modals.forEach(function(id) {
                const modal = document.getElementById(id);
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        }
    </script>
</html>
