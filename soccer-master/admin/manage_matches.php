<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../inc/db.php';

// Fetch available matches from the database
$sql = "SELECT * FROM matches";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Matches</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
</head>
<body>
<div class="site-wrap">
    <!-- Admin Header -->
    <header class="site-navbar py-4" role="banner">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="site-logo">
                    <a href="../index.php">
                        <img src="../images/logo.png" alt="Logo">
                    </a>
                </div>
                <div class="ml-auto">
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                            <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                            <li><a href="manage_users.php" class="nav-link">Manage Users</a></li>
                            <li><a href="manage_players.php" class="nav-link">Manage Players</a></li>
                            <li><a href="manage_blogs.php" class="nav-link">Manage Blogs</a></li>
                            <li><a href="manage_matches.php" class="nav-link">Manage Matches</a></li>
                            <li><a href="../logout.php" class="nav-link">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="hero overlay" style="background-image: url('../images/bg_3.jpg'); height: 100vh; background-size: cover; background-position: center;">
        <div class="container" style="height: 100%; display: flex; justify-content: center; align-items: center;">
            <div class="row align-items-center text-center text-white">
                <div class="col-lg-6 ml-auto">
                    <h1>Manage Matches</h1>
                    <p>Manage all Matches in the system</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Matches Section -->
    <div class="container site-section mt-5">
        <div class="row">
            <!-- Left Side: View and Delete Matches -->
            <div class="col-md-6" id="match-list">
                <h2>Available Matches</h2>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="match mb-4 p-3 border rounded" id="match-<?= $row['id']; ?>">
                            <p><strong>Match:</strong> <?= $row['team_1']; ?> vs <?= $row['team_2']; ?></p>
                            <p><strong>Date:</strong> <?= $row['match_date']; ?> <strong>Time:</strong> <?= $row['match_time']; ?></p>
                            <p><strong>Venue:</strong> <?= $row['venue']; ?> <strong>League:</strong> <?= $row['league']; ?></p>
                            <img src="../images/<?= $row['team_1_logo']; ?>" alt="Team 1 Logo" width="50" class="mr-2">
                            <img src="../images/<?= $row['team_2_logo']; ?>" alt="Team 2 Logo" width="50" class="mr-2">
                            <button class="btn btn-danger mt-3 delete-match" data-id="<?= $row['id']; ?>">Delete</button>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No matches available.</p>
                <?php endif; ?>
            </div>

            <!-- Right Side: Add a New Match -->
            <div class="col-md-6">
                <h2>Add New Match</h2>
                <form method="POST" action="add_match.php" enctype="multipart/form-data" id="add-match-form">
                    <div class="form-group">
                        <label for="team_1">Team 1</label>
                        <input type="text" name="team_1" id="team_1" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="team_1_logo">Team 1 Logo</label>
                        <input type="file" name="team_1_logo" id="team_1_logo" class="form-control" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="team_2">Team 2</label>
                        <input type="text" name="team_2" id="team_2" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="team_2_logo">Team 2 Logo</label>
                        <input type="file" name="team_2_logo" id="team_2_logo" class="form-control" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="match_date">Match Date</label>
                        <input type="date" name="match_date" id="match_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="match_time">Match Time</label>
                        <input type="time" name="match_time" id="match_time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="venue">Venue</label>
                        <input type="text" name="venue" id="venue" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="league">League</label>
                        <input type="text" name="league" id="league" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Add Match</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();

    $(document).ready(function () {
        $('#add-match-form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: 'add_match.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response === 'success' ? 'Match added successfully!' : 'Error adding match.');
                    if (response === 'success') location.reload();
                }
            });
        });

        $('#match-list').on('click', '.delete-match', function () {
            let matchId = $(this).data('id');
            $.ajax({
                url: 'delete_match.php',
                type: 'POST',
                data: { match_id: matchId },
                success: function (response) {
                    if (response === 'success') {
                        alert('Match deleted successfully!');
                        $('#match-' + matchId).remove();
                    } else {
                        alert('Error deleting match.');
                    }
                }
            });
        });
    });
</script>
</body>
</html>
