<?php
session_start();
$conn = new mysqli("localhost", "root", "", "simscharthub");

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION['student_id'];
$message_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($message_id > 0) {
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ? AND student_id = ?");
    $stmt->bind_param("ii", $message_id, $current_user);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit();