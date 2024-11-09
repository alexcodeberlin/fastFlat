<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <link rel="stylesheet" href="css/style_property.css">
</head>
<body>

<div class="container">
    <h2>Add Property</h2> 
    <h3>Welcome, <?php 
    session_start();

    // Start session and check if user is logged in; if not, redirect to login page
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
    // Display the username of the logged-in user
    echo $_SESSION['username']; ?>, please add a property</h3>
    
    <!-- Form for adding property details, using POST -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
        <!-- Field for entering property title -->
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <!-- Field for entering property description -->
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>
        
        <!-- Field for entering property address -->
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
        </div>
        
        <!-- Field for entering the city -->
        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
        </div>
        
        <!-- Field for entering the state -->
        <div class="form-group">
            <label for="state">State:</label>
            <input type="text" id="state" name="state">
        </div>
        
        <!-- Field for entering zip code -->
        <div class="form-group">
            <label for="zipCode">Zip Code:</label>
            <input type="text" id="zipCode" name="zipCode" required>
        </div>
        
        <!-- Field for entering monthly price -->
        <div class="form-group">
            <label for="price">Price per Month:</label>
            <input type="text" id="price" name="price" required>
        </div>
        
        <!-- Field for specifying the number of bedrooms -->
        <div class="form-group">
            <label for="bedrooms">Bedrooms:</label>
            <input type="number" id="bedrooms" name="bedrooms" required>
        </div>
        
        <!-- Field for specifying the number of bathrooms -->
        <div class="form-group">
            <label for="bathrooms">Bathrooms:</label>
            <input type="number" id="bathrooms" name="bathrooms" required>
        </div>
        
        <!-- Field for entering property area in square units -->
        <div class="form-group">
            <label for="area">Area:</label>
            <input type="text" id="area" name="area" required>
        </div>
        
        <!-- Dropdown for selecting the type of property -->
        <div class="form-group">
            <label for="type">Type:</label>
            <select id="type" name="type" required>
                <option value="Flat">Flat</option>
                <option value="OneRoomFlat">One Room Flat</option>
                <option value="SharedFlat">Shared Flat</option>
                <option value="House">House</option>
            </select>
        </div>
        
        <!-- Field for entering the start time of availability -->
        <div class="form-group">
            <label for="startTime">Start Time:</label>
            <input type="datetime-local" id="startTime" name="startTime" required>
        </div>
        
        <!-- Field for entering the end time of availability -->
        <div class="form-group">
            <label for="endTime">End Time:</label>
            <input type="datetime-local" id="endTime" name="endTime" required>
        </div>
        
        <!-- Submit button to add the property -->
        <button type="submit" name="add_property">Add Property</button>
    </form>
</div>

<?php
// Include the file with database connection details
include 'connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_property'])) {
    // Retrieve form data and store in variables
    $title = $_POST['title'];
    $description = $_POST['description'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipCode = $_POST['zipCode'];
    $price = $_POST['price'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $area = $_POST['area'];
    $type = $_POST['type'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    // Retrieve the AgentID for the currently logged-in user based on their username
    $username = $_SESSION['username'];
    $agentIDQuery = "SELECT UserID FROM Users WHERE Username = '$username'";
    $agentIDResult = $conn->query($agentIDQuery);

    if ($agentIDResult->num_rows > 0) {
        // Fetch the UserID of the logged-in agent
        $row = $agentIDResult->fetch_assoc();
        $agentID = $row['UserID'];

        // Insert the property data into the Properties table
        $sql = "INSERT INTO Properties (Title, Description, Address, City, State, ZipCode, PriceForMonth, Bedrooms, Bathrooms, Area, Type, AgentID, StartTime, EndTime) 
                VALUES ('$title', '$description', '$address', '$city', '$state', '$zipCode', '$price', '$bedrooms', '$bathrooms', '$area', '$type', '$agentID', '$startTime', '$endTime')";

        if ($conn->query($sql) === TRUE) {
            // Display a success alert if the property is added successfully
            echo "<script>alert('Property added successfully');</script>";
        } else {
            // Display an error alert if the insertion fails
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    } else {
        // Display an alert if the agent is not found
        echo "<script>alert('Agent not found.');</script>";
    }

    // Close the database connection
    $conn->close();
}
?>
</body>
</html>
