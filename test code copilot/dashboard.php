<?php
// Purpose: Admin homepage/dashboard
session_start();
if (!isset($_SESSION['organizer'])) header("Location: login.php");
require 'db.php';

// Get dashboard data
$event_count = $conn->query("SELECT COUNT(*) FROM event")->fetch_row()[0];
$org_count = $conn->query("SELECT COUNT(*) FROM organizer")->fetch_row()[0];
$latest_events = $conn->query("SELECT * FROM event ORDER BY Evt_id DESC LIMIT 3");
$all_latest = $conn->query("SELECT * FROM event ORDER BY Evt_id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <h2 style="color:#1565c0;">Dashboard</h2>
    <div class="cards-row">
        <div class="card">
            <div style="font-size:30px;"><?php echo $event_count; ?></div>
            <div>Events</div>
        </div>
        <div class="card">
            <div style="font-size:30px;"><?php echo $org_count; ?></div>
            <div>Organizers</div>
        </div>
    </div>
    <h3>Latest Events</h3>
    <div class="latest-events">
        <?php while($row = $latest_events->fetch_assoc()): ?>
        <div class="latest-event-poster">
            <?php if (!empty($row['Evt_poster'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['Evt_poster']); ?>" alt="Poster">                
            <?php else: ?>
                <img src="assets/default-event.png" alt="Poster">
            <?php endif; ?>
            <div class="event-title"><?php echo $row['Evt_title']; ?></div>
        </div>
        <?php endwhile; ?>
    </div>
    <h3 style="margin-top:30px;">Recent Events List</h3>
    <table class="table">
        <tr>
            <th>Title</th>
            <th>Venue</th>
            <th>Start Date</th>
        </tr>
        <?php while($row = $all_latest->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['Evt_title']; ?></td>
            <td><?php echo $row['Evt_loc']; ?></td>
            <td><?php echo $row['Evt_date_start']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
