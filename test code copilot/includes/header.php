<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Event Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css" />
</head>
<body class="bg-white text-dark">
<nav class="navbar navbar-light bg-primary px-3">
    <span class="navbar-brand text-white">Event Admin Panel</span>
    <?php if(isset($_SESSION['organizer'])): ?>
        <a href="../logout.php" class="btn btn-outline-light">Logout</a>
    <?php endif; ?>
</nav>
<div class="d-flex">
    <div class="p-3 bg-light border-end" style="width: 220px;">
        <h5>Menu</h5>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="../dashboard.php" class="nav-link">🏠 Dashboard</a></li>
            <li class="nav-item"><a href="../event_crud.php" class="nav-link">📅 Manage Events</a></li>
            <li class="nav-item"><a href="../profile.php" class="nav-link">👤 Profile</a></li>
            <li class="nav-item"><a href="../logout.php" class="nav-link text-danger">🚪 Logout</a></li>
        </ul>
    </div>
    <div class="p-4 flex-grow-1"></div>
</div>
</body>
</html>
