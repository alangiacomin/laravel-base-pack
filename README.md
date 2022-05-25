# Laravel Base Pack

Base pack for Laravel application

It provides:
* CQRS and event sourcing
* Repositories
* Artisan commands
* React frontend

## Requirements

- PHP >= 8.1
- Laravel Application 9.x

## Installation

Before installing package, database configuration must be set in ```.env``` file.

### Install package

Execute command
```
composer require alangiacomin/laravel-base-pack
php artisan basepack:install
```

### Install React frontend

Execute command
```
php artisan basepack:react
npm run dev
```

Edit ```routes/web.php```
```php
Route::fallback(function () {
    return view('react');
});
```

## Create ...

_... to be continued_
