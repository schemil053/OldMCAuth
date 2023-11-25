<?php
include "config.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if (!$conn || !isset($conn)) {
    die();
}

$max_session_duration = 900;

$current_time = time();

$sql = "SELECT * FROM sessions WHERE timestamp < " . ($current_time - $max_session_duration);

$result = mysqli_query($conn, $sql);
if (!$result) {
    return;
}


while ($row = mysqli_fetch_assoc($result)) {
    $delete_sql = "DELETE FROM sessions WHERE id=" . $row["id"];

    mysqli_query($conn, $delete_sql);
}
?>
