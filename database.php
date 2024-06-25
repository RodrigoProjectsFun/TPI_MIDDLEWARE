<?php
class Database
{
    private static $dbName = 'nodemcu_rfidrc522_mysql';
    private static $dbHost = 'localhost';
    private static $dbUsername = 'smart_admin';
    private static $dbUserPassword = 'smart_admin';

    private static $cont = null;

    public function __construct() {
        die('Init function is not allowed');
    }

    public static function connect()
    {
        if (null == self::$cont) {
            try {
                self::$cont  = new PDO("mysql:host=" . self::$dbHost . ";" . "dbname=" . self::$dbName, self::$dbUsername, self::$dbUserPassword);
                self::$cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$cont;
    }

    public static function disconnect()
    {
        self::$cont = null;
    }
}
?>
