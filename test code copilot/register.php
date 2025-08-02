<?php
// Purpose: First-time organizer registration
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use the correct POST keys matching your form fields
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    // Use the correct column names from your database
    $sql = "INSERT INTO organizer (Org_name, Org_email, Org_password) VALUES ('$name', '$email', '$pass')";
    if ($conn->query($sql)) {
        $msg = "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Organizer</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="main" style="max-width:400px;margin:auto;margin-top:100px;">
    <h2 style="color:#1565c0;">Register Organizer</h2>
    <?php if($msg) echo "<div style='color:green;'>$msg</div>"; ?>
    <form method="post">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button class="btn" type="submit">Register</button>
    </form>
</div>
</body>
</html>
