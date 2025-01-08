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

    // Delete the record
    $sql = "DELETE FROM records WHERE record_ID = ? AND user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $record_ID, $user_ID);

    if ($stmt->execute()) {
        echo "Record deleted successfully!";
        header("Location: records.php?category=" . urlencode($_GET['category']));
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
