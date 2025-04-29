<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists in admins table
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already exists. Try logging in.";
        exit;
    }

    // Insert new admin
    $stmt = $conn->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashedPassword);
    if ($stmt->execute()) {
        $_SESSION['admin'] = $email;
        header("Location: index.php");
        exit;
    } else {
        echo "Signup failed.";
    }
}
?>
