SEA WATCH APP
===================

About this Project
---------------------
This Application shall be used to organize emergency calls to a coordination center which can send the emergency calls to a rescue team.

This repo contains the backend and the app files.
The backend is build with [Laravel 5](https://github.com/laravel/laravel) and is based on [Laravel Framework 5.1 Bootstrap 3 Starter Site](https://github.com/mrakodol/Laravel-5-Bootstrap-3-Starter-Site).
It is located in the **admin/** directory

There will be two mobile apps, one for refugees and the other one for SAR Teams the app for distress calls. Both apps are build with Angular and wrapped with [ionic](ionicframework.com/) to support as many systems as possible. The apps are located in the **app_refugee/** & **app_spotter/** directories.

The curent apps in app_spotter and app_refugee will be reimplmented with angular and ionic and will be found seperate repos soon.


Requirements
----------------
(Same as Laravel 5 Bootrap Starter)

- PHP >= 5.5.9
- OpenSSL PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- MySQL



Getting Started
------------------

1. Download the Source Code:

    https://github.com/sea-watch/sea-watch-app/archive/master.zip and unzip it in your www or htdocs folder.

2. Create a Database on your Server with uft8_general_c

3. Update the .env file to your local settings

3. Go to the root directory and type 'php artisan migrate'.



Features
----------
User and Organisation Management
Operation Area & Border Management
Track cases and case positions

Planed Features:
SMS Support for the Refugee App to keep connection alive







Contributing
---------------

You want to contribute to this project? Just write a mail to nic@transparency-everywhere.com. 



More...
--------
... docu can be found in **docu/**
