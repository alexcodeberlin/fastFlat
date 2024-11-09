<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Flat Request</title>
</head>
<body>
    <h1>My Flat Request</h1>
    <!-- Form to submit flat request  -->
    <form action="submit_request.php" method="post">
        <!-- Description section for users  -->
        <div>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea>
        </div>
        
        <!-- Field for entering the preferred city -->
        <div>
            <label for="preferred_city">Preferred City:</label><br>
            <input type="text" id="preferred_city" name="preferred_city">
        </div>
        
        <!-- Field for entering the preferred federal state -->
        <div>
            <label for="preferred_state">Preferred Federal State:</label><br>
            <input type="text" id="preferred_state" name="preferred_state">
        </div>
        
        <!-- Submit button to send the request data -->
        <div>
            <input type="submit" value="Submit Request">
        </div>
    </form>
</body>
</html>
