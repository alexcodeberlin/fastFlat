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
        include 'connect.php'; // Include database connection file
        session_start(); // Start or resume session for handling user data

        // Function to add property to user's list of favorites
        function addToFavorites($propertyID) {
            global $conn;

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];

                // Use prepared statement to retrieve user ID
                $userIDQuery = $conn->prepare("SELECT UserID FROM Users WHERE Username = ?");
                $userIDQuery->bind_param("s", $username);
                $userIDQuery->execute();
                $userIDResult = $userIDQuery->get_result();

                if ($userIDResult->num_rows > 0) {
                    $row = $userIDResult->fetch_assoc();
                    $userID = $row['UserID'];

                    // Use prepared statement to check if the property is already in favorites
                    $checkQuery = $conn->prepare("SELECT * FROM UserFavorites WHERE UserID = ? AND PropertyID = ?");
                    $checkQuery->bind_param("ii", $userID, $propertyID);
                    $checkQuery->execute();
                    $checkResult = $checkQuery->get_result();

                    if ($checkResult->num_rows == 0) {
                        // Use prepared statement to insert property into favorites
                        $insertQuery = $conn->prepare("INSERT INTO UserFavorites (UserID, PropertyID) VALUES (?, ?)");
                        $insertQuery->bind_param("ii", $userID, $propertyID);
                        if ($insertQuery->execute()) {
                            return "Property added to favorites successfully.";
                        } else {
                            return "Error: " . $conn->error;
                        }
                    } else {
                        return "Property is already in favorites.";
                    }
                } else {
                    return "User not found.";
                }
            } else {
                return "Please log in to add properties to favorites.";
            }
        }

        // Retrieve search parameters securely
        $city = isset($_GET['city']) ? $_GET['city'] : null;
        $propertyType = isset($_GET['property-type']) ? $_GET['property-type'] : null;
        $movinDate = isset($_GET['property-dates']) ? $_GET['property-dates'] : null;
        $latestMoveinDate = isset($_GET['property-dates-latest']) ? $_GET['property-dates-latest'] : null;

        if ($city && $propertyType && $movinDate && $latestMoveinDate) {
            // Use prepared statement for the property search query
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

            if ($result->num_rows > 0) {
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
            } else {
                echo "<p>No properties found.</p>";
            }
        } else {
            echo "<p>Please provide valid search criteria (city, property type, and move-in dates).</p>";
        }

        if (isset($_POST['add_to_favorites'])) {
            $propertyID = (int)$_POST['propertyID']; // Cast to integer for added security
            echo addToFavorites($propertyID);
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
