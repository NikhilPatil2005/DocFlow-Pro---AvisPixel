<?php
$host = '127.0.0.1';
$rootUser = 'root';
$rootPass = 'NikPatil@2005';

$newUser = 'test';
$newPass = 'NikPatil@2005';
$dbName = 'notice_system';

echo "Connecting as root...\n";
$conn = new mysqli($host, $rootUser, $rootPass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Database
echo "Creating database if not exists...\n";
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
}
else {
    echo "Error creating database: " . $conn->error . "\n";
}

// Create User
echo "Creating user '$newUser'...\n";
$sql = "CREATE USER IF NOT EXISTS '$newUser'@'localhost' IDENTIFIED BY '$newPass'";
if ($conn->query($sql) === TRUE) {
    echo "User created successfully\n";
}
else {
    // If user exists, update password to ensure it matches
    $sql = "ALTER USER '$newUser'@'localhost' IDENTIFIED BY '$newPass'";
    if ($conn->query($sql) === TRUE) {
        echo "User password updated successfully\n";
    }
    else {
        echo "Error creating/updating user: " . $conn->error . "\n";
    }
}

// Grant Privileges
echo "Granting privileges...\n";
$sql = "GRANT ALL PRIVILEGES ON $dbName.* TO '$newUser'@'localhost'";
if ($conn->query($sql) === TRUE) {
    echo "Privileges granted successfully\n";
}
else {
    echo "Error granting privileges: " . $conn->error . "\n";
}

$conn->select_db($dbName);

// Import setup.sql
echo "Importing setup.sql...\n";
$sqlFile = 'setup.sql';
if (file_exists($sqlFile)) {
    $sqlContent = file_get_contents($sqlFile);
    if ($conn->multi_query($sqlContent)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        echo "Schema imported successfully\n";
    }
    else {
        echo "Error importing schema: " . $conn->error . "\n";
    }
}
else {
    echo "setup.sql not found!\n";
}

$conn->close();
?>
