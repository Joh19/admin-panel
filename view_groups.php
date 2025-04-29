<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Groups</title></head>
<body>
    <h2>All Groups</h2>
    <ul>
    <?php
    $res = $conn->query("SELECT * FROM groups");
    while ($row = $res->fetch_assoc()) {
        echo "<li>" . $row['group_name'] . " (ID: " . $row['group_id'] . ")</li>";
    }
    ?>
    </ul>
</body>
</html>
