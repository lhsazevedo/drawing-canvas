# Drawing Canvas App

## Setup

1. Clone the repository
2. Install API application dependencies
    ```bash
    docker compose run --rm api composer install
    ```
2. Create the environment files
    ```bash
    cp api/.env.example api/.env
    cp client/.env.example client/.env.local
    docker compose run --rm api php artisan key:generate
    ```
3. Install client application dependencies
    ```bash
    docker compose run --rm client npm install
    ```
4. Start the services
    ```bash
    docker compose up -d
    ```
5. Run the database migrations
    ```bash
    docker compose run --rm api php artisan migrate
    ```
6. Access the client application at http://localhost:3000
