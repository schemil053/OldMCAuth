<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
  header('HTTP/1.0 403 Forbidden');
  exit('Sie haben keine Berechtigung für diese Aktion.');
}


function generateUUID() {
    $randomBytes = random_bytes(16); // generate 16 random bytes
    $randomBytes[6] = chr(ord($randomBytes[6]) & 0x0f | 0x40);
    $randomBytes[8] = chr(ord($randomBytes[8]) & 0x3f | 0x80);
    $uuidString = implode('-', str_split(sprintf('%02x', ...$randomBytes), 4));
    return $uuidString;
}



function convertToJavaUUID($uuidString) {
    $uuidString = str_replace('-', '', $uuidString);

    $mostSignificantBits = substr($uuidString, 0, 16);
    $leastSignificantBits = substr($uuidString, 16);

    $mostSignificantBits = intval($mostSignificantBits, 16);
    $leastSignificantBits = intval($leastSignificantBits, 16);

    $packedBytes = pack('J', $mostSignificantBits) . pack('J', $leastSignificantBits);

    $hexString = bin2hex($packedBytes);

    $uuidString = substr($hexString, 0, 8) . '-' . substr($hexString, 8, 4) . '-' . substr($hexString, 12, 4) . '-' . substr($hexString, 16, 4) . '-' . substr($hexString, 20);

    return $uuidString;
}

function convertUUID($uuidString) {
    $uuidString = str_replace('-', '', $uuidString);

    $bytes = hex2bin($uuidString);

    $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));

    return $uuid;
}

function generateUUIDA() {
    $randomBytes = random_bytes(16);
    $randomBytes[6] = chr((ord($randomBytes[6]) & 0x0f) | 0x40);
    $randomBytes[8] = chr((ord($randomBytes[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($randomBytes), 4));
}

function generate_uuid() {
  $data = openssl_random_pseudo_bytes(16);
  $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
  $hex = bin2hex($data);
  return sprintf('%s-%s-%s-%s-%s', substr($hex, 0, 8), substr($hex, 8, 4), substr($hex, 12, 4), substr($hex, 16, 4), substr($hex, 20, 12));
}




$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['createUser'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $uuid = generate_uuid();

  $checkUser = mysqli_prepare($conn, "SELECT id FROM users WHERE username=?");
  mysqli_stmt_bind_param($checkUser, "s", $username);
  mysqli_stmt_execute($checkUser);
  mysqli_stmt_store_result($checkUser);

  if (mysqli_stmt_num_rows($checkUser) > 0) {
    echo "Dieser Benutzername ist bereits vergeben.";
    exit();
  }

  $addUser = mysqli_prepare($conn, "INSERT INTO users (username, password, uuid) VALUES (?, ?, ?)");
  mysqli_stmt_bind_param($addUser, "sss", $username, $password, $uuid);
  mysqli_stmt_execute($addUser);

  echo "Benutzer erfolgreich erstellt.";
  exit();
}

?>
<html>
  <head>
    <title>Benutzerverwaltung</title>
  </head>
  <body>
    <h1>Benutzerverwaltung</h1>
    <h2>Neuen Benutzer erstellen:</h2>
    <form method="post">
      <label for="username">Benutzername:</label>
      <input type="text" id="username" name="username" required><br>
      <label for="password">Passwort:</label>
      <input type="password" id="password" name="password" required><br>
      <input type="submit" name="createUser" value="Benutzer erstellen">
    </form>
    <h2>Bereits erstellte Benutzer:</h2>
    <ul>
      <?php
      $usersQuery = "SELECT id, username FROM users";
      $usersResult = mysqli_query($conn, $usersQuery);
      while ($user = mysqli_fetch_assoc($usersResult)) {
        echo "<li>" . $user['username'] . " <a href='deleteUser.php?user=" . $user['username'] . "'>Löschen</a></li>";
      }
      ?>
    </ul>
  </body>
</html>
