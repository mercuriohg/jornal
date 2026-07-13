<?php

require_once __DIR__ . '/../config/Database.php';

class MemberModel
{
    public static function all(): array
    {
        $stmt = Database::getConnection()->query(
            "SELECT * FROM members ORDER BY created_at DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(string $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT * FROM members WHERE id = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(array $data): bool
    {
        $stmt = Database::getConnection()->prepare(
            "INSERT INTO members
            (id, name, role, bio, photo, created_at)
            VALUES
            (:id, :name, :role, :bio, :photo, :created_at)"
        );

        return $stmt->execute($data);
    }

    public static function update(string $id, array $data): bool
    {
        $stmt = Database::getConnection()->prepare(
            "UPDATE members
             SET
                name = :name,
                role = :role,
                bio = :bio,
                photo = :photo
             WHERE id = :id"
        );

        $data[':id'] = $id;

        return $stmt->execute($data);
    }

    public static function delete(string $id): bool
    {
        $stmt = Database::getConnection()->prepare(
            "DELETE FROM members WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}