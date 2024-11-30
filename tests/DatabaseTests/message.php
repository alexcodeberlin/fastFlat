<?php

use PHPUnit\Framework\TestCase;

class message extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Create a PDO connection to the test database
        $this->pdo = new PDO('mysql:host=localhost;port=3306;dbname=testing_property;charset=utf8', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Set up test data
        $this->pdo->exec("INSERT INTO Users (UserID, Username, PasswordHash, Email) 
                          VALUES (1, 'testSender', 'hashedPassword', 'sender@example.com')");
        $this->pdo->exec("INSERT INTO Users (UserID, Username, PasswordHash, Email) 
                          VALUES (2, 'testReceiver', 'hashedPassword', 'receiver@example.com')");

        $this->pdo->exec("INSERT INTO Messages (MessageID, SenderID, ReceiverID, MessageText, SendDateTime) 
                          VALUES (1, 1, 2, 'Hello, how are you?', '2024-11-30 10:00:00')");
        $this->pdo->exec("INSERT INTO Messages (MessageID, SenderID, ReceiverID, MessageText, SendDateTime) 
                          VALUES (2, 2, 1, 'I am fine, thank you!', '2024-11-30 10:05:00')");
    }

    protected function tearDown(): void
    {
        // Clean up test data
        $this->pdo->exec("DELETE FROM Messages");
        $this->pdo->exec("DELETE FROM Users");
    }

    public function testCreateUser(): void
    {
        // Create a new user
        $this->pdo->exec("INSERT INTO Users (UserID, Username, PasswordHash, Email) 
                          VALUES (3, 'newUser', 'hashedPassword', 'newuser@example.com')");

        // Verify the user was created
        $stmt = $this->pdo->query("SELECT * FROM Users WHERE UserID = 3");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($user, "User entry should exist in the database.");
        $this->assertEquals('newUser', $user['Username']);
        $this->assertEquals('newuser@example.com', $user['Email']);
    }

    public function testReceivedMessages(): void
    {
        // Fetch received messages for UserID = 2
        $stmt = $this->pdo->prepare("SELECT * FROM Messages WHERE ReceiverID = ?");
        $stmt->execute([2]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Assert that there is one received message
        $this->assertCount(1, $messages, "There should be 1 received message.");
        $this->assertEquals('Hello, how are you?', $messages[0]['MessageText']);
    }

    public function testSentMessages(): void
    {
        // Fetch sent messages for UserID = 1
        $stmt = $this->pdo->prepare("SELECT * FROM Messages WHERE SenderID = ?");
        $stmt->execute([1]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Assert that there is one sent message
        $this->assertCount(1, $messages, "There should be 1 sent message.");
        $this->assertEquals('Hello, how are you?', $messages[0]['MessageText']);
    }
}
