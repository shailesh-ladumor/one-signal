<?php

namespace Ladumor\OneSignal;

/**
 * Class OneSignalClient
 */
class OneSignalClient
{
    // One Signal App Key
    public string $authorization;

    /**
     *
     * @return string $authorization
     */
    private function getAuthorization(): string
    {
        return $this->authorization;
    }

    /**
     * @param string $key
     */
    public function setAuthorization(string $key): void
    {
        $this->authorization = $key;
    }

    // One Signal EndPoint Url
    protected string $url;

    /**
     * @param string $url
     *
     * @return string $url
     */
    public function getUrl(string $url): string
    {
        return $this->url . $url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    // One Signal App ID
    protected string $appId;

    /**
     * @return string $appId
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @param string $appId
     */
    public function setAppId(string $appId): void
    {
        $this->appId = $appId;
    }

    // Default mutable content is enabled
    protected string $mutableContent;

    /**
     * @param string $mutableContent
     */
    public function setMutableContent(string $mutableContent): void
    {
        $this->mutableContent = $mutableContent;
    }

    /**
     * @return string $mutableContent
     */
    public function getMutableContent(): string
    {
        return $this->mutableContent;
    }

    // One Signal Auth key
    protected string $authKey;

    /**
     * @param string $authKey
     */
    public function setAuthKey(string $authKey): void
    {
        $this->authKey = trim($authKey);
    }

    /**
     * @return string $authKey
     */
    public function getAuthKey(): string
    {
        return $this->authKey;
    }

    /**
     * return headers
     * @return array
     */
    protected function getHeaders(): array
    {
        return array(
            'Content-Type: application/json; charset=utf-8',
            'X-Requested-With:XMLHttpRequest',
            'Authorization: Basic ' . $this->getAuthorization(),
        );
    }

    /**
     * GEt Method
     * @param string $url
     * @return array|mixed
     */
    public function get(string $url): mixed
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => $this->getHeaders(),
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
                'code' => $exception->getCode(),
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
    public function post(string $url, string $fields): mixed
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
                'code' => $exception->getCode(),
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
    public function put(string $url, string $fields): mixed
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
                'code' => $exception->getCode(),
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
    public function delete(string $url): mixed
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
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
    }
}
