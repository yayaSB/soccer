<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Include database connection
include('../inc/db.php');

if (isset($_POST['add_player'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $position = $conn->real_escape_string($_POST['position']);
    $age = (int)$_POST['age'];
    $goals = (int)$_POST['goals'];
    $assists = (int)$_POST['assists'];
    $matches = (int)$_POST['matches'];
    $clean_sheets = (int)$_POST['clean_sheets'];
    $yellow_cards = (int)$_POST['yellow_cards'];
    $red_cards = (int)$_POST['red_cards'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target_dir = "../images/players/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($image);
    $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate image
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $max_file_size = 5 * 1024 * 1024; // 5MB

    // Vérification complète du fichier
    $upload_ok = true;
    $error_message = "";

    // Vérifier si un fichier a été uploadé
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $upload_ok = false;
        $error_message = "Erreur lors de l'upload du fichier.";
    }
    // Vérifier le type MIME
    elseif (!in_array($image_type, $allowed_types)) {
        $upload_ok = false;
        $error_message = "Seuls les fichiers JPG, JPEG, PNG, GIF et WEBP sont autorisés.";
    }
    // Vérifier la taille du fichier
    elseif ($_FILES['image']['size'] > $max_file_size) {
        $upload_ok = false;
        $error_message = "Le fichier est trop volumineux. Taille maximum : 5MB.";
    }
    // Vérifier si c'est une vraie image
    elseif (!getimagesize($_FILES['image']['tmp_name'])) {
        $upload_ok = false;
        $error_message = "Le fichier n'est pas une image valide.";
    }

    if (!$upload_ok) {
        echo "<script>alert('$error_message');</script>";
    } elseif (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insert player into players table
        $stmt = $conn->prepare("INSERT INTO players (name, position, age, goals, assists, matches, clean_sheets, yellow_cards, red_cards, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiiiiiis", $name, $position, $age, $goals, $assists, $matches, $clean_sheets, $yellow_cards, $red_cards, $image);

        if ($stmt->execute()) {
            echo "<script>alert('Player added successfully!');</script>";
            $stmt->close();
        } else {
            echo "<script>alert('Error adding player: " . $stmt->error . "');</script>";
            $stmt->close();
        }
    } else {
        echo "<script>alert('Failed to upload the image.');</script>";
    }
}

// Update player stats
if (isset($_POST['update_stats'])) {
    $player_id = (int)$_POST['player_id'];
    $goals = (int)$_POST['goals'];
    $assists = (int)$_POST['assists'];
    $matches = (int)$_POST['matches'];
    $clean_sheets = (int)$_POST['clean_sheets'];
    $yellow_cards = (int)$_POST['yellow_cards'];
    $red_cards = (int)$_POST['red_cards'];

    $stmt = $conn->prepare("UPDATE players SET goals = ?, assists = ?, matches = ?, clean_sheets = ?, yellow_cards = ?, red_cards = ? WHERE id = ?");
    $stmt->bind_param("iiiiiii", $goals, $assists, $matches, $clean_sheets, $yellow_cards, $red_cards, $player_id);

    if ($stmt->execute()) {
        echo "<script>alert('Player statistics updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating player statistics: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Delete player functionality
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM players WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Player deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting player: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Fetch players from the database
$players = $conn->query("SELECT * FROM players");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Players</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Force all form text to be black in all states */
.form-control,
.form-control:not(:focus),
.form-control:disabled,
.form-control[readonly],
.form-control::placeholder,
.form-select,
.form-select:not(:focus),
textarea,
textarea:not(:focus) {
    color: #000000 !important;
}

/* Ensure placeholders are visible but slightly lighter */
.form-control::placeholder {
    color: #666666 !important;
    opacity: 1 !important;
}

/* Light background for better contrast */
.form-control,
.form-select,
textarea {
    background-color: #ffffff !important;
    border: 2px solid #ddd !important;
}

/* Focus state styling */
.form-control:focus,
.form-select:focus,
textarea:focus {
    border-color: #e31837 !important;
    box-shadow: 0 0 5px rgba(227, 24, 55, 0.3) !important;
    color: #000000 !important; /* Maintain black text when focused */
}
/* Style for select dropdown options */
.form-select option {
    color: #000000 !important;
    background-color: #ffffff !important;
}
    .stats-form {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .player-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .player-header {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .player-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #e31837;
    }

    .player-info {
        margin-left: 20px;
        flex-grow: 1;
    }

    .player-name {
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
    }

    .player-details {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }

    .detail-item {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        text-align: center;
    }

    .detail-label {
        font-size: 0.8rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 1.2rem;
        font-weight: bold;
        color: #e31837;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }

    .stat-item {
        text-align: center;
        padding: 10px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stat-value {
        font-size: 1.2rem;
        font-weight: bold;
        color: #e31837;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #666;
    }

    .section-title {
        color: #e31837;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid #e31837;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background-color: #e31837;
    }

    .delete-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

    .actions-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .form-group label {
        font-weight: bold;
        color: #333333;
        margin-bottom: 8px;
        font-size: 1rem;
        display: block;
    }

    .form-control {
    border: 2px solid #ddd;
    border-radius: 5px;
    padding: 12px;
    font-size: 1rem;
    background-color: #ffffff;
    color: #000000;  /* Ensure text remains black */
    transition: all 0.3s ease;
}

.form-control::placeholder {
    color: #888;  /* Light gray for placeholder text */
    opacity: 1;
}

.form-control:focus {
    border-color: #e31837;
    box-shadow: 0 0 5px rgba(227, 24, 55, 0.3);
    outline: none;
    color: #000000; /* Ensure text remains black when focused */
}

.form-control:focus::placeholder {
    opacity: 0.5;  /* Slight fade effect on placeholder when focused */
}


    select.form-control {
        height: 45px;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #e31837;
        border: none;
        padding: 12px 30px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #c41230;
        transform: translateY(-2px);
    }

    .form-row {
        margin-bottom: 20px;
    }

    .image-input-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 2px dashed #ddd;
    }

    .image-type-selector {
        margin-bottom: 20px;
    }

    .form-check-label {
        color: #333333;
        font-weight: 500;
    }

    /* Style pour les messages d'erreur */
    .invalid-feedback {
        color: #e31837;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    /* Style pour les champs requis */
    .form-group label::after {
        content: " *";
        color: #e31837;
    }

    .stats-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
    }

    .stats-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e31837;
    }

    .image-input-container {
        margin-top: 15px;
    }

    .preview-image {
        max-width: 150px;
        max-height: 150px;
        border-radius: 10px;
        margin-top: 10px;
        display: none;
    }

    /* Style spécifique pour les champs numériques */
    input[type="number"] {
        -moz-appearance: textfield;
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
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
            <div class="row align-items-center" style="height: 100%; width: 100%; text-align: center;">
                <div class="col-lg-6 ml-auto text-center text-white">
                    <h1>Manage Players</h1>
                    <p>Manage all players in the system</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Players Section -->
    <div class="container my-5">
        <!-- Add Player Form -->
        <h2 class="section-title">Ajouter un nouveau joueur</h2>
        <div class="stats-form">
            <form method="POST" enctype="multipart/form-data">
                <!-- Informations de base -->
                <div class="form-section">
                    <h3 class="subsection-title">Informations de base</h3>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="player_name">Nom du joueur</label>
                            <input type="text" id="player_name" name="name" class="form-control" required placeholder="Entrez le nom du joueur">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="player_position">Position</label>
                            <select id="player_position" name="position" class="form-control" required>
                                <option value="">Sélectionnez une position</option>
                                <option value="forward">Attaquant</option>
                                <option value="midfielder">Milieu</option>
                                <option value="defender">Défenseur</option>
                                <option value="goalkeeper">Gardien</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="player_age">Âge</label>
                            <input type="number" id="player_age" name="age" class="form-control" required min="15" max="45" placeholder="Âge du joueur">
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="form-section">
                    <h3 class="subsection-title">Statistiques</h3>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="player_goals">Buts</label>
                            <input type="number" id="player_goals" name="goals" class="form-control" value="0" min="0" placeholder="Nombre de buts">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="player_assists">Passes décisives</label>
                            <input type="number" id="player_assists" name="assists" class="form-control" value="0" min="0" placeholder="Nombre de passes décisives">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="player_matches">Matches joués</label>
                            <input type="number" id="player_matches" name="matches" class="form-control" value="0" min="0" placeholder="Nombre de matches">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="player_clean_sheets">Clean sheets</label>
                            <input type="number" id="player_clean_sheets" name="clean_sheets" class="form-control" value="0" min="0" placeholder="Nombre de clean sheets">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="player_yellow_cards">Cartons jaunes</label>
                            <input type="number" id="player_yellow_cards" name="yellow_cards" class="form-control" value="0" min="0" placeholder="Nombre de cartons jaunes">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="player_red_cards">Cartons rouges</label>
                            <input type="number" id="player_red_cards" name="red_cards" class="form-control" value="0" min="0" placeholder="Nombre de cartons rouges">
                        </div>
                    </div>
                </div>

                <!-- Photo du joueur -->
                <div class="form-section">
                    <h3 class="subsection-title">Photo du joueur</h3>
                    <div class="form-group">
                        <div class="image-input-section">
                            <label for="player_image">Photo</label>
                            <input type="file" id="player_image" name="image" class="form-control" accept="image/*" required>
                            <small class="form-text text-muted">Formats acceptés : JPG, JPEG, PNG, GIF, WEBP (Max: 5MB)</small>
                            <img id="image_preview" class="preview-image mt-3" alt="Aperçu de l'image">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="add_player" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Ajouter le joueur
                    </button>
                </div>
            </form>
        </div>

        <hr>

        <!-- Display Players -->
        <h2 class="section-title">Players List</h2>
        <div class="row">
            <?php while ($player = $players->fetch_assoc()) { ?>
                <div class="col-md-6">
                    <div class="player-card">
                        <div class="player-header">
                            <img src="<?php 
                                if (isset($player['is_image_url']) && $player['is_image_url']) {
                                    echo htmlspecialchars($player['image']);
                                } else {
                                    echo '../images/players/' . htmlspecialchars($player['image']);
                                }
                            ?>" alt="<?php echo htmlspecialchars($player['name']); ?>" class="player-image">
                            <div class="player-info">
                                <h3 class="player-name"><?php echo htmlspecialchars($player['name']); ?></h3>
                                <div class="player-details">
                                    <div class="detail-item">
                                        <div class="detail-label">Position</div>
                                        <div class="detail-value"><?php echo ucfirst($player['position']); ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Age</div>
                                        <div class="detail-value"><?php echo $player['age']; ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Jersey Number</div>
                                        <div class="detail-value"><?php echo isset($player['number']) ? $player['number'] : 'N/A'; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value"><?php echo isset($player['goals']) ? $player['goals'] : 0; ?></div>
                                <div class="stat-label">Goals</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo isset($player['assists']) ? $player['assists'] : 0; ?></div>
                                <div class="stat-label">Assists</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo isset($player['matches']) ? $player['matches'] : 0; ?></div>
                                <div class="stat-label">Matches</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo isset($player['clean_sheets']) ? $player['clean_sheets'] : 0; ?></div>
                                <div class="stat-label">Clean Sheets</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo isset($player['yellow_cards']) ? $player['yellow_cards'] : 0; ?></div>
                                <div class="stat-label">Yellow Cards</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo isset($player['red_cards']) ? $player['red_cards'] : 0; ?></div>
                                <div class="stat-label">Red Cards</div>
                            </div>
                        </div>

                        <div class="stats-section">
                            <h4 class="stats-title">Update Player Statistics</h4>
                            <form method="POST" class="flex-grow-1">
                                <input type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label>Goals</label>
                                        <input type="number" name="goals" class="form-control" value="<?php echo isset($player['goals']) ? $player['goals'] : 0; ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Assists</label>
                                        <input type="number" name="assists" class="form-control" value="<?php echo isset($player['assists']) ? $player['assists'] : 0; ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Matches</label>
                                        <input type="number" name="matches" class="form-control" value="<?php echo isset($player['matches']) ? $player['matches'] : 0; ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Clean Sheets</label>
                                        <input type="number" name="clean_sheets" class="form-control" value="<?php echo isset($player['clean_sheets']) ? $player['clean_sheets'] : 0; ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Yellow Cards</label>
                                        <input type="number" name="yellow_cards" class="form-control" value="<?php echo isset($player['yellow_cards']) ? $player['yellow_cards'] : 0; ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Red Cards</label>
                                        <input type="number" name="red_cards" class="form-control" value="<?php echo isset($player['red_cards']) ? $player['red_cards'] : 0; ?>">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" name="update_stats" class="btn btn-success">Update Stats</button>
                                    <a href="?delete=<?php echo $player['id']; ?>" class="btn btn-danger ml-2" onclick="return confirm('Are you sure you want to delete this player?');">Delete Player</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

</div>

<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction pour gérer les champs numériques
        function handleNumericInput(input) {
            // S'assurer que la valeur est toujours visible
            input.style.backgroundColor = '#fff';
            input.style.color = '#333';
            
            // Mettre à jour la valeur quand elle change
            input.addEventListener('input', function() {
                this.setAttribute('value', this.value);
                // Ajouter une classe active quand le champ a une valeur
                if (this.value !== '') {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });

            // Empêcher les valeurs négatives
            input.addEventListener('change', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        }

        // Appliquer aux champs numériques
        const numericInputs = document.querySelectorAll('input[type="number"]');
        numericInputs.forEach(handleNumericInput);

        // Gérer l'aperçu de l'image
        const imageInput = document.getElementById('player_image');
        const imagePreview = document.getElementById('image_preview');

        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>

</body>
</html>
