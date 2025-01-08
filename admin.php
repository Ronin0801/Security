<?php
session_start();
include 'db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION['user_ID']) || $_SESSION['user_type'] != 1) {
    header("Location: dashboard.php"); // Redirect to dashboard if not an admin
    exit();
}

// Fetch all users from the database
$sql = "SELECT user_ID, user_name, user_email, user_type FROM users";
$result = $conn->query($sql);

// Handle user deletion
if (isset($_GET['delete_user_ID'])) {
    $user_ID_to_delete = $_GET['delete_user_ID'];

    // Prevent deleting the admin user or the logged-in user
    if ($user_ID_to_delete != $_SESSION['user_ID']) {
        $delete_sql = "DELETE FROM users WHERE user_ID = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $user_ID_to_delete);
        $delete_stmt->execute();
        $delete_stmt->close();
        header("Location: admin.php"); // Refresh the page after deletion
        exit();
    } else {
        echo "You cannot delete your own account.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h2>Admin Panel - Manage Users</h2>
    <p>Here you can manage users and their accounts:</p>

    <table border="1">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Display user data
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['user_email']) . "</td>";
                    echo "<td>" . ($row['user_type'] == 1 ? 'Admin' : 'User') . "</td>";
                    echo "<td>
                            <a href='admin.php?delete_user_ID=" . $row['user_ID'] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
