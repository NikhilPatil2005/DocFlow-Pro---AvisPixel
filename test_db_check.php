<?php
$user = 'test';
$pass = 'NikPatil@2005';
$host = '127.0.0.1';

echo "Attempting: Host=$host, User=$user, Pass=" . substr($pass, 0, 3) . "...\n";

try {
    $conn = new mysqli($host, $user, $pass);
    echo "SUCCESS\n";
    $conn->close();
}
catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}

$user = 'root';
echo "Attempting: Host=$host, User=$user, Pass=" . substr($pass, 0, 3) . "...\n";
try {
    $conn = new mysqli($host, $user, $pass);
    echo "SUCCESS\n";
    $conn->close();
}
catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}
?>
