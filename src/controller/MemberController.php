<?php
class MemberController
{
    private const DATA_FILE = __DIR__ . '/../data/member.json';

    public static function readMembers(): array
    {
        if (!file_exists(self::DATA_FILE)) {
            return [];
        }

        $content = file_get_contents(self::DATA_FILE);
        $members = json_decode($content, true);

        return is_array($members) ? $members : [];
    }

    public static function getMembers(): array
    {
        $members = self::readMembers();

        usort($members, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return $members;
    }

    public static function getMemberById(string $id): ?array
    {
        foreach (self::readMembers() as $member) {
            if ($member['id'] === $id) {
                return $member;
            }
        }

        return null;
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

        $members = self::readMembers();
        $members[] = [
            'id' => uniqid('member_', true),
            'name' => $name,
            'role' => $role,
            'bio' => $bio,
            'photo' => $photo,
            'created_at' => time(),
        ];

        self::writeMembers($members);

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

        $photoUpload = self::processImageUpload($_FILES['photo_file'] ?? []);

        $members = self::readMembers();
        $found = false;

        foreach ($members as &$member) {
            if ($member['id'] === $id) {
                $member['name'] = $name;
                $member['role'] = $role;
                $member['bio'] = $bio;
                if ($photoUpload) {
                    $member['photo'] = $photoUpload;
                } else {
                    $member['photo'] = $photo;
                }
                $found = true;
                break;
            }
        }
        unset($member);

        if (!$found) {
            header('Location: /admin-members?error=notfound');
            exit;
        }

        self::writeMembers($members);
        header('Location: /admin-members?updated=1');
        exit;
    }

    public static function delete(string $id): void
    {
        if ($id === '') {
            header('Location: /admin-members?error=missing');
            exit;
        }

        $members = self::readMembers();
        $filtered = array_filter($members, function ($item) use ($id) {
            return $item['id'] !== $id;
        });

        if (count($filtered) === count($members)) {
            header('Location: /admin-members?error=notfound');
            exit;
        }

        self::writeMembers(array_values($filtered));
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

    private static function writeMembers(array $members): void
    {
        $dir = dirname(self::DATA_FILE);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents(self::DATA_FILE, json_encode($members, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
