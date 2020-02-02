<?php

namespace Ladumor\OneSignal;

/**
 * Class OneSignalManager
 */
class OneSignalManager extends OneSignalClient
{
    // One Signal EndPoint Url
    protected $url;

    /**
     * @return string $url
     */
    private function getUrl()
    {
        return $this->url;
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
     * Set required configuration
     */
    protected function initConfig()
    {
        $this->setUrl(config('one-signal.url'));
        $this->setAppId(config('one-signal.app_id'));
        $this->setAppAuthorize(config('one-signal.authorize'));
        $this->setMutableContent(config('one-signal.mutable_content'));
    }

    /**
     * @param $fields
     * @param string $message
     * @return array|mixed
     */
    public function sendPush($fields, $message = '')
    {
        $content = array(
            "en" => $message,
        );

        $fields['app_id'] = $this->getAppId();
        $fields['mutable_content'] = $this->getMutableContent();

        if (!isset($fields['contents']) || empty($fields['contents'])) {
            $fields['contents'] = $content;
        }

        $fields = json_encode($fields);

        return $this->post($this->getUrl(), $fields);
    }

    /**
     * GET all notifications of any applications.
     * @param int $limit
     * @param int $offset
     *
     * @return array|mixed
     */
    public function getNotifications($limit = 50, $offset = 0)
    {
        $url = $this->getUrl().'?app_id='.$this->getAppId().'&limit='.$limit.'&offset='.$offset;

        return $this->get($url);
    }
}