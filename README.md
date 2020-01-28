# Laravel OneSignal

Laravel Wrapper for [oneSignal](https://onesignal.com).

[![Total Downloads](https://poser.pugx.org/ladumor/one-signal/downloads)](https://packagist.org/packages/ladumor/one-signal)
[![Daily Downloads](https://poser.pugx.org/ladumor/one-signal/d/daily)](https://packagist.org/packages/ladumor/one-signal)
[![Monthly Downloads](https://poser.pugx.org/ladumor/one-signal/d/monthly)](https://packagist.org/packages/ladumor/one-signal)
[![License](https://poser.pugx.org/ladumor/one-signal/license)](https://packagist.org/packages/ladumor/one-signal)

## Installation

Install the package by the following command,

    composer require Ladumor/one-signal

## Publish the config file

Run the following command to publish config file,

    php artisan vendor:publish --provider="ladumor\OneSignal\OneSignalServiceProvider"

## Add Facade

Add the Facade to your `config/app.php` into `aliases` section,

    'OneSignal' => \Ladumor\OneSignal\OneSignal::class,

## Usage

### Send Push

For send push notification, use the sendPush method by calling,
    
    $fields['include_player_ids'] = ['xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy'];
    $message = 'hey!! this is test push.!'   
    
    \OneSignal::sendPush($fields, $message);