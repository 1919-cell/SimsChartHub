<?php
session_start();
$conn = new mysqli("localhost", "root", "", "simscharthub");

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION['student_id'];

// Fetch messages with student info
$query = "SELECT messages.id AS message_id, messages.content, messages.created_at, 
                 students.name, students.course, students.contact, students.profile_pic, students.id AS student_id
          FROM messages
          JOIN students ON messages.student_id = students.id
          ORDER BY messages.created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>SimsChartHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .message-box {
            background: #f4f4f4;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
        }
        .message-box img {
            border-radius: 50%;
            width: 60px;
            height: 60px;
            margin-right: 15px;
            object-fit: cover;
        }
        .message-content {
            flex-grow: 1;
        }
        .message-content h4 {
            margin: 0;
            font-size: 18px;
        }
        .message-content p {
            margin: 5px 0 0;
        }
        .message-actions {
            margin-top: 5px;
        }
        .message-actions a {
            margin-right: 10px;
            color: blue;
            text-decoration: none;
        }
        .message-actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>SimsChartHub</h2>
    <ul>
        <li><a href="index.php">Dashboard</a></li>
        <li><a href="add_message.php">Post Message</a></li>
        <li><a href="profile.php?id=<?php echo $current_user; ?>">My Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
<div class="main">
    <h2>Recent Messages</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="message-box">
            <a href="profile.php?id=<?php echo $row['student_id']; ?>">
                <img src="uploads/<?php echo htmlspecialchars($row['profile_pic'] ?: 'default.png'); ?>" alt="Profile Picture">
            </a>
            <div class="message-c
                <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                <p><?php echo htmlspecialchars($row['content']); ?></p>
                <small><?php echo $row['created_at']; ?></small>

                <?php if ($row['student_id'] == $current_user): ?>
                    <div class="message-actions">
                        <a href="edit_message.php?id=<?php echo $row['message_id']; ?>">Edit</a>
                        <a href="delete_message.php?id=<?php echo $row['message_id']; ?>" onclick="return confirm('Delete this message?')">Delete</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>