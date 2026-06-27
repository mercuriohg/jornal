<?php
require_once __DIR__ . '/Database.php';

class NewsController
{
    public static function readNews(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT * FROM news ORDER BY created_at DESC'
        );

        return self::rowsToNews($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function save(): void
    {
        $title = trim($_POST['title'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $tag = trim($_POST['tag'] ?? '');
        $type = trim($_POST['type'] ?? 'noticia');
        $image = trim($_POST['image'] ?? '');

        if ($title === '' || $summary === '' || $tag === '' || $type === '') {
            header('Location: /admin?error=missing');
            exit;
        }

        $imageUpload = self::processImageUpload($_FILES['image_file'] ?? []);
        if ($imageUpload) {
            $image = $imageUpload;
        }

        $attachments = self::processAttachments($_FILES['attachments'] ?? []);
        $attachmentsJson = self::encodeAttachments($attachments);
        $id = uniqid('news_', true);

        $stmt = Database::getConnection()->prepare(
            'INSERT INTO news (id, title, summary, content, tag, type, image, attachments, created_at)
             VALUES (:id, :title, :summary, :content, :tag, :type, :image, :attachments, :created_at)'
        );

        $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':summary' => $summary,
            ':content' => $content,
            ':tag' => $tag,
            ':type' => $type,
            ':image' => $image,
            ':attachments' => $attachmentsJson,
            ':created_at' => time(),
        ]);

        header('Location: /admin?success=1');
        exit;
    }

    public static function getLatestNews(int $limit = 3): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM news ORDER BY created_at DESC LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return self::rowsToNews($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function getNewsByType(string $type, int $limit = 3): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM news WHERE LOWER(type) = LOWER(:type) ORDER BY created_at DESC LIMIT :limit'
        );
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return self::rowsToNews($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function searchNews(string $query): array
    {
        $query = trim(mb_strtolower($query));
        if ($query === '') {
            return self::readNews();
        }

        $like = '%' . $query . '%';
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM news
             WHERE LOWER(title) LIKE :q
                OR LOWER(summary) LIKE :q
                OR LOWER(content) LIKE :q
                OR LOWER(tag) LIKE :q
             ORDER BY created_at DESC'
        );
        $stmt->execute([':q' => $like]);

        return self::rowsToNews($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function getNewsByTag(string $tag): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM news WHERE LOWER(tag) = LOWER(:tag) ORDER BY created_at DESC'
        );
        $stmt->execute([':tag' => $tag]);

        return self::rowsToNews($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function getNewsByTags(array $tags, int $limit = 3): array
    {
        if (empty($tags)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($tags), '?'));
        $sql = 'SELECT * FROM news WHERE LOWER(tag) IN (' . $placeholders . ') ORDER BY created_at DESC LIMIT ?';
        $stmt = Database::getConnection()->prepare($sql);

        $values = array_map('mb_strtolower', $tags);
        $values[] = $limit;
        $stmt->execute($values);

        return self::rowsToNews($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function getNewsById(string $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM news WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? self::hydrateNewsRow($row) : null;
    }

    public static function delete(string $id): void
    {
        if ($id === '') {
            header('Location: /admin?error=missing');
            exit;
        }

        $stmt = Database::getConnection()->prepare(
            'DELETE FROM news WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            header('Location: /admin?error=notfound');
            exit;
        }

        header('Location: /admin?deleted=1');
        exit;
    }

    public static function update(string $id): void
    {
        if ($id === '') {
            header('Location: /admin?error=missing');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $tag = trim($_POST['tag'] ?? '');
        $type = trim($_POST['type'] ?? 'noticia');
        $image = trim($_POST['image'] ?? '');

        if ($title === '' || $summary === '' || $tag === '' || $type === '') {
            header('Location: /admin?error=missing');
            exit;
        }

        $existing = self::getNewsById($id);
        if (!$existing) {
            header('Location: /admin?error=notfound');
            exit;
        }

        $imageUpload = self::processImageUpload($_FILES['image_file'] ?? []);
        if ($imageUpload) {
            $image = $imageUpload;
        }

        $attachments = $existing['attachments'];
        $newAttachments = self::processAttachments($_FILES['attachments'] ?? []);
        if (!empty($newAttachments)) {
            $attachments = array_values(array_merge($attachments, $newAttachments));
        }

        $stmt = Database::getConnection()->prepare(
            'UPDATE news
             SET title = :title,
                 summary = :summary,
                 content = :content,
                 tag = :tag,
                 type = :type,
                 image = :image,
                 attachments = :attachments
             WHERE id = :id'
        );

        $stmt->execute([
            ':title' => $title,
            ':summary' => $summary,
            ':content' => $content,
            ':tag' => $tag,
            ':type' => $type,
            ':image' => $image,
            ':attachments' => self::encodeAttachments($attachments),
            ':id' => $id,
        ]);

        header('Location: /admin?updated=1');
        exit;
    }

    private static function rowsToNews(array $rows): array
    {
        return array_map(function ($row) {
            return self::hydrateNewsRow($row);
        }, $rows);
    }

    private static function hydrateNewsRow(array $row): array
    {
        $row['attachments'] = json_decode($row['attachments'] ?? '[]', true);
        if (!is_array($row['attachments'])) {
            $row['attachments'] = [];
        }
        return $row;
    }

    private static function encodeAttachments(array $attachments): string
    {
        return json_encode(array_values($attachments), JSON_UNESCAPED_UNICODE);
    }

    private static function processAttachments(array $files): array
    {
        if (empty($files['name']) || !is_array($files['name'])) {
            return [];
        }

        $saved = [];
        $uploadDir = __DIR__ . '/../public/assets/uploads';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowed = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        foreach ($files['name'] as $index => $name) {
            if ($files['error'][$index] !== UPLOAD_ERR_OK) {
                continue;
            }

            $type = $files['type'][$index] ?? '';
            if (!in_array($type, $allowed, true)) {
                continue;
            }

            $tmpName = $files['tmp_name'][$index];
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            $baseName = pathinfo($name, PATHINFO_FILENAME);
            $fileName = self::sanitizeFilename($baseName) . '-' . uniqid() . '.' . $extension;
            $target = $uploadDir . '/' . $fileName;

            if (move_uploaded_file($tmpName, $target)) {
                $saved[] = '/assets/uploads/' . $fileName;
            }
        }

        return $saved;
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