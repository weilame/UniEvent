<?php
// filepath: index.php
require '../db.php';
$campus = isset($_GET['Evt_campus']) ? $_GET['Evt_campus'] : '';
$sql = "SELECT * FROM event";
if ($campus) {
    $sql .= " WHERE Evt_campus='$campus'";
}
$sql .= " ORDER BY Evt_date_start DESC";
$events = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Listing</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .event-grid { display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; margin-top: 30px;}
        .event-card {
            width: 260px; height: 340px; background: #fff; border-radius: 18px; box-shadow: 0 2px 12px #e3eafc;
            overflow: hidden; position: relative; cursor: pointer; transition: box-shadow .2s;
        }
        .event-card:hover { box-shadow: 0 6px 24px #b3c6e6; }
        .event-card img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .event-info {
            position: absolute; bottom: 0; left: 0; right: 0; background: rgba(21,101,192,0.95);
            color: #fff; padding: 18px 12px 12px 12px; opacity: 0; transition: opacity .2s;
            border-bottom-left-radius: 18px; border-bottom-right-radius: 18px;
        }
        .event-card:hover .event-info { opacity: 1; }
        .filter-btn { background:#1976d2;color:#fff;border:none;padding:8px 18px;border-radius:20px;cursor:pointer; }
        .filter-dropdown { position:relative;display:inline-block; }
        .filter-list { display:none;position:absolute;right:0;background:#fff;box-shadow:0 2px 8px #e3eafc;border-radius:8px;z-index:10;}
        .filter-list a { display:block;padding:10px 20px;color:#1565c0;text-decoration:none;}
        .filter-list a:hover { background:#e3eafc;}
        @media (max-width:700px) {
            .event-grid { flex-direction:column; align-items:center;}
            .event-card { width:90vw; height:220px;}
        }
    </style>
    <script>
        function toggleFilter() {
            var list = document.getElementById('filter-list');
            list.style.display = (list.style.display === 'block') ? 'none' : 'block';
        }
        document.addEventListener('click', function(e){
            if (!e.target.closest('.filter-dropdown')) {
                document.getElementById('filter-list').style.display = 'none';
            }
        });
    </script>
</head>
<body style="background:#f8faff;">
<?php include 'navbar.php'; ?>
<div style="max-width:1200px;margin:auto;">
    <div style="display:flex;justify-content:flex-end;margin-top:25px;">
        <div class="filter-dropdown">
            <button class="filter-btn" onclick="toggleFilter()">Filter by Campus</button>
            <div class="filter-list" id="filter-list">
                <a href="index_event.php?Evt_campus=Puncak Perdana">Puncak Perdana</a>
                <a href="index_event.php?Evt_campus=Puncak Alam">Puncak Alam</a>
                <a href="index_event.php?Evt_campus=Shah Alam">Shah Alam</a>
                <a href="index_event.php?Evt_campus=Dengkil">Dengkil</a>
                <a href="index_event.php?Evt_campus=Sungai Buloh">Sungai Buloh</a>
                <a href="index_event.php" style="color:#888;">Show All</a>
            </div>
        </div>
    </div>
    <div class="event-grid">
        <?php while($row = $events->fetch_assoc()): ?>
        <div class="event-card" onclick="window.location='event_detail.php?id=<?php echo $row['Evt_id']; ?>'">
            <?php if (!empty($row['Evt_poster'])): ?>
                <img src="data:<?php echo $row['Evt_poster_type'] ?: 'image/jpeg'; ?>;base64,<?php echo base64_encode($row['Evt_poster']); ?>">
            <?php else: ?>
                <img src="assets/default-event.png">
            <?php endif; ?>
            <div class="event-info">
                <div style="font-size:18px;font-weight:bold;"><?php echo $row['Evt_title']; ?></div>
                <div style="margin-top:5px;"><?php echo $row['Evt_date_start']; ?></div>
                <div style="margin-top:2px;"><?php echo $row['Evt_time_start']; ?> – <?php echo $row['Evt_time_end']; ?></div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>