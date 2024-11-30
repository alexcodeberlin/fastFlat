<?php

use PHPUnit\Framework\TestCase;

class editProfile extends TestCase
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
        $_SESSION['username'] = 'testUser';
    }

    protected function tearDown(): void
    {
        // Clean up test data
        $this->pdo->exec("DELETE FROM Users");
    }

    public function testUpdateProfile(): void
    {
        // Set up mock POST data
        $_POST['firstName'] = 'UpdatedFirst';
        $_POST['lastName'] = 'UpdatedLast';
        $_POST['phone'] = '987654321';

        // Simulate the form submission
        $this->updateProfile($_POST['firstName'], $_POST['lastName'], $_POST['phone']);

        // Verify that the user's profile was updated in the database
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE Username = :username");
        $stmt->execute(['username' => $_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($user, "User should exist in the database.");
        $this->assertEquals('UpdatedFirst', $user['FirstName']);
        $this->assertEquals('UpdatedLast', $user['LastName']);
        $this->assertEquals('987654321', $user['Phone']);
    }

    public function testInvalidUpdate(): void
    {
        // Test case for invalid input (e.g., empty first name)
        $_POST['firstName'] = '';
        $_POST['lastName'] = 'LastName';
        $_POST['phone'] = '987654321';

        $result = $this->updateProfile($_POST['firstName'], $_POST['lastName'], $_POST['phone']);

        // Assert that the update fails when required fields are empty
        $this->assertEquals("Error updating profile: Invalid input.", $result);
    }

    private function updateProfile($firstName, $lastName, $phone)
    {
        // Simulate the implementation of the profile update logic from your PHP code
        if (empty($firstName) || empty($lastName)) {
            return "Error updating profile: Invalid input.";
        }

        $username = $_SESSION['username'];
        $updateQuery = $this->pdo->prepare("
            UPDATE Users 
            SET FirstName = :firstName, LastName = :lastName, Phone = :phone 
            WHERE Username = :username
        ");

        if ($updateQuery->execute([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'phone' => $phone,
            'username' => $username
        ])) {
            return "Profile updated successfully.";
        } else {
            return "Error updating profile.";
        }
    }
}
