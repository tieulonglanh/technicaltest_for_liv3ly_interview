# technicaltest_for_liv3ly_interview
# README #

This README will guide you how to setup this project.

### Project description ###

* Project technical test for Liv3ly interview
* Version 1.0
* PHP 7.4
* Build wiht Laravel 8.x
* Database Mariadb 10.x

### How do I get set up? ###
1. Clone project
* git clone git@github.com:tieulonglanh/technicaltest_for_liv3ly_interview.git
* cd technicaltest_for_liv3ly_interview
2. Install project
* composer install
* cp .env.example .env
* php artisan key:generate
* create database name: technicaltestforliv3lyinterview
* update database config to match with your local machine config
* php artisan migrate
* php artisan passport:install
3. run test:
* vendor/bin/phpunit tests/Feature/.
4. run dev:
* php artisan serve


### Who do I talk to? ###

* Please contact tieulonglanh@gmail.com for more information
