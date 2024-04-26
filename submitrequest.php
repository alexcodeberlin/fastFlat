<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection file
    include "db_connection.php";

    // Get data from form inputs
    $description = $_POST["description"];
    $preferred_city = $_POST["preferred_city"];
    $preferred_state = $_POST["preferred_state"];

    // Get the UserID of the logged-in user (you need to implement the login system)
    // For demonstration purposes, let's assume the UserID is stored in a session variable
    session_start();
    $userID = $_SESSION["UserID"];

    // Prepare and execute the SQL statement to insert data into the Request table
    $sql = "INSERT INTO Request (UserID, Description, PreferedCity, PreferedFederalstate) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $userID, $description, $preferred_city, $preferred_state);
    $stmt->execute();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    // Redirect back to the page with a success message
    header("Location: myflatrequest.php?success=1");
    exit();
} else {
    // If the form is not submitted, redirect to the form page
    header("Location: myflatrequest.php");
    exit();
}
?>