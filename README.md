# FastFlat

FastFlat is a web-based real estate platform allowing users to search, favorite, and interact with property listings. This project is designed with PHP and MySQL, using XAMPP for local development and the Doctrine ORM for database management.

## Features

- **User Registration and Login**: Users can register for an account and log in to save their preferences.
- **Property Search**: Search functionality allows users to filter properties by city, type and move-in date.
- **Favorite Properties**: Users can mark properties as favorites, which are saved in their profile for future access.
- **User-Property Interaction**: Users can contact agents through the platform.
- **User-Create flat request**: User can create a flat request for flat owners.

## Getting Started

### Prerequisites

- **XAMPP**: Install XAMPP for an Apache server, MySQL, and PHP support.
- **Composer**: For managing dependencies.

### Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/alexcodeberlin/fastFlat.git

### 2 Start XAMPP

1. Open the XAMPP Control Panel.
2. Start the Apache and MySQL modules:
   - Click the "Start" button next to **Apache** to run the web server on port 80.
   - Click the "Start" button next to **MySQL** to run the MySQL server on port 3306.
3. If there are any port conflicts, ensure Apache is set to port 80 (or an available port) and MySQL to port 3306.
4. To confirm both servers are running:
   - Open a browser and visit `http://localhost`. If successful you should see the XAMPP dashboard.

### 3 Database Setup

1. Open phpMyAdmin by going to `http://localhost/phpmyadmin`.
2. Create a new database named `property`.
3. Import the SQL file located in the `database` folder of the project to set up the initial structure.
4. For testing: Create a new database named `test_property`

### 4 Install Dependencies

- Navigate to the project directory and install the necessary dependencies using Composer:
  ```bash
  composer install
  
  ## Installing PHPUnit via Composer (for testing)

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

  
  ## Configuration

1. Copy `.env.example` to a new file called `.env`.
2. Open the `.env` file and enter your database credentials:
   - **Host**: The database host (usually `localhost`).
   - **Username**: Your database username (e.g., `root`).
   - **Password**: Your database password (leave empty if you're using XAMPP defaults).
   - **Database Name**: The name of the database you created (e.g., `property`).

## Run the Application

1. Move or copy the project files into the XAMPP `htdocs` directory (e.g., `C:\xampp\htdocs\fastFlat` on Windows).
2. Open your web browser and navigate to `http://localhost/fastFlat` to access the application.

## Code Structure

- **`search.php`**: The main script handling property search functionality.

### ERM:

   Database ERM:
   ![ermzoom](https://github.com/alexcodeberlin/fastFlat/assets/159266599/295b4ee5-7778-4357-8163-ff69d2e16735)




## Testing

- **Integration Test**: FastFlat uses PHPUnit for unit and integration testing. Goto Test.readme for more information..










   





