# How to run laravel project from git clone??
## Requirements
* Composer
* PHP
* Mysql
Of course, you can use XAMPP or LAMP

1. clone your project
2. Go to the folder application using **cd** command on your cmd or terminal
3. Run **composer install** on your cmd or terminal 
4. Copy **.env.example** file to **.env** on the root folder. You can type **cp .env.example .env**
5. Open your **.env** file and change the database name (**DB_DATABASE**) to whatever you have, username (**DB_USERNAME**) and password (**DB_PASSWORD**) field
correspond to your configuration. By default, the username is **root** and you can leave the password field empty (**This is for XAMPP**)
By default, the username is **root** and password is also **root** (**This is for LAMP**)
6. Run **php artisan key:generate**
7. Run **php artisan jwt:secret**
8. Run **php artisan migrate:fresh**
9. Run **php artisan db:seed**
10. Run **php artisan serve**
11. Go to **localhost:8000**   
Good Luck!
