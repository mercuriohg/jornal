<?php
require_once __DIR__ . '/Database.php';

class MemberController
{
    public static function readMembers(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT * FROM members ORDER BY created_at DESC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMembers(): array
    {
        return self::readMembers();
    }

    public static function getMemberById(string $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM members WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);

        $member = $stmt->fetch(PDO::FETCH_ASSOC);
        return $member ?: null;
    }

    public static function save(): void
    {
        $name = trim($_POST['name'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $photo = trim($_POST['photo'] ?? '');

        if ($name === '' || $role === '' || $bio === '') {
            header('Location: /admin-members?error=missing');
            exit;
        }

        $photoUpload = self::processImageUpload($_FILES['photo_file'] ?? []);
        if ($photoUpload) {
            $photo = $photoUpload;
        }

        $stmt = Database::getConnection()->prepare(
            'INSERT INTO members (id, name, role, bio, photo, created_at)
             VALUES (:id, :name, :role, :bio, :photo, :created_at)'
        );

        $stmt->execute([
            ':id' => uniqid('member_', true),
            ':name' => $name,
            ':role' => $role,
            ':bio' => $bio,
            ':photo' => $photo,
            ':created_at' => time(),
        ]);

        header('Location: /admin-members?success=1');
        exit;
    }

    public static function update(string $id): void
    {
        if ($id === '') {
            header('Location: /admin-members?error=missing');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $photo = trim($_POST['photo'] ?? '');

        if ($name === '' || $role === '' || $bio === '') {
            header('Location: /admin-members?error=missing');
            exit;
        }

        $existing = self::getMemberById($id);
        if (!$existing) {
            header('Location: /admin-members?error=notfound');
            exit;
        }

        $photoUpload = self::processImageUpload($_FILES['photo_file'] ?? []);
        if ($photoUpload) {
            $photo = $photoUpload;
        }

        $stmt = Database::getConnection()->prepare(
            'UPDATE members
             SET name = :name,
                 role = :role,
                 bio = :bio,
                 photo = :photo
             WHERE id = :id'
        );

        $stmt->execute([
            ':name' => $name,
            ':role' => $role,
            ':bio' => $bio,
            ':photo' => $photo,
            ':id' => $id,
        ]);

        header('Location: /admin-members?updated=1');
        exit;
    }

    public static function delete(string $id): void
    {
        if ($id === '') {
            header('Location: /admin-members?error=missing');
            exit;
        }

        $stmt = Database::getConnection()->prepare(
            'DELETE FROM members WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            header('Location: /admin-members?error=notfound');
            exit;
        }

        header('Location: /admin-members?deleted=1');
        exit;
    }

    private static function processImageUpload(array $file): string
    {
        if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        $allowed = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        $type = $file['type'] ?? '';
        if (!in_array($type, $allowed, true)) {
            return '';
        }

        $uploadDir = __DIR__ . '/../public/assets/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $baseName = pathinfo($file['name'], PATHINFO_FILENAME);
        $fileName = self::sanitizeFilename($baseName) . '-' . uniqid() . '.' . $extension;
        $target = $uploadDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return '/assets/uploads/' . $fileName;
        }

        return '';
    }

    private static function sanitizeFilename(string $filename): string
    {
        return preg_replace('/[^a-zA-Z0-9_-]/', '-', $filename);
    }
}