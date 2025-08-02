<?php
// filepath: register_event.php
require '../db.php';
$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM event WHERE Evt_id=$id");
$event = $res->fetch_assoc();
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['Student_name'];
    $email = $_POST['Student_email'];
    $course = $_POST['Student_course'];
    // Save registration (example: insert into registration table)
    $conn->query("INSERT INTO registration (Evt_id, Student_name, Student_email, Student_course) VALUES ($id, '$name', '$email', '$course')");
    echo "<script>
        alert('Registration Successful');
        setTimeout(function(){ window.location='event_detail.php?id=$id'; }, 1000);
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register for <?php echo $event['Evt_title']; ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .reg-card { background:#fff;max-width:400px;margin:40px auto 0 auto;padding:30px;border-radius:18px;box-shadow:0 2px 12px #e3eafc;}
        .form-group { margin-bottom:18px;}
        input[type="text"],input[type="email"] { width:100%;padding:10px;border:1px solid #e3eafc;border-radius:6px;}
        .btn { background:#1565c0;color:#fff;padding:10px 25px;border:none;border-radius:8px;font-size:16px;cursor:pointer;}
        .btn:hover { background:#1976d2;}
    </style>
</head>
<body style="background:#f8faff;">
<?php include 'navbar.php'; ?>
<div class="reg-card">
    <h2 style="color:#1565c0;">Register for <?php echo $event['Evt_title']; ?></h2>
    <form method="post">
        <div class="form-group">
            <label>Student Name</label>
            <input type="text" name="Student_name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="Student_email" required>
        </div>
        <div class="form-group">
            <label>Course</label>
            <input type="text" name="Student_course" required>
        </div>
        <button class="btn" type="submit">Register Now</button>
    </form>
</div>
</body>
</html>