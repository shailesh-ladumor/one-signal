<?php

namespace Ladumor\OneSignal;

// end point
use InvalidArgumentException;

define("NOTIFICATIONS", "notifications");
define("DEVICES", "players");
define("APPS", "apps");
define("SEGMENTS", "segments");
define("USERS", "users");
define("USERS_BY", "users/by");
define("SUBSCRIPTIONS", "subscriptions");

/**
 * Class OneSignalManager
 */
class OneSignalManager extends OneSignalClient
{

    /**
     * OneSignalManager constructor.
     */
    public function __construct()
    {
        $this->initConfig();
    }

    /**
     * Set up required configuration
     */
    protected function initConfig(): void
    {
        $this->setUrl(config('one-signal.url'));
        $this->setAppId(config('one-signal.app_id'));
        $this->setAuthorization(config('one-signal.authorize'));
        $this->setAuthKey(config('one-signal.auth_key'));
        $this->setMutableContent(config('one-signal.mutable_content'));
    }

    /**
     * Send a Push Notification to user on device
     *
     * @param array $fields
     * @param string $message
     *
     * @return array|mixed
     */
    public function sendPush(array $fields, string $message): mixed
    {
        $content = [
            "en" => $message,
        ];

        $fields['app_id'] = $this->getAppId();
        $fields['mutable_content'] = $this->getMutableContent();

        if (empty($fields['contents'])) {
            $fields['contents'] = $content;
        }

        return $this->post($this->getUrl(NOTIFICATIONS, ['c' => 'push']), json_encode($fields));
    }

    /**
     * Send an Email to users
     *
     * @param array $fields
     *
     * @return array|mixed
     */
    public function sendEmail(array $fields)
    {
        $fields['app_id'] = $this->getAppId();

        if (empty($fields['email_subject'])) {
            throw new InvalidArgumentException('email_subject is required');
        }

        if (empty($fields['email_body'])) {
            throw new InvalidArgumentException('email_body is required');
        }

        return $this->post($this->getUrl(NOTIFICATIONS, ['c' => 'email']), json_encode($fields));
    }

    /**
     * Send an SMS to users
     *
     * @param array $fields
     * @param string $message
     *
     * @return array|mixed
     */
    public function sendSMS(array $fields, string $message = ''): mixed
    {
        $fields['app_id'] = $this->getAppId();
        $content = [
            "en" => $message,
        ];

        if (empty($fields['contents'])) {
            $fields['contents'] = $content;
        }

        return $this->post($this->getUrl(NOTIFICATIONS, ['c' => 'sms']), json_encode($fields));
    }

    /**
     * GET all notifications/messages of any applications.
     *
     * @param array $params
     *
     * @return array|mixed
     */
    public function viewMessages(array $params = []): mixed
    {
        $url = $this->getUrl(NOTIFICATIONS) . '?app_id=' . $this->getAppId();

        if (count($params) > 0) {
            $url = $url . join('&', $params);
        }

        return $this->get($url);
    }

    /**
     * Get Single notification/message
     *
     * @param string $notificationId
     *
     * @return object
     */
    public function getMessage(string $notificationId): object
    {
        $url = $this->getUrl(NOTIFICATIONS) . '/' . $notificationId . "?app_id=" . $this->getAppId();

        return $this->get($url);
    }

    /**
     * @param string $notificationId
     * @param null $appId
     *
     * @return array|mixed
     */
    public function cancelNotification(string $notificationId, $appId = null): mixed
    {
        if (empty($appId)) { // take a default if does not specified
            $appId = $this->getAppId();
        }

        return $this->delete($this->getUrl(NOTIFICATIONS . '/' . $notificationId . '?app_id=' . $appId));
    }

    /**
     * Get Message History
     * Query params are optional and supported param email,events
     *
     * @param string $notificationId - notification id
     * @param array $params - query params (optional)
     *
     * @return object|array|mixed
     */
    public function getMessageHistory(string $notificationId, array $params = []): object
    {
        if (empty($notificationId)) {
            throw new InvalidArgumentException('Notification id is required');
        }

        if (empty($fields['events'])) {
            throw new InvalidArgumentException('events is required. Ex. "sent", "clicked"');
        }

        $partUrl = DIRECTORY_SEPARATOR . $notificationId . "/history?app_id=" . $this->getAppId();
        $url = $this->getUrl(NOTIFICATIONS . $partUrl, $params);

        return $this->get($url);
    }

    /**
     * Create Segment
     *
     * @param $fields
     * @param null $appId
     *
     * @return array|mixed
     */
    public function createSegment($fields, $appId = null): mixed
    {
        if (empty($appId)) { // take a default if does not specified
            $appId = $this->getAppId();
        }

        return $this->post($this->getUrl(APPS . '/' . $appId . '/' . SEGMENTS), json_encode($fields));
    }

    /**
     * @param string $segmentId
     * @param null $appId
     *
     * @return array|mixed
     */
    public function deleteSegment(string $segmentId, $appId = null): mixed
    {
        if (empty($appId)) { // take a default if does not specified
            $appId = $this->getAppId();
        }

        return $this->delete($this->getUrl(APPS . '/' . $appId . '/' . SEGMENTS . '/' . $segmentId));
    }

    /**
     * View segments for an app
     *
     * @param string|null $appId
     *
     * @return array|mixed
     */
    public function viewSegments(int $limit = 0, $offset = 0, string $appId = null): mixed
    {
        if (empty($appId)) {
            $appId = $this->getAppId();
        }

        $url = $this->getUrl(APPS . '/' . $appId . '/' . SEGMENTS);

        // Set query params here
        if ($limit > 0) {
            $url .= '?limit=' . $limit;
            if ($offset > 0) {
                $url .= '&offset=' . $offset;
            }
        }

        return $this->get($url);
    }

    /**
     * GET all apps of your one signal.
     *
     * @return array|mixed
     */
    public function getApps(): mixed
    {
        $this->setAuthorization($this->getAuthKey());

        $url = $this->getUrl(APPS);

        return $this->get($url);
    }

    /**
     * GET single app of your one signal.
     *
     * @param string|null $appId
     *
     * @return array|mixed
     */
    public function getApp(string $appId = null): mixed
    {
        $this->setAuthorization($this->getAuthKey());

        $url = $this->getUrl(APPS . '/' . $appId);

        return $this->get($url);
    }

    /**
     * Add new application on your one signal.
     *
     * @param array $fields
     *
     * @return array|mixed
     */
    public function createApp(array $fields): mixed
    {
        $this->setAuthorization($this->getAuthKey());

        return $this->post($this->getUrl(APPS), json_encode($fields));
    }

    /**
     * Update existing application on your one signal.
     *
     * @param array $fields
     * @param string|null $appId
     *
     * @return array|mixed
     */
    public function updateApp(array $fields, string $appId = null): mixed
    {
        $this->setAuthorization($this->getAuthKey());

        if (empty($appId)) { // take a default if does not specified
            $appId = $this->getAppId();
        }

        return $this->put($this->getUrl(APPS . '/' . $appId), json_encode($fields));
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array|mixed
     * @deprecated
     * GET all devices of any applications.
     *
     */
    public function getDevices(int $limit = 50, int $offset = 0): mixed
    {
        $url = $this->getUrl(DEVICES) . '?app_id=' . $this->getAppId() . '&limit=' . $limit . '&offset=' . $offset;

        return $this->get($url);
    }

    /**
     * @param string $playerId
     *
     * @return object
     * @deprecated
     * Get Single Device information
     *
     */
    public function getDevice(string $playerId): object
    {
        $url = $this->getUrl(DEVICES) . '/' . $playerId . "?app_id=" . $this->getAppId();

        return $this->get($url);
    }

    /**
     * @param array $fields
     *
     * @return array|mixed
     * @deprecated
     * Add new device on your application
     *
     */
    public function addDevice(array $fields): mixed
    {
        if (empty($fields['app_id'])) {
            $fields['app_id'] = $this->getAppId();
        }

        if (empty($fields['language'])) {
            $fields['language'] = "en";
        }

        return $this->post($this->getUrl(DEVICES), json_encode($fields));
    }

    /**
     * @param array $fields
     * @param string $playerId
     *
     * @return array|mixed
     * @deprecated
     * update existing device on your application
     *
     */
    public function updateDevice(array $fields, string $playerId): mixed
    {
        if (empty($fields['app_id'])) {
            $fields['app_id'] = $this->getAppId();
        }

        if (empty($fields['language'])) {
            $fields['language'] = "en";
        }

        return $this->put($this->getUrl(DEVICES) . '/' . $playerId, json_encode($fields));
    }

    /**
     * @param string $playerId
     *
     * @return array|mixed
     * @deprecated
     * delete existing device on your application
     *
     */
    public function deleteDevice(string $playerId): mixed
    {
        $url = $this->getUrl(DEVICES) . '/' . $playerId . '?app_id=' . $this->getAppId();

        return $this->delete($url);
    }

    /**
     * GET all outcomes of any applications.
     * Outcomes are only accessible for around 30 days
     *
     * @param array $params
     *
     * @return array|mixed
     */
    public function getOutcomes(array $params = []): mixed
    {
        $url = $this->getUrl(APPS . '/outcomes' . $this->getAppId(), $params);

        return $this->get($url);
    }

    /** This API lets you start a Live Activity by sending a Push Notification.
     * @param string $activityType
     * @param array $fields
     *
     * @return mixed
     */
    public function startLiveActivity(string $activityType, array $fields): mixed
    {
        $url = $this->getUrl(APPS . '/' . $this->getAppId() . '/activities/activity/' . $activityType);
        return $this->post($url, json_encode($fields));
    }

    /** Make updates and terminate running Live Activities.
     *
     * @param string $activityId
     * @param array $fields
     *
     * @return mixed
     */
    public function updateLiveActivity(string $activityId, array $fields): mixed
    {
        if (empty($fields['event'])) {
            throw new InvalidArgumentException('event is required. for example: "end", "update"');
        }

        $url = $this->getUrl(APPS . '/' . $this->getAppId() . '/live_activities/' . $activityId . '/notifications');
        return $this->post($url, json_encode($fields));
    }
    /**
     * Create a new user.
     *
     * @param array $fields
     *
     * @return mixed
     */
    public function createUser(array $fields): mixed
    {
        $url = $this->getUrl(USERS);
        return $this->post($url, json_encode($fields));
    }

    /**
     * View user details including aliases, properties, and subscriptions
     *
     * @param string $aliasLabel The type of alias (e.g. 'external_id', 'onesignal_id')
     * @param string $aliasId The value of the alias
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function viewUser(string $aliasLabel, string $aliasId): mixed
    {
        if (empty($aliasLabel)) {
            throw new InvalidArgumentException('alias_label is required');
        }

        if (empty($aliasId)) {
            throw new InvalidArgumentException('alias_id is required');
        }


        $url = $this->getUrl(APPS . '/' . $this->getAppId() . '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId);
        return $this->get($url);
    }

    /**
     * Update an existing user.
     *
     * @param string $aliasLabel The type of alias (e.g. 'external_id', 'onesignal_id')
     * @param string $aliasId The value of the alias
     * @param array $fields The fields to update
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function updateUser(string $aliasLabel, string $aliasId, array $fields): mixed
    {
        if (empty($aliasLabel)) {
            throw new InvalidArgumentException('alias_label is required');
        }

        if (empty($aliasId)) {
            throw new InvalidArgumentException('alias_id is required');
        }

        $url = $this->getUrl(APPS . '/' . $this->getAppId() . '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId);
        return $this->patch($url, json_encode($fields));
    }


    /**
     * Delete a user including all associated properties, subscriptions, and identity.
     *
     * @param string $aliasLabel The type of alias (e.g. 'external_id', 'onesignal_id')
     * @param string $aliasId The value of the alias
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function deleteUser(string $aliasLabel, string $aliasId): mixed
    {
        if (empty($aliasLabel)) {
            throw new InvalidArgumentException('alias_label is required');
        }

        if (empty($aliasId)) {
            throw new InvalidArgumentException('alias_id is required');
        }

        $url = $this->getUrl(APPS . '/' . $this->getAppId() . '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId);

        return $this->delete($url);
    }

    /**
     *Retrieve all aliases associated with a user using a known alias.
     *
     * @param string $aliasLabel The type of alias (e.g. 'external_id', 'onesignal_id')
     * @param string $aliasId The value of the alias
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function viewUserIdentity(string $aliasLabel, string $aliasId): mixed
    {
        if (empty($aliasLabel)) {
            throw new InvalidArgumentException('alias_label is required');
        }

        if (empty($aliasId)) {
            throw new InvalidArgumentException('alias_id is required');
        }

        $basicUrl = APPS . '/' . $this->getAppId() . '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId . '/identity';
        $url = $this->getUrl($basicUrl);
        return $this->get($url);
    }

    /**
     * Retrieve all aliases associated with a user using a known subscription ID.
     *
     * @param string $subscriptionId The unique identifier that represents a subscription in our system.
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function viewUserIdentityBySubscription(string $subscriptionId): mixed
    {
        if (empty($subscriptionId)) {
            throw new InvalidArgumentException('subscription id is required.');
        }

        $basicUrl = APPS . '/' . $this->getAppId() . '/' . SUBSCRIPTIONS . '/' . $subscriptionId . '/user/identity';
        $url = $this->getUrl($basicUrl);
        return $this->get($url);
    }

    /**
     * Create or update an alias for a user using a known alias.
     *
     * @param string $aliasLabel The type of alias (e.g. 'external_id', 'onesignal_id')
     * @param string $aliasId The value of the alias
     * @param array $fields The fields to update
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function createAlias(string $aliasLabel, string $aliasId, array $fields): mixed
    {
        if (empty($aliasLabel)) {
            throw new InvalidArgumentException('alias_label is required');
        }

        if (empty($aliasId)) {
            throw new InvalidArgumentException('alias_id is required');
        }

        $basicUrl = APPS . '/' . $this->getAppId() . '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId. '/identity';
        $url = $this->getUrl($basicUrl);
        return $this->patch($url, json_encode($fields));
    }

    /** Create or update an alias for a user using a known subscription ID.
     * @param string $subscriptionId
     * @param array $fields
     *
     * @return mixed
     */
    public function createAliasBySubscription(string $subscriptionId, array $fields): mixed
    {
        if (empty($subscriptionId)) {
            throw new InvalidArgumentException('subscription id is required');
        }

        $basicUrl = APPS . '/' . $this->getAppId() . '/' . SUBSCRIPTIONS . '/' . $subscriptionId. '/user/identity';
        $url = $this->getUrl($basicUrl);
        return $this->patch($url, json_encode($fields));
    }

    /**
     * @param string $aliasLabel
     * @param string $aliasId
     * @param string $aliasLabelToDelete
     *
     * @return mixed
     */
    public function deleteAlias(string $aliasLabel, string $aliasId, string $aliasLabelToDelete): mixed
    {
        if (empty($aliasLabel)) {
            throw new InvalidArgumentException('alias_label is required');
        }

        if (empty($aliasId)) {
            throw new InvalidArgumentException('alias_id is required');
        }

        if (empty($aliasLabelToDelete)) {
            throw new InvalidArgumentException('alias_label_to_delete is required');
        }

        $basicUrl = APPS . '/' . $this->getAppId() . '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId . '/identity/' . $aliasLabelToDelete;
        $url = $this->getUrl($basicUrl);
        return $this->delete($url);
    }
}
