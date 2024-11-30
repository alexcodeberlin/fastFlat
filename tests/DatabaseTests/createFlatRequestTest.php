<?php

use PHPUnit\Framework\TestCase;

class createFlatRequestTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Create a PDO connection to the test database
        $this->pdo = new PDO('mysql:host=localhost;port=3306;dbname=testing_property;charset=utf8', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert a test user into the database
        $this->pdo->exec("
            INSERT INTO Users (UserID, Username, PasswordHash, Email, FirstName, LastName, Phone)
            VALUES (1, 'testUser', 'hashedPassword', 'testUser@example.com', 'John', 'Doe', '123456789')
        ");

        // Simulate a logged-in user
        session_start();
        $_SESSION['UserID'] = 1;
        $_SESSION['username'] = 'testUser';
    }

    protected function tearDown(): void
    {
        // Clean up test data
        $this->pdo->exec("DELETE FROM Request");
        $this->pdo->exec("DELETE FROM Users");
    }

    public function testSubmitFlatRequest(): void
    {
        // Mock POST data
        $_POST = [
            'description' => 'Looking for a 2-bedroom apartment near the city center.',
            'preferred_city' => 'Testville',
            'preferred_state' => 'TestState'
        ];

        // Call the method to submit the flat request
        $result = $this->submitFlatRequest();

        // Verify the request was successfully added to the database
        $stmt = $this->pdo->prepare("SELECT * FROM Request WHERE UserID = :userID");
        $stmt->execute(['userID' => $_SESSION['UserID']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($request, "Flat request should exist in the database.");
        $this->assertEquals('Looking for a 2-bedroom apartment near the city center.', $request['Description']);
        $this->assertEquals('Testville', $request['PreferedCity']);
        $this->assertEquals('TestState', $request['PreferedFederalstate']);
    }

    public function testInvalidFlatRequest(): void
    {
        // Test case for invalid input (e.g., empty description)
        $_POST = [
            'description' => '',
            'preferred_city' => 'Testville',
            'preferred_state' => 'TestState'
        ];

        $result = $this->submitFlatRequest();

        // Assert that the submission fails due to invalid input
        $this->assertEquals("Error: Invalid input.", $result);
    }

    private function submitFlatRequest()
    {
        // Simulate the implementation of the flat request submission logic
        if (empty($_POST['description']) || empty($_POST['preferred_city']) || empty($_POST['preferred_state'])) {
            return "Error: Invalid input.";
        }

        $userID = $_SESSION['UserID'];

        // Insert the request into the database
        $query = $this->pdo->prepare("
            INSERT INTO Request (UserID, Description, PreferedCity, PreferedFederalstate)
            VALUES (:userID, :description, :preferred_city, :preferred_state)
        ");

        $success = $query->execute([
            'userID' => $userID,
            'description' => $_POST['description'],
            'preferred_city' => $_POST['preferred_city'],
            'preferred_state' => $_POST['preferred_state']
        ]);

        return $success ? "Request submitted successfully." : "Error submitting request.";
    }
}
