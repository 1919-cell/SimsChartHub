<?php
session_start();
$host = "sql109.hstn.me";
$user = "mseet_39061377";
$pass = "charthub19"; // Replace with your actual cPanel password
$dbname = "mseet_39061377_simscharthub";
$conn = new mysqli("$host", "$user", "$pass", "$dbname");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION['student_id'];
$message_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($message_id <= 0) {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);
    if ($content === '') {
        $error = "Message content cannot be empty.";
    } else {
        // Verify ownership and update
        $stmt = $conn->prepare("UPDATE messages SET content = ? WHERE id = ? AND student_id = ?");
        $stmt->bind_param("sii", $content, $message_id, $current_user);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            $error = "Update failed or you don't have permission.";
        }
    }
}

// Fetch existing message content
$stmt = $conn->prepare("SELECT content FROM messages WHERE id = ? AND student_id = ?");
$stmt->bind_param("ii", $message_id, $current_user);
$stmt->execute();
$stmt->bind_result($existing_content);
if (!$stmt->fetch()) {
    // Message not found or not owned by user
    $stmt->close();
    header("Location: index.php");
    exit();
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Message</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="main">
    <h2>Edit Your Message</h2>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST">
        <textarea name="content" rows="5" cols="50" required><?php echo htmlspecialchars($existing_content); ?></textarea><br><br>
        <input type="submit" value="Update Message">
        <a href="index.php">Cancel</a>
    </form>
</div>
</body>
</html>
