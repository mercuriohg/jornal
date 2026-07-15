<?php

require_once __DIR__ . '/../controller/Database.php';

class NewsModel
{
    public static function all(): array
    {
        $stmt = Database::getConnection()->query(
            "SELECT * FROM news ORDER BY created_at DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function latest(int $limit): array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT * FROM news
             ORDER BY created_at DESC
             LIMIT :limit"
        );

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(string $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT * FROM news
             WHERE id = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function findByType(string $type, int $limit): array
    {
        if (mb_strtolower($type) === 'evento') {

            $stmt = Database::getConnection()->prepare(
                "SELECT *
                 FROM news
                 WHERE LOWER(type)=LOWER(:type)
                 ORDER BY COALESCE(event_date, created_at) ASC
                 LIMIT :limit"
            );

        } else {

            $stmt = Database::getConnection()->prepare(
                "SELECT *
                 FROM news
                 WHERE LOWER(type)=LOWER(:type)
                 ORDER BY created_at DESC
                 LIMIT :limit"
            );

        }

        $stmt->bindValue(':type', $type);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByTag(string $tag): array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT *
             FROM news
             WHERE LOWER(tag)=LOWER(:tag)
             ORDER BY created_at DESC"
        );

        $stmt->execute([
            ':tag' => $tag
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function search(string $query): array
    {
        $like = "%" . mb_strtolower($query) . "%";

        $stmt = Database::getConnection()->prepare(
            "SELECT *
             FROM news
             WHERE
                LOWER(title) LIKE :q
                OR LOWER(summary) LIKE :q
                OR LOWER(content) LIKE :q
                OR LOWER(tag) LIKE :q
             ORDER BY created_at DESC"
        );

        $stmt->execute([
            ':q' => $like
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(array $data): bool
    {
        $stmt = Database::getConnection()->prepare(
            "INSERT INTO news
            (
                id,
                title,
                summary,
                content,
                tag,
                type,
                image,
                attachments,
                event_date,
                created_at
            )
            VALUES
            (
                :id,
                :title,
                :summary,
                :content,
                :tag,
                :type,
                :image,
                :attachments,
                :event_date,
                :created_at
            )"
        );

        return $stmt->execute([
            ':id' => $data['id'],
            ':title' => $data['title'],
            ':summary' => $data['summary'],
            ':content' => $data['content'],
            ':tag' => $data['tag'],
            ':type' => $data['type'],
            ':image' => $data['image'],
            ':attachments' => $data['attachments'],
            ':event_date' => $data['event_date'],
            ':created_at' => $data['created_at'],
        ]);
    }

    public static function update(string $id, array $data): bool
    {
        $stmt = Database::getConnection()->prepare(
            "UPDATE news
             SET
                title = :title,
                summary = :summary,
                content = :content,
                tag = :tag,
                type = :type,
                image = :image,
                attachments = :attachments,
                event_date = :event_date
             WHERE id = :id"
        );

        return $stmt->execute([
            ':title' => $data['title'],
            ':summary' => $data['summary'],
            ':content' => $data['content'],
            ':tag' => $data['tag'],
            ':type' => $data['type'],
            ':image' => $data['image'],
            ':attachments' => $data['attachments'],
            ':event_date' => $data['event_date'],
            ':id' => $id,
        ]);
    }

    public static function delete(string $id): bool
    {
        $stmt = Database::getConnection()->prepare(
            "DELETE FROM news
             WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => $id
        ]);
    }
    public static function findByTags(array $tags, int $limit): array
{
    if (empty($tags)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($tags), '?'));

    $sql = "SELECT *
            FROM news
            WHERE LOWER(tag) IN ($placeholders)
            ORDER BY created_at DESC
            LIMIT ?";

    $stmt = Database::getConnection()->prepare($sql);

    $values = array_map('mb_strtolower', $tags);
    $values[] = $limit;

    $stmt->execute($values);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
}
