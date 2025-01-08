<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_ID = $_SESSION['user_ID'];
    $category_name = $_POST['category'];
    $record_title = $_POST['record_title'];
    $record_username = $_POST['record_username'];
    $record_password = $_POST['record_password'];
    $record_notes = $_POST['record_notes'];

    // Get the category_ID based on category name
    $sql = "SELECT category_ID FROM categories WHERE category_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $stmt->bind_result($category_ID);
    $stmt->fetch();
    $stmt->close();

    // Insert the new record
    $sql = "INSERT INTO records (user_ID, category_ID, record_title, record_username, record_password, record_notes) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $user_ID, $category_ID, $record_title, $record_username, $record_password, $record_notes);

    if ($stmt->execute()) {
        echo "Record added successfully!";
        header("Location: records.php?category=" . urlencode($category_name));
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
