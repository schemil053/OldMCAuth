<?php
include "config.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['user'])) {

    $user = $_POST['user'];
    $password = $_POST['password'];
    $version = $_POST['version'];

    if ($version != 13) {
        echo "Old version";
        exit;
    }

    $user = mysqli_real_escape_string($conn, $user);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM users WHERE username='$user' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        $row = $result->fetch_assoc();
        $uuid = $row['uuid'];

        $downloadticket = bin2hex(random_bytes(32));
        $sessionid = bin2hex(random_bytes(32));

        $time = time();

        $sql = "INSERT INTO sessions (user_id, session_id, timestamp, server_id, user_uuid) VALUES ('$user', '$sessionid', '$time', '0', '$uuid')";
        $conn->query($sql);

        echo "1:$downloadticket:$user:$sessionid:$uuid";

    } else {
        echo "Bad Login";
    }

} else {
    echo "Missing parameters";
}

$conn->close();
?>
