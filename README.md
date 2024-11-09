# RentACarAPI

## Project Description
RentACarAPI is a RESTful API built with the Symfony framework, designed to manage car rental operations.
It includes functionalities for handling car listings, rental bookings, and customer data.

## Requirements

- PHP: 7.1.3 or higher (tested with PHP 8.1)
- Composer: For dependency management
- Symfony CLI: Optional, but helpful for running the server locally
- Database: MySQL

## Installation

1. Install dependencies
   composer install

2. Set up the database Copy the .env file to .env.local and configure the DATABASE_URL with your MySQL credentials:
   DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name"

3. Run migrations Run the following command to create the necessary tables in your database:
   php bin/console doctrine:migrations:migrate

## Running the Server
To start the Symfony server, run:

symfony server:start

This will start a local server, typically accessible at http://127.0.0.1:8000

## API Endpoints
List of primary endpoints:

GET /api/vehicle/list - Retrieve a list of all vehicles
POST /api/vehicle/create - Add a new vehicle to the inventory
GET /api/vehicle/read/{id} - Get vehicle details by ID
PUT /api/vehicle/update/{id} - Update vehicle details by ID
DELETE /api/vehicle/delete/{id} - Delete a vehicle by ID

## Documentation
Swagger UI: http://localhost:8000/api/doc
Interactive interface to test the API. You can try out the endpoints directly from this interface.

JSON Documentation: http://localhost:8000/api/doc.json
OpenAPI specification in JSON format, describing all available endpoints and their details.

![img.png](img.png)

## ENJOY!
