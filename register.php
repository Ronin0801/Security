<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_pass = $_POST['user_pass'];

    // Validate password
    if (strlen($user_pass) < 8 || !preg_match('/\d/', $user_pass)) {
        echo "Password must be at least 8 characters long and contain at least one number.";
    } else {
        $hashed_pass = password_hash($user_pass, PASSWORD_DEFAULT); // Hash password

        // Check if username or email already exists
        $check_sql = "SELECT * FROM users WHERE user_name = ? OR user_email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $user_name, $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username or email already exists. Please choose a different one.";
        } else {
            // Insert new user
            $sql = "INSERT INTO users (user_name, user_email, user_pass) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $user_name, $user_email, $hashed_pass);

            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="">
        <label for="user_name">Username:</label>
        <input type="text" id="user_name" name="user_name" required><br>
        <label for="user_email">Email:</label>
        <input type="email" id="user_email" name="user_email" required><br>
        <label for="user_pass">Password:</label>
        <input type="password" id="user_pass" name="user_pass" required><br>
        <small>Password must be at least 8 characters long and contain at least one number.</small><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account?</p>
    <a href="login.php">
        <button type="button">Login</button>
    </a>
</body>
</html>

