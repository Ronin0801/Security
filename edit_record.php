<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['record_ID'])) {
    $record_ID = $_GET['record_ID'];
    $user_ID = $_SESSION['user_ID'];

    // Fetch the record to edit
    $sql = "SELECT records.*, categories.category_name 
            FROM records 
            JOIN categories ON records.category_ID = categories.category_ID
            WHERE records.record_ID = ? AND records.user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $record_ID, $user_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Record ID not specified.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $record_title = $_POST['record_title'];
    $record_username = $_POST['record_username'];
    $record_password = $_POST['record_password'];
    $record_notes = $_POST['record_notes'];

    // Update the record
    $sql = "UPDATE records SET record_title = ?, record_username = ?, record_password = ?, record_notes = ? 
            WHERE record_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $record_title, $record_username, $record_password, $record_notes, $record_ID);

    if ($stmt->execute()) {
        echo "Record updated successfully!";
        header("Location: records.php?category=" . urlencode($record['category_name']));
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Record</title>
</head>
<body>
    <h2>Edit Record</h2>
    <form method="POST" action="">
        <label for="record_title">Title:</label>
        <input type="text" name="record_title" value="<?php echo htmlspecialchars($record['record_title']); ?>" required><br>
        <label for="record_username">Username:</label>
        <input type="text" name="record_username" value="<?php echo htmlspecialchars($record['record_username']); ?>"><br>
        <label for="record_password">Password:</label>
        <input type="password" name="record_password" value="<?php echo htmlspecialchars($record['record_password']); ?>"><br>
        <label for="record_notes">Notes:</label>
        <textarea name="record_notes"><?php echo htmlspecialchars($record['record_notes']); ?></textarea><br>
        <button type="submit">Update Record</button>
    </form>
</body>
</html>
