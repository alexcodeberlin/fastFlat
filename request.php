<?php
// Establish a connection to the database (Replace these variables with your actual database credentials)
include 'connect.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the city entered in index.php
$city = $_GET['city'];

// Prepare SQL query to select requests based on the city
$sql = "SELECT UserID, Description, PreferedCity FROM Request WHERE PreferedCity = ?";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("s", $city);

// Execute statement
$stmt->execute();

// Get result
$result = $stmt->get_result();

// Close statement
$stmt->close();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
        }
        .container {
            margin-top: 50px;
			
        }
        .card {
            margin-bottom: 20px;
            cursor: pointer;
			
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-body {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card-title {
            font-size: 20px;
            color: #333;
            font-weight: bold;
        }
        .card-text {
            color: #666;
        }.row{
			
		}
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center mb-4">Requests</h1>
    <div class="row justify-content-center">
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="col-md-4">
                <div class="card" onclick="openRequestPersonsList(<?php echo $row['UserID']; ?>)">
                    <div class="card-body">
                        <h5 class="card-title">User ID: <?php echo $row['UserID']; ?></h5>
                        <p class="card-text">Description: <?php echo $row['Description']; ?></p>
                        <p class="card-text">Preferred City: <?php echo $row['PreferedCity']; ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function openRequestPersonsList(userID) {
        window.location.href = 'requestPersonsList.php?userID=' + userID;
    }
</script>
</body>
</html>
