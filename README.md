## Quickstart Guide

### 1. Start the Application

Run the command `docker-compose up -d`. This command starts the application on your local machine.

### 2. Import Postman Collection

Import the `arvan.postman_collection.json` file into your Postman application.

### 3. Test Data Store Request

Interact with the `store data` request which you find in the Postman collection. This will ensure that the application
is functioning correctly.

## Tips for User Management and Authorization

* The users are defined in the `config/users.php` file, where you could modify them.
* Authorization is simplified by using the header along with user IDs. 
