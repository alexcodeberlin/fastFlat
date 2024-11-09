<?php
include 'connect.php';
// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Retrieve the city parameter from the GET request which was entered in index.php
$city = $_GET['city'];
// SQL query to select requests that match the entered city
$sql = "SELECT UserID, Description, PreferedCity FROM Request WHERE PreferedCity = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $city);
// Execute the prepared statement
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style_request.css">
</head>
<body>

    <!-- Main container for displaying request cards -->
    <div class="container">
        <h1 class="text-center mb-4">Requests</h1>
        <div class="row justify-content-center">
            <!-- Loop through each request record and display it in a Bootstrap card -->
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="col-md-4">
                    <!-- Card with onclick event to navigate to the request persons list -->
                    <div class="card" onclick="openRequestPersonsList(<?php echo $row['UserID']; ?>)">
                        <div class="card-body">
                            <!-- Display User ID, Description, and Preferred City in each card -->
                            <h5 class="card-title">User ID: <?php echo $row['UserID']; ?></h5>
                            <p class="card-text">Description: <?php echo $row['Description']; ?></p>
                            <p class="card-text">Preferred City: <?php echo $row['PreferedCity']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Bootstrap and jQuery JS for dynamic behavior and layout support -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // JavaScript function to redirect to the request persons list page with the selected user ID 
        function openRequestPersonsList(userID) {
            window.location.href = 'requestPersonsList.php?userID=' + userID;
        }
    </script>
</body>
</html>
