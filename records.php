<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: login.php");
    exit();
}

$category = $_GET['category']; // Get the category from the URL
$user_ID = $_SESSION['user_ID'];

// Fetch records for the selected category
$sql = "SELECT * FROM records 
        JOIN categories ON records.category_ID = categories.category_ID 
        WHERE user_ID = ? AND category_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_ID, $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($category); ?> Records</title>
</head>
<body>
    <h2><?php echo htmlspecialchars($category); ?> Records</h2>
    <a href="dashboard.php">Back to Dashboard</a>
    <h3>Add New Record</h3>
    <form method="POST" action="add_record.php">
        <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
        <label for="record_title">Title:</label>
        <input type="text" name="record_title" required><br>
        <label for="record_username">Username:</label>
        <input type="text" name="record_username"><br>
        <label for="record_password">Password:</label>
        <input type="password" name="record_password"><br>
        <label for="record_notes">Notes:</label>
        <textarea name="record_notes"></textarea><br>
        <button type="submit">Add Record</button>
    </form>

    <h3>Existing Records</h3>
    <table>
        <tr>
            <th>Title</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['record_title']); ?></td>
                <td><?php echo htmlspecialchars($row['record_username']); ?></td>
                <td>
                    <a href="edit_record.php?record_ID=<?php echo $row['record_ID']; ?>">Edit</a> |
                    <a href="delete_record.php?record_ID=<?php echo $row['record_ID']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$stmt->close();
?>
