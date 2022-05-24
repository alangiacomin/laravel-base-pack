# Laravel Base Pack

Base pack for Laravel application

It provides:
* CQRS and event sourcing
* Repositories
* Artisan commands

## Requirements

- PHP >= 8.1
- Laravel Application 9.x

## Installation

Install package
```
composer require alangiacomin/laravel-base-pack
```

Publish configuration
```
php artisan vendor:publish --provider=Alangiacomin\LaravelBasePack\LaravelBasePackServiceProvider
```

Update database tables 
```
php artisan migrate
```

_... to be continued_
