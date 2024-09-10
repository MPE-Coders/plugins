<?php

namespace XackiGiFF\MPEBanSystem\provider;

use PDO;
use PDOException;
use pocketmine\utils\Config;

class MySQLProvider extends Provider
{
    private static Provider $instance;
    private static Config $config;
    private static PDO $DB;
    protected string $type;

    public function __construct($path) {

        $logger = \GlobalLogger::get();
        $logger->alert("MySQL provider started");

        self::$config = new Config($path . 'config.yml', Config::YAML);

        $dsn = self::$config->getNested('user-data.dsn');
        $username = self::$config->getNested('user-data.username') ?? null;
        $password = self::$config->getNested('user-data.password') ?? null;

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            self::$DB = new PDO($dsn, $username, $password, $options);
            $logger->alert("MySQL database connected/created successfully");
        }
        catch(PDOException $e) {
            $logger->critical("Ошибка с хранением базы данных! Причина: " . $e->getMessage());
        }

        $this->type = "mysql";

        self::$instance = $this;
        parent::__construct();
    }

    public function select(array $select, string $table, string $conditions = ''): array
    {
        $columns = implode(", ", $select);
        $query = "SELECT $columns FROM " . $table;
        if ($conditions !== '') {
            $query .= " WHERE $conditions";
        }
        $stmt = self::$DB->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insert(string $table, $array): void
    {
        $columns = implode(", ", array_keys($array));
        $placeholders = implode(", ", array_fill(0, count($array), '?'));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = self::$DB->prepare($sql);
        $stmt->execute(array_values($array));
    }

    public function update($id, string $table, $array): bool
    {
        $set = [];
        foreach ($array as $key => $value) {
            $set[] = "{$key} = ?";
        }
        $set = implode(", ", $set);
        $sql = "UPDATE {$table} SET {$set} WHERE id = ?";
        $stmt = self::$DB->prepare($sql);
        return $stmt->execute(array_merge(array_values($array), [$id]));
    }

    public function remove($id, string $table): void
    {
        $sql = "DELETE FROM {$table} WHERE id = ?";
        $stmt = self::$DB->prepare($sql);
        $stmt->execute([$id]);
    }
}