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
4) php bin/console doctrine:fixtures:load
Load fixtures data to table 
5) php bin/console app:create-admin
This command generate admin 
6) php bin/console app:import-mock-data
This command generate mocking sales data 

Go to 127.0.0.1/admin and authed here as admin. 

In admin in section settings you can correct pagination parameter api_per_page and 	api_secret. Setting api_secret delivery access to application api (use MD5 hash for this). 

You can find you API with MD5 hash (secret = myApiSecret01): 
1) Sales is http://127.0.0.1/api/salesdata/sales?secret=ee503d6873239aabd8d877b224ce4e64&page=1 
2) Stocks is http://127.0.0.1/api/salesdata/stocks?secret=ee503d6873239aabd8d877b224ce4e64&page=1
3) Returns http://127.0.0.1/api/salesdata/returns?secret=ee503d6873239aabd8d877b224ce4e64&page=1

Is you have any questions contact me: agafonov_av@adrussia.ru 

With best regards Alex. 



