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
define("TEMPLATES", "templates");

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
    public function sendEmail(array $fields): mixed
    {
        $fields['app_id'] = $this->getAppId();

        $this->checkEmptyValidation($fields, 'email_subject');
        $this->checkEmptyValidation($fields, 'email_body');

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
    public function viewMessage(string $notificationId): object
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

        if (empty($params['events'])) {
            throw new InvalidArgumentException('events is required. Ex. "sent", "clicked"');
        }

        $partUrl = DIRECTORY_SEPARATOR . $notificationId . "/history";

        $params['app_id'] = $this->getAppId();

        $url = $this->getUrl(NOTIFICATIONS . $partUrl, $params);

        return $this->get($url);
    }

    /** This API lets you start a Live Activity by sending a Push Notification.
     *
     * @param string $activityType
     * @param array $fields
     *
     * @return mixed
     */
    public function startLiveActivity(string $activityType, array $fields): mixed
    {
        $this->checkEmptyValidation($fields, 'activity_id');
        $this->checkEmptyValidation($fields, 'name');
        $this->checkEmptyValidation($fields, 'event_attributes');
        $this->checkEmptyValidation($fields, 'event_updates');
        $this->checkEmptyValidation($fields, 'contents');
        $this->checkEmptyValidation($fields, 'headings');

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
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function viewUser(string $aliasLabel, string $aliasId): mixed
    {
        $this->checkEmptyValidation($aliasLabel, 'alias_label');
        $this->checkEmptyValidation($aliasId, 'alias_id');

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
        $this->checkEmptyValidation($aliasLabel, 'alias_label');
        $this->checkEmptyValidation($aliasId, 'alias_id');

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
        // Validations
        $this->checkEmptyValidation($aliasLabel, 'alias_label');
        $this->checkEmptyValidation($aliasId, 'alias_id');

        // Prepare URL
        $url = $this->getUrl(APPS . '/' . $this->getAppId() . '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId);

        // Execute API
        return $this->delete($url);
    }

    /**
     *Retrieve all aliases associated with a user using a known alias.
     *
     * @param string $aliasLabel The type of alias (e.g. 'external_id', 'onesignal_id')
     * @param string $aliasId The value of the alias
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function viewUserIdentity(string $aliasLabel, string $aliasId): mixed
    {
        // Validations
        $this->checkEmptyValidation($aliasLabel, 'alias_label');
        $this->checkEmptyValidation($aliasId, 'alias_id');

        // Prepare URL
        $basicUrl = APPS . '/' . $this->getAppId() . '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId . '/identity';
        $url = $this->getUrl($basicUrl);

        // Execute API
        return $this->get($url);
    }

    /**
     * Retrieve all aliases associated with a user using a known subscription ID.
     *
     * @param string $subscriptionId The unique identifier that represents a subscription in our system.
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function viewUserIdentityBySubscription(string $subscriptionId): mixed
    {
        // Validations
        $this->checkEmptyValidation($subscriptionId, 'subscriptionId');

        // Prepare URL
        $basicUrl = APPS . '/' . $this->getAppId() . '/' . SUBSCRIPTIONS . '/' . $subscriptionId . '/user/identity';
        $url = $this->getUrl($basicUrl);

        // Execute API
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
        // Validations
        $this->checkEmptyValidation($aliasLabel, 'alias_label');
        $this->checkEmptyValidation($aliasId, 'alias_id');

        // Prepare URL
        $basicUrl = APPS . '/' . $this->getAppId() . '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId . '/identity';
        $url = $this->getUrl($basicUrl);

        // Execute API
        return $this->patch($url, json_encode($fields));
    }

    /** Create or update an alias for a user using a known subscription ID.
     *
     * @param string $subscriptionId
     * @param array $fields
     *
     * @return mixed
     */
    public function createAliasBySubscription(string $subscriptionId, array $fields): mixed
    {
        // Validations
        $this->checkEmptyValidation($subscriptionId, 'subscriptionId');

        // Prepare URL
        $basicUrl = APPS . '/' . $this->getAppId() . '/' . SUBSCRIPTIONS . '/' . $subscriptionId . '/user/identity';
        $url = $this->getUrl($basicUrl);

        // Execute API
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
        // Validations
        $this->checkEmptyValidation($aliasLabel, 'alias_label');
        $this->checkEmptyValidation($aliasId, 'alias_id');
        $this->checkEmptyValidation($aliasLabelToDelete, 'alias_label_to_delete');

        // Prepare URL
        $basicUrl = APPS . '/' . $this->getAppId() . '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId . '/identity/' . $aliasLabelToDelete;
        $url = $this->getUrl($basicUrl);

        // Execute API
        return $this->delete($url);
    }

    /**
     * Add a new subscription to your user.
     *
     * @param string $aliasLabel The type of alias (e.g. 'external_id', 'onesignal_id')
     * @param string $aliasId The value of the alias
     * @param array $fields The fields to update
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function createSubscription(string $aliasLabel, string $aliasId, array $fields): mixed
    {
        // validations
        $this->checkEmptyValidation($aliasLabel, 'alias_label');
        $this->checkEmptyValidation($aliasId, 'alias_id');
        $this->checkEmptyValidation($fields, 'subscription');

        // prepare URL
        $basicUrl = APPS . '/' . $this->getAppId();
        $partURL = '/' . USERS_BY . '/' . $aliasLabel . '/' . $aliasId . '/' . SUBSCRIPTIONS;
        $url = $this->getUrl($basicUrl . $partURL);

        // Execute API
        return $this->post($url, json_encode($fields));
    }

    /**
     * Update a subscription's properties.
     *
     * @param string $subscriptionId
     * @param array $fields
     *
     * @return mixed
     */
    public function updateSubscription(string $subscriptionId, array $fields): mixed
    {
        // validations
        $this->checkEmptyValidation($subscriptionId, 'subscriptionId');
        $this->checkEmptyValidation($fields, 'subscription');

        // prepare URL
        $basicUrl = APPS . '/' . $this->getAppId() . '/' . SUBSCRIPTIONS . '/' . $subscriptionId;
        $url = $this->getUrl($basicUrl);

        // Execute API
        return $this->patch($url, json_encode($fields));
    }

    /**
     * Delete an existing subscription.
     *
     * @param string $subscriptionId
     *
     * @return mixed
     */
    public function deleteSubscription(string $subscriptionId): mixed
    {
        // validations
        $this->checkEmptyValidation($subscriptionId, 'subscriptionId');

        // prepare URL
        $basicUrl = APPS . '/' . $this->getAppId() . '/' . SUBSCRIPTIONS . '/' . $subscriptionId;
        $url = $this->getUrl($basicUrl);

        // Execute API
        return $this->delete($url);
    }

    /**
     * Transfer a subscription to a different user.
     *
     * @param string $subscriptionId
     * @param array $fields
     *
     * @return mixed
     */
    public function transferSubscription(string $subscriptionId, array $fields): mixed
    {
        // validations
        $this->checkEmptyValidation($subscriptionId, 'subscriptionId');
        $this->checkEmptyValidation($fields, 'identity');

        // prepare URL
        $basicUrl = APPS . '/' . $this->getAppId() . '/' . SUBSCRIPTIONS . '/' . $subscriptionId . '/owner';
        $url = $this->getUrl($basicUrl);

        // Execute API
        return $this->patch($url, json_encode($fields));
    }

    /**
     * @param string $notificationId
     * @param string $token
     *
     * @return array|mixed
     */
    public function unsubscribeNotification(string $notificationId, string $token): mixed
    {
        // validations
        $this->checkEmptyValidation($notificationId, 'notificationId');
        $this->checkEmptyValidation($token, 'token');

        $basicUrl = APPS . '/' . $this->getAppId() . '/' . NOTIFICATIONS . '/' . $notificationId . '/unsubscribe';
        $url = $this->getUrl($basicUrl, ['token' => $token]);
        return $this->post($url, '');
    }

    /**
     * Create templates for push, email and sms visible and usable in the dashboard and API.
     *
     * @param array $fields
     *
     * @return mixed
     */
    public function createTemplate(array $fields): mixed
    {
        // validations
        $this->checkEmptyValidation($fields, 'name');

        // set app id if not set in payload
        if (empty($fields['app_id'])) {
            $fields['app_id'] = $this->getAppId();
        }

        // prepare URL
        $url = $this->getUrl(TEMPLATES);

        // Execute API
        return $this->post($url, json_encode($fields));
    }

    /**
     * Update previously created templates for push, email and sms visible and usable in the dashboard and API.
     *
     * @param string $templateId
     * @param array $fields
     *
     * @return mixed
     */
    public function updateTemplate(string $templateId, array $fields): mixed
    {
        // validations
        $this->checkEmptyValidation($fields, 'name');

        // prepare URL
        $url = $this->getUrl(TEMPLATES . '/' . $templateId, ['app_id' => $this->getAppId()]);

        // Execute API
        return $this->patch($url, json_encode($fields));
    }

    /**
     * Returns an array of templates from an app.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function viewTemplates(array $params): mixed
    {
        if (empty($fields['app_id'])) {
            $params['app_id'] = $this->getAppId();
        }
        if (empty($fields['limit'])) {
            $params['limit'] = 50;
        }
        if (empty($fields['offset'])) {
            $params['offset'] = 0;
        }

        // prepare URL
        $url = $this->getUrl(TEMPLATES, $params);

        // Execute API
        return $this->get($url);
    }

    /**
     * @param string $templateId
     *
     * @return mixed
     */
    public function viewTemplate(string $templateId): mixed
    {
        // prepare URL
        $url = $this->getUrl(TEMPLATES . '/' . $templateId, ['app_id' => $this->getAppId()]);

        // Execute API
        return $this->get($url);
    }

    /**
     * Delete templates from OneSignal.
     *
     * @param string $templateId
     * @param array $params
     *
     * @return mixed
     */
    public function deleteTemplate(string $templateId, array $params): mixed
    {
        if (empty($fields['app_id'])) {
            $params['app_id'] = $this->getAppId();
        }

        // prepare URL
        $url = $this->getUrl(TEMPLATES . '/' . $templateId, $params);

        // Execute API
        return $this->delete($url);
    }

    /**
     * Create a duplicate of a template in another app
     *
     * @param string $templateId
     * @param array $fields
     *
     * @return mixed
     */
    public function copyTemplate(string $templateId, array $fields): mixed
    {
        // validations
        $this->checkEmptyValidation($fields, 'target_app_id');

        // prepare URL
        $params['app_id'] = empty($fields['app_id']) ? $this->getAppId() : $fields['app_id'];

        $url = $this->getUrl(TEMPLATES . '/' . $templateId, $params);

        // Execute API
        return $this->post($url, json_encode(['target_app_id' => $fields['target_app_id']]));
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

    /** This method check empty validation and throw exception if empty.
     *
     * @param $value
     * @param string $field
     *
     * @return void
     */
    private function checkEmptyValidation($value, string $field): void
    {
        if (is_array($value) && empty($value[$field])) {
            throw new InvalidArgumentException($field . ' is required');
        }

        if (empty($value)) {
            throw new InvalidArgumentException($field . ' is required');
        }
    }
}
