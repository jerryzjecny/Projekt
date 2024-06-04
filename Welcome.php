<?php
session_start();

// Include the database connection class
require_once 'Connect.php';

// Function to fetch public recipes from the database
function getPublicRecipes() {
    $conn = DatabaseConnection::connect();

    $sql = "SELECT * FROM Meal WHERE IsPublic = 1";
    $stmt = sqlsrv_query($conn, $sql);
    
    $recipes = array();

    if ($stmt !== false) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $recipes[] = $row;
        }
    } else {
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    return $recipes;
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Logout logic
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to index page after logout
    exit();
}

// Fetch public recipes
$publicRecipes = getPublicRecipes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="WelcomeStyle.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>

        <h2>Public Recipes:</h2>
        <ul>
            <?php foreach ($publicRecipes as $recipe): ?>
                <li>
                    <strong><?php echo $recipe['Name']; ?></strong><br>
                    <?php echo $recipe['Recipe']; ?>
                    <form action="Details.php" method="post">
                        <input type="hidden" name="recipe_id" value="<?php echo $recipe['ID']; ?>">
                        <button type="submit">View Details</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <form action="" method="post">
            <input type="hidden" name="logout" value="1">
            <button type="submit">Logout</button>
        </form>
    </div>
    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
