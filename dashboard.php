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

// Sample data for demonstration purposes
$sample_data = [
    ["123456789", "John Hay", "CP460", "66.7"],
    ["223456789", "Mary Smith", "CP414", "74.8"],
    ["423456789", "David Lee", "CP317", "70.5"]
];
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
        input[type="text"], select {
            padding: .4rem;
            width: 200px;
        }
        input[type="submit"], button {
            padding: .5rem 1rem;
            margin-left: .5rem;
        }
        .form-row {
            margin-bottom: .8rem;
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
        <h2>Student Final Grades</h2>

        <!-- Search box to filter students by ID or name -->
        <div class="search-box">
            <form method="GET" action="dashboard.php">
                <label for="search">Search by Student ID or Name:</label>
                <input type="text" name="search" placeholder="e.g. John or 123456789">
                <input type="submit" value="Search">
            </form>
        </div>

        <!-- Modal Trigger Buttons -->
        <div class="search-box">
            <button onclick="openModal('updateModal')">Update Grade</button>
            <button onclick="openModal('insertModal')">Insert New Grade</button>
        </div>

        <!-- Grade table displaying sample records -->
        <table>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Course Code</th>
                <th>Final Grade</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($sample_data as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row[0]); ?></td>
                    <td><?php echo htmlspecialchars($row[1]); ?></td>
                    <td><?php echo htmlspecialchars($row[2]); ?></td>
                    <td><?php echo htmlspecialchars($row[3]); ?></td>
                    <td>
                        <!-- Delete button form -->
                        <form method="POST" action="delete.php" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $row[0]; ?>">
                            <input type="hidden" name="course_code" value="<?php echo $row[2]; ?>">
                            <button type="submit">Delete</button>
                        </form>
                        <!-- Edit button form -->
                        <form method="GET" action="edit.php" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $row[0]; ?>">
                            <input type="hidden" name="course_code" value="<?php echo $row[2]; ?>">
                            <button type="submit">Edit</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

                <!-- Update Modal -->
<div id="updateModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('updateModal')">&times;</span>
    <h3>Update Grade</h3>
    <form method="POST" action="update.php">
        <div class="form-row">
            <label>Student ID: <input type="text" name="student_id" required></label>
        </div>
        <div class="form-row">
            <label>Course Code: <input type="text" name="course_code" required></label>
        </div>
        <div class="form-row">
            <label>Final Grade: <input type="text" name="final_grade" required></label>
        </div>
        <input type="submit" value="Update">
    </form>
  </div>
</div>

<!-- Insert Modal -->
<div id="insertModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('insertModal')">&times;</span>
    <h3>Insert New Grade</h3>
    <form method="POST" action="insert.php">
        <div class="form-row">
            <label>Student ID: <input type="text" name="student_id" required></label>
        </div>
        <div class="form-row">
            <label>Student Name: <input type="text" name="student_name" required></label>
        </div>
        <div class="form-row">
            <label>Course Code: <input type="text" name="course_code" required></label>
        </div>
        <div class="form-row">
            <label>Final Grade: <input type="text" name="final_grade" required></label>
        </div>
        <input type="submit" value="Insert">
    </form>
  </div>
</div>

    <!-- JavaScript to toggle visibility of forms -->
    <script>
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = (form.style.display === "none") ? "block" : "none";
        }
    </script>
</body>
<script>
function openModal(id) {
    document.getElementById(id).style.display = "block";
}
function closeModal(id) {
    document.getElementById(id).style.display = "none";
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
