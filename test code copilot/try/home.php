<?php
require '../db.php';
// Get latest 3 events for featured section
$latest_events = $conn->query("SELECT * FROM event ORDER BY Evt_id DESC LIMIT 3");
?>
<!DOCTYPE html>
<html>
<head>
    <title>UNI EVENT - Home</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .home-header {
            text-align: center;
            margin-top: 60px;
        }
        .home-title {
            font-size: 2.2em;
            color: #1565c0;
            margin-bottom: 10px;
        }
        .home-desc {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 36px;
        }
        .home-logo {
            display: block;
            margin: 0 auto 24px auto;
            width: 130px;
            height: auto;
        }
        .featured-events {
            max-width: 900px;
            margin: 40px auto 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px #e3eafc;
            padding: 28px 28px 18px 28px;
        }
        .featured-events h2 {
            color: #1565c0;
            text-align: center;
            margin-bottom: 18px;
        }
        .event-list {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;
            justify-content: center;
        }
        .event-card {
            background: #f8faff;
            border-radius: 8px;
            box-shadow: 0 1px 4px #e3eafc;
            padding: 15px 18px;
            min-width: 170px;
            max-width: 220px;
            text-align: center;
        }
        .event-card img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .event-title {
            font-weight: bold;
            color: #1565c0;
            margin-bottom: 6px;
            font-size: 1.1em;
        }
        .event-date {
            color: #555;
            font-size: 0.98em;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

    <div class="home-header">
        <img class="home-logo" src="../assets/logo_unievent.png" alt="UNI EVENT">
        <div class="home-title">Welcome to UNI EVENT</div>
        <div class="home-desc">
            University event portal. Discover and join various exciting campus activities in Selangor branch!
        </div>
        <!--<a class="btn" href="../login.php" style="margin-top:10px;">Login as Organizer</a>-->
    </div>

    <div class="featured-events">
        <h2>Featured Events</h2>
        <div class="event-list">
            <?php if ($latest_events->num_rows == 0): ?>
                <div style="text-align:center;width:100%;">No upcoming events yet.</div>
            <?php else: ?>
                <?php while($row = $latest_events->fetch_assoc()): ?>
                <div class="event-card">
                    <?php if (!empty($row['Evt_poster'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['Evt_poster']); ?>" alt="Event Poster">
                    <?php else: ?>
                        <img src="assets/default-event.png" alt="Event Poster">
                    <?php endif; ?>
                    <div class="event-title"><?php echo htmlspecialchars($row['Evt_title']); ?></div>
                    <div class="event-date">
                        <?php
                            $start = date('d M Y', strtotime($row['Evt_date_start']));
                            echo $start;
                        ?>
                    </div>
                    <div><?php echo htmlspecialchars($row['Evt_loc']); ?></div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        <div style="text-align:center;margin-top:20px;">
            <a href="index_event.php" class="btn" style="padding:8px 22px;">View All Events</a>
        </div>
    </div>
</body>
</html>