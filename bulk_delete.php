<?php
include 'db.php';

// Check if user IDs are passed
if (isset($_POST['user_ids']) && is_array($_POST['user_ids'])) {
    // Prepare user IDs for deletion
    $user_ids = implode(',', $_POST['user_ids']);
    
    // Delete the selected users from the database
    $delete_users = $conn->query("DELETE FROM users WHERE user_id IN ($user_ids)");
    
    if ($delete_users) {
        // Successfully deleted users
        header("Location: index.php");  // Redirect back to the user management page
        exit;
    } else {
        echo "Failed to delete the selected users.";
    }
} else {
    echo "No users selected for deletion.";
}
?>
