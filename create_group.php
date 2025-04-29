<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = $_POST["group_name"];

    // Step 1: Insert dummy row to get auto-increment ID
    $stmt = $conn->prepare("INSERT INTO groups (group_name) VALUES (?)");
    $stmt->bind_param("s", $group_name);
    $stmt->execute();

    $last_id = $conn->insert_id; // Get last auto-incremented ID

    // Step 2: Generate group_id like G001
    $group_id = 'G' . str_pad($last_id, 3, '0', STR_PAD_LEFT);

    // Step 3: Update the row with formatted group_id
    $stmt2 = $conn->prepare("UPDATE groups SET group_id = ? WHERE id = ?");
    $stmt2->bind_param("si", $group_id, $last_id);
    $stmt2->execute();

    header("Location: index.php");
    exit;
}
?>
