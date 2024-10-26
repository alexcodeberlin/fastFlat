<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Property Details</title>
<style>
    /* Your existing CSS styles here */
</style>
</head>
<body>

<div class="container">
<h3>Welcome, <?php session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
	
	
}
echo $_SESSION['username']; ?>
    <?php
    include 'connect.php'; // Include database connection file

    // Get PropertyID from URL query parameter
    if (isset($_GET['propertyID'])) {
        $propertyID = $_GET['propertyID'];
        
        // Prepare SQL query to select detailed information for the specified property
        $sql = "SELECT Properties.*, PropertyPhotos.PhotoURL 
                FROM Properties 
                LEFT JOIN PropertyPhotos ON Properties.PropertyID = PropertyPhotos.PropertyID 
                WHERE Properties.PropertyID = $propertyID";

        $result = $conn->query($sql);

        // Check if property was found
        if ($result->num_rows > 0) {
            // Output data of the property
            $row = $result->fetch_assoc();
			            echo "<h1>Property Details</h1>";

            echo "<h1>" . $row['Title'] . "</h1>";
            echo "<img src='" . $row['PhotoURL'] . "' alt='Property Image'>";
            echo "<p>Description: " . $row['Description'] . "</p>";
            echo "<p>Address: " . $row['Address'] . "</p>";
            echo "<p>City: " . $row['City'] . "</p>";
            echo "<p>City Description: " . $row['CityDescription'] . "</p>"; // Added City Description
            echo "<p>State: " . $row['State'] . "</p>"; // Added State
            echo "<p>ZipCode: " . $row['ZipCode'] . "</p>"; // Added ZipCode
            echo "<p>Price per Month: $" . $row['PriceForMonth'] . "</p>";
            echo "<p>Bedrooms: " . $row['Bedrooms'] . "</p>"; // Added Bedrooms
            echo "<p>Bathrooms: " . $row['Bathrooms'] . "</p>"; // Added Bathrooms
            echo "<p>Area: " . $row['Area'] . "</p>"; // Added Area
            echo "<p>Type: " . $row['Type'] . "</p>"; // Added Type
            echo "<p>Start Time: " . $row['StartTime'] . "</p>"; // Added StartTime
            echo "<p>End Time: " . $row['EndTime'] . "</p>"; // Added EndTime

            // Send message button and form
            echo "<button id='sendMessageButton'>Send Message</button>";
            echo "<div id='messageForm' style='display:none;'>";
            echo "<form id='messageSubmitForm' method='post'>";
            echo "<input type='text' id='messageInput' name='messageInput' placeholder='Enter your message' required>";
            echo "<button type='submit' name='sendMessage'>Submit</button>";
            echo "</form>";
            echo "</div>";

            // Handle sending message
            if(isset($_POST['sendMessage'])) {
                // Get SenderID from current session
                
                $senderUsername = $_SESSION['username'];
                $senderIDQuery = "SELECT UserID FROM Users WHERE Username = '$senderUsername'";
                $senderIDResult = $conn->query($senderIDQuery);
                if ($senderIDResult->num_rows > 0) {
                    $senderRow = $senderIDResult->fetch_assoc();
                    $senderID = $senderRow['UserID'];

                    // Get ReceiverID (AgentID)
                    $receiverID = $row['AgentID'];

                    // Get message text from form input
                    $messageText = $_POST['messageInput'];

                    // Insert message into Messages table
                    $insertMessageQuery = "INSERT INTO Messages (SenderID, ReceiverID, MessageText) 
                                           VALUES ('$senderID', '$receiverID', '$messageText')";
                    if ($conn->query($insertMessageQuery) === TRUE) {
                        echo "<script>alert('Message sent successfully.');</script>";
                    } else {
                        echo "<script>alert('Error sending message: " . $conn->error . "');</script>";
                    }
                }
            }

            // JavaScript to toggle message form visibility
            echo "<script>
                    document.getElementById('sendMessageButton').addEventListener('click', function() {
                        document.getElementById('messageForm').style.display = 'block';
                    });
                  </script>";
        } else {
            echo "Property not found.";
        }
    } else {
        echo "Invalid property ID.";
    }
    $conn->close();
    ?>
    
    <button class="back-button" onclick="goBack()">Go Back</button> <!-- Back button -->
</div>

<script>
    function goBack() {
        window.history.back(); // Go back to the previous page
    }
</script>

</body>
</html>
