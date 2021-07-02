# REST API

REST API to manage books

# Prerequisite

Composer : https://getcomposer.org/  
Symfony CLI : https://symfony.com/download

# Installation

1. Clone the git repo
2. Install dependencies : `composer install`
3. Create Database : `php bin/console doctrine:database:create`
4. Launch migrations :  `php bin/console doctrine:migrations:migrate`
5. Launch fixtures : `php bin/console doctrine:fixtures:load`
6. Start the server : `composer start-server` or `symfony server:start`
7. The app should be available at http://127.0.0.1:8000/

# Available tools/information

* Insomnia export to test the api available at `resource/Insomnia export.json`
* PHPStan : `composer phpstan`
* PHPUnit : `vendor/bin/phpunit`