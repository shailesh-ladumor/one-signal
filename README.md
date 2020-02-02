# Laravel OneSignal

Laravel Wrapper for [One Signal](https://onesignal.com). One Signal is a great platform for send a push notification to your users.

[![Total Downloads](https://poser.pugx.org/ladumor/one-signal/downloads)](https://packagist.org/packages/ladumor/one-signal)
[![Daily Downloads](https://poser.pugx.org/ladumor/one-signal/d/daily)](https://packagist.org/packages/ladumor/one-signal)
[![Monthly Downloads](https://poser.pugx.org/ladumor/one-signal/d/monthly)](https://packagist.org/packages/ladumor/one-signal)
[![License](https://poser.pugx.org/ladumor/one-signal/license)](https://packagist.org/packages/ladumor/one-signal)
## Contents

- [Installation](#installation)
     - [Publish the config file](#Publish the config file)
     - [Add Provider](#Add Provider)
     - [Add Facade](#Add Facade)
- [Usage](#Usage)
    - [Send Push Notification](#Send Push Notification)
    - [Customise Contents](#Customise Contents)
    - [Get All Notifications](#Get All Notifications)
    - [Get Single Notification](#Get Single Notification)
- [License](#License)

## Installation

Install the package by the following command,

    composer require ladumor/one-signal

## Publish the config file

Run the following command to publish config file,

    php artisan vendor:publish --provider="Ladumor\OneSignal\OneSignalServiceProvider"

## Add Provider

Add the provider to your `config/app.php` into `provider` section if using lower version of laravel,

    Ladumor\OneSignal\OneSignalServiceProvider::class,

## Add Facade

Add the Facade to your `config/app.php` into `aliases` section,

    'OneSignal' => \Ladumor\OneSignal\OneSignal::class,

## Usage

### Send Push Notification

For send push notification, use the sendPush method by calling,
    
    $fields['include_player_ids'] = ['xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy'];
    $message = 'hey!! this is test push.!'   
    
    \OneSignal::sendPush($fields, $message);
    
### Customise Contents

You can customise a contents and pass it in fields. message does not required when you pass contents
    
    $fields['include_player_ids'] = ['xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy'];
    $fields['contents'] = array(
                              "en" => 'English Message',
                              "es" => 'Spanish Message',
                          );
    \OneSignal::sendPush($fields);
### Get All Notifications

For retrieve all notifications, use the `getNotifications` method by calling,    
    
    OneSignal::getNotifications();
You can check [here](https://documentation.onesignal.com/reference#section-result-format-view-notifications) return response format. 
    
### Get Single Notification

For retrieve single notification, use the `getNotification` method with id param by calling, 
    
    OneSignal::getNotification($notificationId);    
You can check [here](https://documentation.onesignal.com/reference#section-result-format-view-notification) return response format.
 
### License
 The MIT License (MIT). Please see [License](LICENSE.md) File for more information   