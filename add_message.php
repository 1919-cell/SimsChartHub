<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}


$host = "sql109.hstn.me";
$user = "mseet_39061377";
$pass = "charthub19"; // Replace with your actual cPanel password
$dbname = "mseet_39061377_simscharthub";
$conn = new mysqli("$host", "$user", "$pass", "$dbname");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id'];
    $content = $_POST['content'];
    $stmt = $conn->prepare("INSERT INTO messages (student_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $student_id, $content);
    $stmt->execute();
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Post Message</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="sidebar">
    <h2>SimsChartHub</h2>
    <ul>
        <li><a href="index.php">Dashboard</a></li>
        <li><a href="add_message.php">Post Message</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
<div class="main">
    <h2>Post a Message</h2>
    <form method="POST">
        <label for="content">Message:</label><br>
        <textarea name="content" rows="5" cols="40" required></textarea><br><br>
        <input type="submit" value="Post Message">
    </form>
</div>
</body>
</html>