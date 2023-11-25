<?php
include "config.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['action'])) {

    if (isset($_GET['uuid']) && !empty($_GET['uuid'])) {
        $uuid = $conn->real_escape_string($_GET['uuid']);
    }
    if (isset($_GET['username']) && !empty($_GET['username'])) {
        $username = $conn->real_escape_string($_GET['username']);
    }

    if ($_GET['action'] == 'name') {

        $stmt = $conn->prepare("SELECT username FROM users WHERE uuid = ?");
        $stmt->bind_param("s", $uuid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "" . $row["username"];
        } else {
            echo "?";
        }

    } elseif ($_GET['action'] == 'uuid') {

        $stmt = $conn->prepare("SELECT uuid FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "" . $row["uuid"];
        } else {
            echo "?";
        }

    } else {
        echo "Ungültige Aktion.";
    }

    $conn->close();

} else {
    echo "Keine Aktion angegeben.";
}

?>
