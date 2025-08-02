<?php
// Purpose: Organizer login page
session_start();
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $sql = "SELECT * FROM organizer WHERE Org_email='$email' AND Org_password='$pass'";
    $res = $conn->query($sql);
    if ($res->num_rows == 1) {
        $_SESSION['organizer'] = $res->fetch_assoc();
        header("Location: dashboard.php");
        exit;
    } else {
        $msg = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="main" style="max-width:400px;margin:auto;margin-top:100px;">
    <h2 style="color:#1565c0;">Organizer Login</h2>
    <?php if($msg) echo "<div style='color:red;'>$msg</div>"; ?>
    <form method="post">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button class="btn" type="submit">Login</button>
    </form>
    <div style="margin-top:15px;">
        <a href="register.php" style="color:#1565c0;">First time? Register</a> | 
        <a href="forgot_password.php" style="color:#1565c0;">Forgot password?</a>
    </div>
</div>
</body>
</html>

