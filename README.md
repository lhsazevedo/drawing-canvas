# Drawing Canvas App

## Installation

1. Clone the repository
2. Install API application dependencies
    ```bash
    docker compose run --rm api composer install
    ```
2. Create the environment file
    ```bash
    cp api/.env.example api/.env
    php artisan key:generate
    ```
3. Install client application dependencies
    ```bash
    docker compose run --rm client npm install
    ```
4. Start the containers
    ```bash
    docker compose up -d
    ```
5. Run the migrations
    ```bash
    docker compose run --rm api php artisan migrate
    ```
6. Access the client application at http://localhost:3000
