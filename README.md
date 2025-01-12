# Laravel One Signal

Laravel One Signal is Laravel Wrapper for [One Signal](https://onesignal.com). One Signal is a great platform for send a
push notification to your users. This package mentions in One Signal's official Document. you can
see [here](https://documentation.onesignal.com/docs/other-cms-setup)

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

<a href="https://www.buymeacoffee.com/ladumor"><img src="https://img.buymeacoffee.com/button-api/?text=Buy me a coffee&emoji=&slug=ladumor&button_colour=BD5FFF&font_colour=ffffff&font_family=Cookie&outline_colour=000000&coffee_colour=FFDD00" /></a>

## Requirements.

v2.0.0 is released with new features and new APIs with latest version of one signal. this version is supported in
laravel 8 and more.
if you want to use this package in laravel 7 or lower version than you can use v1.0.0.

## Contents

- [Installation](#installation)
    - [Publish the config file](#publish-the-config-file)
    - [Add Provider](#add-provider)
    - [Add Facade](#add-facade)
- [Usage](#usage)
    - Messages
        - [Send Notification](#send-notification)
            - [push](#push)
            - [email](#email)
            - [sms](#sms)
            - [Customise Contents](#customise-contents)
        - [Get Single Notification](#get-single-notification)
        - [Get All Notifications](#get-all-notifications)
        - [Cancel Notification](#cancel-notification)
        - [Notification History](#notification-history)
    - Live Activities
        - [Start Live Activity](#start-live-activity)
        - [Update Live Activity](#update-live-activity)
    - User
        - [Create User](#create-user)
        - [View User](#view-user)
        - [Update User](#update-user)
        - [Delete User](#delete-user)
        - [View User Identity](#view-user-identity)
        - [View User Identity By Sponsorship](#view-user-identity-by-sponsorship)
        - [Create Alias](#create-alias)
        - [Create Alias by subscription](#create-alias-by-subscription)
        - [Delete Alias](#delete-alias)
    - Subscription
        - [Create Subscription](#create-subscription)
        - [Update Subscription](#update-subscription)
        - [Delete Subscription](#delete-subscription)
        - [Transfer Subscription](#transfer-subscription)
        - [Unsubscribe Notification](#unsubscribe-notification)
    - Template
        - [Create Template](#create-template)
        - [Update Template](#update-template)
        - [View Template](#view-template)
        - [View Templates](#view-templates)
        - [Delete Template](#delete-template)
        - [Copy Template](#copy-template)
    - Segment
        - [Create Segment (NEED PAID PLAN)](#create-segment)
        - [Delete Segment (NEED PAID PLAN)](#delete-segment)
    - Apps
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

Install the package by the following command

    composer require ladumor/one-signal:2.0.0

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

### Send Notification

#### Push

For send push notification, use the sendPush method by calling, you can refer
this [docs](https://documentation.onesignal.com/reference/create-message) for more fields details.

    $fields['include_aliases']['onesignal_id'] = ['xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy'];
    $message = 'hey!! this is test push.!'   
    
    OneSignal::sendPush($fields, $message);

Optionally, you can obtain the id of the notification like this,

    $notificationID = OneSignal::sendPush($fields, $message);
    echo $notificationID["id"];

#### SMS

For send SMS notification, use the sendSMS method by calling,

    $fields['include_aliases']['onesignal_id'] = ['xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy'];
    OR
    $fields['include_phone_numbers'] = ['+15558675310'];
    
    $fields['sms_from']= "+15558675309";

    $message = 'hey!! this is test SMS.!'   
    
    OneSignal::sendSMS($fields, $message);

#### EMAIL

Ensure your Email [setup](https://documentation.onesignal.com/docs/email-setup) is complete.
For send Email notification, use the sendEmail method by calling, see more payload
param [here](https://documentation.onesignal.com/reference/email#schedule-delivery)

    $fields = [
       'email_from_name' => 'Shailesh',
       'email_from_address' => "sml@gmail.com",
       'email_reply_to_address' => "reply@gmail.com",
       'email_subject' => "Welcome to Cat Facts",
       'email_body' => "html>Welcome to Cat Facts</html>",
       'disable_email_click_tracking' => true,
       'include_unsubscribed' => true
    ];
    
    $fields['include_aliases']['onesignal_id'] = ['xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy'];
    OR
    $fields['include_email_tokens'] = ['sample@gmail.com'];
    
    OneSignal::sendEmail($fields);

#### Customise Contents

You can customise a contents and pass it in fields. message does not required when you pass contents

    $fields['include_aliases']['onesignal_id'] = ['xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy'];
    or
    $fields['include_aliases']['external_id'] = ['xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy'];

    $fields['contents'] = array(
                              "en" => 'English Message',
                              "es" => 'Spanish Message',
                          );
    OneSignal::sendPush($fields);

### Get Single Notification

For retrieve single notification, use the `getNotification` method with id param by calling,

    OneSignal::getNotification($notificationId);    

You can check [here](https://documentation.onesignal.com/reference/view-message#response-data) return response format.

### Get All Notifications

For retrieve all notifications, use the `getNotifications` method by calling,

    OneSignal::getNotifications();

### Cancel Notification

To cancel a notification, use the cancelNotification method by calling,

    $notificationID = 'xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy';
    
    OneSignal::cancelNotification($notificationID);

You can check [here](https://documentation.onesignal.com/reference#section-result-format-view-notifications) return
response format.

### Notification History

For retrieve notification History, use the `getMessageHistory` method by calling,

    $notificationID = 'xxxxxxxx-xxxx-xxx-xxxx-yyyyyyyyy';
    $params = [
        'events' => 'sent', // required
        'email' => 'sample@gmail.com', // optional, he email address in which to deliver the report.
    ];

    OneSignal::getMessageHistory($notificationID, $params);

### Start Live Activity

This API lets you start a Live Activity by sending a Push Notification., use the `startLiveActivity` method by calling,
See for more params [here](https://documentation.onesignal.com/reference/start-live-activity#body-parameters)

    $params = [
        'activity_id' => '217aae2b-42ee-4097-bc3f-b7a6e9d15b9b'
    ];

    OneSignal::startLiveActivity($activityType, $params);

### Update Live Activity

Make updates and terminate running Live Activities, use the `updateLiveActivity` method by calling,
See for more params [here](https://documentation.onesignal.com/reference/update-live-activity-api#body-parameters)

```injectablephp
    $params = [
        'event' => 'update'
    ];

    OneSignal::updateLiveActivity($params);
```

### Create User

for Register a new user in OneSignal’s system, use the `createUser` method by calling,

```injectablephp

$params = [
        'properties' => [
            "tags" => [
                "foo"  => "bar",
                "this" => "that"
            ],
            "language"     =>  "en",
            "timezone_id"  =>  "America/Los_Angeles",
            "lat"          =>  37.7749,
            "long"         =>  -122.4194,
            "country"      =>  "US",
            "first_active" =>  1589788800,
            "last_active"  =>  1589788800,
            "purchases"    =>  0,
            "ip"           =>  3232235777
        ],
        'identity' = [
            "external_id"    => "An ID, defined by you, to refer to this user in the future",
            "facebook_id"    =>  "user_facebook_id",
            "amplitude_id"   =>  "user_amplitude_id",
            "mixpanel_id"    => "user_mixpanel_id",
            "custom_alias_N" =>  "An alternative ID you'll want for retrieving this user's profile data and tags"
        ],
        'subscriptions' => [
            [
                "type" => "Email",
                "token" =>  "sample@email.com",
                "enabled" => true,
            ],
            [
                "type" => "SMS",
                "token" =>  "phone_number_in_E.164_format",
                "enabled" => true,
            ],
            [
                 "type"=>  "iOSPush",
                 "token"=>  "20bdb8fb3bdadc1bef037eefcaeb56ad6e57f3241c99e734062b6ee829271b71",
                 "enabled"=>  true,
                 "notification_types"=>  1,
                 "session_time"=> 98,
                 "session_count"=> 6,
                 "sdk"=>  "",
                 "device_model"=>  "iPhone 14",
                 "device_os"=> "18.0",
                 "rooted"=>  false,
                 "test_type"=>  1,
                 "app_version"=>  "5.1.7",
                 "web_auth"=>  "",
                 "web_p256"=> : ""
            ]
        ];
    
    OneSignal::createUser($params);
```

### View User

for Retrieve a user including aliases, properties, and subscriptions, use the `viewUser` method by calling,

```injectablephp
    OneSignal::viewUser($onesignalId, $aliasId);
```

### Update User

for Update a user’s properties, tags, and subscriptions, use the `updateUser` method by calling,
for $params you can refer [here](https://documentation.onesignal.com/reference/update-user#body-parameters)

```injectablephp
    OneSignal::updateUser($onesignalId, $aliasId, $params);
```

### Delete User

Delete a user including all associated properties, subscriptions, and identity. use the `deleteUser` method by calling,

```injectablephp
    OneSignal::deleteUser($onesignalId, $aliasId);
```

### View User Identity

Retrieve a user’s identity, use the `viewUserIdentity` method by calling,

```injectablephp
    OneSignal::viewUserIdentity($onesignalId, $aliasId);
```

### View User Identity By Sponsorship

Retrieve a user’s identity by sponsorship, use the `viewUserIdentityBySponsorship` method by calling,

```injectablephp
    OneSignal::viewUserIdentityBySponsorship($subscriptionId);
```

### Create Alias

Create an alias for a user, use the `createAlias` method by calling,
Refer docs [here](https://documentation.onesignal.com/reference/create-alias#how-to-use-this-api) for more details

```injectablephp
    $params = [
        'external_id'  => "",
        'onesignal_id' => "",
    ];
    
    OneSignal::createAlias($onesignalId, $aliasId, $params);
```

### Create Alias by subscription

Create an alias for a user by subscription, use the `createAliasBySubscription` method by calling,
Refer docs [here](https://documentation.onesignal.com/reference/create-alias-by-subscription#how-to-use-this-api) for
more details

```injectablephp
    $params = [
        'external_id'  => "",
        'onesignal_id' => "",
    ];
    
    OneSignal::createAliasBySubscription($subscriptionId, $params);
```

### Delete Alias

Delete an alias for a user, use the `deleteAlias` method by calling,
Refer docs [here](https://documentation.onesignal.com/reference/delete-alias) for more details

```injectablephp
    OneSignal::deleteAlias($onesignalId, $aliasId, $aliasLabelToDelete);
```

### Create Subscription
Add a new subscription to your user. use the `createSubscription` method by calling,
Refer docs [here](https://documentation.onesignal.com/reference/create-subscription#examples) for more body params

```injectablephp
    $fields = [
        'subscription'  => [],
    ];
    
    OneSignal::createSubscription($fields);
```

### Update Subscription

Update an existing subscription, use the `updateSubscription` method by calling,
Refer docs [here](https://documentation.onesignal.com/reference/update-subscription) for more details

```injectablephp
     $fields = [
        'subscription'  => [],
    ];
    
    OneSignal::updateSubscription($subscriptionId, $fields);
```

### Delete Subscription

Delete an existing subscription, use the `deleteSubscription` method by calling,
Refer docs [here](https://documentation.onesignal.com/reference/delete-subscription) for more details

```injectablephp
    OneSignal::deleteSubscription($subscriptionId);
```

### Transfer Subscription

Transfer a subscription to a different user, use the `transferSubscription` method by calling,
Refer docs [here](https://documentation.onesignal.com/reference/transfer-subscription) for more details

```injectablephp
    $fields = [
        'identity'  => [],
    ];
    
    OneSignal::transferSubscription($subscriptionId, $fields);
```

### Unsubscribe Notification

Unsubscribe a user from a notification, use the `unsubscribeNotification` method by calling,
Refer docs [here](https://documentation.onesignal.com/reference/unsubscribe-with-token) for more details

```injectablephp
    OneSignal::unsubscribeNotification($notificationId, $token);
```

### Create Template

Create a template for push, email and sms, use the `createTemplate` method by calling,
Refer docs [here](https://documentation.onesignal.com/reference/create-template) for push, email and sms template

```injectablephp
    $fields = [
        'name'  => "",
        'content' => [
            "en": "English Message" 
        ],
        "sms_from"=>"+1234567890",
         "isSMS"=> true,
    ];
    
    OneSignal::createTemplate($fields);
```

### Update Template
update a template for push, email and sms, use the `updateTemplate` method by calling,
Refer docs [here](https://documentation.onesignal.com/reference/update-template#examples) for push, email and sms template

```injectablephp
    $fields = [
        'name'  => "",
        'content' => [
            "en": "English Message" 
        ],
        "sms_from"=>"+1234567890",
        "isSMS"=> true,
    ];
    
    OneSignal::updateTemplate($templateId, $fields);
```

### View Template
For retrieve a template, use the `viewTemplate` method by calling,

```injectablephp
    OneSignal::viewTemplate($templateId);
```

### View Templates
For retrieve all templates, use the `viewTemplates` method by calling,

```injectablephp
    $params = [
        'limit' => 10,
        'offset' => 0,
        'channel' => 'push',
     ];
     
    OneSignal::viewTemplates($params);
```

### Delete Template
For delete a template, use the `deleteTemplate` method by calling,

```injectablephp
    OneSignal::deleteTemplate($templateId);
```

### Copy Template
For transfer a template, use the `transferTemplate` method by calling,

```injectablephp
    $fields = [
        'target_app_id' => "",
    ];
    
    OneSignal::copyTemplate($templateId, $fields);
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

Note*: `Auth key must be set in one-signal.php` how to
get [auth_key](https://documentation.onesignal.com/docs/accounts-and-keys#section-user-auth-key)?

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

### Get All Devices

***This method @deperecated by onesingnal.***

For retrieve all user devices, use the `getDevices` method by calling,

    OneSignal::getDevices();

You can check [here](https://documentation.onesignal.com/reference#view-devices) return response format.

### Get Single Device

***This method @deperecated by onesingnal.***

For retrieve single Devices, use the `getDevice` method with id param by calling,

    OneSignal::getDevice($deviceId);    

You can check [here](https://documentation.onesignal.com/reference#view-device) return response format.

### Create Device

***This method @deperecated by onesingnal.***

For add a device in your application, use the `addDevice` method by calling, if you want to create device in different
application than you can specify `app_id` in `$fields` array.

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

You can check [here](https://documentation.onesignal.com/reference#section-example-code-add-a-device) supported
parameters and guide.

### Update Device

***This method @deperecated by onesingnal.***

For update a device in your application, use the `addDevice` method by calling, if you want to update device in
different application than you can specify `app_id` in `$fields` array.

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

You can check [here](https://documentation.onesignal.com/reference#section-body-parameters) supported parameters and
guide.

### Delete Device

***This method @deperecated by onesingnal.***

Delete existing device on your application

```injectablephp
OneSignal::deleteDevice($deviceId);
```

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
