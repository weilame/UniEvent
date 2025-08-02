<?php
// Purpose: Sidebar navigation and profile (include in dashboard.php, events.php, profile.php)
$org = $_SESSION['organizer'];
?>
<div class="sidebar">
    <?php if (!empty($org['Org_photo'])): ?>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($org['Org_photo']); ?>" alt="Profile">
    <?php else: ?>
        <img src="assets/default-profile.jpg" alt="Profile">
    <?php endif; ?>
    <div class="profile-info">
        <div><b><?php echo $org['Org_name']; ?></b></div>
        <div style="font-size:13px;"><?php echo $org['Org_email']; ?></div>
    </div>
    <form action="dashboard.php" method="post">
        <button type="submit">Dashboard</button>
    </form>
    <form action="event_crud.php" method="post">
        <button type="submit">Manage Events</button>
    </form>
    <form action="profile.php" method="post">
        <button type="submit">Profile</button>
    </form>
    <form action="logout.php" method="post">
        <button type="submit">Logout</button>
    </form>
    <div style="margin-top: 18px; margin-bottom:8px; text-align:center;">
        <a href="dashboard.php">
            <img src="assets/logo_unievent.png" alt="Logo Sistem" style="width:150px; height:auto;">
        </a>
    </div>
</div>