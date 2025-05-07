Distribution Example Application realized by Symfony PHP Framework 

For local development simple start command in console: 

docker compose up -d --build 

Ater that copy .env.example to .env file.  

In conteiner php perform command in bash: 

1) composer install 
2) php bin/console app:regenerate-secret
Generate app secret and insert it in .env in APP_SECRET variable 
3) php bin/console doctrine:migrations:migrate
Make migration and create database structures
4) php bin/console app:create-admin
This command generate admin 
5) 