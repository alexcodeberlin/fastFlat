<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Search</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f0f0f0;
        }
        .container {
            margin-top: 50px;
        }
        .property-container {
            display: flex;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .property-container img {
            border-radius: 10px;
            margin-right: 20px;
        }
        .property-details {
            flex: 1;
        }
        .btn-add-to-favorites {
            background-color: #ffc107;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .btn-add-to-favorites:hover {
            background-color: #ff9800;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        include 'connect.php'; // Include database connection file
        session_start(); // Start or resume session

        // Function to add property to favorites
        function addToFavorites($propertyID) {
            global $conn;
            // Check if the user is logged in
            if(isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                // Get UserID based on the provided username
                $userIDQuery = "SELECT UserID FROM Users WHERE Username = '$username'";
                $userIDResult = $conn->query($userIDQuery);
                if ($userIDResult->num_rows > 0) {
                    $row = $userIDResult->fetch_assoc();
                    $userID = $row['UserID'];
                    // Check if the property is already in favorites
                    $checkQuery = "SELECT * FROM UserFavorites WHERE UserID = $userID AND PropertyID = $propertyID";
                    $checkResult = $conn->query($checkQuery);
                    if ($checkResult->num_rows == 0) {
                        // Insert the property into favorites
                        $insertQuery = "INSERT INTO UserFavorites (UserID, PropertyID) VALUES ($userID, $propertyID)";
                        if ($conn->query($insertQuery) === TRUE) {
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

        // Get city and property type from the form submission
        $city = $_GET['city'];
        $propertyType = $_GET['property-type'];
		$movinDate = $_GET['property-dates'];
		$latestMoveinDate = $_GET['property-dates-latest'];

        // Prepare SQL query to select properties based on city and property type
        $sql = "SELECT Properties.*, PropertyPhotos.PhotoURL 
                FROM Properties 
                LEFT JOIN PropertyPhotos ON Properties.PropertyID = PropertyPhotos.PropertyID 
                WHERE Properties.City = '$city' AND Properties.Type = '$propertyType'
				AND '$movinDate' BETWEEN Properties.StartTime AND Properties.EndTime
				AND '$latestMoveinDate' BETWEEN Properties.StartTime AND Properties.EndTime
                GROUP BY Properties.StartTime";

        $result = $conn->query($sql);

        // Check if properties were found
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                // Display property details
                echo "<div class='property-container'>";
                // Display property photo on the left side
                echo "<img src='/website/images/firstimage.jpg' alt='Property Image' width='200px' height='200px'>";
                // Property details on the right side
                echo "<div class='property-details'>";
                echo "<h2>" . $row['Title'] . "</h2>";
                echo "<p>" . $row['Description'] . "</p>";
                echo "<p>Price per Month: $" . $row['PriceForMonth'] . "</p>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='propertyID' value='" . $row['PropertyID'] . "'>";
                if(isset($_SESSION['username'])) {
                    echo "<button type='submit' name='add_to_favorites' class='btn-add-to-favorites'>Add to Favorites</button>";
                } else {
                    echo "<span>Please log in to add properties to favorites.</span>";
                }
                echo "</form>";
                echo "<a href='property_details.php?propertyID=" . $row['PropertyID'] . "'>View Details</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No properties found.</p>";
        }

        // Check if add to favorites button is clicked
        if(isset($_POST['add_to_favorites'])) {
            $propertyID = $_POST['propertyID'];
            echo addToFavorites($propertyID);
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
