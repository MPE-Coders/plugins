<?php

namespace XackiGiFF\MPEBanSystem\provider;

use pocketmine\utils\Config;

class JsonProvider extends Provider
{
    private static Provider $instance;
    private static Config $config;
    protected string $type;

    public function __construct(string $path) {

        $logger = \GlobalLogger::get();
        $logger->alert("JsonProvider provider started");

        self::$config = new Config($path . 'UserData.json', Config::JSON);

        $this->type = "json";
        self::$instance = $this;
        parent::__construct();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function select(array $select, string $table, string $conditions): array
    {
        // TODO: Implement select() method.
    }

    public function insert(string $table, $array): void
    {
        
        // TODO: Implement insert() method.
    }

    public function update($id, string $table, $array): string
    {
        // TODO: Implement update() method.
    }

    public function remove($id, string $table): void
    {
        // TODO: Implement remove() method.
    }
}