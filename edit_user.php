<?php
include 'db.php';

// Fetch the user details by phone number
if (isset($_GET['phone'])) {
    $phone = $_GET['phone'];

    $sql = "SELECT * FROM users WHERE phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} else {
    // Redirect if no phone number is specified
    header("Location: index.php");
    exit();
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $group_id = $_POST['group_id'];
    $type = $_POST['type'];

    // Update the user details in the database
    $sql = "UPDATE users SET name = ?, phone = ?, group_id = ?, type = ? WHERE phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $name, $phone, $group_id, $type, $phone);
    $stmt->execute();

    // Redirect back to the index page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">✏️ Edit User</h2>
    <form method="POST" action="edit_user.php?phone=<?php echo $phone; ?>" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" readonly>
        </div>
        <div class="col-md-3">
            <select name="group_id" class="form-select" required>
                <option value="">Select Group</option>
                <?php
                $groups = $conn->query("SELECT * FROM groups ORDER BY group_name ASC");
                while ($group = $groups->fetch_assoc()):
                    $selected = ($group['group_id'] == $user['group_id']) ? 'selected' : '';
                    echo "<option value='{$group['group_id']}' $selected>{$group['group_name']}</option>";
                endwhile;
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="type" class="form-select" required>
                <option value="Single" <?php echo ($user['type'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                <option value="Double" <?php echo ($user['type'] == 'Double') ? 'selected' : ''; ?>>Double</option>
            </select>
        </div>
        <div class="col-md-12 text-end">
            <button class="btn btn-primary" name="update">Update User</button>
        </div>
    </form>
</div>
</body>
</html>
