<?php
require_once 'Connect.php'; // Include the file containing your DatabaseConnection class

$conn = DatabaseConnection::Connect();

function registerUser($username, $password) {
    global $conn;
    $hashedPassword = hash('sha256', $password); // Hashování hesla pro bezpečné uložení
    $sql = "INSERT INTO Credentials (Username, Password) VALUES (?, ?)";
    $params = array($username, $hashedPassword);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    return $stmt;
}

// Inicializace SESSION
session_start();

// Obsluha registrace
if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = registerUser($username, $password);
    if($result) {
        echo "Registrace úspěšná!";
        header("Location: login.php");
    } else {
        echo "Registrace selhala.";
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
    <h2>Registrace</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="reg_username">Uživatelské jméno:</label>
            <input type="text" name="username" id="reg_username" required>
        </div>
        <div class="form-group">
            <label for="reg_password">Heslo:</label>
            <input type="password" name="password" id="reg_password" required>
        </div>
        <input type="submit" name="register" value="Registrovat">
        <a class="back-button" href="index.php">Zpět</a> <!-- Back button -->
    </form>
    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</div>
</body>
</html>
