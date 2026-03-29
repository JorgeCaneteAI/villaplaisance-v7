<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class MediaController extends AdminBaseController
{
    private const UPLOAD_DIR = ROOT . '/public/uploads/';
    private const MAX_SIZE = 5 * 1024 * 1024; // 5 Mo
    private const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/avif'];
    private const FOLDERS = ['general', 'chambres', 'villa', 'exterieurs', 'journal', 'surplace', 'hero', 'divers'];

    public function index(): void
    {
        $folder = $_GET['folder'] ?? '';
        $search = trim($_GET['q'] ?? '');

        $sql = "SELECT * FROM vp_media";
        $params = [];
        $where = [];

        if ($folder !== '') {
            $where[] = "folder = ?";
            $params[] = $folder;
        }
        if ($search !== '') {
            $where[] = "(filename LIKE ? OR alt_fr LIKE ? OR title LIKE ? OR tags LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $sql .= " ORDER BY created_at DESC";

        $media = [];
        try {
            $media = \Database::fetchAll($sql, $params);
        } catch (\Throwable) {}

        $folders = self::FOLDERS;
        $stats = $this->getStats();
        $csrf = $this->csrf();

        $this->render('admin/media/index', compact('media', 'folders', 'folder', 'search', 'stats', 'csrf'));
    }

    public function upload(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/media');
            return;
        }

        if (empty($_FILES['images'])) {
            $this->flash('error', 'Aucun fichier sélectionné.');
            $this->redirect('/admin/media');
            return;
        }

        $folder = $_POST['folder'] ?? 'general';
        $files = $_FILES['images'];
        $uploaded = 0;
        $errors = 0;

        // Normalize files array (multi-upload)
        $fileCount = is_array($files['name']) ? count($files['name']) : 1;

        for ($i = 0; $i < $fileCount; $i++) {
            $name = is_array($files['name']) ? $files['name'][$i] : $files['name'];
            $tmpName = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
            $size = is_array($files['size']) ? $files['size'][$i] : $files['size'];
            $error = is_array($files['error']) ? $files['error'][$i] : $files['error'];
            $type = is_array($files['type']) ? $files['type'][$i] : $files['type'];

            if ($error !== UPLOAD_ERR_OK || $size === 0) {
                $errors++;
                continue;
            }

            if ($size > self::MAX_SIZE) {
                $errors++;
                continue;
            }

            // Validate MIME from file content
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $realMime = $finfo->file($tmpName);
            if (!in_array($realMime, self::ALLOWED_MIME, true)) {
                $errors++;
                continue;
            }

            // Generate SEO-friendly filename
            $seoName = $this->seoFilename($name);
            $ext = $this->getExtension($realMime);
            $finalName = $seoName . '.' . $ext;

            // Avoid collisions
            $counter = 1;
            while (file_exists(self::UPLOAD_DIR . $finalName)) {
                $finalName = $seoName . '-' . $counter . '.' . $ext;
                $counter++;
            }

            // Convert to WebP if JPEG/PNG and GD available
            $converted = false;
            if (in_array($realMime, ['image/jpeg', 'image/png'], true) && function_exists('imagecreatefromjpeg')) {
                $webpName = $seoName . '.webp';
                $wcounter = 1;
                while (file_exists(self::UPLOAD_DIR . $webpName)) {
                    $webpName = $seoName . '-' . $wcounter . '.webp';
                    $wcounter++;
                }

                $converted = $this->convertToWebp($tmpName, self::UPLOAD_DIR . $webpName, $realMime);
                if ($converted) {
                    $finalName = $webpName;
                    $realMime = 'image/webp';
                }
            }

            // Move file if not already converted
            if (!$converted) {
                if (!move_uploaded_file($tmpName, self::UPLOAD_DIR . $finalName)) {
                    $errors++;
                    continue;
                }
            }

            // Get image dimensions
            $dims = @getimagesize(self::UPLOAD_DIR . $finalName);
            $width = $dims[0] ?? 0;
            $height = $dims[1] ?? 0;
            $finalSize = filesize(self::UPLOAD_DIR . $finalName);

            // Generate default alt from filename
            $defaultAlt = $this->filenameToAlt($seoName);

            // Insert into DB
            try {
                \Database::insert('vp_media', [
                    'filename' => $finalName,
                    'original_name' => $name,
                    'alt_fr' => $defaultAlt,
                    'title' => $defaultAlt,
                    'mime_type' => $realMime,
                    'file_size' => $finalSize ?: $size,
                    'width' => $width,
                    'height' => $height,
                    'folder' => $folder,
                    'seo_filename' => $seoName,
                ]);
                $uploaded++;
            } catch (\Throwable $e) {
                @unlink(self::UPLOAD_DIR . $finalName);
                $errors++;
            }
        }

        if ($uploaded > 0) {
            $this->flash('success', "{$uploaded} fichier(s) importé(s)" . ($errors > 0 ? " ({$errors} erreur(s))" : '') . '.');
        } else {
            $this->flash('error', 'Aucun fichier importé.');
        }

        $this->redirect('/admin/media' . ($folder !== 'general' ? '?folder=' . $folder : ''));
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/media');
            return;
        }

        $data = [
            'alt_fr' => trim($_POST['alt_fr'] ?? ''),
            'alt_en' => trim($_POST['alt_en'] ?? ''),
            'alt_es' => trim($_POST['alt_es'] ?? ''),
            'alt_de' => trim($_POST['alt_de'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'caption' => trim($_POST['caption'] ?? ''),
            'credit' => trim($_POST['credit'] ?? ''),
            'folder' => $_POST['folder'] ?? 'general',
            'tags' => trim($_POST['tags'] ?? ''),
        ];

        \Database::update('vp_media', $data, 'id = ?', [$id]);
        $this->flash('success', 'Média mis à jour.');
        $this->redirect('/admin/media');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/media');
            return;
        }

        $media = \Database::fetchOne("SELECT * FROM vp_media WHERE id = ?", [$id]);
        if ($media) {
            $filePath = self::UPLOAD_DIR . $media['filename'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            \Database::delete('vp_media', 'id = ?', [$id]);
            $this->flash('success', 'Média supprimé.');
        }

        $this->redirect('/admin/media');
    }

    public function edit(int $id): void
    {
        $media = \Database::fetchOne("SELECT * FROM vp_media WHERE id = ?", [$id]);
        if (!$media) {
            $this->flash('error', 'Média introuvable.');
            $this->redirect('/admin/media');
            return;
        }

        $folders = self::FOLDERS;
        $csrf = $this->csrf();
        $this->render('admin/media/edit', compact('media', 'folders', 'csrf'));
    }

    // --- Helpers ---

    private function seoFilename(string $name): string
    {
        $name = pathinfo($name, PATHINFO_FILENAME);
        $name = mb_strtolower($name);
        // Transliterate accents
        $name = str_replace(
            ['à', 'â', 'ä', 'é', 'è', 'ê', 'ë', 'î', 'ï', 'ô', 'ö', 'ù', 'û', 'ü', 'ç', 'ñ'],
            ['a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'c', 'n'],
            $name
        );
        // Replace non-alphanumeric with hyphens
        $name = preg_replace('/[^a-z0-9]+/', '-', $name);
        $name = trim($name, '-');
        // Prefix for SEO context
        return 'villa-plaisance-' . ($name ?: 'image');
    }

    private function filenameToAlt(string $seoName): string
    {
        // "villa-plaisance-chambre-deluxe" → "Villa Plaisance Chambre Deluxe"
        $words = explode('-', $seoName);
        return implode(' ', array_map('ucfirst', $words));
    }

    private function getExtension(string $mime): string
    {
        return match ($mime) {
            'image/webp' => 'webp',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/avif' => 'avif',
            default => 'webp',
        };
    }

    private function convertToWebp(string $source, string $dest, string $mime): bool
    {
        try {
            $image = match ($mime) {
                'image/jpeg' => imagecreatefromjpeg($source),
                'image/png' => imagecreatefrompng($source),
                default => false,
            };

            if (!$image) return false;

            // Quality 82 = good balance size/quality
            $result = imagewebp($image, $dest, 82);
            imagedestroy($image);

            return $result && file_exists($dest);
        } catch (\Throwable) {
            return false;
        }
    }

    private function getStats(): array
    {
        $stats = ['total' => 0, 'size' => 0, 'webp' => 0];
        try {
            $row = \Database::fetchOne("SELECT COUNT(*) as total, COALESCE(SUM(file_size), 0) as size FROM vp_media");
            $stats['total'] = (int)($row['total'] ?? 0);
            $stats['size'] = (int)($row['size'] ?? 0);
            $webp = \Database::fetchOne("SELECT COUNT(*) as c FROM vp_media WHERE mime_type = 'image/webp'");
            $stats['webp'] = (int)($webp['c'] ?? 0);
        } catch (\Throwable) {}
        return $stats;
    }

    public static function formatSize(int $bytes): string
    {
        if ($bytes < 1024) return $bytes . ' o';
        if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . ' Ko';
        return round($bytes / (1024 * 1024), 2) . ' Mo';
    }
}
