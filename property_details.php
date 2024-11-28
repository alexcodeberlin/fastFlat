<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Properties</title>
    <link rel="stylesheet" href="css/style_favourite.css">
</head>
<body>
<?php
include 'connect.php'; // Include database connection file
session_start(); // Start or resume session

// Verify user session
if (!isset($_SESSION['username'])) {
    echo "Please log in to view favorite properties.";
    exit();
}

$username = $_SESSION['username'];

// Retrieve UserID for the logged-in user
$userIDQuery = "SELECT UserID FROM Users WHERE Username = '$username'";
$userIDResult = $conn->query($userIDQuery);

if ($userIDResult->num_rows === 0) {
    echo "User not found.";
    exit();
}

$userID = $userIDResult->fetch_assoc()['UserID'];

// Retrieve user's favorite properties
$sql = "
    SELECT Properties.*, PropertyPhotos.PhotoURL 
    FROM Properties 
    LEFT JOIN PropertyPhotos ON Properties.PropertyID = PropertyPhotos.PropertyID 
    INNER JOIN UserFavorites ON Properties.PropertyID = UserFavorites.PropertyID 
    WHERE UserFavorites.UserID = $userID
";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "No favorite properties found.";
} else {
    // Display favorite properties
    while ($row = $result->fetch_assoc()) {
        echo "<div class='property-container'>";
        echo "<img src='" . htmlspecialchars($row['PhotoURL']) . "' alt='Property Image' width='100' height='100'>";
        echo "<h2>" . htmlspecialchars($row['Title']) . "</h2>";
        echo "<p>" . htmlspecialchars($row['Description']) . "</p>";
        echo "<p>Price per Month: $" . htmlspecialchars($row['PriceForMonth']) . "</p>";
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='propertyID' value='" . htmlspecialchars($row['PropertyID']) . "'>";
        echo "<button type='submit' name='remove_from_favorites'>Remove from Favorites</button>";
        echo "</form>";
        echo "</div>";
    }
}

// Handle property removal from favorites
if (isset($_POST['remove_from_favorites'])) {
    $propertyID = (int)$_POST['propertyID'];
    $removeQuery = "DELETE FROM UserFavorites WHERE UserID = $userID AND PropertyID = $propertyID";
    
    if ($conn->query($removeQuery)) {
        echo "Property removed from favorites successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
</body>
</html>
