# TEST - Contact Management App

## Requirements
- php 8.x
- mysql 5.x/8.x
- apache 2.x

## Installation
- create MySQL database
- create an apache vhost for the app and enable `rewrite` apache mod (if not enabled yet)
- cp .env.dist .env and set proper values for your local setup
- run `php src/migrations/initial.php` to create required tables in db
- go to `http://<vhost>` in your web browser 
