# FastFlat

FastFlat is a web-based real estate platform allowing users to search, favorite and interact with property listings. This project is designed with PHP and MySQL, using XAMPP for local development and the Doctrine ORM for database management.

## Features

- **User Registration and Login**: Users can register for an account and log in to save their preferences.
- **Property Search**: The application includes a search functionality that allows users to filter properties based on various criteria, such as city, property type (e.g., flat, house) and move-in date. This makes it easy for users to find properties that match their specific needs and preferences.
- **Favorite Properties**: Users can mark properties as favorites with a single click, making it simple to keep track of listings they are interested in. These favorites are stored in the user's profile, allowing for quick access during future visits.
- **User-Property Interaction**: Users can send messages or inquiries about specific properties, making the process of finding and securing a rental more efficient.
- **User-Create flat request**:  Users can submit requests for flats that meet their requirements. This feature allows potential renters to specify their needs (e.g., location, budget, amenities) and enables property owners to respond with suitable options. This interaction enhances the chances of matching users with ideal properties.
## Getting Started

### Prerequisites

- **XAMPP**: Install XAMPP for an Apache server, MySQL and PHP support.
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
3. To confirm both servers are running:
   - Open a browser and visit `http://localhost`. If successful you should see the XAMPP dashboard.

### 3 Database Setup

1. Open phpMyAdmin by going to `http://localhost/phpmyadmin`.
2. Create a new database named `property`.
3. Import the SQL file located in the `database` folder of the project to set up the initial structure.
4. For testing: Create a new database named `test_property` and do the same.

### 4 Install Dependencies

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

## Clean Code principles 
1. The projects code follows proper and consistent naming conventions. It doesn't need a lot of comments because it is self documenting. The names for variables and functions are Intention revealing. My functions have sensefull argument names and they are easy to test. 
2. I use error handling with Clear Error identification and messages. 
3. All nested if conditions are removed.
3. The app is secure against sql injection.
### ERM:

   Database ERM:
   ![ermzoom](https://github.com/alexcodeberlin/fastFlat/assets/159266599/295b4ee5-7778-4357-8163-ff69d2e16735)




## Testing

- **Integration Test**: FastFlat uses PHPUnit for unit and integration testing. Goto Test.readme for more information..










   





