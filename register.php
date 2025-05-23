<?php
$conn = new mysqli("localhost", "root", "", "simscharthub");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for the form
$name = $course = $contact = $email = "";
$password_required = true; // Only required on registration

// Detect action and id if provided
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle Delete
if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

// Handle Edit form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = $_POST['name'];
    $course = $_POST['course'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password_input = $_POST['password'] ?? '';

    if (isset($_POST['id']) && intval($_POST['id']) > 0) {
        // Editing existing record
        $id = intval($_POST['id']);
        
        if (!empty($password_input)) {
            $password = password_hash($password_input, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE students SET name=?, course=?, contact=?, email=?, password=? WHERE id=?");
            $stmt->bind_param("sssssi", $name, $course, $contact, $email, $password, $id);
        } else {
            // Password not changed, update other fields only
            $stmt = $conn->prepare("UPDATE students SET name=?, course=?, contact=?, email=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $course, $contact, $email, $id);
        }

        $stmt->execute();
        $stmt->close();

    } else {
        // New registration
        $password = password_hash($password_input, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO students (name, course, contact, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $course, $contact, $email, $password);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

// If editing, pre-fill the form with existing data
if ($action === 'edit' && $id > 0) {
    $stmt = $conn->prepare("SELECT name, course, contact, email FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($name, $course, $contact, $email);
    if (!$stmt->fetch()) {
        // ID not found, reset variables
        $name = $course = $contact = $email = "";
        $action = ''; // fallback to registration mode
    }
    $stmt->close();
    $password_required = false; // password optional when editing
}

// Fetch all students to display below the form
$result = $conn->query("SELECT id, name, course, contact, email FROM students ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration & Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions a { margin-right: 10px; text-decoration: none; color: blue; }
    </style>
</head>
<body>
<div class="main">
    <h2><?php echo $action === 'edit' ? "Edit Student" : "Student Registration"; ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>
        <label>Course:</label><br>
        <input type="text" name="course" value="<?php echo htmlspecialchars($course); ?>" required><br><br>
        <label>Contact:</label><br>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>
        <label>Password: <?php if (!$password_required) echo "(Leave blank to keep current)"; ?></label><br>
        <input type="password" name="password" <?php echo $password_required ? "required" : ""; ?>><br><br>
        <input type="submit" value="<?php echo $action === 'edit' ? "Update" : "Register"; ?>">
        <?php if ($action === 'edit'): ?>
            <a href="<?php echo strtok($_SERVER["REQUEST_URI"], '?'); ?>">Cancel</a>
        <?php endif; ?>
    </form>

    <h3>Registered Students</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Course</th><th>Contact</th><th>Email</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['course']); ?></td>
                <td><?php echo htmlspecialchars($row['contact']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="actions">
                    <a href="?action=edit&id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <p>Already registered? <a href='login.php'>Login here</a></p>
</div>
</body>
</html>