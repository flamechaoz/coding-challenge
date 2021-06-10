# Laravel Vue Codebase
This is a repository containing a simple codebase for Laravel Vue projects.
This repository has a number of packages installed including Vuetify, Vuex, and Vue router.

## Setup

1. Create .env file and copy the contents of .env.example to the .env 
    
    ```
    php -r "file_exists('.env') || copy('.env.example', '.env');"
    ```
    
2. Configure the .env
    * Setup the database connection
        
        ```
        Example:
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=watch
        DB_USERNAME=root
        DB_PASSWORD=
        ```
3. Run the following commands
    * composer install
    * npm install && npm run dev
    * php artisan key:generate
    * php artisan config:cache
    * php artisan route:cache
    * php artisan view:cache
    * php artisan migrate
    * php artisan passport:install

4. Run the Server
    * php artisan serve
        
