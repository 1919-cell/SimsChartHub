<?php
$conn = new mysqli("localhost", "root", "", "simscharthub");
$messages = $conn->query("SELECT messages.*, students.name, students.photo FROM messages JOIN students ON messages.student_id = students.id ORDER BY messages.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>SimsChartHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="sidebar">
    <h2>SimsChartHub</h2>
    <ul>
        <li><a href="index.php">Dashboard</a></li>
        <li><a href="add_message.php">Post Message</a></li>
    </ul>
</div>
<div class="main">
    <h2>Public Messages</h2>
    <?php while($msg = $messages->fetch_assoc()): ?>
        <div class="message-card">
            <a href="profile.php?id=<?= $msg['student_id'] ?>">
                <img src="uploads/<?= $msg['photo'] ?>" class="avatar">
            </a>
            <div class="message-content">
                <strong><?= $msg['name'] ?></strong><br>
                <?= $msg['content'] ?><br>
                <small><?= $msg['created_at'] ?></small>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>