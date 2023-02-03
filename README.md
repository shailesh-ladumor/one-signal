# Laravel One Signal

Laravel One Signal is Laravel Wrapper for [One Signal](https://onesignal.com). One Signal is a great platform for send a push notification to your users. This package mentions in One Signal's official Document. you can see [here](https://documentation.onesignal.com/docs/other-cms-setup)

[![Total Downloads](https://poser.pugx.org/ladumor/one-signal/downloads)](https://packagist.org/packages/ladumor/one-signal)
[![Daily Downloads](https://poser.pugx.org/ladumor/one-signal/d/daily)](https://packagist.org/packages/ladumor/one-signal)
[![Monthly Downloads](https://poser.pugx.org/ladumor/one-signal/d/monthly)](https://packagist.org/packages/ladumor/one-signal)
[![License](https://poser.pugx.org/ladumor/one-signal/license)](LICENSE.md)

# Give a Star if this package realy usefull to you. it's free :laughing:

## :film_strip: here are video tutorials
#### How to install and how to implement notifications and devices APIs.

 [<img src="https://img.youtube.com/vi/c1W7unOw3s4/0.jpg" width="250">](https://youtu.be/c1W7unOw3s4)

#### how to implement Segment and Apps APIs.
 [<img src="https://img.youtube.com/vi/mxiEZ4H2cgY/0.jpg" width="250">](https://youtu.be/mxiEZ4H2cgY)
 
 
## Contents

- [Installation](#installation)
    - [Publish the config file](#publish-the-config-file)
    - [Add Provider](#add-provider)
    - [Add Facade](#add-facade)
- [Usage](#usage)
    - [Send Push Notification](#send-push-notification)
    - [Cancel Notification](#cancel-notification)
    - [Customise Contents](#customise-contents)
    - [Get All Notifications](#get-all-notifications)
    - [Get Single Notification](#get-single-notification)
    - [Get All Devices](#get-all-devices)
    - [Get Single Device](#get-single-device)
    - [Create Device](#get-single-device)
    - [Update Device](#update-device)
    - [Delete Device](#delete-device)
    - [Create Segment (NEED PAID PLAN)](#create-segment)
    - [Delete Segment (NEED PAID PLAN)](#delete-segment)
    - [View Apps](#view-apps)
    - [View App](#view-app)
    - [Create App](#create-app)
    - [Update App](#update-app)
    - [View Outcomes](#view-outcomes)
- [User Device](#user-device)
- [Change Log](#change-log)
- [License](#license)

## Watch Other Lavavel tutorial here
[<img src="https://img.youtube.com/vi/yMtsgBsqDQs/0.jpg" width="580">](https://www.youtube.com/channel/UCuCjzuwBqMqFdh0EU-UwQ-w?sub_confirmation=1))

## Installation

Install the package by the following command,

    composer require ladumor/one-signal:0.4.4


## Publish the config file

Run the following command to publish config file,

    php artisan vendor:publish --provider="Ladumor\OneSignal\OneSignalServiceProvider"


## Add Provider

Add the provider to your `config/app.php` into `provider` section if using lower version of laravel,

    Ladumor\OneSignal\OneSignalServiceProvider::class,


## Add Facade

Add the Facade to your `config/app.php` into `aliases` section,

    'OneSignal' => \Ladumor\OneSignal\OneSignal::class,
    

## Add ENV data

Add your api keys and OneSignal app id to your `.env`,

    ONE_SIGNAL_APP_ID=XXXXXX-XXXXXX-XXXXXX-XXXXXX  (YOUR APP ID)
    ONE_SIGNAL_AUTHORIZE=XXXXXX                    (REST API KEY)
    ONE_SIGNAL_AUTH_KEY=XXXXXXX                    (YOUR USER AUTH KEY)

You can call them into your code with,

    

## Usage

### Send Push Notification

For send push notification, use the sendPush method by calling,
    
    $fields['include_player_ids'] = ['xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy'];
    $message = 'hey!! this is test push.!'   
    
    OneSignal::sendPush($fields, $message);

Optionally, you can obtain the id of the notification like this,
    
    $notificationID = OneSignal::sendPush($fields, $message);
    echo $notificationID["id"];
    
    
### Cancel Notification

To cancel a notification, use the cancelNotification method by calling,
    
    $notificationID = 'xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy';
    
    OneSignal::cancelNotification($notificationID);


### Customise Contents

You can customise a contents and pass it in fields. message does not required when you pass contents
    
    $fields['include_player_ids'] = ['xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy'];
    $fields['contents'] = array(
                              "en" => 'English Message',
                              "es" => 'Spanish Message',
                          );
    OneSignal::sendPush($fields);
    
    
### Get All Notifications

For retrieve all notifications, use the `getNotifications` method by calling,    
    
    OneSignal::getNotifications();
You can check [here](https://documentation.onesignal.com/reference#section-result-format-view-notifications) return response format. 
    

### Get Single Notification

For retrieve single notification, use the `getNotification` method with id param by calling, 
    
    OneSignal::getNotification($notificationId);    
You can check [here](https://documentation.onesignal.com/reference#section-result-format-view-notification) return response format.


### Get All Devices

For retrieve all user devices, use the `getDevices` method by calling,    
    
    OneSignal::getDevices();
You can check [here](https://documentation.onesignal.com/reference#view-devices) return response format. 


### Get Single Device

For retrieve single Devices, use the `getDevice` method with id param by calling, 
    
    OneSignal::getDevice($deviceId);    
You can check [here](https://documentation.onesignal.com/reference#view-device) return response format.


### Create Device

For add a device in your application, use the `addDevice` method by calling, if you want to create device in different application than you can specify `app_id` in `$fields` array.
        
     $fields = [
            'device_type'  => 0,
            'identifier'   => '7abcd558f29d0b1f048083e2834ad8ea4b3d87d8ad9c088b33c132706ff445f0',
            'timezone'     => '-28800',
            'game_version' => '1.1',
            'device_os'    => '7.0.4',
            'test_type'    => 1,
            'device_model' => "iPhone 8,2",
            'tags'         => array("foo" => "bar")
        ];
        
     return OneSignal::addDevice($fields);   
You can check [here](https://documentation.onesignal.com/reference#section-example-code-add-a-device) supported parameters and guide.


### Update Device

For update a device in your application, use the `addDevice` method by calling, if you want to update device in different application than you can specify `app_id` in `$fields` array.
        
     $fields = [
            'device_type'  => 0,
            'identifier'   => '7abcd558f29d0b1f048083e2834ad8ea4b3d87d8ad9c088b33c132706ff445f0',
            'timezone'     => '-28800',
            'game_version' => '1.1',
            'device_os'    => '7.0.4',
            'test_type'    => 1,
            'device_model' => "iPhone 8,2",
            'tags'         => array("foo" => "bar")
        ];
        
     $playerId = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        
     return OneSignal::updateDevice($fields, $playerId);   
You can check [here](https://documentation.onesignal.com/reference#section-body-parameters) supported parameters and guide.

### Delete Device
Delete existing device on your application

```injectablephp
OneSignal::deleteDevice($deviceId);
```

### Create Segment
 #### NOTE: REQUIRED ONE-SIGNAL [PAID PLAN](https://documentation.onesignal.com/docs/paid-plan-benefits)
For add a new segment in your application, use the `createSegment` method by calling,
       
     $fields = [
             'name' => 'iOS, Android, Web',
             "filters" => array("field" => "device_type", "relation" => "=", "value" => "Android"),
         ];
    
    return OneSignal::createSegment($fields); 

You can check [here](https://documentation.onesignal.com/reference#create-segments) supported parameters and guide.

    OneSignal::deleteSegment('YOUR_SEGMENT_ID')
    

### Delete Segment
 #### NOTE: REQUIRED ONE-SIGNAL [PAID PLAN](https://documentation.onesignal.com/docs/paid-plan-benefits)

You can check [here](https://documentation.onesignal.com/reference#delete-segments) for more guide.
## Apps
Note*: `Auth key must be set in one-signal.php` how to get [auth_key](https://documentation.onesignal.com/docs/accounts-and-keys#section-user-auth-key)?


### View Apps
View the details of all of your current OneSignal apps

     $apps = OneSignal::getApps();
     
You can check [here](https://documentation.onesignal.com/reference#view-apps-apps) api response.


### View App
View the details of single of your current OneSignal app or other app by passing app id.

     // It's return default site which is configured in config.
     $app = OneSignal::getApp();
     
     // you can specify app id as wel but it's optional
     $app = OneSignal::getApp('YOUR_APP_ID');
     
     
You can check [here](https://documentation.onesignal.com/reference#view-an-app) api response.


### Create App
Creates a new OneSignal app.
 
     $fields = array(
            'name' => "TestByMe"
        );
    
     OneSignal::createApp($fields);
 
You can check [here](https://documentation.onesignal.com/reference#create-an-app) supported parameters and guide.


### Update App
Update a new OneSignal app.

     $fields = array(
            'name' => "TestByMe"
        );
    
     OneSignal::updateApp($fields);
     // you can pass second param as a appId if you want to update other app.. default take from config.

You can check [here](https://documentation.onesignal.com/reference#update-an-app) supported parameters and guide.



### View Outcomes
Update a new OneSignal app.

     $fields = array(
            'outcome_names'       => "os__click.count",
            'outcome_time_range'  => '1h',
            'outcome_platform'    => 0,
            'outcome_attribution' => 'direct'
        );

     OneSignal::getOutcomes($fields);   // with params
     OneSignal::getOutcomes();  // without any params
     // you can pass params in this method, it's optional.

You can check [here](https://documentation.onesignal.com/reference/view-outcomes) supported parameters and guide.


## User Device
[<img src="https://img.youtube.com/vi/wOH1qsQ3SL8/0.jpg" width="250">](https://youtu.be/wOH1qsQ3SL8)

You can generate a User Device APIs with just one command,

```injectablephp
php artisan one-signal.userDevice:publish
```

this command generate following files,

* UserDeviceAPIController
* UserDeviceAPIRepository
* UserDevice (model)
* Migration

Also, do not forget to add following routes in to the `api.php` file.

```injectablephp
use App\Http\Controllers\API\UserDeviceAPIController;
```

```injectablephp
  Route::post('user-device/register', [UserDeviceAPIController::class, 'registerDevice']);
  Route::get('user-device/{playerId}/update-status', [UserDeviceAPIController::class, 'updateNotificationStatus']);
```

### Change Log
 Please see [Change Log](CHANGELOG.md) here
### License
 The MIT License (MIT). Please see [License](LICENSE.md) File for more information   
