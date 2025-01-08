<?php
session_start();
if (!isset($_SESSION['user_ID'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    <p>Select a category to manage your information:</p>
    <ul>
        <li><a href="records.php?category=Banks">Banks</a></li>
        <li><a href="records.php?category=Emails">Emails</a></li>
        <li><a href="records.php?category=Social Media">Social Media</a></li>
        <li><a href="records.php?category=Entertainment">Entertainment</a></li>
        <li><a href="records.php?category=Others">Others</a></li>
    </ul>
    <a href="logout.php">Logout</a>
</body>
</html>
