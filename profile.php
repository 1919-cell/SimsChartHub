<?php
$conn = new mysqli("localhost", "root", "", "simscharthub");
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $student['name'] ?>'s Profile</title>
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
    <div class="profile">
        <img src="uploads/<?= $student['photo'] ?>" class="avatar-large"><br><br>
        <h2><?= $student['name'] ?></h2>
        <p><strong>Course:</strong> <?= $student['course'] ?></p>
        <p><strong>Contact:</strong> <?= $student['contact'] ?></p>
    </div>
</div>
</body>
</html>