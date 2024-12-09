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

// Check if `matric` is passed in the URL
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Prepare and execute the DELETE query
    $stmt = $conn->prepare("DELETE FROM users WHERE matric = ?");
    if (!$stmt) {
        die("Failed to prepare DELETE statement: " . $conn->error);
    }
    $stmt->bind_param("s", $matric);

    if ($stmt->execute()) {
        header("Location: display.php");
        exit;
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
} else {
    die("Invalid or missing Matric.");
}

$conn->close();
?>
