<?php

use PHPUnit\Framework\TestCase;

class NavbarTest extends TestCase
{
    public function testNavbarLinks()
    {
        // Load the HTML content of the index.php file
        $html = file_get_contents('C:/xampp2/htdocs/website02/website/index.php'); 

        // Assert that the HTML contains the expected navigation links
        $this->assertStringContainsString('href="editprofile.php"', $html, "Link to 'editprofile.php' is missing");
        $this->assertStringContainsString('href="myflatrequest.php"', $html, "Link to 'myflatrequest.php' is missing");
        $this->assertStringContainsString('href="message.php"', $html, "Link to 'message.php' is missing");
        $this->assertStringContainsString('href="favourite.php"', $html, "Link to 'favourite.php' is missing");
        $this->assertStringContainsString('href="property.php"', $html, "Link to 'property.php' is missing");
        $this->assertStringContainsString('href="login.php"', $html, "Link to 'login.php' is missing");
        $this->assertStringContainsString('href="register.php"', $html, "Link to 'register.php' is missing");
    }
}
