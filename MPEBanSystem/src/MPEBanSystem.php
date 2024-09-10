<?php

declare(strict_types=1);

namespace XackiGiFF\MPEBanSystem;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use XackiGiFF\MPEBanSystem\provider\Provider;

class MPEBanSystem extends PluginBase{

    private static Config $config;
    private static Provider $userData;

    public function onEnable() : void {

        $this->saveResource("config.yml");
        
        self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $user_data_type = self::$config->getNested("user-data.type");
        
        self::$userData = Provider::initProvider($this->getDataFolder(), $user_data_type);
        $this->registerCMD();
    }

    private function registerCMD()
    {

    }
}
