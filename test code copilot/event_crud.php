<?php
// Purpose: Add, edit, remove events
session_start();
if (!isset($_SESSION['organizer'])) header("Location: login.php");
require 'db.php';

// Handle add event
if (isset($_POST['add_event'])) {
    $title = $_POST['Evt_title'];
    $desc = $_POST['Evt_desc'];
    $venue = $_POST['Evt_loc'];
    $campus = $_POST['Evt_campus'];
    $start_date = $_POST['Evt_date_start'];
    $end_date = $_POST['Evt_date_end'];
    $start_time = $_POST['Evt_time_start'];
    $end_time = $_POST['Evt_time_end'];
    $poster = null;
    $poster_type = null;
    if (!empty($_FILES['Evt_poster']['tmp_name'])) {
        $poster = addslashes(file_get_contents($_FILES['Evt_poster']['tmp_name']));
        $poster_type = $_FILES['Evt_poster']['type'];
    }
    $sql = "INSERT INTO event (Evt_title, Evt_desc, Evt_loc, Evt_campus, Evt_date_start, Evt_date_end, Evt_time_start, Evt_time_end, Evt_poster, Evt_poster_type) VALUES 
    ('$title', '$desc', '$venue', '$campus', '$start_date', '$end_date', '$start_time', '$end_time', '$poster', '$poster_type')";
    $conn->query($sql);
    echo "<script>
        window.onload = function() {
            let popup = document.getElementById('success-popup');
            popup.style.display = 'block';
            setTimeout(()=>{ popup.style.display='none'; }, 2000);
        }
    </script>";
}

// Handle delete event
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM event WHERE Evt_id=$id");
    header("Location: event_crud.php");
    exit;
}

// Handle edit event
if (isset($_POST['edit_event'])) {
    $id = $_POST['Evt_id'];
    $title = $_POST['Evt_title'];
    $desc = $_POST['Evt_desc'];
    $venue = $_POST['Evt_loc'];
    $campus = $_POST['Evt_campus'];
    $start_date = $_POST['Evt_date_start'];
    $end_date = $_POST['Evt_date_end'];
    $start_time = $_POST['Evt_time_start'];
    $end_time = $_POST['Evt_time_end'];
    $updatePoster = "";
    if (!empty($_FILES['poster']['tmp_name'])) {
        $poster = addslashes(file_get_contents($_FILES['poster']['tmp_name']));
        $poster_type = $_FILES['poster']['type'];
        $updatePoster = ", Evt_poster='$poster', Evt_poster_type='$poster_type'";
    }
    $sql = "UPDATE event SET Evt_title='$title', Evt_desc='$desc', Evt_loc='$venue', Evt_date_start='$start_date', Evt_date_end='$end_date', Evt_time_start='$start_time', Evt_time_end='$end_time' $updatePoster WHERE Evt_id=$id";
    $conn->query($sql);
    header("Location: event_crud.php");
    exit;
}

// Fetch events
$events = $conn->query("SELECT * FROM event ORDER BY Evt_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Events</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <h2 style="color:#1565c0;">Manage Events</h2>
    <!-- Success popup -->
    <div id="success-popup" class="success-popup">Successfully added event!</div>
    <!-- Add Event Form -->
    <form method="post" enctype="multipart/form-data" style="max-width:600px;">
        <h3>Add Event</h3>
        <!-- Pinpoint: Add your event data here -->
        <div class="form-group">
            <label>Event Title</label>
            <input type="text" name="Evt_title" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="Evt_desc" required></textarea>
        </div>
        <div class="form-group">
            <label>Venue</label>
            <input type="text" name="Evt_loc" required>
        </div>
        <div class="form-group">
            <label>Campus</label>
            <input type="text" name="Evt_campus" required>
        </div>
        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="Evt_date_start" required>
        </div>
        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="Evt_date_end" required>
        </div>
        <div class="form-group">
            <label>Start Time</label>
            <input type="time" name="Evt_time_start" required>
        </div>
        <div class="form-group">
            <label>End Time</label>
            <input type="time" name="Evt_time_end" required>
        </div>
        <div class="form-group">
            <label>Event Poster</label>
            <input type="file" name="Evt_poster" accept="image/*" required>
        </div>
        <button class="btn" type="submit" name="add_event">Add Event</button>
    </form>
    <hr>
    <!-- Events Table -->
    <h3>All Events</h3>
    <table class="table">
        <tr>
            <th>Poster</th>
            <th>Title</th>
            <th>Venue</th>
            <th>Campus</th>
            <th>Start</th>
            <th>End</th>
            <th>Action</th>
        </tr>
        <?php while($row = $events->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if (!empty($row['Evt_poster'])): ?>
                    <img src="data:<?php echo $row['Evt_poster_type'] ?: 'image/jpeg'; ?>;base64,<?php echo base64_encode($row['Evt_poster']); ?>" style="width:60px;height:40px;object-fit:cover;">
                <?php else: ?>
                    <img src="assets/default-event.png" style="width:60px;height:40px;object-fit:cover;">
                <?php endif; ?>
            </td>
            <td><?php echo $row['Evt_title']; ?></td>
            <td><?php echo $row['Evt_loc']; ?></td>
            <td><?php echo $row['Evt_campus']; ?></td>
            <td><?php echo $row['Evt_date_start']." ".$row['Evt_time_start']; ?></td>
            <td><?php echo $row['Evt_date_end']." ".$row['Evt_time_end']; ?></td>
            <td>
                <!-- Edit Button (shows inline form) -->
                <button class="btn" onclick="showEditForm(<?php echo $row['Evt_id']; ?>)">Edit</button>
                <a class="btn" href="event_crud.php?delete=<?php echo $row['Evt_id']; ?>" onclick="return confirm('Delete this event?')">Remove</a>
            </td>
        </tr>
        <!-- Edit Form (hidden by default) -->
        <tr id="edit-form-<?php echo $row['Evt_id']; ?>" style="display:none;">
            <td colspan="6">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="Evt_id" value="<?php echo $row['Evt_id']; ?>">
                    <div class="form-group">
                        <label>Event Title</label>
                        <input type="text" name="Evt_title" value="<?php echo $row['Evt_title']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="Evt_desc" required><?php echo $row['Evt_desc']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Venue</label>
                        <input type="text" name="Evt_loc" value="<?php echo $row['Evt_loc']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Campus</label>
                        <input type="text" name="Evt_campus" value="<?php echo $row['Evt_campus']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="Evt_date_start" value="<?php echo $row['Evt_date_start']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="Evt_date_end" value="<?php echo $row['Evt_date_end']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Start Time</label>
                        <input type="time" name="Evt_time_start" value="<?php echo $row['Evt_time_start']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>End Time</label>
                        <input type="time" name="Evt_time_end" value="<?php echo $row['Evt_time_end']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Event Poster</label>
                        <input type="file" name="poster" accept="image/*">
                    </div>
                    <button class="btn" type="submit" name="edit_event">Save</button>
                    <button class="btn" type="button" onclick="hideEditForm(<?php echo $row['Evt_id']; ?>)">Cancel</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<script>
function showEditForm(id) {
    document.getElementById('edit-form-'+id).style.display = '';
}
function hideEditForm(id) {
    document.getElementById('edit-form-'+id).style.display = 'none';
}
</script>
</body>
</html>
