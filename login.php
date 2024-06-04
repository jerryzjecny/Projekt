<?php
session_start(); // Start the session

require_once 'Connect.php'; // Include the file containing your DatabaseConnection class

$conn = DatabaseConnection::Connect();

// Funkce pro ověření přihlášení uživatele
function loginUser($username, $password) {
    global $conn;
    $sql = "SELECT * FROM Credentials WHERE Username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($row) {
        $hashedPassword = $row['Password'];
        if (hash('sha256', $password) === $hashedPassword) {
            // Přihlášení úspěšné
            $_SESSION['username'] = $username;
            return true;
        } else {
            // Neplatné heslo
            return false;
        }
    } else {
        // Uživatel nenalezen
        return false;
    }
}

// Funkce pro zaznamenání neúspěšného pokusu o přihlášení
function logFailedLoginAttempt($username) {
    $logfile = "login_attempts.log";
    $logdata = "[" . date("Y-m-d H:i:s") . "] Failed login attempt for username: $username\n";
    file_put_contents($logfile, $logdata, FILE_APPEND);
}

// Obsluha přihlášení
if(isset($_POST['login'])) {
    $username = $_POST['login_username']; // Update to 'login_username'
    $password = $_POST['login_password']; // Update to 'login_password'
    if(loginUser($username, $password)) {
        // Successful login redirects to Welcome.php
        header("Location: Welcome.php");
        exit(); // Ensure that code execution stops after redirection
    } else {
        echo "Přihlášení neúspěšné. Zkontrolujte své údaje.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="LRStyle.css">
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Přihlášení</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="login_username">Uživatelské jméno:</label>
                <input type="text" name="login_username" id="login_username" required>
            </div>
            <div class="form-group">
                <label for="login_password">Heslo:</label>
                <input type="password" name="login_password" id="login_password" required>
            </div>
            <input type="submit" name="login" value="Přihlásit">
            <a class="back-button" href="index.php">Zpět</a>
        </form>
    </div>
</div>
<footer class="site-footer">
    <?php include 'footer.html'; ?>
</footer>
</body>
</html>



