<?php

namespace SDK;

use SDK\ConfigManager;

class Auth {

    public $config;
    public $configManager;
    public $baseConfigDir = './config';

    public function __construct() {
        $configFileParts = $this->getConfigFile();

        $configManagerType = "SDK\ConfigManager\\" . strtoupper($configFileParts['extension']) . 'ConfigManager';
        $this->configManager = new $configManagerType($this->baseConfigDir . DIRECTORY_SEPARATOR . $configFileParts['basename']);

        $this->config = $this->configManager->getConfigArray();
    }
    
     /**
  * Get the config file. In confog folder should be only the one config file named config.[ext]. Ext can be: json, php
  *
  * @return array
  */

    public function getConfigFile() {
        $files = scandir($this->baseConfigDir);
        foreach ($files as $file) {
            $pathParts = pathinfo($file);
            if ($pathParts['filename'] == 'config') {
                return $pathParts;
            }
        }
        throw new InstaException("Config file not found");
    }
    
    /**
  * Set an access_token to a config file
  *
  * @param string $accessToken Instagram API access token
  *
  * @return void
  */

    public function registerAccessToken($accessToken) {
        $this->configManager->setItem('access_token', $accessToken);
        $this->refreshConfig();
    }
    
     /**
  * Get an Instagram API access token from config
  *
  * @return string
  */

    public function getAccessToken() {
        return $this->configManager->getItem('access_token');
    }

     /**
  * Set a config item
  *
  * @param string $item key of the item
  * @param string $value value of the item
  *
  * @return void
  */
    
    public function setConfigItem($item, $value) {
        $this->configManager->setItem($item, $value);
        $this->refreshConfig();
    }
    
       /**
  * Get a config item
  *
  * @param string $item key of the item
  *
  * @return string
  */

    public function getConfigItem($item) {
        return $this->configManager->getItem($item);
    }
    
          /**
  * refreshes the $this->config after changes in the config file
  *
  * @return void
  */

    public function refreshConfig() {
        $this->config = $this->configManager->getConfigArray();
    }

}
