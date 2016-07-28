SEA WATCH APP
===================

About this Project
---------------------
This Application shall be used to organize emergency calls to a coordination center which can send the emergency calls to a rescue team.

This repo contains the adminstration backend and the database. 

There is also:

-  an App for involved "Spotters" to send new cases and to track the current position of the Spotter.
The Source for the Spotter App can be found [here](https://github.com/sea-watch/app_spotter)

- a "Refugee App" which can be used by people who are in a current case of distress.
The Source for the Refugee app can be found [here](https://github.com/sea-watch/app_refugee)


*Both apps are build with Angular and wrapped with [ionic](ionicframework.com/) to support as many systems as possible.* 

Userguides
----------------
[Adminpanel](docu/userguide/admin.md)

[Spotter App](docu/userguide/spotter_app.md)

[FAQ](docu/userguide/faq.md)


Requirements
----------------


- PHP >= 5.5.9
- OpenSSL PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- MySQL

*The backend is build with [Laravel 5](https://github.com/laravel/laravel) and is based on [Laravel Framework 5.1 Bootstrap 3 Starter Site](https://github.com/mrakodol/Laravel-5-Bootstrap-3-Starter-Site).*

How to install
--------------------

### Step 1: Get the Source Code:

You can download https://github.com/sea-watch/sea-watch-app/archive/master.zip and unzip it in your www or htdocs folder.


Or just clone this Repo
### Step 2: Use Composer to install dependencies

Laravel utilizes [Composer](http://getcomposer.org/) to manage its dependencies. First, download a copy of the composer.phar.
Once you have the PHAR archive, you can either keep it in your local project directory or move to
usr/local/bin to use it globally on your system.
On Windows, you can use the Composer [Windows installer](https://getcomposer.org/Composer-Setup.exe).

Enter the admin/ directory and run:

    composer dump-autoload
    composer install --no-scripts


### Step 3: Create a Database on your Server with uft8_general_ci

### Step 4: Update the .env file to your local settings

### Step 5: Generate Application Key

Laravel needs an application key, which can be generated with:

php artisan key:generate

### Step 5: DB Migration

Go to the root directory and type 'php artisan migrate'.

### Step 6: Change URLS

In
    admin/public/js/config.js


Features
----------
User and Organisation Management
Operation Area & Border Management
Track cases and case positions
Track Spotters and Vehicles

Planed Features:
SMS Support for the Refugee App to keep connection alive







Contributing
---------------

You want to contribute to this project? Just write a mail to nic@transparency-everywhere.com. 



More...
--------
... docu can be found in **docu/**
