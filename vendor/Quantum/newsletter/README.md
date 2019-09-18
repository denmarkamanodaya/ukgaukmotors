# Quantum Newsletter

To provide newsletter management

## Install

``` bash
"Quantum/newsletter" : "0.1.*"
```

## Usage

``` php
php artisan vendor:publish
php artisan migrate
```
## Env
Add to your ENV file
``` php
NEWSLETTER_QUEUE=true
```

## Cron
Add the following to app/kernel::schedule

```php
$schedule->command('quantum:newsletterSendShots')->everyFiveMinutes()->withoutOverlapping();
$schedule->command('quantum:newsletterSendResponders ')->everyThirtyMinutes()->withoutOverlapping();
$schedule->command('quantum:newsletterCountOpened ')->everyThirtyMinutes()->withoutOverlapping();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

