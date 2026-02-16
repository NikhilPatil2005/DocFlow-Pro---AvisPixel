<?php
$host = '127.0.0.1';
$user = 'test';
$pass = 'NikPatil@2005';
$db = 'notice_system';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SHOW TABLES");
if ($result) {
    echo "Tables in $db:\n";
    while ($row = $result->fetch_array()) {
        echo "- " . $row[0] . "\n";
    }
}
else {
    echo "Error showing tables: " . $conn->error . "\n";
}
$conn->close();
?>
