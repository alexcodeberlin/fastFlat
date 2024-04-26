<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Property</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        max-width: 600px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
    }

    input, select {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>


<div class="container">
    <h2>Add Property</h2> <h3>Welcome, <?php session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
	
	
}
echo $_SESSION['username']; ?>, please add a property</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
        </div>
        <div class="form-group">
            <label for="state">State:</label>
            <input type="text" id="state" name="state">
        </div>
        <div class="form-group">
            <label for="zipCode">Zip Code:</label>
            <input type="text" id="zipCode" name="zipCode" required>
        </div>
        <div class="form-group">
            <label for="price">Price per Month:</label>
            <input type="text" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="bedrooms">Bedrooms:</label>
            <input type="number" id="bedrooms" name="bedrooms" required>
        </div>
        <div class="form-group">
            <label for="bathrooms">Bathrooms:</label>
            <input type="number" id="bathrooms" name="bathrooms" required>
        </div>
        <div class="form-group">
            <label for="area">Area:</label>
            <input type="text" id="area" name="area" required>
        </div>
        <div class="form-group">
            <label for="type">Type:</label>
            <select id="type" name="type" required>
                <option value="Flat">Flat</option>
                <option value="OneRoomFlat">One Room Flat</option>
                <option value="SharedFlat">Shared Flat</option>
                <option value="House">House</option>
            </select>
        </div>
        <div class="form-group">
            <label for="startTime">Start Time:</label>
            <input type="datetime-local" id="startTime" name="startTime" required>
        </div>
        <div class="form-group">
            <label for="endTime">End Time:</label>
            <input type="datetime-local" id="endTime" name="endTime" required>
        </div>
        <button type="submit" name="add_property">Add Property</button>
    </form>
</div>

<?php
include 'connect.php'; // Include database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_property'])) {
    // Retrieve form data
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

    // Get AgentID from the currently logged-in user
    
        $username = $_SESSION['username'];
        // Retrieve AgentID based on the provided username
        $agentIDQuery = "SELECT UserID FROM Users WHERE Username = '$username'";
        $agentIDResult = $conn->query($agentIDQuery);
        if ($agentIDResult->num_rows > 0) {
            $row = $agentIDResult->fetch_assoc();
            $agentID = $row['UserID'];

            // SQL insert statement
            $sql = "INSERT INTO Properties (Title, Description, Address, City, State, ZipCode, PriceForMonth, Bedrooms, Bathrooms, Area, Type, AgentID, StartTime, EndTime) 
                    VALUES ('$title', '$description', '$address', '$city', '$state', '$zipCode', '$price', '$bedrooms', '$bathrooms', '$area', '$type', '$agentID', '$startTime', '$endTime')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Property added successfully');</script>";
            } else {
                echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Agent not found.');</script>";
        }
    

    $conn->close();
}
?>
</body>
</html>
