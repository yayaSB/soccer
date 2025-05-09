<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Include database connection
include '../inc/db.php';

// Fetch available players, blogs, and matches
$players_result = $conn->query("SELECT * FROM players");
$blogs_result = $conn->query("SELECT * FROM blogs");
$matches_result = $conn->query("SELECT * FROM matches");

// Fetch detailed player statistics for charts
$stats_query = "SELECT 
    name, 
    position,
    goals, 
    assists, 
    matches,
    yellow_cards, 
    red_cards,
    ROUND(goals / NULLIF(matches, 0), 2) as goals_per_match
FROM players 
ORDER BY goals DESC 
LIMIT 5";
$stats_result = $conn->query($stats_query);

// Initialize arrays for player data
$player_names = [];
$player_goals = [];
$player_assists = [];
$player_matches = [];
$player_cards = [];
$player_positions = [];
$goals_per_match = [];

while ($row = $stats_result->fetch_assoc()) {
    $player_names[] = $row['name'];
    $player_goals[] = $row['goals'];
    $player_assists[] = $row['assists'];
    $player_matches[] = $row['matches'];
    $player_positions[] = $row['position'];
    $goals_per_match[] = $row['goals_per_match'];
    $player_cards[] = [
        'yellow' => $row['yellow_cards'],
        'red' => $row['red_cards']
    ];
}

// Get team total statistics
$team_stats_query = "SELECT 
    COUNT(*) as total_players,
    SUM(goals) as total_goals,
    SUM(assists) as total_assists,
    SUM(matches) as total_matches,
    SUM(yellow_cards) as total_yellow_cards,
    SUM(red_cards) as total_red_cards,
    ROUND(AVG(goals), 2) as avg_goals,
    ROUND(AVG(assists), 2) as avg_assists
FROM players";
$team_stats = $conn->query($team_stats_query)->fetch_assoc();

// Get statistics by position
$position_stats_query = "SELECT 
    position,
    COUNT(*) as players_count,
    SUM(goals) as total_goals,
    SUM(assists) as total_assists
FROM players 
GROUP BY position";
$position_stats_result = $conn->query($position_stats_query);

$positions = [];
$position_goals = [];
$position_assists = [];
$position_players = [];

while ($row = $position_stats_result->fetch_assoc()) {
    $positions[] = $row['position'];
    $position_goals[] = $row['total_goals'];
    $position_assists[] = $row['total_assists'];
    $position_players[] = $row['players_count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stats-section {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
            margin-bottom: 20px;
        }
        .stats-title {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e31837;
        }
        .team-stats-card {
            background: linear-gradient(135deg, #1a237e, #e31837);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .team-stat-item {
            text-align: center;
            padding: 10px;
        }
        .team-stat-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .team-stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
    </style>
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
                            <li><a href="manage_users.php" class="nav-link">Manage User</a></li>
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
                    <h1>Manage Everything</h1>
                    <p>Manage all the system</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Statistiques de l'Équipe</h2>

        <!-- Team Overview Stats -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="team-stats-card">
                    <div class="row">
                        <div class="col-md-3 team-stat-item">
                            <div class="team-stat-value"><?php echo $team_stats['total_players']; ?></div>
                            <div class="team-stat-label">Joueurs Total</div>
                        </div>
                        <div class="col-md-3 team-stat-item">
                            <div class="team-stat-value"><?php echo $team_stats['total_goals']; ?></div>
                            <div class="team-stat-label">Buts Total</div>
                        </div>
                        <div class="col-md-3 team-stat-item">
                            <div class="team-stat-value"><?php echo $team_stats['avg_goals']; ?></div>
                            <div class="team-stat-label">Moyenne de Buts</div>
                        </div>
                        <div class="col-md-3 team-stat-item">
                            <div class="team-stat-value"><?php echo $team_stats['total_matches']; ?></div>
                            <div class="team-stat-label">Matchs Joués</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Player Statistics Charts -->
        <div class="row">
            <!-- Goals and Assists Chart -->
            <div class="col-md-6">
                <div class="stats-section">
                    <h3 class="stats-title">Buts & Passes Décisives</h3>
                    <div class="chart-container">
                        <canvas id="goalsAssistsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Goals per Match Chart -->
            <div class="col-md-6">
                <div class="stats-section">
                    <h3 class="stats-title">Ratio Buts/Match</h3>
                    <div class="chart-container">
                        <canvas id="goalsPerMatchChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Position Performance Chart -->
            <div class="col-md-6">
                <div class="stats-section">
                    <h3 class="stats-title">Performance par Position</h3>
                    <div class="chart-container">
                        <canvas id="positionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Cards Chart -->
            <div class="col-md-6">
                <div class="stats-section">
                    <h3 class="stats-title">Distribution des Cartons</h3>
                    <div class="chart-container">
                        <canvas id="cardsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing content -->
        <h2 class="text-center mb-4">Available Data</h2>

        <div class="row">
            <!-- Players -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">Players</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <?php while ($player = $players_result->fetch_assoc()) { ?>
                                <li><?php echo htmlspecialchars($player['name']); ?> (<?php echo htmlspecialchars($player['position']); ?>)</li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="manage_players.php" class="btn btn-primary">Manage Players</a>
                    </div>
                </div>
            </div>

            <!-- Blogs -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">Blogs</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <?php while ($blog = $blogs_result->fetch_assoc()) { ?>
                                <li>
                                    <a href="view_blog.php?id=<?php echo $blog['id']; ?>">
                                        <?php echo htmlspecialchars($blog['title']); ?>
                                    </a> - <?php echo htmlspecialchars($blog['author']); ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="manage_blogs.php" class="btn btn-primary">Manage Blogs</a>
                    </div>
                </div>
            </div>

            <!-- Matches -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">Matches</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <?php while ($match = $matches_result->fetch_assoc()) { ?>
                                <li><?php echo htmlspecialchars($match['team_1']); ?> vs. <?php echo htmlspecialchars($match['team_2']); ?> - Date: <?php echo htmlspecialchars($match['date']); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="manage_matches.php" class="btn btn-primary">Manage Matches</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>

<script>
    // Goals and Assists Chart
    new Chart(document.getElementById('goalsAssistsChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($player_names); ?>,
            datasets: [{
                label: 'Buts',
                data: <?php echo json_encode($player_goals); ?>,
                backgroundColor: '#e31837',
                borderColor: '#e31837',
                borderWidth: 1
            }, {
                label: 'Passes Décisives',
                data: <?php echo json_encode($player_assists); ?>,
                backgroundColor: '#1a237e',
                borderColor: '#1a237e',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Goals per Match Chart
    new Chart(document.getElementById('goalsPerMatchChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($player_names); ?>,
            datasets: [{
                label: 'Buts par Match',
                data: <?php echo json_encode($goals_per_match); ?>,
                borderColor: '#4caf50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Position Performance Chart
    new Chart(document.getElementById('positionChart'), {
        type: 'radar',
        data: {
            labels: <?php echo json_encode($positions); ?>,
            datasets: [{
                label: 'Buts',
                data: <?php echo json_encode($position_goals); ?>,
                backgroundColor: 'rgba(227, 24, 55, 0.2)',
                borderColor: '#e31837',
                borderWidth: 2
            }, {
                label: 'Passes Décisives',
                data: <?php echo json_encode($position_assists); ?>,
                backgroundColor: 'rgba(26, 35, 126, 0.2)',
                borderColor: '#1a237e',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true
                }
            }
        }
    });

    // Cards Chart
    new Chart(document.getElementById('cardsChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($player_names); ?>,
            datasets: [{
                label: 'Cartons Jaunes',
                data: <?php echo json_encode(array_column($player_cards, 'yellow')); ?>,
                backgroundColor: '#ffc107',
                borderColor: '#ffc107',
                borderWidth: 1
            }, {
                label: 'Cartons Rouges',
                data: <?php echo json_encode(array_column($player_cards, 'red')); ?>,
                backgroundColor: '#e31837',
                borderColor: '#e31837',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    document.getElementById('goals').addEventListener('input', function() {
        document.getElementById('goals-value').textContent = this.value;
    });
</script>

</body>
</html>
