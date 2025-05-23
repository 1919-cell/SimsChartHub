<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "SimsChartHub");

// Check if viewing own profile or someone else's
$student_id = $_SESSION['student_id'];
$view_id = isset($_GET['id']) ? intval($_GET['id']) : $student_id;

// Handle profile image upload if viewing own profile
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image']) && $view_id === $student_id) {
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
$stmt->bind_param("i", $view_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo ($view_id === $student_id) ? "My Profile" : "Student Profile"; ?></title>
    <style>
        body { font-family: Arial; margin: 40px; }
        img { border-radius: 50%; width: 150px; height: 150px; object-fit: cover; }
        .info { margin-top: 20px; }
        .upload-form { margin-top: 20px; }
    </style>
</head>
<body>
    <h2><?php echo ($view_id === $student_id) ? "Your Profile" : htmlspecialchars($student['name']) . "'s Profile"; ?></h2>

    <img src="uploads/<?php echo htmlspecialchars($student['profile_pic'] ?? 'default.png'); ?>" alt="Profile Picture"><br>

    <div class="info">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
        <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($student['contact']); ?></p>
    </div>

    <?php if ($view_id === $student_id): ?>
    <div class="upload-form">
        <h3>Edit Profile Picture</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_image" required>
            <button type="submit">Upload</button>
        </form>
    </div>
    <?php endif; ?>
</body>
</html>