<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $group_id = $_POST['group_id'];
    $type = $_POST['type']; // New field

    $stmt = $conn->prepare("INSERT INTO users (name, phone, group_id, type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $group_id, $type);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
