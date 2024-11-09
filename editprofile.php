<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include 'connect.php';

// Fetch user's profile information from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM Users WHERE Username='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Update user's information if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $phone = $_POST['phone'];

    // Prepare and execute the SQL query to update user's information
    $sqlUpdate = "UPDATE Users SET FirstName='$firstName', LastName='$lastName', Phone='$phone' WHERE Username='$username'";
    if ($conn->query($sqlUpdate) === TRUE) {
        // Update successful
        echo "Profile updated successfully";
        
        // Fetch updated information
        $sql = "SELECT * FROM Users WHERE Username='$username'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    } else {
        // Error updating profile
        echo "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/style_editprofile.css">

</head>
<body>
<!-- form for typing in editing information -->
    <div class="container">
        <h3>Welcome, <?php echo $_SESSION['username']; ?></h3>
        <h2>Edit Profile</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="<?php echo $row['Username']; ?>" disabled><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo $row['Email']; ?>" disabled><br><br>

            <label for="firstName">First Name:</label><br>
            <input type="text" id="firstName" name="firstName" value="<?php echo $row['FirstName']; ?>" required><br><br>

            <label for="lastName">Last Name:</label><br>
            <input type="text" id="lastName" name="lastName" value="<?php echo $row['LastName']; ?>" required><br><br>

            <label for="phone">Phone:</label><br>
            <input type="text" id="phone" name="phone" value="<?php echo $row['Phone']; ?>"><br><br>

            <input type="submit" value="Update">
        </form>
        <br>
        <a href="index.php">Back to Home</a>
    </div>
</body>
</html>
