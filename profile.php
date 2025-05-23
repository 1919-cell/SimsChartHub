<?php
session_start();
$conn = new mysqli("localhost", "root", "", "simscharthub");

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$logged_in_id = $_SESSION['student_id'];
$viewed_id = isset($_GET['id']) ? intval($_GET['id']) : $logged_in_id;

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $logged_in_id == $viewed_id && isset($_FILES['profile_image'])) {
    $image = $_FILES['profile_image']['name'];
    $target = "uploads/" . basename($image);
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("UPDATE students SET profile_pic = ? WHERE id = ?");
        $stmt->bind_param("si", $image, $logged_in_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Get student info
$stmt = $conn->prepare("SELECT name, course, contact, profile_pic FROM students WHERE id = ?");
$stmt->bind_param("i", $viewed_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="profile">
        <h2>Student Profile</h2>
        <img src="uploads/<?php echo htmlspecialchars($student['profile_pic'] ?? 'default.png'); ?>" 
             alt="Profile Picture" style="width:150px;height:150px;"><br>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
        <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($student['contact']); ?></p>

        <?php if ($logged_in_id == $viewed_id): ?>
            <form method="POST" enctype="multipart/form-data">
                <label>Change Profile Picture:</label>
                <input type="file" name="profile_image" required>
                <button type="submit">Upload</button>
            </form>
        <?php endif; ?>
<<<<<<< Updated upstream
    </div>
=======
    
>>>>>>> Stashed changes
</body>
</html>