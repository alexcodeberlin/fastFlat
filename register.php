<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Include database connection file
        include 'connect.php';

        // Retrieve form data
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phone = $_POST['phone'];

        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL query to insert data into the Users table
        $sql = "INSERT INTO Users (Username, PasswordHash, Email, FirstName, LastName, Phone) 
                VALUES ('$username', '$passwordHash', '$email', '$firstName', '$lastName', '$phone')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the database connection
        $conn->close();
    }
    ?>

    <h2>Register</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="firstName">First Name:</label><br>
        <input type="text" id="firstName" name="firstName"><br><br>

        <label for="lastName">Last Name:</label><br>
        <input type="text" id="lastName" name="lastName"><br><br>

        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone"><br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>