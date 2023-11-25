<?php
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    die();
}
include "config.php";
$db = mysqli_connect($servername, $username, $password, $dbname);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (!isset($_GET['user'])) {
    die("Es wurde kein Nutzername übergeben.");
}

$user = $db->real_escape_string($_GET['user']);

$sql = "DELETE FROM users WHERE username='$user'";

if ($db->query($sql) === TRUE) {
    echo "Nutzer $user erfolgreich gelöscht.";
} else {
    echo "Fehler beim Löschen des Nutzers: " . $db->error;
}

$db->close();
?>
