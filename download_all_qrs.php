<?php
include 'db.php';
require_once 'phpqrcode/qrlib.php';
$group_id = $_GET['group_id'];

// Create the ZIP file
$zip = new ZipArchive();
$zip_filename = "qr_codes_group_{$group_id}.zip";

// Create or open the zip file
if ($zip->open($zip_filename, ZipArchive::CREATE) !== TRUE) {
    exit("Cannot open <$zip_filename>\n");
}

// Fetch users for the group
$users = $conn->query("SELECT * FROM users WHERE group_id = '$group_id'");
while ($user = $users->fetch_assoc()) {
    $name = $user['name'];
    $phone = $user['phone'];
    $type = $user['type'] ?? 'N/A';
    $filenameSafe = preg_replace('/[^a-zA-Z0-9-_]/', '_', $name);
    $qrPath = "qr_codes/{$filenameSafe}.png";

    // Generate QR if not exists
    if (!file_exists($qrPath)) {
        if (!file_exists('qr_codes')) mkdir('qr_codes', 0777, true);
        $data = "Name: $name\nPhone: $phone\nType: $type\nGroup: $group_id";
        QRcode::png($data, $qrPath, QR_ECLEVEL_L, 4);
    }

    // Add QR code to the zip
    $zip->addFile($qrPath, "{$filenameSafe}.png");
}

// Close the ZIP file
$zip->close();

// Serve the ZIP file for download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . basename($zip_filename) . '"');
header('Content-Length: ' . filesize($zip_filename));

readfile($zip_filename);

// Delete the zip file after download
unlink($zip_filename);
?>
