<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class messageSystemTest extends TestCase
{
    private $client;
    private $username;
    private $email;
    private $cookieJar;

    protected function setUp(): void
    {
        // Create a new Guzzle HTTP client
        $this->client = new Client(['cookies' => true]);

        // Generate unique username and email for testing
        $this->username = 'testuser_' . uniqid();
        $this->email = 'testuser_' . uniqid() . '@example.com';
    }

    public function testMessageFlow()
    {
        // Step 1: Register a new user
        $registerUrl = 'http://localhost/website02/website/register.php';
        $response = $this->client->post($registerUrl, [
            'form_params' => [
                'username' => $this->username,
                'password' => 'testpassword',
                'email' => $this->email,
                'firstName' => 'Test',
                'lastName' => 'User',
                'phone' => '1234567890',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('New record created successfully', (string) $response->getBody());

        // Step 2: Log in the newly created user
        $loginUrl = 'http://localhost/website02/website/login.php';
        $response = $this->client->post($loginUrl, [
            'form_params' => [
                'username' => $this->username,
                'password' => 'testpassword',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Login successful', (string) $response->getBody());

        // Step 3: Send a message to another user (assuming receiverID is 2 for testing purposes)
        $messageUrl = 'http://localhost/website02/website/message.php';
        $response = $this->client->post($messageUrl, [
            'form_params' => [
                'messageInput' => 'Hello, this is a test message.',
                'sendMessage' => 'Submit',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Message sent successfully', (string) $response->getBody());

        // Step 4: View received messages (assume the receiver logs in to check messages)
        $response = $this->client->get('http://localhost/website02/website/message.php');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Hello, this is a test message.', (string) $response->getBody());
    }
}
