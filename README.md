# Laravel 5 &amp; AngularJs

##Introduction
A starter dashboard app build with Laravel 5 &amp; AngularJs. Including users and roles management.

##Included
* Laravel 5
* Angular Js
* Bootstrap

##Installation

###Step 1

Clone the repo
```
git clone https://github.com/rasitapalak/laravel5-angular.git
```

or download the zip file
```
https://github.com/rasitapalak/laravel5-angular/archive/master.zip
```

###Step 2

Install dependencies
```
cd laravel5-angular
composer install
```

###Step 3

Generate a unique key for your application.
```
php artisan key:generate
```

###Step 4

Configure .env file

```
APP_URL
```
Change this to your public folder.

```
APP_LANG
```
Two languages are available. Turkish(tr) and English(en).

Configure database settings.


###Step 5

Create and seed the database
```
php artisan migrate
php artisan db:seed
```

###Step 6

Change permissions of storage folder.
```
chmod 755 -R storage
```

This should work but if not try
```
chmod 777 -R storage
```

###Step 7

Login to application

Username : admin

Password : admin


##Adding new pages
You can add new pages to your application. Further information will be added.








