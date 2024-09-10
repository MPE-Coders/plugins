<?php

namespace XackiGiFF\MPEBanSystem\provider;

use XackiGiFF\MPEBanSystem\api\player\BPlayer;

abstract class Provider
{
    const JSON = 'json';
    const YML = 'yaml';
    const SQLITE3 = 'sqlite3';
    const MYSQL = 'mysql';
    private static Provider $instance;

    public function __construct() {
        self::$instance = $this;
    }

    public static function getInstance(): object
    {
        return static::$instance;
    }

    public static function initProvider($path, $type) : Provider
    {
        switch ($type) {
            default:
            case self::JSON:
                return new JsonProvider($path);
                // TODO;
                break;
            case self::YML:
                return new YMLProvider($path);
                // TODO;
                break;
            case self::SQLITE3:
                return new SQLite3Provider($path);
                // TODO;
                break;
            case self::MYSQL:
                return new MySQLProvider($path);
                // TODO;
                break;
        }
        // TODO;
    }

    public function getType() : string {
        return static::getInstance()->type;
    }

    abstract public function select( array $select, string $table, string $conditions ) : array;

    abstract public function insert( string $table, $array ) : void;

    abstract public function update( $id, string $table, $array ) : bool;

    abstract public function remove( $id, string $table ) : void;

}