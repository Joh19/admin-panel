<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';
$adminEmail = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scosiah Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #343a40;
            color: white;
        }
        .table thead th {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="header-bar">
    <h5 class="m-0">📋 Scosiah Admin Panel</h5>
    <div>
        <span class="me-3">👋 Welcome, <?php echo $adminEmail; ?></span>
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
    </div>
</div>

<div class="container py-4">

    <!-- Group Creation -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Create Group</div>
        <div class="card-body">
            <form class="row g-3" action="create_group.php" method="POST">
                <div class="col-md-4">
                    <input type="text" name="group_id" class="form-control" placeholder="Group ID" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="group_name" class="form-control" placeholder="Group Name" required>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-success w-100">Add Group</button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Add -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">Add User</div>
        <div class="card-body">
            <form class="row g-3" action="add_user.php" method="POST">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="User Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                </div>
                <div class="col-md-3">
                    <select name="group_id" class="form-select" required>
                        <option value="">Select Group</option>
                        <?php
                        $groups = $conn->query("SELECT * FROM groups ORDER BY group_name ASC");
                        while ($group = $groups->fetch_assoc()):
                            echo "<option value='{$group['group_id']}'>{$group['group_name']}</option>";
                        endwhile;
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Display Groups and Users -->
    <?php
    $groups = $conn->query("SELECT * FROM groups ORDER BY group_name ASC");
    while ($group = $groups->fetch_assoc()):
        $gid = $group['group_id'];
        $gname = $group['group_name'];
        echo "
        <div class='mb-4'>
            <div class='d-flex justify-content-between align-items-center mb-2'>
                <h5 class='text-primary'>{$gname} <small class='text-muted'>($gid)</small></h5>
                <div>
                    <a href='edit_group.php?group_id=$gid' class='btn btn-warning btn-sm me-1'>Edit</a>
                    <a href='delete_group.php?group_id=$gid' class='btn btn-danger btn-sm me-1' onclick='return confirm(\"Delete this group?\")'>Delete</a>
                    <a href='import_excel.php?group_id=$gid' class='btn btn-info btn-sm'>Import Excel</a>
                </div>
            </div>
        ";

        $users = $conn->query("SELECT * FROM users WHERE group_id = '$gid' ORDER BY name ASC");
        if ($users->num_rows > 0):
            echo "<div class='table-responsive'><table class='table table-bordered table-striped'>";
            echo "<thead><tr><th>Name</th><th>Phone</th><th>Type</th><th>QR Code</th><th>Actions</th></tr></thead><tbody>";

            while ($user = $users->fetch_assoc()):
                $phone = $user['phone'];
                $name = $user['name'];
                $type = $user['type'] ?? 'N/A';
                $filenameSafe = preg_replace('/[^a-zA-Z0-9-_]/', '_', $name);
                $qrPath = "qr_codes/{$filenameSafe}.png";

                if (!file_exists($qrPath)) {
                    include_once 'phpqrcode/qrlib.php';
                    if (!file_exists('qr_codes')) mkdir('qr_codes', 0777, true);
                    $data = "Name: $name\nPhone: $phone\nType: $type\nGroup: $gname";
                    QRcode::png($data, $qrPath, QR_ECLEVEL_L, 4);
                }

                echo "<tr>
                    <td>$name</td>
                    <td>$phone</td>
                    <td>$type</td>
                    <td><a href='$qrPath' download='{$filenameSafe}.png' class='btn btn-outline-success btn-sm'>Download</a></td>
                    <td class='d-flex gap-2'>
                        <form method='POST' action='delete_user.php' onsubmit='return confirm(\"Delete this user?\")'>
                            <input type='hidden' name='phone' value='$phone'>
                            <button class='btn btn-danger btn-sm'>Delete</button>
                        </form>
                        <a href='edit_user.php?phone=$phone' class='btn btn-warning btn-sm'>Edit</a>
                    </td>
                </tr>";
            endwhile;

            echo "</tbody></table></div>";
        else:
            echo "<p class='text-muted'>No users in this group.</p>";
        endif;

        echo "</div>";
    endwhile;
    ?>

</div>

</body>
</html>
