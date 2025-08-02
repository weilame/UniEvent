<?php
// Purpose: Organizer profile page with image upload
session_start();
if (!isset($_SESSION['organizer'])) header("Location: login.php");
require 'db.php';

// Handle image upload
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $imgData = addslashes(file_get_contents($_FILES['profile_image']['tmp_name']));
    $org_id = $_SESSION['organizer']['Org_id'];
    $sql = "UPDATE organizer SET Org_photo='$imgData' WHERE Org_id='$org_id'";
    if ($conn->query($sql)) {
        // Refresh session data
        $res = $conn->query("SELECT * FROM organizer WHERE Org_id='$org_id'");
        $_SESSION['organizer'] = $res->fetch_assoc();
        $msg = "Profile photo updated!";
    } else {
        $msg = "Error updating photo: " . $conn->error;
    }
}
$org = $_SESSION['organizer'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Organizer Profile</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <h2 style="color:#1565c0;">Profile</h2>
    <?php if($msg) echo "<div style='color:green;'>$msg</div>"; ?>
    <div style="max-width:400px;">
        <!-- Show uploaded image if exists, else default -->
        <?php if (!empty($org['Org_photo'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($org['Org_photo']); ?>"
                 style="width:120px;height:120px;border-radius:50%;border:3px solid #1565c0;">
        <?php else: ?>
            <img src="assets/default-profile.jpg"
                 style="width:120px;height:120px;border-radius:50%;border:3px solid #1565c0;">
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" style="margin-top:15px;">
            <input type="file" name="profile_image" accept="image/*" required>
            <button class="btn" type="submit">Upload Photo</button>
        </form>
        <div style="margin-top:20px;">
            <b>Name:</b> <?php echo $org['Org_name']; ?><br>
            <b>Email:</b> <?php echo $org['Org_email']; ?><br>
        </div>
    </div>
</div>
</body>
</html>
