<?php

namespace XackiGiFF\MPEBanSystem\provider;

use pocketmine\utils\Config;

class YMLProvider extends Provider
{
    private static Provider $instance;
    private static Config $DB;
    protected string $type;

    public function __construct($path) {

        $logger = \GlobalLogger::get();
        $logger->alert("YMLProvider provider started");

        self::$DB = new Config($path . "userdata.yml", Config::YAML);
        $this->type = "yaml";
        self::$instance = $this;
        parent::__construct();
    }

    public function select(array $select, string $table, string $conditions): array
    {
        // TODO: Implement select() method.
    }

    public function insert(string $table, $array): void
    {
        // TODO: Implement insert() method.
    }

    public function update($id, string $table, $array): bool
    {
        // TODO: Implement update() method.
    }

    public function remove($id, string $table): void
    {
        // TODO: Implement remove() method.
    }
}