<?php
class Database
{
    private const DB_FILE = __DIR__ . '/../data/jornalgremio.sqlite';
    private static $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $dir = dirname(self::DB_FILE);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $dsn = 'sqlite:' . self::DB_FILE;
            self::$connection = new PDO($dsn);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            self::createSchema(self::$connection);
        }

        return self::$connection;
    }

    private static function createSchema(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS news (
                id TEXT PRIMARY KEY,
                title TEXT NOT NULL,
                summary TEXT NOT NULL,
                content TEXT,
                tag TEXT NOT NULL,
                type TEXT NOT NULL,
                image TEXT,
                attachments TEXT,
                event_date INTEGER,
                created_at INTEGER NOT NULL
            )'
        );

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS members (
                id TEXT PRIMARY KEY,
                name TEXT NOT NULL,
                role TEXT NOT NULL,
                bio TEXT NOT NULL,
                photo TEXT,
                created_at INTEGER NOT NULL
            )'
        );

        self::ensureNewsSchema($pdo);
    }

    private static function ensureNewsSchema(PDO $pdo): void
    {
        $stmt = $pdo->query('PRAGMA table_info(news)');
        $columns = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'name');

        if (!in_array('event_date', $columns, true)) {
            $pdo->exec('ALTER TABLE news ADD COLUMN event_date INTEGER');
        }
    }
    }
