<?php
// Include Composer's autoloader
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Database connection
include 'db.php';

// Get group_id from the URL
$groupId = $_GET['group_id'];

// Debugging: Check if group_id exists in the URL
if (empty($groupId)) {
    die("Group ID is missing.");
}

// Check if the group_id exists in the groups table
$groupCheckQuery = "SELECT * FROM groups WHERE group_id = ?";
$stmt = $conn->prepare($groupCheckQuery);
$stmt->bind_param("i", $groupId);
$stmt->execute();
$result = $stmt->get_result();

// If the group_id does not exist in the groups table, show an error
if ($result->num_rows == 0) {
    die("Group ID not found in the database.");
}

// Handle the file upload and import logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
    $fileTmpPath = $_FILES['excel_file']['tmp_name'];
    $fileName = $_FILES['excel_file']['name'];
    $fileSize = $_FILES['excel_file']['size'];
    $fileType = $_FILES['excel_file']['type'];

    // Check if the file is an Excel file
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    if ($fileExtension != 'xlsx' && $fileExtension != 'xls') {
        die("Only Excel files are allowed.");
    }

    // Read the Excel file using PhpSpreadsheet
    $spreadsheet = IOFactory::load($fileTmpPath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // Insert the data into the database
    foreach ($rows as $row) {
        // Skip empty rows and ensure correct column structure
        if (!empty($row[0]) && !empty($row[1])) {
            $name = $row[0];
            $phone = $row[1];

            // Insert data into the 'users' table
            $stmt = $conn->prepare("INSERT INTO users (name, phone, group_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $name, $phone, $groupId);
            
            // Check if the statement executed successfully
            if (!$stmt->execute()) {
                // Debugging: Output the error message if insertion fails
                echo "Error inserting user: " . $stmt->error . "<br>";
            } else {
                echo "User inserted successfully: $name, $phone<br>";
            }
        }
    }

    echo "Excel file successfully imported!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import Excel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">📋 Import Excel</h2>

    <!-- File upload form -->
    <form action="import_excel.php?group_id=<?php echo $_GET['group_id']; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="excel_file" class="form-label">Choose Excel File</label>
            <input type="file" class="form-control" name="excel_file" id="excel_file" required>
        </div>
        <button type="submit" class="btn btn-primary">Import Excel</button>
    </form>

    <p class="mt-4">
        <a href="index.php" class="btn btn-secondary">Back to Admin Panel</a>
    </p>
</div>
</body>
</html>
