<?php

namespace Abibockun\Bundle\CurlBundle\Service;

/**
 * Class CurlService
 */
class CurlService
{
    const MODE_DEBUG = 'debug';
    const MODE_PRODUCTION = 'production';

    /** @var string */
    private $endPointBaseUrl;

    /** @var string */
    private $mode;

    /** @var array */
    private $extraHeaders;

    /**
     * CurlService constructor.
     */
    public function __construct()
    {
        $this->mode = self::MODE_PRODUCTION;
    }

    /**
     * Sending CURL requests to the API
     *
     * @param string $url
     * @param string $requestType
     * @param array $data
     * @param bool $dataJson
     * @param bool $returnObject
     * @return string|array
     */
    public function send(
        $url,
        $requestType = 'GET',
        $data = [],
        $dataJson = false,
        $returnObject = true )
    {
        $url = $this->endPointBaseUrl.$url;

        $curlHeaders = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ];

        if ( !$dataJson && count($data) > 0 ) {
            $fields_string = "";
            foreach($data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');
            $url .= '?'.$fields_string;
        }
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        $curlHeaders[CURLOPT_URL] = $url;

        $data_string = json_encode($data);

        $curlHeaders[CURLOPT_HTTPHEADER] = array_merge_recursive(
            (isset($this->extraHeaders[CURLOPT_HTTPHEADER])) ? $this->extraHeaders[CURLOPT_HTTPHEADER] : []
        );

        if ( $requestType == 'POST' && !empty($data)) {
            $curlHeaders[CURLOPT_CUSTOMREQUEST] = "POST";
            $curlHeaders[CURLOPT_POSTFIELDS] = $data_string;
        }

        if ( $requestType == 'PATCH' && !empty($data)) {
            $curlHeaders[CURLOPT_CUSTOMREQUEST] = "PATCH";
            $curlHeaders[CURLOPT_POSTFIELDS] = $data_string;
        }

        if ( $requestType == 'DELETE') {
            $curlHeaders[CURLOPT_CUSTOMREQUEST] = "DELETE";
        }

        curl_setopt_array($ch, $curlHeaders);
        if (self::MODE_DEBUG === $this->mode) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);
        }
        //execute post
        $result = curl_exec($ch);
        $err = curl_error($ch);

        if (self::MODE_DEBUG === $this->mode) {
            if ($err) {
                $message = sprintf('cURL ERROR: "%s"',
                    $err
                );
                echo $message.'Debug Break point in a File: '.__FILE__.' LINE: '.__LINE__;die;
            } else {
                $info = curl_getinfo($ch);
                dump($data);
                dump($info);
                echo 'Debug Break point a File: '.__FILE__.' LINE: '.__LINE__;die;
            }
        }
        //close connection
        curl_close($ch);

        if ( $returnObject ) {
            return json_decode($result);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getEndPointBaseUrl()
    {
        return $this->endPointBaseUrl;
    }

    /**
     * @param string $endPointBaseUrl
     */
    public function setEndPointBaseUrl($endPointBaseUrl)
    {
        $this->endPointBaseUrl = $endPointBaseUrl;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return array
     */
    public function getExtraHeaders()
    {
        return $this->extraHeaders;
    }

    /**
     * @param array $extraHeaders
     */
    public function setExtraHeaders($extraHeaders)
    {
        $this->extraHeaders = $extraHeaders;
    }
}