<?php
include "config.php";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    uuid VARCHAR(255) NOT NULL
)";

if (mysqli_query($conn, $sql)) {
    echo "Table 'users' created successfully<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS sessions (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    user_uuid VARCHAR(255) NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    server_id INT(11) NOT NULL,
    timestamp VARCHAR(20) NOT NULL
)";

if (mysqli_query($conn, $sql)) {
    echo "Table 'sessions' created successfully<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

if(!rename("setup.php", "setup.php_")) {
    echo "Please remove setup.php";
}

mysqli_close($conn);
?>
