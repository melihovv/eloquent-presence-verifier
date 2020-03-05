# Eloquent Presence Verifier

![GitHub Workflow Status](https://img.shields.io/github/workflow/status/melihovv/eloquent-presence-verifier/run-php-tests?label=Tests)
[![styleci](https://styleci.io/repos/103585916/shield)](https://styleci.io/repos/103585916)

[![Packagist](https://img.shields.io/packagist/v/melihovv/eloquent-presence-verifier.svg)](https://packagist.org/packages/melihovv/eloquent-presence-verifier)
[![Packagist](https://poser.pugx.org/melihovv/eloquent-presence-verifier/d/total.svg)](https://packagist.org/packages/melihovv/eloquent-presence-verifier)
[![Packagist](https://img.shields.io/packagist/l/melihovv/eloquent-presence-verifier.svg)](https://packagist.org/packages/melihovv/eloquent-presence-verifier)

Perform presence verification through eloquent Model class instead of DB facade.

## Motivation

Awesome package for database query caching [spiritix/lada-cache](https://github.com/spiritix/lada-cache) demands
that all database queries should be ran from Eloquent Model subclasses which has `LadaCacheTrait`. But if you use
some of the following validations rules `exists:users,id` or `unique:users,email` Laravel run queries through `DB`
facade.

## Installation

Install via composer
```
composer require melihovv/eloquent-presence-verifier
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
Melihovv\EloquentPresenceVerifier\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
Melihovv\EloquentPresenceVerifier\Facades\EloquentPresenceVerifier::class,
```

### Publish Configuration File

```
php artisan vendor:publish --provider="Melihovv\EloquentPresenceVerifier\ServiceProvider" --tag="config"
```

In this config you can specify custom model though which all queries will be send.
For `spiritix/lada-cache` users:

- create `App\Models\TempModel`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempModel extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
}
```

- specify created model in `config/eloquent-presence-verifier`
```php
return [
    'model' => \App\Models\TempModel::class,
];
```

## Usage

You don't need to do anything. All is done in this package service provider: EloquentPresenceVerifier is set as default
presence verificator instead of DatabasePresenceVerifier.

## Security

If you discover any security related issues, please email amelihovv@ya.ru
instead of using the issue tracker.

## Credits

- [Alexander Melihov](https://github.com/melihovv/eloquent-presence-verifier)
- [All contributors](https://github.com/melihovv/eloquent-presence-verifier/graphs/contributors)

This package is bootstrapped with the help of [melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
