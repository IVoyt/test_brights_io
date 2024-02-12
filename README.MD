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

