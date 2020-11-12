# technicaltest_for_liv3ly_interview
# README #

This README will guide you how to setup this project.

### Project description ###

* Project technical test for Liv3ly interview
* Version 1.0
* Build wiht Laravel 8.x
* Database Mariadb 10.x

### How do I get set up? ###

* Install project
- composer install
- cp .env.example .env
- create database name: technicaltestforliv3lyinterview
- update database config to match with your local machine config
- php artisan migrate
- php artisan passport:install

* run test:
- vendor/bin/phpunit tests/Feature/.


### Who do I talk to? ###

* Please contact tieulonglanh@gmail.com for more information
