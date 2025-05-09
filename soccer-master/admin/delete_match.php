<?php
include '../inc/db.php';

if (isset($_POST['match_id'])) {
    $match_id = $_POST['match_id'];

    // Prepare query to delete a match
    $sql = "DELETE FROM matches WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $match_id);
    
    // Execute query and check for success
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
