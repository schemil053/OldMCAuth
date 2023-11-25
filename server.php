<?php
include "config.php";

$conn = new mysqli($servername, $username, $password, $dbname);

include "clean.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function checkSession($user, $sessionid) {
    global $conn;
    $user = mysqli_real_escape_string($conn, $user);
    $sessionid = mysqli_real_escape_string($conn, $sessionid);
    $sql = "SELECT * FROM sessions WHERE user_id = '$user' AND session_id = '$sessionid'";
    $result = $conn->query($sql);
    if(!$result) {
       return false;
    }

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $time = $row["timestamp"];
        if (time() - $time < 900) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

$action = mysqli_real_escape_string($conn, $_GET["action"]);
$user = mysqli_real_escape_string($conn, $_GET["user"]);
$sessionid = mysqli_real_escape_string($conn, $_GET["sessionid"]);
$serverid = mysqli_real_escape_string($conn, $_GET["serverid"]);
$uuid = mysqli_real_escape_string($conn, $_GET["uuid"]);

if ($action == "join") {
    if (checkSession($user, $sessionid)) {
        $sql = "UPDATE sessions SET server_id = '$serverid' WHERE user_id = '$user' AND session_id = '$sessionid'";
        if ($conn->query($sql) === TRUE) {
            echo "ok";
        } else {
            echo "An internal error occured. Please try again later.";
        }
    } else {
        echo "Invalid Session. Try restarting your game.";
    }
} elseif ($action == "check") {
    $sql = "SELECT * FROM sessions WHERE user_id = '$user' AND server_id = '$serverid' AND user_uuid = '$uuid'";
    $result = $conn->query($sql);


    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $time = $row["timestamp"];
        if (time() - $time < 900) {
            echo "YES";
        } else {
            echo "NO";
        }
    } else {
        echo "NO";
    }
}

$conn->close();
?>
