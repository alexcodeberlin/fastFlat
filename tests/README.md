# Testing in FastFlat

This document provides instructions on how to run tests for the FastFlat project. 

## Testing Framework

FastFlat uses PHPUnit for end to end and integration testing. It is also using Guzzle. 

## Installation

1. **Ensure Composer is Installed**:
   - Follow the instructions in the main README to install Composer.

To install PHPUnit for your project, follow these steps:

1. **Ensure Composer is Installed**:
   - Before installing PHPUnit, make sure you have Composer installed. You can download it from [getcomposer.org](https://getcomposer.org/download/).

2. **Navigate to Your Project Directory**:
   - Open your terminal (Command Prompt, PowerShell, or any terminal emulator).
   - Change to your project directory where your PHP project is located:
     ```bash
     cd path/to/your/project
     ```

3. **Install PHPUnit**:
   - Run the following command to require PHPUnit as a development dependency:
     ```bash
     composer require --dev phpunit/phpunit
     ```

4. **Verify Installation**:
   - After installation, you can verify that PHPUnit is installed by running:
     ```bash
     ./vendor/bin/phpunit --version
     ```

This will ensure that PHPUnit is available for testing your PHP code within the project.

## Guzzle

**Guzzle** is a PHP HTTP client that makes it easy to send HTTP requests and integrate with web services. In the FastFlat project, Guzzle is utilized to simulate requests to the web application.


### Installation

To install Guzzle, run the following command in your project directory:

```bash
composer require guzzlehttp/guzzle


## Running Tests


## 1. SearchFormTest

The `SearchFormTest` class is a PHPUnit test case that validates the functionality of the search form in the FastFlat application. It ensures that the form inputs meet the required criteria before processing search requests.

### Test Overview

This test suite checks various aspects of the search form, including required fields: valid property types, valid search types and the correct handling of move-in dates.

### How to Run the Test

1. Ensure you have PHPUnit installed (see [Installing PHPUnit via Composer](#installing-phpunit-via-composer)).
2. Run the tests using the following command:
   ```bash
   ./vendor/bin/phpunit tests/searchFormTest.php



## 2. Integration test: Testing the Property Favorites Functionality

The `PropertyFavoritesTest` class uses PHPUnit to test the functionality of adding properties to a user's favorites. It ensures that the application behaves as expected when interacting with the database.

### Test Overview

- **Purpose**: To verify that users can successfully add properties to their favorites and handle scenarios where a property is already in favorites.
- **Database Used**: A separate test database named `testing_property` is created to prevent interference with the main application data.


### How to Run the Test

1. Ensure you have PHPUnit installed (see [Installing PHPUnit via Composer](#installing-phpunit-via-composer)).
2. Create a test database named `testing_property` with the necessary tables.
3. Run the tests using the following command:
   ```bash
   ./vendor/bin/phpunit tests/PropertyFavoritesTest.php



## 3. End-to-End Test for User Flow

This test contains a end-to-end test using PHPUnit and GuzzleHTTP. The test covers the complete user flow from registration to sending a message regarding a property.

### Test Overview
Purpose: This End-to-End (E2E) test verifies the core user flow of the real estate web application. It simulates real user actions, from registration and login to property search, viewing property details and sending a message to an agent.


## Prerequisites

Make sure you installed Guzzle.

### How to Run the Test

1. Ensure installed phpUnit
2. Create a test database named `testing_property` with the necessary tables.
3. Run the tests using the following command:
   ```bash
   ./vendor/bin/phpunit tests/EndtoEnd/EndtoEndtest.php



To run the tests go to the root directory of you project and use the following command:
```bash



