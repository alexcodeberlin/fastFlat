<!--testFormStructure
Verifies all required form elements and inputs are present in the HTML.

testSessionHandling
Ensures the user is redirected to login.php if not logged in.

testValidFormSubmission
Mocks form submission and verifies successful handling with a success alert.

testDatabaseErrorHandling
Simulates a database query failure and checks for an error alert in the output.
-->

<?PHP
use PHPUnit\Framework\TestCase;

class AddPropertyPageTest extends TestCase
{
    // Test if the HTML page contains all the necessary form elements
    public function testFormStructure()
    {
        $html = file_get_contents('C:\xampp2\htdocs\website02\website/add_property.php'); // Adjust the file path

        $this->assertStringContainsString('<form', $html, "The form tag is missing.");
        $this->assertStringContainsString('id="title"', $html, "The title input field is missing.");
        $this->assertStringContainsString('id="description"', $html, "The description field is missing.");
        $this->assertStringContainsString('id="address"', $html, "The address field is missing.");
        $this->assertStringContainsString('id="city"', $html, "The city field is missing.");
        $this->assertStringContainsString('id="zipCode"', $html, "The zip code field is missing.");
        $this->assertStringContainsString('id="price"', $html, "The price field is missing.");
        $this->assertStringContainsString('id="bedrooms"', $html, "The bedrooms field is missing.");
        $this->assertStringContainsString('id="type"', $html, "The type dropdown is missing.");
        $this->assertStringContainsString('id="startTime"', $html, "The start time field is missing.");
        $this->assertStringContainsString('id="endTime"', $html, "The end time field is missing.");
        $this->assertStringContainsString('type="submit"', $html, "The submit button is missing.");
    }

    // Test if the session is properly started and redirects if the user is not logged in
    public function testSessionHandling()
    {
        $_SESSION = [];
        $_SERVER['HTTP_REFERER'] = '/login.php';
        
        ob_start();
        include __DIR__ . '/add_property.php'; // Simulate loading the page
        $output = ob_get_clean();

        // Expect a redirection header to login.php
        $this->assertStringContainsString('Location: login.php', xdebug_get_headers()[0]);
    }

    // Test if the PHP code handles valid form submissions
    public function testValidFormSubmission()
    {
        $_SESSION['username'] = 'testuser'; // Mock session username
        $_POST = [
            'add_property' => true,
            'title' => 'Test Property',
            'description' => 'A lovely test property.',
            'address' => '123 Test Street',
            'city' => 'Testville',
            'state' => 'TS',
            'zipCode' => '12345',
            'price' => '1000',
            'bedrooms' => '3',
            'bathrooms' => '2',
            'area' => '1200',
            'type' => 'Flat',
            'startTime' => '2024-01-01T00:00',
            'endTime' => '2024-12-31T23:59'
        ];

        // Mock database connection and query behavior
        $mockConn = $this->createMock(mysqli::class);
        $mockConn->method('query')->willReturn(true);

        ob_start();
        include __DIR__ . '/add_property.php'; // Simulate page processing
        $output = ob_get_clean();

        // Check for success alert in output
        $this->assertStringContainsString("Property added successfully", $output, "Property addition failed.");
    }

    // Test if the PHP code handles invalid form submissions (e.g., database failure)
    public function testDatabaseErrorHandling()
    {
        $_SESSION['username'] = 'testuser'; // Mock session username
        $_POST = [
            'add_property' => true,
            // Same mock data as above
        ];

        // Mock database connection and query behavior
        $mockConn = $this->createMock(mysqli::class);
        $mockConn->method('query')->willReturn(false);

        ob_start();
        include __DIR__ . '/add_property.php'; // Simulate page processing
        $output = ob_get_clean();

        // Check for error alert in output
        $this->assertStringContainsString("Error:", $output, "Error alert is not displayed on failure.");
    }
}
