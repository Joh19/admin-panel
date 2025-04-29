<?php
include 'db.php';

if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];

    // Delete the user from the users table
    $sql = "DELETE FROM users WHERE phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $phone);
    $stmt->execute();

    // Redirect back to the index page
    header("Location: index.php");
    exit();
}
?>
