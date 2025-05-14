<?php
$conn = new mysqli("localhost", "root", "", "simscharthub");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $course = $_POST['course'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO students (name, course, contact, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $course, $contact, $email, $password);
    $stmt->execute();

    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="main">
    <h2>Student Registration</h2>
    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>
        <label>Course:</label><br>
        <input type="text" name="course" required><br><br>
        <label>Contact:</label><br>
        <input type="text" name="contact" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Register">
    </form>
    <p>Already registered? <a href='login.php'>Login here</a></p>
</div>
</body>
</html>