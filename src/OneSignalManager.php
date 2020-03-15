<?php

namespace Ladumor\OneSignal;

// end point
define("NOTIFICATIONS", "notifications");
define("DEVICES", "players");

/**
 * Class OneSignalManager
 */
class OneSignalManager extends OneSignalClient
{
    // One Signal EndPoint Url
    protected $url;

    /**
     * @param string $url
     *
     * @return string $url
     */
    private function getUrl($url)
    {
        return $this->url . $url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    // One Signal App ID
    protected $appId;

    /**
     * @return string $appId
     */
    private function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param string $appId
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }

    // One Signal Authorize key
    protected $appAuthorize;

    /**
     * @param string $appAuthorize
     */
    public function setAppAuthorize($appAuthorize)
    {
        $this->appAuthorize = $appAuthorize;
    }

    /**
     * @return string $appAuthorize
     */
    private function getAppAuthorize()
    {
        return $this->appAuthorize;
    }

    // Default mutable content is enabled
    protected $mutableContent;

    /**
     * @param string $mutableContent
     */
    public function setMutableContent($mutableContent)
    {
        $this->mutableContent = $mutableContent;
    }

    /**
     * @return string $mutableContent
     */
    private function getMutableContent()
    {
        return $this->mutableContent;
    }

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
        $this->setAppAuthorize(config('one-signal.authorize'));
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

        $fields = json_encode($fields);

        return $this->post($this->getUrl(NOTIFICATIONS), $fields);
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
        $fields['app_id'] = $this->getAppId();

        if ( ! isset($fields['language']) || empty($fields['language'])) {
            $fields['language'] = "en";
        }

        $fields = json_encode($fields);

        return $this->post($this->getUrl(NOTIFICATIONS), $fields);
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
        $fields['app_id'] = $this->getAppId();

        if ( ! isset($fields['language']) || empty($fields['language'])) {
            $fields['language'] = "en";
        }

        $fields = json_encode($fields);

        $url = $this->getUrl(DEVICES) . '/' . $playerId;

        return $this->put($url, $fields);
    }
}
