<?php

$host = "sql109.hstn.me";
$user = "mseet_39061377";
$pass = "charthub19"; // Replace with your actual cPanel password
$dbname = "mseet_39061377_simscharthub";
$conn = new mysqli("$host", "$user", "$pass", "$dbname");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>