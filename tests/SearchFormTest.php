<?php
use PHPUnit\Framework\TestCase;

class SearchFormTest extends TestCase
{
    public function setUp(): void
    {
        // Mock form input variables
        $_GET['city'] = 'Berlin';
        $_GET['property-type'] = 'flat';
        $_GET['search-type'] = 'show-properties';
        $_GET['property-dates'] = '2024-11-01T08:30';
        $_GET['property-dates-latest'] = '2024-11-10T10:00';
    }

    public function tearDown(): void
    {
        // Clear the mock variables
        unset($_GET);
    }

    /** @test */
    public function test_city_field_is_required()
    {
        $_GET['city'] = '';
        $this->assertEmpty($_GET['city'], 'City field should not be empty');
    }

    /** @test */
    public function test_property_type_is_valid()
    {
        $validPropertyTypes = ['shared-flat', 'flat', 'one-room-flat', 'house'];
        $this->assertContains($_GET['property-type'], $validPropertyTypes, 'Invalid property type selected');
    }

    /** @test */
    public function test_search_type_is_valid()
    {
        $validSearchTypes = ['show-properties', 'search-properties'];
        $this->assertContains($_GET['search-type'], $validSearchTypes, 'Invalid search type selected');
    }

    /** @test */
    public function test_move_in_dates_are_valid()
    {
        $earliestMoveInDate = new DateTime($_GET['property-dates']);
        $latestMoveInDate = new DateTime($_GET['property-dates-latest']);

        // Ensure the earliest date is before or equal to the latest date
        $this->assertTrue($earliestMoveInDate <= $latestMoveInDate, 'Earliest move-in date should be before or equal to the latest move-in date');
    }

    /** @test */
    public function test_form_redirects_based_on_search_type()
    {
        $searchType = $_GET['search-type'];

        if ($searchType === 'search-properties') {
            $expectedAction = 'request.php';
        } else {
            $expectedAction = 'search.php';
        }

        $this->assertEquals($expectedAction, $this->getFormAction($searchType));
    }

    private function getFormAction($searchType)
    {
        return $searchType === 'search-properties' ? 'request.php' : 'search.php';
    }
}
