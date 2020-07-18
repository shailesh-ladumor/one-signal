<?php

namespace Ladumor\OneSignal;

/**
 * Class OneSignalClient
 */
class OneSignalClient
{
    // One Signal App Key
    public $authorization;

    /**
     *
     * @return string $authorization
     */
    private function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @param  string  $key
     */
    public function setAuthorization($key)
    {
        $this->authorization = $key;
    }

    // One Signal EndPoint Url
    protected $url;

    /**
     * @param string $url
     *
     * @return string $url
     */
    public function getUrl($url)
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
    public function getAppId()
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
    public function getMutableContent()
    {
        return $this->mutableContent;
    }

    // One Signal Auth key
    protected $authKey;

    /**
     * @param  string  $authKey
     */
    public function setAuthKey($authKey)
    {
        $this->authKey = trim($authKey);
    }

    /**
     * @return string $authKey
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * return headers
     * @return array
     */
    protected function getHeaders()
    {
        return array(
            'Content-Type: application/json; charset=utf-8',
            'X-Requested-With:XMLHttpRequest',
            'Authorization: Basic '.$this->getAuthorization(),
        );
    }

    /**
     * GEt Method
     * @param string $url
     * @return array|mixed
     */
    public function get($url)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => $this->getHeaders(),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if (!empty($err)) {
                return json_decode($err, true);
            }

            return json_decode($response, true);
        } catch (\Exception $exception) {
            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
    }

    /**
     * Post Method
     * @param string $url
     * @param string $fields
     *
     * @return array|mixed
     */
    public function post($url, $fields)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if (!empty($err)) { // return  error
                return json_decode($err, true);
            }

            return json_decode($response, true); // return success
        } catch (\Exception $exception) {
            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
    }

    /**
     * Put Method
     * @param string $url
     * @param string $fields
     *
     * @return array|mixed
     */
    public function put($url, $fields)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            curl_close($ch);

            if (!empty($err)) { // return  error
                return json_decode($err, true);
            }

            return json_decode($response, true); // return success
        } catch (\Exception $exception) {
            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
    }

    /**
     * Delete Method
     * @param string $url
     *
     * @return array|mixed
     */
    public function delete($url)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if (!empty($err)) { // return  error
                return json_decode($err, true);
            }

            return json_decode($response, true); // return success
        } catch (\Exception $exception) {
            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
    }
}
