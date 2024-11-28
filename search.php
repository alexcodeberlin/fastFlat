<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" author="Alexander Martens">
    <title>Property Search</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS FILE -->
    <link rel="stylesheet" href="css/style_search.css">
</head>
<body>
    <div class="container">
        <?php
        include 'connect.php';
        session_start();

        // Function to add property to user's list of favorites
        function addToFavorites($propertyID) {
            global $conn;

            if (!isset($_SESSION['username'])) {
                return "Please log in to add properties to favorites.";
            }

            $username = $_SESSION['username'];

            // Retrieve user ID for the logged-in user
            $userIDQuery = $conn->prepare("SELECT UserID FROM Users WHERE Username = ?");
            $userIDQuery->bind_param("s", $username);
            $userIDQuery->execute();
            $userIDResult = $userIDQuery->get_result();

            if ($userIDResult->num_rows === 0) {
                return "User not found.";
            }

            $userID = $userIDResult->fetch_assoc()['UserID'];

            // Check if the property is already in favorites
            $checkQuery = $conn->prepare("SELECT * FROM UserFavorites WHERE UserID = ? AND PropertyID = ?");
            $checkQuery->bind_param("ii", $userID, $propertyID);
            $checkQuery->execute();

            if ($checkQuery->get_result()->num_rows > 0) {
                return "Property is already in favorites.";
            }

            // Add the property to favorites
            $insertQuery = $conn->prepare("INSERT INTO UserFavorites (UserID, PropertyID) VALUES (?, ?)");
            $insertQuery->bind_param("ii", $userID, $propertyID);

            if (!$insertQuery->execute()) {
                return "Error: " . $conn->error;
            }

            return "Property added to favorites successfully.";
        }

        // Retrieve search parameters securely
        $city = $_GET['city'] ?? null;
        $propertyType = $_GET['property-type'] ?? null;
        $movinDate = $_GET['property-dates'] ?? null;
        $latestMoveinDate = $_GET['property-dates-latest'] ?? null;

        if (!$city || !$propertyType || !$movinDate || !$latestMoveinDate) {
            echo "<p>Please provide valid search criteria (city, property type, and move-in dates).</p>";
            $conn->close();
            exit;
        }

        // Execute the property search query
        $sql = $conn->prepare("
            SELECT Properties.*, PropertyPhotos.PhotoURL
            FROM Properties
            LEFT JOIN PropertyPhotos ON Properties.PropertyID = PropertyPhotos.PropertyID
            WHERE Properties.City = ? AND Properties.Type = ?
            AND ? BETWEEN Properties.StartTime AND Properties.EndTime
            AND ? BETWEEN Properties.StartTime AND Properties.EndTime
            GROUP BY Properties.StartTime
        ");
        $sql->bind_param("ssss", $city, $propertyType, $movinDate, $latestMoveinDate);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows === 0) {
            echo "<p>No properties found.</p>";
            $conn->close();
            exit;
        }

        echo "<p>Found properties</p>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='property-container'>";
            echo "<img src='/website/images/firstimage.jpg' alt='Property Image' width='200px' height='200px'>";
            echo "<div class='property-details'>";
            echo "<h2>" . htmlspecialchars($row['Title']) . "</h2>";
            echo "<p>" . htmlspecialchars($row['Description']) . "</p>";
            echo "<p>Price per Month: $" . htmlspecialchars($row['PriceForMonth']) . "</p>";
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='propertyID' value='" . htmlspecialchars($row['PropertyID']) . "'>";
            if (isset($_SESSION['username'])) {
                echo "<button type='submit' name='add_to_favorites' class='btn-add-to-favorites'>Add to Favorites</button>";
            } else {
                echo "<span>Please log in to add properties to favorites.</span>";
            }
            echo "</form>";
            echo "<a href='property_details.php?propertyID=" . htmlspecialchars($row['PropertyID']) . "'>View Details</a>";
            echo "</div>";
            echo "</div>";
        }

        // Handle add-to-favorites action
        if (isset($_POST['add_to_favorites'])) {
            $propertyID = (int)$_POST['propertyID']; // Cast to integer for added security
            echo addToFavorites($propertyID);
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
