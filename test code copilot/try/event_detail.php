<?php
// filepath: event_detail.php
require '../db.php';
$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM event WHERE Evt_id=$id");
$event = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $event['Evt_title']; ?> - Event Details</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .event-detail-card { background:#fff;max-width:600px;margin:40px auto 0 auto;padding:30px;border-radius:18px;box-shadow:0 2px 12px #e3eafc;}
        .event-detail-card img {
                width: 100%;
                border-radius: 12px;
                object-fit: contain;      /* <-- Ubah cover kepada contain */
                max-height: 340px;
                background: #fff;         /* <-- Tambah latar supaya poster nampak kemas */
                display: block;
            }
        .event-detail-info { margin-top:20px;}
        .btn-register { background:#1565c0;color:#fff;padding:12px 30px;border:none;border-radius:8px;font-size:18px;cursor:pointer;margin-top:25px;}
        .btn-register:hover { background:#1976d2;}
    </style>
</head>
<body style="background:#f8faff;">
<?php include 'navbar.php'; ?>
<div class="event-detail-card">
    <?php if (!empty($event['Evt_poster'])): ?>
        <img src="data:<?php echo $event['Evt_poster_type'] ?: 'image/jpeg'; ?>;base64,<?php echo base64_encode($event['Evt_poster']); ?>">
    <?php else: ?>
        <img src="assets/default-event.png">
    <?php endif; ?>
    <div class="event-detail-info">
        <h2 style="color:#1565c0;"><?php echo $event['Evt_title']; ?></h2>
        <div style="margin-bottom:10px;"><?php echo $event['Evt_desc']; ?></div>
        <div><b>Date:</b> <?php echo $event['Evt_date_start']; ?></div>
        <div><b>Time:</b> <?php echo $event['Evt_time_start']; ?> – <?php echo $event['Evt_time_end']; ?></div>
        <!--<button class="btn-register" onclick="window.location='register_event.php?id=<?php echo $event['Evt_id']; ?>'">Register Now</button>-->
    </div>
</div>
</body>
</html>