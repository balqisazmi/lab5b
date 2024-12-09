<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <!-- Matric input -->
            <label>Matric:</label><br>
            <input type="text" name="matric" required><br>

            <!-- Password input -->
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <!-- Submit Button -->
            <button type="submit" name="login">Login</button>
        </form>
        <a href="register.php">Don't have an account? Register here</a>
    </div>
</body>
</html>
<?php
// Include the database connection
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if (isset($_POST['login'])) {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Prepare the SQL query to fetch user by matric
    $stmt = $conn->prepare("SELECT * FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables and redirect to the dashboard or home page
            $_SESSION['logged_in'] = true;
            $_SESSION['matric'] = $matric;
            $_SESSION['role'] = $user['role'];
            header("Location: display.php");
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that matric.";
    }

    $stmt->close();
}
$conn->close();
?>
