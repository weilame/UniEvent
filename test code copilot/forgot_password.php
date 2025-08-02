<?php
// Purpose: Organizer forgot password (plain text version)
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $newpass = $_POST['new_password'];
    $sql = "SELECT * FROM organizer WHERE Org_email='$email'";
    $res = $conn->query($sql);
    if ($res->num_rows == 1) {
        // Set new password in plain text
        $conn->query("UPDATE organizer SET Org_password='$newpass' WHERE Org_email='$email'");
        $msg = "Password reset successful! <a href='login.php'>Login here</a>";
    } else {
        $msg = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="main" style="max-width:400px;margin:auto;margin-top:100px;">
    <h2 style="color:#1565c0;">Forgot Password</h2>
    <?php if($msg) echo "<div style='color:green;'>$msg</div>"; ?>
    <form method="post">
        <div class="form-group">
            <label>Enter your email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Enter new password</label>
            <input type="password" name="new_password" required>
        </div>
        <button class="btn" type="submit">Reset Password</button>
    </form>
</div>
</body>
</html>
