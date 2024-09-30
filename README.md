# Symfony MongoDB

## Description

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

## Tests
