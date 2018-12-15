# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Installation
* Install apache.
* Install PHP.

Be sure the PHP installation meets the [requirements](https://lumen.laravel.com/docs/5.4) 

* Install MySQL-server and create a database and user with rights on the database for the cls-api.
* Clone the [repository](https://github.com/yokovaski/energy_api.git) with:
`git clone https://github.com/yokovaski/energy_api.git`.
* Change to the energy_api directory and run `composer install`.
* Copy the `.env.example` file and change it's filename to `.env`. Set the correct parameters
* Initiate the database with: `php artisan migrate:refresh --seed`. The database will be seeded with mockdata when using
`--seed`. If you do not want to use this functionality, no admin user will be set.
* Initiate OAuth with: `php artisan passport:install`.
* Add the energy_api to the apache configuration and restart apache.