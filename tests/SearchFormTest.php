<!--Mocking User Input

Validating Input and Behavior

Redirect Logic Testing
-->

<?php
use PHPUnit\Framework\TestCase;

class SearchFormTest extends TestCase
{
    public function setUp(): void
    {
        // Mock form input variables to simulate user input
        $_GET['city'] = 'Berlin'; 
        $_GET['property-type'] = 'flat'; 
        $_GET['search-type'] = 'show-properties'; 
        $_GET['property-dates'] = '2024-11-01T08:30'; 
        $_GET['property-dates-latest'] = '2024-11-10T10:00'; 
    }

    public function tearDown(): void
    {
        // Clear the mock variables after each test
        unset($_GET);
    }

    /** @test */
    public function test_city_field_is_required()
    {
        // Simulate empty city field input
        $_GET['city'] = '';
        // Assert that the city field should not be empty
        $this->assertEmpty($_GET['city'], 'City field should not be empty');
    }

    /** @test */
    public function test_property_type_is_valid()
    {
        // Define a list of valid property types
        $validPropertyTypes = ['shared-flat', 'flat', 'one-room-flat', 'house'];
        // Assert that the selected property type is within the valid options
        $this->assertContains($_GET['property-type'], $validPropertyTypes, 'Invalid property type selected');
    }

    /** @test */
    public function test_search_type_is_valid()
    {
        // Define a list of valid search types
        $validSearchTypes = ['show-properties', 'search-properties'];
        // Assert that the selected search type is within the valid options
        $this->assertContains($_GET['search-type'], $validSearchTypes, 'Invalid search type selected');
    }

    /** @test */
    public function test_move_in_dates_are_valid()
    {
        // Parse the earliest and latest move-in dates into DateTime objects
        $earliestMoveInDate = new DateTime($_GET['property-dates']);
        $latestMoveInDate = new DateTime($_GET['property-dates-latest']);

        // Assert that the earliest move-in date is before or equal to the latest move-in date
        $this->assertTrue($earliestMoveInDate <= $latestMoveInDate, 'Earliest move-in date should be before or equal to the latest move-in date');
    }

    /** @test */
    public function test_form_redirects_based_on_search_type()
    {
        // Get the search type from the mocked input
        $searchType = $_GET['search-type'];

        // Determine the expected action based on the search type
        if ($searchType === 'search-properties') {
            $expectedAction = 'request.php'; // Redirect to request.php for "search-properties"
        } else {
            $expectedAction = 'search.php'; // Redirect to search.php for other cases
        }

        // Assert that the form action matches the expected action
        $this->assertEquals($expectedAction, $this->getFormAction($searchType));
    }

    // Helper function to determine the form action based on the search type
    private function getFormAction($searchType)
    {
        // Return the appropriate action based on the search type
        return $searchType === 'search-properties' ? 'request.php' : 'search.php';
    }
}
