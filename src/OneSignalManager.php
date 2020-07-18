<?php

namespace Ladumor\OneSignal;

// end point
define("NOTIFICATIONS", "notifications");
define("DEVICES", "players");
define("APPS","apps");
define("SEGMENTS","segments");
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
    protected function initConfig()
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
     * @param $fields
     * @param string $message
     *
     * @return array|mixed
     */
    public function sendPush($fields, $message = '')
    {
        $content = array(
            "en" => $message,
        );

        $fields['app_id']          = $this->getAppId();
        $fields['mutable_content'] = $this->getMutableContent();

        if ( ! isset($fields['contents']) || empty($fields['contents'])) {
            $fields['contents'] = $content;
        }

        return $this->post($this->getUrl(NOTIFICATIONS), json_encode($fields));
    }

    /**
     * GET all notifications of any applications.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array|mixed
     */
    public function getNotifications($limit = 50, $offset = 0)
    {
        $url = $this->getUrl(NOTIFICATIONS) . '?app_id=' . $this->getAppId() . '&limit=' . $limit . '&offset=' . $offset;

        return $this->get($url);
    }

    /**
     * Get Single notification
     *
     * @param string $notificationId
     *
     * @return object
     */
    public function getNotification($notificationId)
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
    public function getDevices($limit = 50, $offset = 0)
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
    public function getDevice($playerId)
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
    public function addDevice($fields)
    {
        if ( ! isset($fields['app_id']) || empty($fields['app_id'])) {
            $fields['app_id'] = $this->getAppId();
        }

        if ( ! isset($fields['language']) || empty($fields['language'])) {
            $fields['language'] = "en";
        }

        return $this->post($this->getUrl(DEVICES), json_encode($fields));
    }

    /**
     * update existing device on your application
     *
     * @param array $fields
     * @param int $playerId
     *
     * @return array|mixed
     */
    public function updateDevice($fields, $playerId)
    {
        if ( ! isset($fields['app_id']) || empty($fields['app_id'])) {
            $fields['app_id'] = $this->getAppId();
        }

        if ( ! isset($fields['language']) || empty($fields['language'])) {
            $fields['language'] = "en";
        }

        return $this->put($this->getUrl(DEVICES) . '/' . $playerId, json_encode($fields));
    }

    /**
     * Create Segment
     *
     * @param $fields
     * @param  null  $appId
     *
     * @return array|mixed
     */
    public function createSegment($fields, $appId = null)
    {
        if (empty($appId)) { // take a default if does not specified
            $appId = $this->getAppId();
        }

        return $this->post($this->getUrl(APPS.'/'.$appId.'/'.SEGMENTS), json_encode($fields));
    }

    /**
     * @param $segmentId
     * @param  null  $appId
     *
     * @return array|mixed
     */
    public function deleteSegment($segmentId, $appId = null)
    {
        if (empty($appId)) { // take a default if does not specified
            $appId = $this->getAppId();
        }

        return $this->delete($this->getUrl(APPS.'/'.$appId.'/'.SEGMENTS.'/'.$segmentId));
    }

    /**
     * GET all apps of your one signal.
     *
     * @return array|mixed
     */
    public function getApps()
    {
        $this->setAuthorization($this->getAuthKey());

        $url = $this->getUrl(APPS);

        return $this->get($url);
    }

    /**
     * GET single app of your one signal.
     *
     * @param null|string $appId
     *
     * @return array|mixed
     */
    public function getApp($appId = null)
    {
        $this->setAuthorization($this->getAuthKey());

        $url = $this->getUrl(APPS. '/'.$appId);

        return $this->get($url);
    }

    /**
     * Add new application on your one signal.
     *
     * @param array $fields
     *
     * @return array|mixed
     */
    public function createApp($fields)
    {
        $this->setAuthorization($this->getAuthKey());

        return $this->post($this->getUrl(APPS), json_encode($fields));
    }

    /**
     * Update existing application on your one signal.
     *
     * @param array $fields
     * @param null|string $appId
     *
     * @return array|mixed
     */
    public function updateApp($fields, $appId = null)
    {
        $this->setAuthorization($this->getAuthKey());

        if (empty($appId)) { // take a default if does not specified
            $appId = $this->getAppId();
        }

        return $this->put($this->getUrl(APPS.'/'.$appId), json_encode($fields));
    }
}
