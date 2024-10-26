<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class EndToEndTest extends TestCase
{
    private $client;
    private $username;
    private $email;
    private $cookieJar; // To maintain the session between requests

    protected function setUp(): void
    {
        // Create a new Guzzle HTTP client
        $this->client = new Client([
            'cookies' => true // Enable cookies to handle sessions
        ]);

        // Generate a unique username and email for the test
        $this->username = 'testuser_' . uniqid();
        $this->email = 'testuser_' . uniqid() . '@example.com';
    }

    public function testUserFlow()
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

        // Step 2: Log in with the new user
        $loginUrl = 'http://localhost/website02/website/login.php';
        $response = $this->client->post($loginUrl, [
            'form_params' => [
                'username' => $this->username,
                'password' => 'testpassword',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Login successful', (string) $response->getBody());

        // Step 3: Search for properties (you can modify search criteria if needed)
        $searchUrl = 'http://localhost/website02/website/search.php';
        $response = $this->client->get($searchUrl, [
            'query' => [
                'city' => 'berlin',
                'property-type' => 'flat',
                'property-dates' => '2024-10-01',
                'property-dates-latest' => '2024-12-01',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Found properties', (string) $response->getBody());

        // Step 4: View property details and get propertyID from URL
        // Assume property ID is dynamically retrieved, replace with the actual logic
        $propertyId = 1; // Modify this as needed based on your search result or database

        $propertyDetailsUrl = "http://localhost/website02/website/property_details.php?propertyID=$propertyId";
        $response = $this->client->get($propertyDetailsUrl);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Property Details', (string) $response->getBody());

        // Step 5: Send a message
        // You might need to dynamically fetch the senderID and receiverID based on the database
        $messageUrl = 'http://localhost/website02/website/property_details.php?propertyID=' . $propertyId;
        $response = $this->client->post($messageUrl, [
            'form_params' => [
                'messageInput' => 'Hello, I am interested in your property.', // Message text
                'sendMessage' => 'Submit', // Ensure this matches the form submission button name
            ],
        ]);

        // Assert that the message was sent successfully
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Message sent successfully', (string) $response->getBody());
    }
}
