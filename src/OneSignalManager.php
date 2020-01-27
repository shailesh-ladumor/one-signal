<?php

namespace Ladumor\OneSignal;

class OneSignalManager
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
     * return headers
     * @return array
     */
    protected function getHeaders()
    {
        return array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic '.$this->getAppAuthorize()
        );
    }

    /**
     * @param $fields
     * @param string $message
     * @return array|mixed
     */
    public function sendPush($fields, $message = '')
    {
        $content = array(
            "en" => $message
        );

        $fields['app_id'] = $this->getAppId();
        $fields['mutable_content'] = $this->getMutableContent();

        if (!isset($fields['contents']) || empty($fields['contents'])) {
            $fields['contents'] = $content;
        }

        $fields = json_encode($fields);

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->getUrl());
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        } catch (\Exception $exception) {
            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
    }
}
