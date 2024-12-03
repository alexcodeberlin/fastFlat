<!-- Testing Property Addition:

The testAddProperty() method verifies that a new property can be added to the database. It mocks data for property.
Testing Invalid Input Handling:

The testInvalidPropertyAddition() method checks the behavior when invalid input (e.g., missing title) is provided.

The addProperty() method returns an error message, which is validated in this test case.

Helper Function for Adding Properties:

The tearDown() method ensures that the Properties and Users tables are cleaned after each test to maintain isolation between test cases.
-->

<?php

use PHPUnit\Framework\TestCase;

class addPropertyTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Create a PDO connection to the test database
        $this->pdo = new PDO('mysql:host=localhost;port=3306;dbname=testing_property;charset=utf8', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert a test user (agent) into the database
        $this->pdo->exec("
            INSERT INTO Users (UserID, Username, PasswordHash, Email, FirstName, LastName, Phone)
            VALUES (1, 'testAgent', 'hashedPassword', 'testAgent@example.com', 'Jane', 'Doe', '123456789')
        ");

        // Simulate a logged-in user
        $_SESSION['username'] = 'testAgent';
    }

    protected function tearDown(): void
    {
        // Clean up test data
        $this->pdo->exec("DELETE FROM Properties");
        $this->pdo->exec("DELETE FROM Users");
    }

    public function testAddProperty(): void
    {
        // Mock POST data for the property
        $_POST = [
            'title' => 'Test Property',
            'description' => 'A beautiful test property.',
            'address' => '123 Test St',
            'city' => 'Testville',
            'state' => 'TS',
            'zipCode' => '12345',
            'price' => '1200',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area' => '1500',
            'type' => 'Flat',
            'startTime' => '2024-12-01T10:00',
            'endTime' => '2025-12-01T10:00',
            'add_property' => true,
        ];

        // Call the method to add the property
        $result = $this->addProperty();

        // Assert that the property was added successfully
        $stmt = $this->pdo->prepare("SELECT * FROM Properties WHERE Title = :title");
        $stmt->execute(['title' => 'Test Property']);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($property, "Property should exist in the database.");
        $this->assertEquals('Test Property', $property['Title']);
        $this->assertEquals('A beautiful test property.', $property['Description']);
        $this->assertEquals('123 Test St', $property['Address']);
    }

    public function testInvalidPropertyAddition(): void
    {
        // Test case for invalid input (e.g., missing title)
        $_POST = [
            'title' => '',
            'description' => 'A property with missing title.',
            'address' => '123 Test St',
            'city' => 'Testville',
            'state' => 'TS',
            'zipCode' => '12345',
            'price' => '1200',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area' => '1500',
            'type' => 'Flat',
            'startTime' => '2024-12-01T10:00',
            'endTime' => '2025-12-01T10:00',
            'add_property' => true,
        ];

        $result = $this->addProperty();

        // Assert that the addition fails due to invalid input
        $this->assertEquals("Error adding property: Invalid input.", $result);
    }

    private function addProperty()
    {
        // Simulate the implementation of the property addition logic
        if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['address'])) {
            return "Error adding property: Invalid input.";
        }

        // Retrieve the agent's UserID
        $username = $_SESSION['username'];
        $stmt = $this->pdo->prepare("SELECT UserID FROM Users WHERE Username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return "Error: Agent not found.";
        }

        $agentID = $user['UserID'];

        // Insert the property into the database
        $query = $this->pdo->prepare("
            INSERT INTO Properties 
            (Title, Description, Address, City, State, ZipCode, PriceForMonth, Bedrooms, Bathrooms, Area, Type, AgentID, StartTime, EndTime) 
            VALUES 
            (:title, :description, :address, :city, :state, :zipCode, :price, :bedrooms, :bathrooms, :area, :type, :agentID, :startTime, :endTime)
        ");

        $success = $query->execute([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'zipCode' => $_POST['zipCode'],
            'price' => $_POST['price'],
            'bedrooms' => $_POST['bedrooms'],
            'bathrooms' => $_POST['bathrooms'],
            'area' => $_POST['area'],
            'type' => $_POST['type'],
            'agentID' => $agentID,
            'startTime' => $_POST['startTime'],
            'endTime' => $_POST['endTime'],
        ]);

        return $success ? "Property added successfully." : "Error adding property.";
    }
}
