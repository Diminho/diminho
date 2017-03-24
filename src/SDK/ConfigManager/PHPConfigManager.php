<?php
namespace SDK\ConfigManager;

use SDK;

class PHPConfigManager {

    private $configFilename;
    private $config;

    public function __construct($configFilename) 
    {
        $this->configFilename = $configFilename;
    }

    public function getConfigArray() 
    {
        $this->config = include $this->configFilename;
        return $this->config;
    }

    public function setItem($item, $value)
    {
        $this->config[$item] = $value;
        $newConfig = var_export($this->config, true);
        file_put_contents($this->configFilename, "<?php return $newConfig ;");
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
