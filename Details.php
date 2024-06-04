<?php
session_start();

// Include the database connection class
require_once 'Connect.php';

// Check if recipe ID is set
if (!isset($_POST['recipe_id'])) {
    header("Location: index.php"); // Redirect back if no recipe ID is provided
    exit();
}

// Fetch recipe details and ingredients from the database based on the recipe ID
$conn = DatabaseConnection::connect();
$recipe_id = $_POST['recipe_id'];
$sql = "SELECT Meal.Name AS RecipeName, Meal.Recipe AS Recipe, Ingredient.IngredientName 
        FROM Meal 
        INNER JOIN Ingredient ON Meal.ID = Ingredient.MealID 
        WHERE Meal.ID = ?";
$params = array($recipe_id);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt !== false) {
    $recipe_details = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $recipe_details['RecipeName'] = $row['RecipeName'];
        $recipe_details['Recipe'] = $row['Recipe'];
        $recipe_details['Ingredients'][] = $row['IngredientName'];
    }
} else {
    die("Failed to fetch recipe details: " . print_r(sqlsrv_errors(), true));
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <link rel="stylesheet" href="DetailsStyle.css">
</head>
<body>
    <div class="container">
        <h1>Recipe Details</h1>
        <?php if (isset($recipe_details['RecipeName']) && isset($recipe_details['Ingredients'])): ?>
            <h2><?php echo $recipe_details['RecipeName']; ?></h2>
            <h3>Ingredients:</h3>
            <ul>
                <?php foreach ($recipe_details['Ingredients'] as $ingredient): ?>
                    <li><?php echo $ingredient; ?></li>
                <?php endforeach; ?>
            </ul>
            <h3>Recipe:</h3>
            <p><?php echo $recipe_details['Recipe']; ?></p>
        <?php else: ?>
            <p>Recipe not found!</p>
        <?php endif; ?>
        <a href="Welcome.php">Back to Recipes</a>
    </div>
    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</body>
</html>
