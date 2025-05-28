<?php
include 'includes/connect.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Invalid or missing token.");
}

$stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE verify_token = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $token);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Email verified! You can now <a href='login.php'>login</a>.";
} else {
    echo "Invalid or already used verification link.";
}

$stmt->close();
$conn->close();
?>
