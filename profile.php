<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Connect to the database
$conn = new mysqli("localhost", "root", "", "SimsChartHub");

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $image = $_FILES['profile_image']['name'];
    $target = "uploads/" . basename($image);

    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
        $update = $conn->prepare("UPDATE students SET profile_pic = ? WHERE id = ?");
        $update->bind_param("si", $image, $student_id);
        $update->execute();
        $update->close();
    }
}

// Fetch student details
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<html>
<head><title>Profile</title></head>
<body>
    <h2>choose or edit photo here</h2>
    <img src="uploads/<?php echo htmlspecialchars($student['profile_image'] ?? 'default.png'); ?>" 
         alt="Profile Picture" style="width:150px;height:150px;"><br>
    <form method="POST" enctype="multipart/form-data">
        <label>Upload Profile Picture:</label>
        <input type="file" name="profile_image" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
