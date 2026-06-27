<?php
require_once __DIR__ . '/Database.php';

class Migration
{
    public static function migrateJsonToSqlite(): void
    {
        self::migrateNews();
        self::migrateMembers();
        echo "Migração concluída.\n";
    }

    private static function migrateNews(): void
    {
        $file = __DIR__ . '/../data/news.json';
        if (!file_exists($file)) {
            echo "Arquivo news.json não encontrado.\n";
            return;
        }

        $content = file_get_contents($file);
        $news = json_decode($content, true);

        if (!is_array($news)) {
            echo "Formato inválido em news.json.\n";
            return;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('
            INSERT OR IGNORE INTO news (
                id, title, summary, content, tag, type, image, attachments, created_at
            ) VALUES (
                :id, :title, :summary, :content, :tag, :type, :image, :attachments, :created_at
            )
        ');

        foreach ($news as $item) {
            $stmt->execute([
                ':id' => $item['id'] ?? uniqid('news_', true),
                ':title' => $item['title'] ?? '',
                ':summary' => $item['summary'] ?? '',
                ':content' => $item['content'] ?? '',
                ':tag' => $item['tag'] ?? '',
                ':type' => $item['type'] ?? 'noticia',
                ':image' => $item['image'] ?? '',
                ':attachments' => json_encode($item['attachments'] ?? [], JSON_UNESCAPED_UNICODE),
                ':created_at' => $item['created_at'] ?? time(),
            ]);
        }

        echo "Notícias migradas.\n";
    }

    private static function migrateMembers(): void
    {
        $file = __DIR__ . '/../data/member.json';
        if (!file_exists($file)) {
            echo "Arquivo member.json não encontrado.\n";
            return;
        }

        $content = file_get_contents($file);
        $members = json_decode($content, true);

        if (!is_array($members)) {
            echo "Formato inválido em member.json.\n";
            return;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('
            INSERT OR IGNORE INTO members (
                id, name, role, bio, photo, created_at
            ) VALUES (
                :id, :name, :role, :bio, :photo, :created_at
            )
        ');

        foreach ($members as $member) {
            $stmt->execute([
                ':id' => $member['id'] ?? uniqid('member_', true),
                ':name' => $member['name'] ?? '',
                ':role' => $member['role'] ?? '',
                ':bio' => $member['bio'] ?? '',
                ':photo' => $member['photo'] ?? '',
                ':created_at' => $member['created_at'] ?? time(),
            ]);
        }

        echo "Membros migrados.\n";
    }
}