<?php
/**
 * login.php
 * 
 * Handles user login authentication for the system.
 *
 * PHP version 7 or greater
 *
 * @author Wentao Ma
 * @package CP476
 */

session_start();
// example of user credentials
// please replace with your own user configuration
$valid_username = "example";
$valid_password = "123456";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign in to Your Account</title>
    <style>
        body {
            background-color: #f6f8fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 2rem;
            width: 360px;
            border: 1px solid #d8dee4;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: .5rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: .6rem;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            font-size: 1rem;
        }

        input[type="submit"] {
            width: 100%;
            padding: .6rem;
            font-size: 1rem;
            background-color: #2da44e;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 1rem;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
        }

        .footer {
            text-align: center;
            font-size: 0.85rem;
            color: #57606a;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Sign in to Your Account</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>

            <input type="submit" value="Sign in">
        </form>
        <div class="footer">
            New to this system? <a href="#">Create an account(fake)</a>
        </div>
        <div class="footer">
        forget your password or useranme? <a href="#">reset (fake)</a>
        </div>
    </div>
</body>
</html>
