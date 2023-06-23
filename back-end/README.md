# How to install
- git clone {url}
- cd back-end
- composer update
- change .env vars (DATABASE_URL, MAILER_DSN, etc...)
- php bin/console doctrine:database:create
- php bin/console make:migration
- php bin/console doctrine:migrations:migrate
- php bin/console lexik:jwt:generate-keypair
- php bin/console messenger:consume async -vv (Cf supervisor when production)
- symfony server:start
- Create an API KEY in Tokens menu


# Supervisor 
- sudo supervisorctl reread
- sudo supervisorctl update
- sudo supervisorctl start messenger-consume:*
- sudo supervisorctl status

# Create admin :

- php bin/console create:user <email> <password> <firstName> <lastName>

# Main routes : 

- /         for login
- /admin    for administration
- /api      for API

# Api routes

- /api is public
- /api/auth is public
- /api/* is protected : requires an api key (x-api-key header) with proper scope and JWT Token (Authorization header)

