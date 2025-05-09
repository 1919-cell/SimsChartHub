<?php
$conn = new mysqli("localhost", "root", "", "simscharthub");
$students = $conn->query("SELECT id, name FROM students");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
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
    </ul>
</div>
<div class="main">
    <h2>Post a Message</h2>
    <form method="POST">
        <label for="student_id">Select Student:</label><br>
        <select name="student_id" required>
            <option value="">--Select Student--</option>
            <?php while($s = $students->fetch_assoc()): ?>
                <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
            <?php endwhile; ?>
        </select><br><br>
        <label for="content">Message:</label><br>
        <textarea name="content" rows="5" cols="40" required></textarea><br><br>
        <input type="submit" value="Post Message">
    </form>
</div>
</body>
</html>