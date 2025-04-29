<?php
include 'db.php'; // Include your database connection file

// Check if the phone number is set and the form is submitted
if (isset($_POST['phone']) && !empty($_POST['phone'])) {
    $phone = $_POST['phone'];

    // Prepare the SQL statement to delete the user by phone number
    $stmt = $conn->prepare("DELETE FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone); // Bind the phone number to the SQL query

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // Redirect back to the index page with a success message
        echo "<script>alert('User deleted successfully'); window.location.href='index.php';</script>";
    } else {
        // If the deletion fails, show an error message
        echo "<script>alert('Error deleting user'); window.location.href='index.php';</script>";
    }

    // Close the statement
    $stmt->close();
} else {
    // If no phone number is provided, show an error
    echo "<script>alert('No user selected for deletion'); window.location.href='index.php';</script>";
}

// Close the database connection
$conn->close();
?>
