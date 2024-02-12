# TEST - Contact Management App

## Requirements
- php 8.3
- mysql 5.x/8.x
- apache 2.x

## Installation
### Vanilla
- create MySQL database
- create an apache vhost for the app and enable `rewrite` apache mod (if not enabled yet)
- cp src/.env.dist src/.env and set proper values for your local setup
- run composer install
- run `php src/migrations/initial.php` to create required table in db
- go to `http://<vhost>` in your web browser

### Docker
- cp .env.dist .env and set proper environment variables for the Docker Compose
- cp src/.env.dist src/.env and set proper environment variables for the App
- run `docker compose build`
- run `docker compose up -d`
- run `docker compose exec web php src/migrations/initial.php` to create required table in db
- go to `http://localhost:8080` in your web browser
