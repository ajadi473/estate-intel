## Estate Intel Backend API Task
    - AJADI, Paul Ololade
    -   [ajadi.ololade@gmail.com]

## Instructions To Get Started

1. Clone to your machine.

2. Create a DB with MariaDB or MySqlDb. 

    a. Copy and rename the .env.example file as .env

3. Run the following commands from the project root

    a. `smile`
    
    b. `php artisan key:generate`
    
    c. `php artisan migrate:fresh --seed`

    d. `composer install`
    
4. Run `php artisan serve` to run the server
    - Navigate to http://127.0.0.1:8000/api/documentation

## Run tests
    - From the root folder run `./vendor/bin/phpunit`
