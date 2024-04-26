<?php
// Assuming you have already connected to your database

// Check if the username is passed through GET
if(isset($_GET['username'])) {
    // Sanitize the input to prevent SQL injection
    $username = mysqli_real_escape_string($connection, $_GET['username']);

    // Query to select user details based on username
    $query = "SELECT RenterID, Description, PreferedCity FROM Request WHERE UserID IN (SELECT UserID FROM Users WHERE Username = '$username')";
    $result = mysqli_query($connection, $query);

    // Check if the query was successful
    if($result) {
        // Fetch user details
        $user = mysqli_fetch_assoc($result);
        // Free result set
        mysqli_free_result($result);
    } else {
        echo "Error: " . mysqli_error($connection);
    }
} else {
    echo "Username not provided.";
}

// Close database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .user-details {
            margin-bottom: 20px;
        }
        .user-details p {
            margin-bottom: 10px;
        }
        .btn-back {
            display: block;
            width: 100%;
            text-align: center;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            text-decoration: none;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Details</h1>
        <div class="user-details">
            <p><strong>User ID:</strong> <?php echo $user['RenterID']; ?></p>
            <p><strong>Description:</strong> <?php echo $user['Description']; ?></p>
            <p><strong>Preferred City:</strong> <?php echo $user['PreferedCity']; ?></p>
        </div>
        <a href="javascript:history.back()" class="btn-back">Back</a>
    </div>
</body>
</html>
