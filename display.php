<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Users</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="container">
        <h2>User List</h2>

        <?php
        // Start the session
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['logged_in'])) {
            header("Location: login.php");
            exit;
        }

        // Connect to the database
        $conn = new mysqli('localhost', 'root', '', 'Lab_5b');

        // Check the connection
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        // Query to fetch all users
        $result = $conn->query("SELECT * FROM users");

        // Check if query execution was successful
        if ($result === false) {
            die("Error executing query: " . $conn->error);
        }

        // Display the table if there are records
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Matric</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>";

            // Fetch and display each row
            while ($row = $result->fetch_assoc()) {
                $matric = htmlspecialchars($row['matric']);
                $name = htmlspecialchars($row['name']);
                $role = htmlspecialchars($row['role']);
                echo "<tr>
                        <td>$matric</td>
                        <td>$name</td>
                        <td>$role</td>
                        <td>
                            <a href='update.php?matric=$matric'>Update</a> | 
                            <a href='delete.php?matric=$matric' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
                        </td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No users found.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>
