<?php
include '../inc/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM players WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success"; // Return success message to AJAX
    } else {
        echo "failure"; // Return failure message to AJAX
    }
}
?>
