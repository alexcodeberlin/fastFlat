<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Favorite Properties</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .property-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .property-container img {
        float: left;
        margin-right: 20px;
        border-radius: 10px;
    }

    .property-container h2 {
        color: #333;
        font-size: 20px;
        margin-top: 0;
        margin-bottom: 10px;
    }

    .property-container p {
        color: #666;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .property-container button {
        background-color: #dc3545; /* Red color for remove button */
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .property-container button:hover {
        background-color: #c82333; /* Darker red color on hover */
    }
</style>
</head>
<body>

<?php
include 'connect.php'; // Include database connection file

session_start(); // Start or resume session

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Get UserID based on the provided username
    $userIDQuery = "SELECT UserID FROM Users WHERE Username = '$username'";
    $userIDResult = $conn->query($userIDQuery);

    if ($userIDResult->num_rows > 0) {
        $row = $userIDResult->fetch_assoc();
        $userID = $row['UserID'];

        // Prepare SQL query to select favorite properties for the user
        $sql = "SELECT Properties.*, PropertyPhotos.PhotoURL 
                FROM Properties 
                LEFT JOIN PropertyPhotos ON Properties.PropertyID = PropertyPhotos.PropertyID 
                INNER JOIN UserFavorites ON Properties.PropertyID = UserFavorites.PropertyID 
                WHERE UserFavorites.UserID = $userID";

        $result = $conn->query($sql);

        // Check if favorite properties were found
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                // Display favorite property details
                echo "<div class='property-container'>";
                // Display property photo on the left side
                echo "<img src='" . $row['PhotoURL'] . "' alt='Property Image' width='100' height='100'>";
                echo "<h2>" . $row['Title'] . "</h2>";
                echo "<p>" . $row['Description'] . "</p>";
                echo "<p>Price per Month: $" . $row['PriceForMonth'] . "</p>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='propertyID' value='" . $row['PropertyID'] . "'>";
                echo "<button type='submit' name='remove_from_favorites'>Remove from Favorites</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "No favorite properties found.";
        }
    } else {
        echo "User not found.";
    }
} else {
    echo "Please log in to view favorite properties.";
}

// Check if remove from favorites button is clicked
if(isset($_POST['remove_from_favorites'])) {
    $propertyID = $_POST['propertyID'];
    // Remove the property from favorites
    $removeQuery = "DELETE FROM UserFavorites WHERE UserID = $userID AND PropertyID = $propertyID";
    if ($conn->query($removeQuery) === TRUE) {
        echo "Property removed from favorites successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

</body>
</html>
