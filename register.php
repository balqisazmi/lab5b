<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="register.php">
            <!-- Matric input -->
            <label>Matric:</label><br>
            <input type="text" name="matric" required><br>

            <!-- Name input -->
            <label>Name:</label><br>
            <input type="text" name="name" required><br>

            <!-- Password input -->
            <label>Password:</label><br>
            <input type="password" name="password" required><br>

            <!-- Role select -->
            <label>Role:</label><br>
            <select name="role" required>
                <option value="Lecturer">Lecturer</option>
                <option value="Student">Student</option>
            </select><br><br>

            <!-- Submit Button -->
            <button type="submit" name="register">Register</button>
        </form>
        <a href="login.php">Already have an account? Login here</a>
    </div>
</body>
</html>
<?php
// Include the database connection
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    // Prepare the SQL query to insert data
    $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $matric, $name, $password, $role);

    if ($stmt->execute()) {
        echo "Registration successful!";
        // Redirect to the login page after successful registration
        header("Location: login.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
