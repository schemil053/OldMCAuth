<?php
session_start();

$valid_username = "admin";
$valid_password = "password";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $input_username = trim($_POST["username"]);
    $input_password = trim($_POST["password"]);

    if ($input_username == $valid_username && $input_password == $valid_password) {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $valid_username;

        $_SESSION["admin"] = true;
        $_SESSION["admin"] = $valid_username;

        header("location: admin.php");
    } else {
        $login_err = "Benutzername oder Passwort ungültig.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>

    <?php
    if (isset($login_err)) {
        echo "<div style='color:red'>" . $login_err . "</div>";
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Benutzername:</label><br>
        <input type="text" name="username" required><br>
        <label>Passwort:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

</body>

</html>
