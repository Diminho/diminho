<?php
namespace Http\Client;

class CurlHttpClient {

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    private $ch;
    
     /**
  * Initialization of cURL
  *
  * @return void
  */

    private function init()
    {
        $this->ch = curl_init();
        curl_setopt_array($this->ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => 1,
        ));
    }

    public function __construct() 
    {
        $this->init();
    }
    
     /**
  * Build Query string from array
  *
  * @param array $params parameters for qeery string in array
  *
  * @return string
  */

    private function processParams(array $params)
    {
        if (empty($params)) {
            return '';
        }
        return http_build_query($params);
    }
    
    /**
  * Build Query string from array
  *
  * @param string $url request URL
  * @param array $params parameters for qeery string in array
  *
  * @return string
  */
    
    private function buildUrl($url, $params)
    {
        return $url . $this->processParams($params);
    }
    
    /**
  * Build Query string from array
  *
  * @param const $method HTTP method
  * @param string $url request URL
  * @param array $data data for POST and PUT requests
  *
  * @return Http\Client\Response Object
  */

    private function sendRequest($method, $url, $data = array())
    {

        curl_setopt_array(
                $this->ch, array(
            CURLOPT_URL => $url,
        ));

        switch ($method) {
            case self::METHOD_GET:
                break;
            case self::METHOD_POST:
                curl_setopt($this->ch, CURLOPT_POST, 1);
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case self::METHOD_PUT:
                curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case self::METHOD_DELETE:
                curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;

            default:
                break;
        }

        $rawResponse = curl_exec($this->ch);
        $headerSize = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $rawHeaders = substr($rawResponse, 0, $headerSize);
        $body = substr($rawResponse, $headerSize);
        $response = $this->createResponse($rawHeaders, $body);
        return $response;
    }
    
     /**
  * GET request
  *
  * @param string $url request URL
  * @param array $params parameters for qeery string in array
  *
  * @return Http\Client\Response Object
  */

    public function get($url, $params = array())
    {
        $url = $this->buildUrl($url, $params);
        return $this->sendRequest(self::METHOD_GET, $url);
    }
    
  /**
  * POST request
  *
  * @param string $url request URL
  * @param array $data data body of POST
  * @param array $params parameters for qeery string in array
  *
  * @return Http\Client\Response Object
  */
    
    public function post($url, $data, $params = array())
    {
        $url = $this->buildUrl($url, $params);
        return $this->sendRequest(self::METHOD_POST, $url, $data);
    }
    
    /**
  * PUT request
  *
  * @param string $url request URL
  * @param array $data data body of PUT
  * @param array $params parameters for qeery string in array
  *
  * @return Http\Client\Response Object
  */

    public function put($url, $data, $params = array())
    {
        $url = $this->buildUrl($url, $params);
        return $this->sendRequest(self::METHOD_POST, $url, $data);
    }
    
    /**
  * DELETE request
  *
  * @param string $url request URL
  * @param array $params parameters for qeery string in array
  *
  * @return Http\Client\Response Object
  */

    public function delete($url, $params = array())
    {
        $url = $this->buildUrl($url, $params);
        return $this->sendRequest(self::METHOD_DELETE, $url, $params);
    }
    
      /**
  * create a response for SDK
  *
  * @param string/array $raw_headers request URL
  * @param string $body body of response
  *
  * @return Http\Client\Response Object
  */    
    
    protected function createResponse($raw_headers, $body)
    {        
        return new Response($raw_headers, $body);        
    }

}
