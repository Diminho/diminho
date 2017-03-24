<?php

namespace Http\Client;

use SDK;

class Response {

    public $headers = array();
    private $body;
    public $code;
    public $protocolVersion;

    protected function init($rawHeaders, $body)
    {
        $this->headers = $this->httpParseHeaders($rawHeaders);
        $this->body = $body;
    }

      /**
  * Get array of all headers
  *
  * @return array
  */
    
    public function getHeaders()
    {
        return $this->headers;
    }
 /**
  * Get a single header
  *
  * @param string $header header
  *
  * @return string
  */
    public function getHeader($header)
    {
        try {
            if (array_key_exists($header, $this->headers)) {
                return $this->headers[$header];
            }
            throw new SDK\InstaException("$header does not exists");
        } catch (SDK\InstaException $e) {
            return $e->getMessage();
        }
    }

    public function body()
    {
        return $this->body;
    }

    public function json()
    {
        return json_decode($this->body, true);
    }

    public function __construct($raw_headers, $body)
    {
        $this->init($raw_headers, $body);
    }

    /**
  * Parse headers. Funcion grabbed from internet
  *
  * @param string/array $rawHeaders raw headers
  *
  * @return string
  */
    
    public function httpParseHeaders($rawHeaders)
    {
        if (is_array($rawHeaders)) {
            return $rawHeaders;
        }
        $headers = array();
        $key = '';

        foreach (explode("\n", $rawHeaders) as $header) {
            $header = explode(':', $header, 2);

            if (isset($header[1])) {
                if (!isset($headers[$header[0]]))
                    $headers[$header[0]] = trim($header[1]);
                elseif (is_array($headers[$header[0]])) {
                    $headers[$header[0]] = array_merge($headers[$header[0]], array(trim($header[1])));
                } else {
                    $headers[$header[0]] = array_merge(array($headers[$header[0]]), array(trim($header[1])));
                }

                $key = $header[0];
            } else {
                if (substr($header[0], 0, 1) == "\t")
                    $headers[$key] .= "\r\n\t" . trim($header[0]);
                elseif (!$key)
                    $headers[0] = trim($header[0]);
            }
        }

        if (substr($headers[0], 0, 5) === 'HTTP/') {
            preg_match('/HTTP\/([\d.]+) ([0-9]+)(.*)/i', $headers[0], $matches);
            $this->protocolVersion = $matches[1];
            $this->code = (int) $matches[2];
        }
        return $headers;
    }

}
