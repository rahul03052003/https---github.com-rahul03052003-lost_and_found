<?php
session_start();
include 'pro2connection.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : 'user'; // Default role

    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        $passwordHash = hash('sha256', $password); // Hash the password with SHA-256

        // Check if username already exists
        $sql_check = "SELECT * FROM users WHERE username = '$username'";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
            $error = "Username already exists. Please choose a different username.";
        } else {
            // Insert new user
            $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$passwordHash', '$role')";

            if ($conn->query($sql) === TRUE) {
                $success = "Registration successful! You can now <a href='login.php'>login here</a>.";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">Register</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>
        <p class="text-center mt-3"><a href="login.php">Already have an account? Login</a></p>
    </div>
</body>
</html>
