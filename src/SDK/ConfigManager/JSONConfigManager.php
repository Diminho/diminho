<?php
namespace SDK\ConfigManager;

use SDK;

class JSONConfigManager {

    private $configFilename;
    private $config;

    public function __construct($configFilename) {
        $this->configFilename = $configFilename;
    }

    public function getConfigArray() 
    {
        $this->config = json_decode(file_get_contents($this->configFilename), true);
        return $this->config;
    }

    public function setItem($item, $value) 
    {
        $this->config[$item] = $value;
        file_put_contents($this->configFilename, json_encode($this->config));
    }

    public function getItem($item) 
    {
        try {
            if (array_key_exists($item, $this->config)) {
                return $this->config[$item];
            }
            throw new SDK\InstaException("$item does not exists in config");
        } catch (SDK\InstaException $e) {
            return $e->getMessage();
        }
    }

}
