<?php

namespace Ladumor\OneSignal;

// end point
define("NOTIFICATIONS", "notifications");
define("DEVICES", "players");
define("APPS", "apps");
define("SEGMENTS", "segments");

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
    public function sendPush(array $fields, string $message = ''): mixed
    {
        $content = array(
            "en" => $message,
        );

        $fields['app_id'] = $this->getAppId();
        $fields['mutable_content'] = $this->getMutableContent();

        if (empty($fields['contents'])) {
            $fields['contents'] = $content;
        }

        return $this->post($this->getUrl(NOTIFICATIONS), json_encode($fields));
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
     * GET all notifications of any applications.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array|mixed
     */
    public function getNotifications(int $limit = 50, int $offset = 0): mixed
    {
        $url = $this->getUrl(NOTIFICATIONS) . '?app_id=' . $this->getAppId() . '&limit=' . $limit . '&offset=' . $offset;

        return $this->get($url);
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
        $url = $this->getUrl(APPS) . '/outcomes' . $this->getAppId();

        if (count($params) > 0) {
            $url = $url . '?' . join('&', $params);
        }

        return $this->get($url);
    }

    /**
     * Get Single notification
     *
     * @param string $notificationId
     *
     * @return object
     */
    public function getNotification(string $notificationId): object
    {
        $url = $this->getUrl(NOTIFICATIONS) . '/' . $notificationId . "?app_id=" . $this->getAppId();

        return $this->get($url);
    }

    /**
     * GET all devices of any applications.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array|mixed
     */
    public function getDevices(int $limit = 50, int $offset = 0): mixed
    {
        $url = $this->getUrl(DEVICES) . '?app_id=' . $this->getAppId() . '&limit=' . $limit . '&offset=' . $offset;

        return $this->get($url);
    }

    /**
     * Get Single Device information
     *
     * @param string $playerId
     *
     * @return object
     */
    public function getDevice(string $playerId): object
    {
        $url = $this->getUrl(DEVICES) . '/' . $playerId . "?app_id=" . $this->getAppId();

        return $this->get($url);
    }

    /**
     * Add new device on your application
     *
     * @param array $fields
     *
     * @return array|mixed
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
     * update existing device on your application
     *
     * @param array $fields
     * @param string $playerId
     *
     * @return array|mixed
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
     * delete existing device on your application
     *
     * @param string $playerId
     *
     * @return array|mixed
     */
    public function deleteDevice(string $playerId): mixed
    {
        $url = $this->getUrl(DEVICES) . '/' . $playerId . '?app_id=' . $this->getAppId();

        return $this->delete($url);
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
}
