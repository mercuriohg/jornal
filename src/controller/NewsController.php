<?php
class NewsController
{
    private const DATA_FILE = __DIR__ . '/../data/news.json';

    public static function readNews(): array
    {
        if (!file_exists(self::DATA_FILE)) {
            return [];
        }

        $content = file_get_contents(self::DATA_FILE);
        $news = json_decode($content, true);

        return is_array($news) ? $news : [];
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

        $news = self::readNews();
        $news[] = [
            'id' => uniqid('news_', true),
            'title' => $title,
            'summary' => $summary,
            'content' => $content,
            'tag' => $tag,
            'type' => $type,
            'image' => $image,
            'attachments' => $attachments,
            'created_at' => time(),
        ];

        self::writeNews($news);

        header('Location: /admin?success=1');
        exit;
    }

    public static function getLatestNews(int $limit = 3): array
    {
        $news = self::readNews();

        usort($news, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return array_slice($news, 0, $limit);
    }

    public static function getNewsByType(string $type, int $limit = 3): array
    {
        $news = array_filter(self::readNews(), function ($item) use ($type) {
            return strcasecmp($item['type'] ?? 'noticia', $type) === 0;
        });

        usort($news, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return array_slice(array_values($news), 0, $limit);
    }

    public static function searchNews(string $query): array
    {
        $query = trim(mb_strtolower($query));
        if ($query === '') {
            return self::readNews();
        }

        $filtered = array_filter(self::readNews(), function ($item) use ($query) {
            $title = mb_strtolower($item['title'] ?? '');
            $summary = mb_strtolower($item['summary'] ?? '');
            $content = mb_strtolower($item['content'] ?? '');
            $tag = mb_strtolower($item['tag'] ?? '');

            return str_contains($title, $query)
                || str_contains($summary, $query)
                || str_contains($content, $query)
                || str_contains($tag, $query);
        });

        usort($filtered, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return array_values($filtered);
    }

    public static function getNewsByTag(string $tag): array
    {
        $news = array_filter(self::readNews(), function ($item) use ($tag) {
            return strcasecmp($item['tag'], $tag) === 0;
        });

        usort($news, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return array_values($news);
    }

    public static function getNewsByTags(array $tags, int $limit = 3): array
    {
        $normalized = array_map(fn($tag) => mb_strtolower(trim($tag)), $tags);

        $filtered = array_filter(self::readNews(), function ($item) use ($normalized) {
            return in_array(mb_strtolower($item['tag'] ?? ''), $normalized, true);
        });

        usort($filtered, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return array_slice(array_values($filtered), 0, $limit);
    }

    public static function getNewsById(string $id): ?array
    {
        foreach (self::readNews() as $news) {
            if ($news['id'] === $id) {
                return $news;
            }
        }

        return null;
    }

    public static function delete(string $id): void
    {
        if ($id === '') {
            header('Location: /admin?error=missing');
            exit;
        }

        $news = self::readNews();
        $filtered = array_filter($news, function ($item) use ($id) {
            return $item['id'] !== $id;
        });

        if (count($filtered) === count($news)) {
            header('Location: /admin?error=notfound');
            exit;
        }

        self::writeNews(array_values($filtered));
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

        $imageUpload = self::processImageUpload($_FILES['image_file'] ?? []);

        $news = self::readNews();
        $found = false;

        foreach ($news as &$item) {
            if ($item['id'] === $id) {
                $item['title'] = $title;
                $item['summary'] = $summary;
                $item['content'] = $content;
                $item['tag'] = $tag;
                $item['type'] = $type;
                if ($imageUpload) {
                    $item['image'] = $imageUpload;
                } else {
                    $item['image'] = $image;
                }
                $item['attachments'] = array_values(array_merge($item['attachments'] ?? [], self::processAttachments($_FILES['attachments'] ?? [])));
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            header('Location: /admin?error=notfound');
            exit;
        }

        self::writeNews($news);
        header('Location: /admin?updated=1');
        exit;
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

    private static function writeNews(array $news): void
    {
        $dir = dirname(self::DATA_FILE);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents(self::DATA_FILE, json_encode($news, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
