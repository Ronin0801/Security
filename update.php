<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: login.php");
    exit();
}

$user_ID = $_SESSION['user_ID'];

// Fetch current user information
$sql = "SELECT * FROM users WHERE user_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_ID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_user_name = $_POST['user_name'];
    $new_user_email = $_POST['user_email'];
    $new_user_pass = $_POST['user_pass'];

    // Validate password (if provided)
    if (!empty($new_user_pass)) {
        if (strlen($new_user_pass) < 8) {
            $error = "Password must be at least 8 characters long.";
        } elseif (!preg_match("/\d/", $new_user_pass)) {
            $error = "Password must contain at least one number.";
        }
    }

    if (empty($error)) {
        // Update the user profile (username and email)
        $sql = "UPDATE users SET user_name = ?, user_email = ? WHERE user_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_user_name, $new_user_email, $user_ID);

        if ($stmt->execute()) {
            echo "Profile updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // If the user wants to update their password, hash the new password and update it
        if (!empty($new_user_pass)) {
            $hashed_password = password_hash($new_user_pass, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET user_pass = ? WHERE user_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $user_ID);

            if ($stmt->execute()) {
                echo "Password updated successfully!";
            } else {
                echo "Error updating password: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
</head>
<body>
    <h2>Update Your Profile</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="user_name">Username:</label>
        <input type="text" name="user_name" value="<?php echo htmlspecialchars($user['user_name']); ?>" required><br>

        <label for="user_email">Email:</label>
        <input type="email" name="user_email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required><br>

        <label for="user_pass">New Password (leave empty to keep current password):</label>
        <input type="password" name="user_pass"><br>

        <button type="submit">Update Profile</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>

</body>
</html>
