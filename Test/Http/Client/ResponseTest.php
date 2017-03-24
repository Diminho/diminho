<?php

namespace src\Test;

use Http\Client;

class ResponseTest extends \PHPUnit_Framework_TestCase {

    public $defaultResponseInstanse;
    public $rawHeaders = <<<MY_END_ID
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
Cache-Control: private, no-cache, no-store, must-revalidate
Content-Language: en
x-ratelimit-remaining: 499
Date: Thu, 23 Mar 2017 22:42:11 GMT
Pragma: no-cache
Vary: Cookie, Accept-Language
x-ratelimit-limit: 500
Expires: Sat, 01 Jan 2000 00:00:00 GMT
Set-Cookie: s_network=""; expires=Thu, 23-Mar-2017 23:42:11 GMT; Max-Age=3600; Path=/
Set-Cookie: csrftoken=to4j7PliOWgs9E63PiTJla8gMIwlceVy; expires=Thu, 22-Mar-2018 22:42:11 GMT; Max-Age=31449600; Path=/; Secure
Connection: keep-alive
Content-Length: 37
MY_END_ID;
    public $body = '{"data": {"website": "http://in4ray.com", "id": "1190990766", "full_name": "Roman Zarichnyi", "bio": "","username": "rzarichnyi", "counts": {"follows": 314, "followed_by": 335, "media": 465}, "profile_picture": "https://scontent.cdninstagram.com/t51.2885-19/11371033_552443194903776_1402966518_a.jpg"}, "meta": {"code": 200}}';

    protected function setUp() {
        $this->defaultResponseInstanse = new Client\Response($this->rawHeaders, $this->body);
    }

    public function testHttpParseHeaders() {
        $headers = $this->defaultResponseInstanse->httpParseHeaders($this->rawHeaders);

        $this->assertInternalType('array', $headers);
        $this->assertEquals(13, count($headers)); // since in $rawHeaders are 13 unique headers
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertArrayHasKey('Content-Length', $headers);

        $expectedCode = 200;
        $expectedprotocolVersion = '1.1';

        $this->assertEquals($expectedCode,  $this->defaultResponseInstanse->code);
        $this->assertEquals($expectedprotocolVersion,  $this->defaultResponseInstanse->protocolVersion);
    }

    public function testJson() {
        $json = $this->defaultResponseInstanse->json();
        $this->assertInternalType('array', $json);
        $this->assertArrayHasKey('data', $json);
        $this->assertArrayHasKey('meta', $json);

        $responseFalse = new Client\Response($this->rawHeaders, 'Not a JSON');
        $jsonFalse = $responseFalse->json();
        $this->assertInternalType('null', $jsonFalse);
    }
    
    public function testGetHeader() {
        $actualHeaderValue = $this->defaultResponseInstanse->getHeader('Content-Type');
        $this->assertEquals('application/json; charset=utf-8', $actualHeaderValue);   
        
         $actualFalseHeaderValue = $this->defaultResponseInstanse->getHeader('Not valid header');
         $this->assertEquals('Not valid header does not exists', $actualFalseHeaderValue);  
       
    }
    public function testGetHeaders() {
        $actualHeaders = $this->defaultResponseInstanse->getHeaders();
        
        $this->assertInternalType('array', $actualHeaders);
        $this->assertEquals(13, count($actualHeaders)); // since in $rawHeaders are 13 unique headers
        $this->assertArrayHasKey('Content-Type', $actualHeaders);
        $this->assertArrayHasKey('Content-Length', $actualHeaders);      
    }

}
