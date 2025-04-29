<?php
include 'db.php';
$group_id = $_GET['group_id'] ?? '';
$group = $conn->query("SELECT * FROM groups WHERE group_id = '$group_id'")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = $_POST['group_name'];
    $conn->query("UPDATE groups SET group_name = '$new_name' WHERE group_id = '$group_id'");
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Group</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <h3>Edit Group: <?= $group['group_name'] ?> (<?= $group_id ?>)</h3>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label>New Group Name</label>
            <input type="text" name="group_name" class="form-control" value="<?= $group['group_name'] ?>" required>
        </div>
        <button class="btn btn-primary">Save Changes</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
