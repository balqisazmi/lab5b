<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check user authentication
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch user details if `matric` is provided in the URL
if (isset($_GET['matric'])) {
    $original_matric = $_GET['matric']; // The original matric value for the user

    // Prepare and execute the SELECT query to fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE matric = ?");
    if (!$stmt) {
        die("Failed to prepare SELECT statement: " . $conn->error);
    }
    $stmt->bind_param("s", $original_matric);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("User not found with Matric: $original_matric");
    }
    $stmt->close();
} else {
    die("Invalid or missing Matric.");
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $new_matric = $_POST['new_matric']; // New matric entered by the user
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Prepare and execute the UPDATE query
    $stmt = $conn->prepare("UPDATE users SET matric = ?, name = ?, role = ? WHERE matric = ?");
    if (!$stmt) {
        die("Failed to prepare UPDATE statement: " . $conn->error);
    }
    $stmt->bind_param("ssss", $new_matric, $name, $role, $original_matric);

    if ($stmt->execute()) {
        header("Location: display.php");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update User</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="container">
        <h2>Update User</h2>
        <form method="POST" action="">
            <!-- Input for New Matric -->
            <label>New Matric:</label><br>
            <input type="text" name="new_matric" value="<?php echo htmlspecialchars($user['matric']); ?>" required><br>

            <!-- Input for Name -->
            <label>Name:</label><br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>

            <!-- Select for Role -->
            <label>Role:</label><br>
            <select name="role" required>
                <option value="Lecturer" <?php if ($user['role'] === 'Lecturer') echo 'selected'; ?>>Lecturer</option>
                <option value="Student" <?php if ($user['role'] === 'Student') echo 'selected'; ?>>Student</option>
            </select><br><br>

            <!-- Submit Button -->
            <button type="submit" name="update">Update</button>
        </form>
        <a href="display.php">Back to User List</a>
    </div>
</body>
</html>
