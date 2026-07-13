<?php
class Database
{
    private static PDO .?$connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {

            $dsn = "mysql:host=db;dbname=jornalgremio;charset=utf8mb4";
            $username = "db_user";
            $password = "123";
            self::$connection = new PDO($dsn);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::createSchema(self::$connection);
        }

        return self::$connection;
    }
}
