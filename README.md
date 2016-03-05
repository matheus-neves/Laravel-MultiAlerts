# laravel-multialerts

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A package for managing multiple types and levels of alerts in Laravel.

## Install

To get the latest version of Laravel Multialerts, simply require the project using Composer:

``` bash
$ composer require gsmeira/laravel-multialerts
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

``` bash
{
    "require": {
        "gsmeira/laravel-multialerts": "^0.0.1"
    }
}
```

Once Laravel Multialerts is installed, you need to register the service provider. Open up `config/app.php` and add the following to the providers key.

``` php
'GSMeira\LaravelMultialerts\ServiceProvider'
```

You can register the Laravel Multialerts facade in the aliases key of your config/app.php file if you like.

``` php
'Multialerts' => 'GSMeira\LaravelMultialerts\Facade'
```

In the last step of the installation, you must publish the configuration file.

``` bash
$ php artisan vendor:publish
```

This will create a `config/multialerts.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

## Usage

soon...

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

soon...

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email gustavo@gsmeira.com instead of using the issue tracker.

## Credits

- [Gustavo Meireles][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/gsmeira/laravel-multialerts.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/gsmeira/laravel-multialerts/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/gsmeira/laravel-multialerts.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/gsmeira/laravel-multialerts.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/gsmeira/laravel-multialerts.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/gsmeira/laravel-multialerts
[link-travis]: https://travis-ci.org/gsmeira/laravel-multialerts
[link-scrutinizer]: https://scrutinizer-ci.com/g/gsmeira/laravel-multialerts/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/gsmeira/laravel-multialerts
[link-downloads]: https://packagist.org/packages/gsmeira/laravel-multialerts
[link-author]: https://github.com/gsmeira
[link-contributors]: ../../contributors