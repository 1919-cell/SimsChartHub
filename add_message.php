<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "simscharthub");

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id'];
    $content = trim($_POST['content']);

    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO messages (student_id, content, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $student_id, $content);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Failed to post message.";
        }
        $stmt->close();
    } else {
        $error = "Message cannot be empty.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Post Message - SimsChartHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .main {
            padding: 20px;
            margin-left: 200px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        .error {
            color: red;
        }
    </style>
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
    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="content">Message:</label><br>
        <textarea name="content" rows="5" required></textarea><br><br>
        <input type="submit" value="Post Message">
    </form>
</div>
</body>
</html>
