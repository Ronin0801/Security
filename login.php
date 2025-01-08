<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_identifier = $_POST['user_identifier']; // Username or email
    $user_pass = $_POST['user_pass'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE user_name = ? OR user_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_identifier, $user_identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($user_pass, $user['user_pass'])) {
            // Set session variables
            $_SESSION['user_ID'] = $user['user_ID'];
            $_SESSION['user_name'] = $user['user_name'];
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with that username or email.";
    }

    $stmt->close();
}

if (isset($_GET['message']) && $_GET['message'] == 'logged_out') {
    echo "<p>You have successfully logged out.</p>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="">
        <label for="user_identifier">Username or Email:</label>
        <input type="text" id="user_identifier" name="user_identifier" required><br>
        <label for="user_pass">Password:</label>
        <input type="password" id="user_pass" name="user_pass" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account?</p>
    <a href="register.php">
        <button type="button">Create Account</button>
    </a>
</body>
</html>
