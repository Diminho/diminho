<?php

namespace SDK;

class InstaSDK {

    private $accessToken;
    private $httpClient;
    protected $configs;

    private function init() {
        $auth = new Auth();
        $this->accessToken = $auth->getAccessToken('access_token');
        $client = $auth->getConfigItem('http_client');
        $this->setHttpClient(new $client);
    }

    public function __construct() {
        $this->init();
    }

    public function setHttpClient($client) {
        $this->httpClient = $client;
    }

    //================
    // User section
    //================
    
    /**
  * Get information about a user.
  *
  * @param int $userId user ID
  *
  * @return Http\Client\Response Object
  */

    public function getUserById($userId) {
        return $this->httpClient->get('https://api.instagram.com/v1/users/' . $userId . '/?', array('access_token' => $this->accessToken));
    }

     /**
  * Get the most recent media published by a user.
  *
  * @param int $userId user ID
  * @param array $params Query string parameters 
  *
  * @return Http\Client\Response Object
  */
    
    public function getUsersRecentMedia($userId, array $params = array()) {
        return $this->httpClient->get('https://api.instagram.com/v1/users/' . $userId . '/media/recent/?', array('access_token' => $this->accessToken) + $params);
    }

     /**
  * Search user by username. Get a list of users matching the query.
  *
  * @param string $username username
  * @param array $params Query string parameters 
  *
  * @return Http\Client\Response Object
  */
    
    public function getSearchUserByUsername($username, array $params = array()) {
        return $this->httpClient->get('https://api.instagram.com/v1/users/search?', array('q' => $username, 'access_token' => $this->accessToken) + $params);
    }

    //================
    // Media section
    //================

    /**
  * Get information about a media object. Use the type field to differentiate between
  * image and video media in the response. You will also receive the user_has_liked field 
  * which tells you whether the owner of the access_token has liked this media.
  *
  * @param string $mediaId media ID
  *
  * @return Http\Client\Response Object
  */
    
    public function getMediaById($mediaId) {
        return $this->httpClient->get('https://api.instagram.com/v1/media/' . $mediaId . '?', array('access_token' => $this->accessToken));
    }
    /**
  * This endpoint returns the same response as GET /media/media-id.
  * A media object's shortcode can be found in its shortlink URL. 
  * An example shortlink is http://instagram.com/p/tsxp1hhQTG/. Its corresponding shortcode is tsxp1hhQTG.
  *
  * @param string $shortcode shortcode
  *
  * @return Http\Client\Response Object
  */

    public function getMediaByShortcode($shortcode) {
        return $this->httpClient->get('https://api.instagram.com/v1/media/shortcode/' . $shortcode . '?', array('access_token' => $this->accessToken));
    }
    
    /**
  * Search for recent media in a given area.
  *
  * @param array $params Query string parameters 
  *
  * @return Http\Client\Response Object
  */

    public function getMediaByArea($params) {
        return $this->httpClient->get('https://api.instagram.com/v1/media/search/?', array('access_token' => $this->accessToken) + $params);
    }

    //================
    // Likes section
    //================

    /**
  * Set a like on this media by the currently authenticated user.
  *
  * @param string $mediaId media ID
  *
  * @return Http\Client\Response Object
  */
    
    public function postSetLikeMedia($mediaId) {
        return $this->httpClient->post('https://api.instagram.com/v1/media/' . $mediaId . '/likes', array('access_token' => $this->accessToken));
    }
    
    /**
  * Get a list of users who have liked this media.
  *
  * @param string $mediaId media ID
  *
  * @return Http\Client\Response Object
  */

    public function getLikesByMedia($mediaId) {
        return $this->httpClient->get('https://api.instagram.com/v1/media/' . $mediaId . '/likes?', array('access_token' => $this->accessToken));
    }
    
     /**
  * Remove a like on this media by the currently authenticated user.
  *
  * @param string $mediaId media ID
  *
  * @return Http\Client\Response Object
  */

    public function delUnsetLikesByMedia($mediaId) {
        return $this->httpClient->delete('https://api.instagram.com/v1/media/' . $mediaId . '/likes?access_token=' . $this->accessToken);
    }

}
