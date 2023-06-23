# How to install
- git clone {url}
- cd fog-api
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

# Settings of app

- NODE_RADIUS > correspond to the radius (In meters) that will be discovered around any new node
- MAP_DEFAULT_ZOOM > correspond to the default zoom value of the map when loading the mobile app
- EXPERIENCE_GAIN_WHEN_NEW_NODE	> correspond to the amount of experience earned when a new geolocation nde is updated
- EXPERIENCE_GAIN_WHEN_NEW_ARTICLE > correspond to the amount of experience earned when a new article has been published by an admin
- EXPERIENCE_GAIN_WHEN_NEW_ACHIEVEMENT > correspond to the amount of experience earned when a new achievement has been approved by an admin
- EXPERIENCE_GAIN_WHEN_NEW_COMMENT > correspond to the amount of experience earned when a new comment has been approved by an admin
- EXPERIENCE_GAIN_WHEN_NEW_LIKE  > correspond to the amount of experience earned when a user likes an article
- EXPERIENCE_GAIN_WHEN_NEW_INVITATION  > correspond to the amount of experience earned when a user invites a friend by email
