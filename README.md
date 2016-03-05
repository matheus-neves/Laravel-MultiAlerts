# laravel-multialerts

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A package for managing multiple types and levels of alerts in Laravel.

## Installation

To get the latest version of Laravel Multialerts, simply require the project using Composer.

``` bash
$ composer require gsmeira/laravel-multialerts
```

Instead, you may of course manually update your require block and run `composer update` if you so choose.

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

Just call the helper function `multialerts()` and start chaining.

An basic success alert.

``` php
multialerts()->success('The user was successfully created.')->put()
```

You can put the alert message in your language files.

``` php
multialerts()->success('users.successfully_created')->put()
```

If the alert message have placeholders, you can pass then just like in the default Laravel `trans()` helper function (actually, behind the scenes the `trans()` helper function is called). With this little abstraction, we get a clean and more readable code.

``` php
multialerts()->success('users.successfully_created', [ $user->name ])->put()
```

That's it! You can call `multialerts()` many times as needed to show all the alerts you want.

Available levels: `success`, `error`, `warning`, `info`

### Custom Fields

A very nice feature of Laravel Multialerts is the possibility of adding custom fields to your alert. (By default Laravel Multialerts have one field: `message`)

``` php
multialerts()->error('An unexpected error occurred during the creation process!')->tip('Please, try again later. If the problem persists contact the site administrator.')->put()
```

You can add as many custom fields you want, remembering that you must call the `put()` method in the end of the chaining.

PS: In the custom fields you can pass a pair (language key, placeholders) too.

### Multiple Types

In addition to the levels and custom fields, with Laravel Multialerts we can have various types of alerts.

``` php
multialerts('anothertype')->error('My error message....')->put()
```

When no type is passed, it is used the default value: `default`

### Storage

All alert examples used so far were stored in a flash session, but if we wanted to display the alerts when we return a view and not a redirect?

We can simple pass `false` to the `put()` method, so the alert will be shared with the view.

``` php
multialerts()->warning('You need confirm your email address')->put(false)
```

You can now access the alerts through the variable `$multialerts` (you can change de variable name in the configuration file if you want).

### Displaying

Now that we know how to create our alerts we will learn how to show them.

Get all flash session alerts accessing the default field `message`.

``` php
@foreach (multialerts()->all() as $level => $alerts)
    @foreach ($alerts as $alert)
        {{ $alert['message'] }}
    @endforeach
@endforeach
```

To get all shared view alerts just pass `false` in the `all()` method or access through the `$multialerts` variable.

``` php
multialerts()->all(false)
```

The benefit of using `multialerts()->all(false)` is that you don't need to test if the variable `$multialerts` exists.

Of course you can iterate different types of alerts too.

``` php
multialerts('anothertype')->all()
```

If you are trying to access a specific type of alert shared with the view just do the following.

``` php
$multialerts['anothertype']
```

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