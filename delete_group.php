<?php
include 'db.php';

if (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];

    // Delete all users in the group (optional, depends on your needs)
    $conn->query("DELETE FROM users WHERE group_id = '$group_id'");

    // Delete the group itself
    $conn->query("DELETE FROM groups WHERE group_id = '$group_id'");

    header("Location: index.php");
    exit;
}
?>
