<?php

namespace XackiGiFF\MPEBanSystem\provider;

use PDO;
use PDOException;
use pocketmine\Server;
use pocketmine\utils\Config;

class SQLite3Provider extends Provider
{
    private static Provider $instance;
    private static Config $config;
    private static PDO $DB;
    protected string $type;

    public function __construct($path) {

        $logger = \GlobalLogger::get();
        $logger->alert("SQLite3Provider provider started");

        self::$config = new Config($path . 'config.yml', Config::YAML);

        $dsn = self::$config->getNested('user-data.dsn') ?? "userdata.sqlite3";

        $databaseFilePath = $path . $dsn;

        // Проверим, существует ли файл базы данных

        if (!file_exists($databaseFilePath)) {
            // Если файл не существует, создадим его
            $handle = fopen($databaseFilePath, 'w');
            fclose($handle);
            $logger->alert("Создан новый файл базы данных по пути: " . $databaseFilePath);
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::SQLITE_ATTR_OPEN_FLAGS => PDO::SQLITE_OPEN_READWRITE | PDO::SQLITE_OPEN_CREATE,
        ];

        try {
            $logger->alert($databaseFilePath);
            self::$DB = new PDO("sqlite:" . $databaseFilePath, null, null, $options);
            $logger->alert("SQLite3 database connected/created successfully at " . $databaseFilePath);
            $this->initializeDatabase();
        }
        catch(PDOException $e) {
            $logger->critical("Ошибка с хранением базы данных! Причина: " . $e->getMessage());
        }

        $this->type = "sqlite3";
        self::$instance = $this;
        parent::__construct();
    }

    private function initializeDatabase(): void {
        $createTableQuery = "CREATE TABLE IF NOT EXISTS bans (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            player_name TEXT NOT NULL,
            player_ip TEXT NOT NULL,
            player_xuid TEXT NOT NULL,
            ban_type TEXT NOT NULL,
            ban_reason TEXT,
            ban_time INTEGER NOT NULL,
            ban_duration INTEGER,
            manager_name TEXT NOT NULL
        );";

        self::$DB->exec($createTableQuery);
    }

    public function insert(string $table, $array): void
    {
        $columns = implode(',', array_keys($array));
        $placeholders = implode(',', array_fill(0, count($array), '?'));
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $statement = self::$DB->prepare($query);
        $statement->execute(array_values($array));
    }

    public function select(array $select, string $table, string $conditions = ''): array
    {
        $columns = implode(', ', $select);
        $query = "SELECT $columns FROM " . $table;
        if ($conditions !== '') {
            $query .= " WHERE $conditions";
        }
        $stmt = self::$DB->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, string $table, $array): bool
    {
        $setClause = [];
        foreach ($array as $column => $value) {
            $setClause[] = "$column = :$column";
        }
        $setClause = implode(', ', $setClause);

        $query = "UPDATE $table SET $setClause WHERE id = :id";
        $stmt = self::$DB->prepare($query);

        foreach ($array as $column => $value) {
            $stmt->bindValue(":$column", $value);
        }
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function remove($id, string $table): void
    {
        $query = "DELETE FROM $table WHERE id = :id";
        $stmt = self::$DB->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}