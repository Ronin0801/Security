<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_ID = $_SESSION['user_ID'];
    $password = $_POST['password'];

    // Fetch user password from the database
    $sql = "SELECT user_pass FROM users WHERE user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_ID);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // Verify the entered password
    if (password_verify($password, $stored_password)) {
        // Password is correct, redirect to the profile update page
        header("Location: update.php");
        exit();
    } else {
        $error = "Incorrect password, please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Password</title>
</head>
<body>
    <h2>Confirm Your Password</h2>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Confirm</button>
    </form>

</body>
</html>
