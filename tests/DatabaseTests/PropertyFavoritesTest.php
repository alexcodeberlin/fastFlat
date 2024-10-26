<?php

use PHPUnit\Framework\TestCase;

class PropertyFavoritesTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Create a PDO connection to the test database
        $this->pdo = new PDO('mysql:host=localhost;port=3306;dbname=testing_property;charset=utf8', 'root', '');

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Set up test data (create a user, a property, and an agent)
        $this->pdo->exec("INSERT INTO Users (UserID, Username, PasswordHash, Email, FirstName, LastName, Phone) 
                          VALUES (1, 'testUser', 'hashedPassword', 'testUser@example.com', 'John', 'Doe', '123456789')");
        $this->pdo->exec("INSERT INTO Users (UserID, Username, PasswordHash, Email, FirstName, LastName, Phone) 
                          VALUES (2, 'agentUser', 'hashedPassword', 'agentUser@example.com', 'Jane', 'Doe', '987654321')");
        $this->pdo->exec("INSERT INTO Properties (PropertyID, Title, Description, Address, City, State, ZipCode, PriceForMonth, 
                          Bedrooms, Bathrooms, Area, Type, StartTime, EndTime, AgentID) 
                          VALUES (1, 'Test Property', 'Nice apartment in Test City', '123 Main St', 'Test City', 
                          'Test State', '12345', 1200.00, 2, 1, 800.50, 'Flat', '2024-01-01 00:00:00', 
                          '2024-12-31 23:59:59', 2)");
    }

    protected function tearDown(): void
    {
        // Clean up test data after each test
        $this->pdo->exec("DELETE FROM UserFavorites");
        $this->pdo->exec("DELETE FROM Properties");
        $this->pdo->exec("DELETE FROM Users");
    }

    public function testAddToFavorites(): void
    {
        // Simulate a logged-in user
        $_SESSION['username'] = 'testUser';

        // Call the function that adds the property to favorites
        $result = $this->addToFavorites(1); // 1 is the PropertyID

        // Assert the success message
        $this->assertEquals("Property added to favorites successfully.", $result);

        // Verify that the favorite was inserted in the database
        $stmt = $this->pdo->query("SELECT * FROM UserFavorites WHERE UserID = 1 AND PropertyID = 1");
        $favorite = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($favorite, "Favorite entry should exist in the database.");
        $this->assertEquals(1, $favorite['UserID']);
        $this->assertEquals(1, $favorite['PropertyID']);
    }

    public function testPropertyAlreadyInFavorites(): void
    {
        // Simulate a logged-in user and insert a favorite entry
        $_SESSION['username'] = 'testUser';
        $this->pdo->exec("INSERT INTO UserFavorites (UserID, PropertyID) VALUES (1, 1)");

        // Call the function to try adding the same property to favorites
        $result = $this->addToFavorites(1); // 1 is the PropertyID

        // Assert the "already in favorites" message
        $this->assertEquals("Property is already in favorites.", $result);
    }

    private function addToFavorites($propertyID)
    {
        // Simulate the implementation of the addToFavorites logic from your PHP code
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            // Get UserID based on the provided username
            $userIDQuery = $this->pdo->prepare("SELECT UserID FROM Users WHERE Username = ?");
            $userIDQuery->execute([$username]);
            $user = $userIDQuery->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $userID = $user['UserID'];
                // Check if the property is already in favorites
                $checkQuery = $this->pdo->prepare("SELECT * FROM UserFavorites WHERE UserID = ? AND PropertyID = ?");
                $checkQuery->execute([$userID, $propertyID]);

                if ($checkQuery->rowCount() == 0) {
                    // Insert the property into favorites
                    $insertQuery = $this->pdo->prepare("INSERT INTO UserFavorites (UserID, PropertyID) VALUES (?, ?)");
                    if ($insertQuery->execute([$userID, $propertyID])) {
                        return "Property added to favorites successfully.";
                    } else {
                        return "Error adding to favorites.";
                    }
                } else {
                    return "Property is already in favorites.";
                }
            } else {
                return "User not found.";
            }
        } else {
            return "Please log in to add properties to favorites.";
        }
    }
}
