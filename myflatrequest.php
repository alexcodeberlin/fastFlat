<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Flat Request</title>
</head>
<body>
    <h1>My Flat Request</h1>
    <form action="submit_request.php" method="post">
        <div>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea>
        </div>
        <div>
            <label for="preferred_city">Preferred City:</label><br>
            <input type="text" id="preferred_city" name="preferred_city">
        </div>
        <div>
            <label for="preferred_state">Preferred Federal State:</label><br>
            <input type="text" id="preferred_state" name="preferred_state">
        </div>
        <div>
            <input type="submit" value="Submit Request">
        </div>
    </form>
</body>
</html>
