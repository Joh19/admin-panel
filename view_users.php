<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Users</title></head>
<body>
    <h2>All Users</h2>
    <table border="1">
        <tr><th>Name</th><th>Phone</th><th>Group ID</th><th>QR Code</th></tr>
        <?php
        include 'phpqrcode/qrlib.php';
        $res = $conn->query("SELECT * FROM users");
        while ($row = $res->fetch_assoc()) {
            $qrText = $row['name'] . " | " . $row['phone'];
            $fileName = "assets/" . $row['id'] . ".png";
            QRcode::png($qrText, $fileName);
            echo "<tr><td>{$row['name']}</td><td>{$row['phone']}</td><td>{$row['group_id']}</td><td><img src='$fileName' width='80'></td></tr>";
        }
        ?>
    </table>
</body>
</html>
