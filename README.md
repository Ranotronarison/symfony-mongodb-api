# Symfony MongoDB

## Description
This is a simple Symfony API that provides a few endpoints to manipulate users and articles in a MongoDB database.

## Prerequisites
- Docker and Docker Compose

## Installation
To run this project locally, follow these steps:
1. Clone the repository
2. `docker compose build`
3. `docker compose up -d --wait` to start the app
4. If you run the app for the first time, run these shell commands :  
- Generate private and public keys for jwt sign
```bash
docker compose exec php sh -c "set -e; apt-get install openssl; php bin/console lexik:jwt:generate-keypair; setfacl -R -m u:www-data:rX -m u:\$(whoami):rwX config/jwt; setfacl -dR -m u:www-data:rX -m u:\$(whoami):rwX config/jwt"
```
- Update the database schema
```bash
docker compose exec php sh -c "php bin/console doctrine:mongodb:schema:update"
```

## Use the api
Feel free to use the http client of your choice.
### Swagger UI 
To use the api through the Swagger UI 
1. go to http://localhost:8080/docs or https://localhost:4443/docs on your browser and skip the certificate warning.
2. On the swagger ui, create an new user from the POST /api/users endpoint in the User section.
3. Request an access token from the POST /auth endpoint in the Auth section.
4. Client on Auhorize button or 🔓 icon on endpoints
5. And voila, you are ready to use the api 🙂.


### curl 
To use the command line with curl, run the following command:

Create user (/api/users is a public endpoint):
```bash
curl -k https://localhost:4443/api/users -X POST -H "Content-Type: application/json" -d '{"email": "<your email>", "plainPassword": "<your password>"}'
```
Request an access token :
```bash
curl -k https://localhost:4443/auth -X POST -H "Content-Type: application/json" -d '{"email": "your email>", "password": "<your password>"}'
```

Use the token :
```bash
curl -k https://localhost:4443/api/articles -X GET -H "Authorization: Bearer <token>"
```

## Tests
Launch tests with :
```bash
docker compose exec php sh -c "php bin/phpunit"
```

Launch tests with coverage :
```bash
docker compose exec -e XDEBUG_MODE=coverage -T php bin/phpunit --coverage-text
```
