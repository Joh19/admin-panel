<?php
include 'db.php';

// Autoload PhpSpreadsheet manually
require_once 'PhpSpreadsheet/src/PhpSpreadsheet/Spreadsheet.php';
require_once 'PhpSpreadsheet/src/PhpSpreadsheet/Reader/Xlsx.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if (isset($_FILES['excel_file']['tmp_name'])) {
    $file = $_FILES['excel_file']['tmp_name'];

    $reader = new Xlsx();
    $spreadsheet = $reader->load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // Skip the first row (header)
    for ($i = 1; $i < count($rows); $i++) {
        $name = $rows[$i][0];
        $phone = $rows[$i][1];
        $group_id = $rows[$i][2];

        if ($name && $phone && $group_id) {
            $stmt = $conn->prepare("INSERT INTO users (name, phone, group_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $phone, $group_id);
            if ($stmt->execute()) {
                echo "User $name added successfully.<br>";
            } else {
                echo "Failed to add user $name.<br>";
            }
        }
    }
}

header("Location: index.php");
exit;
