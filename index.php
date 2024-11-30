<?php
// Start a new session or resume the existing session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" author="Alexander Martens">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS FILE -->
    <link rel="stylesheet" href="css/style_index.css">
</head>
<body>
    <header>
        <!-- Navbarr -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="#">fastFlat</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <!-- My Profile Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            My Profile
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                            <h6 class="dropdown-header">Profile</h6>
                            <a class="dropdown-item" href="editprofile.php">Edit Profile</a>
                            <a class="dropdown-item" href="myflatrequest.php">Create Flat Requests</a>
                            <a class="dropdown-item" href="message.php">Messages</a>
                            <a class="dropdown-item" href="favourite.php">Favorite Objects</a>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Offerings</h6>
                            <a class="dropdown-item" href="property.php">Object Offerings</a>
                        </div>
                    </li>
                </ul>

                <!-- Login and Register Button  -->
                <a href="login.php" class="btn btn-primary">Login</a>
                <a href="register.php" class="btn btn-primary">Register</a>
            </div>
        </nav>
    </header>    
    <!-- Main content starting point -->
    <div class="content">
        <h1><?php echo "Welcome, " . $_SESSION['username']; ?></h1>
        <h3>Get a new Home!</h3>
        <!-- Select box -> 1.location 2. property-type 3.search type 4.earliest and latest movein date -->
        <!-- 1. select location -->
        <div class="search-container">
            <h3>Find Your Ideal Property</h3>
            <form id="searchForm" method="GET">
                <div class="form-group">
                    <label for="city">Enter City:</label>
                    <input type="text" id="city" name="city" class="form-control" placeholder="Enter city">
                </div>

                <!-- Custom styled dropdown menu for 2.property type -->
                <div class="form-group">
                    <label for="property-type">Property Type:</label>
                    <select id="property-type" name="property-type" class="custom-dropdown form-control">
                        <option value="shared-flat">Shared Flat</option>
                        <option value="flat">Flat</option>
                        <option value="one-room-flat">1-Room Flat</option>
                        <option value="house">House</option>
                    </select>
                </div>
                <!-- Custom styled dropdown menu for 3.search type -->
                <div class="form-group">
                    <label for="search-type">Search Type:</label>
                    <select id="search-type" name="search-type" class="custom-dropdown form-control">
                        <option value="show-properties">Show Properties</option>
                        <option value="search-properties">Show people requests</option>
                    </select>
                </div>
                <!-- 4. Earliest and latest movein dates -->
                <div class="form-group">
                    <label for="property-dates">earliest move in date</label>
                    <input type="datetime-local" id="property-dates" name="property-dates" class="custom-dropdown form-control">
                        <label for="property-dates-latest">latest move in date</label>
                    <input type="datetime-local" id="property-dates-latest" name="property-dates-latest" class="custom-dropdown form-control">
                    </select>
                </div>
                <!-- Search button -->
                <button type="button" id="searchButton" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 My Website</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Adds click event to the search button to handle form submission based on search type
        document.getElementById('searchButton').addEventListener('click', function() {
        var searchType = document.getElementById('search-type').value;
        if (searchType === 'search-properties') {
            document.getElementById('searchForm').action = 'request.php';
        } else {
            document.getElementById('searchForm').action = 'search.php';
        }
            document.getElementById('searchForm').submit();
        });
    </script>
</body>
</html>
