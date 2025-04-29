<?php
require 'phpqrcode/qrlib.php';

$name = $_GET['name'] ?? 'Unknown';
$phone = $_GET['phone'] ?? 'N/A';
$data = "Name: $name\nPhone: $phone";

$dir = 'qr_codes';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

$filename = "$dir/$phone.png";
QRcode::png($data, $filename, QR_ECLEVEL_L, 4);

echo "<h2>QR Code for $name</h2>";
echo "<img src='$filename' alt='QR Code'><br><br>";
echo "<a href='index.php'>Back to Admin Panel</a>";
?>
