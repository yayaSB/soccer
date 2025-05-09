<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../inc/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['role'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Update user role
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE username = ?");
    $stmt->bind_param("ss", $role, $username);
    if ($stmt->execute()) {
        $message = "User role updated successfully.";
    } else {
        $message = "Failed to update user role.";
    }
}

// Delete user
if (isset($_GET['delete_user'])) {
    $username = $_GET['delete_user'];

    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    if ($stmt->execute()) {
        $message = "User deleted successfully.";
    } else {
        $message = "Failed to delete user.";
    }
}

$result = $conn->query("SELECT username, role FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css"> <!-- Include your common CSS -->
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
                <h1>Manage Users</h1>
                <p>Manage all users in the system</p>
            </div>
        </div>
    </div>
</div>


    <!-- Manage Users Section -->
    <div class="container site-section mt-5">
        <h1 class="text-center mb-4">Manage Users</h1>
        <?php if (isset($message)) echo "<div class='alert alert-info'>$message</div>"; ?>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td>
                                <form method="POST" class="d-flex">
                                    <input type="hidden" name="username" value="<?php echo $row['username']; ?>">
                                    <select name="role" class="form-control w-auto mr-2">
                                        <option value="user" <?php if ($row['role'] == 'user') echo 'selected'; ?>>User</option>
                                        <option value="admin" <?php if ($row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                        <option value="trainer" <?php if ($row['role'] == 'trainer') echo 'selected'; ?>>Trainer</option>
                                        <option value="trainer" <?php if ($row['role'] == 'player') echo 'selected'; ?>>Player</option>
                                       
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                            <td>
                                <a href="?delete_user=<?php echo $row['username']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>

</body>
</html>
