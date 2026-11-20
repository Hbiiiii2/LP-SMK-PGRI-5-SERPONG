<?php

namespace App\Controllers\Admin;

use App\Models\SettingModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class Settings extends BaseController
{
    /**
     * Upload size limit (bytes). The server may be stricter via
     * upload_max_filesize / post_max_size — see effectiveUploadBytes().
     */
    private const int MAX_UPLOAD_BYTES = 5242880; // 5 MB

    /** Total pixel limit so GD does not run out of memory while decoding. */
    private const int MAX_UPLOAD_PIXELS = 25000000; // ±25 megapiksel

    /**
     * Plain-text settings. A single source shared by index() and save(),
     * so the two key lists cannot drift apart.
     */
    private const array PLAIN_KEYS = [
        'site_name', 'site_tagline', 'site_description',
        'site_logo_text', 'site_logo_icon',
        'contact_phone', 'contact_email', 'contact_address', 'contact_hours',
        'social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok',
        'hero_badge', 'hero_title', 'hero_subtitle',
        'hero_btn_primary_text', 'hero_btn_primary_url',
        'hero_btn_secondary_text', 'hero_btn_secondary_url',
        'spmb_url',
        'footer_description', 'footer_copyright',
    ];

    /** Free-form JSON settings (no per-item validation). */
    private const array JSON_DATA_KEYS = ['counter_stats', 'hero_stats', 'page_banners', 'spmb_steps'];

    /** JSON settings shaped as a {label, url} list — their URLs are validated. */
    private const array JSON_LINK_KEYS = ['footer_services', 'footer_links'];

    /** Extra settings that have their own handling. */
    private const array EXTRA_KEYS = ['active_theme', 'about', 'principal'];

    /** Plain-text settings containing URLs (guarded against dangerous schemes). */
    private const array URL_KEYS = [
        'social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok',
        'hero_btn_primary_url', 'hero_btn_secondary_url',
        'spmb_url',
    ];

    /**
     * @var SettingModel
     */
    protected $settingModel;

    /**
     * Initialize the controller with the SettingModel.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     */
    public function initController($request, $response, $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->settingModel = new SettingModel();
    }

    /**
     * Display the settings management page.
     *
     * @return string
     */
    public function index(): string
    {
        helper('theme');

        $keys = array_merge(self::PLAIN_KEYS, self::JSON_DATA_KEYS, self::JSON_LINK_KEYS, self::EXTRA_KEYS);

        $this->data['title'] = 'Pengaturan';
        $this->data['settings'] = $this->settingModel->getMany($keys);
        $this->data['uploadLimit'] = self::humanBytes($this->effectiveUploadBytes());

        return view('admin/settings', $this->data);
    }

    /**
     * Save all settings from the settings form.
     *
     * Stores valid values; rejected values (dangerous URL schemes, invalid
     * email, failed uploads) do not overwrite the old value and are reported
     * through an "error" flash message.
     *
     * @return RedirectResponse
     */
    public function save(): RedirectResponse
    {
        /** @var list<string> $errors */
        $errors = [];

        // (1) Free-form JSON settings. A key that is not submitted means the user
        // removed all of its rows → storing an empty list.
        foreach (self::JSON_DATA_KEYS as $jsonKey) {
            $val = $this->request->getPost($jsonKey);
            if ($val === null) {
                $this->settingModel->setSetting($jsonKey, '[]');
            } elseif (is_array($val)) {
                $this->settingModel->setSetting($jsonKey, $this->encodeJson($val));
            }
        }

        // (2) JSON settings holding footer links → validate the URL scheme.
        foreach (self::JSON_LINK_KEYS as $linkKey) {
            $val = $this->request->getPost($linkKey);
            if ($val === null) {
                $this->settingModel->setSetting($linkKey, '[]');
                continue;
            }
            if (!is_array($val)) {
                continue;
            }

            $clean = [];
            foreach (array_slice($val, 0, 10) as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $label = trim((string)($item['label'] ?? ''));
                $url = trim((string)($item['url'] ?? ''));

                if ($url !== '' && !is_safe_link_url($url)) {
                    $errors[] = 'Tautan footer "' . ($label !== '' ? $label : '(tanpa label)')
                        . '" memakai skema URL yang tidak diizinkan; diubah menjadi "#".';
                    $url = '#';
                }

                $clean[] = ['label' => $label, 'url' => $url];
            }

            $this->settingModel->setSetting($linkKey, $this->encodeJson($clean));
        }

        // (3) Active frontend theme (whitelist, clear cache when it changes)
        $newTheme = $this->request->getPost('active_theme');
        if (is_string($newTheme) && in_array($newTheme, array_keys(theme_list()), true)) {
            $oldTheme = $this->settingModel->get('active_theme');
            if ($oldTheme !== $newTheme) {
                $this->settingModel->setSetting('active_theme', $newTheme);
                try {
                    cache()->clean();
                } catch (Throwable) {
                    // ignore cache backend failures
                }
            }
        }

        // (4) "About Us" section / school profile
        $about = $this->request->getPost('about');
        if (is_array($about)) {
            $currentImage = (string)($about['current_image'] ?? '');
            unset($about['current_image']);

            $about['image'] = $this->handleUpload(
                'about_image',
                'uploads/about',
                1200,
                $currentImage,
                'Gambar Tentang Kami',
                $errors,
            );

            if (isset($about['highlights_text'])) {
                $lines = array_map('trim', explode("\n", (string)$about['highlights_text']));
                // Strict filter: array_filter() without a callback also drops "0".
                $about['highlights'] = array_values(array_filter($lines, static fn($l): bool => $l !== ''));
                unset($about['highlights_text']);
            }

            // Merge with the old data so keys missing from the form
            // (e.g. data from older versions) are not deleted when saving.
            $this->settingModel->setSetting(
                'about',
                $this->encodeJson($this->mergeJson('about', $about)),
            );
        }

        // (5) Principal
        $principal = $this->request->getPost('principal');
        if (is_array($principal)) {
            $currentPhoto = (string)($principal['current_image'] ?? '');
            unset($principal['current_image']);

            $principal['photo'] = $this->handleUpload(
                'principal_photo',
                'uploads/principal',
                600,
                $currentPhoto,
                'Foto Kepala Sekolah',
                $errors,
            );

            $this->settingModel->setSetting(
                'principal',
                $this->encodeJson($this->mergeJson('principal', $principal)),
            );
        }

        // (6) Plain-text settings (+ server-side URL and email validation)
        foreach (self::PLAIN_KEYS as $key) {
            $value = $this->request->getPost($key);
            if (!is_string($value)) {
                continue;
            }

            if ($value !== '' && in_array($key, self::URL_KEYS, true) && !is_safe_link_url($value)) {
                $errors[] = 'Nilai "' . $key . '" bukan tautan yang diizinkan (hanya http/https/mailto/tel,'
                    . ' anchor #, atau path relatif); nilai lama dipertahankan.';
                continue;
            }

            if ($key === 'contact_email') {
                $value = trim($value);
                if ($value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
                    $errors[] = 'Alamat email kontak tidak valid; nilai lama dipertahankan.';
                    continue;
                }
            }

            $this->settingModel->setSetting($key, $value);
        }

        if ($errors !== []) {
            return redirect()->to('/admin/settings')
                ->with('error', 'Sebagian pengaturan tidak disimpan. ' . implode(' ', $errors));
        }

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Handle a single image upload: validate, resize, delete the old image.
     * Returns the new image URL, or $currentImage when there is no file /
     * the upload failed (also records the message in $errors).
     *
     * @param string $fieldName Form field name.
     * @param string $subdir FCPATH-relative subfolder, e.g. "uploads/about".
     * @param int $maxWidth Maximum result width (px).
     * @param string $currentImage Current image URL.
     * @param string $label Human-readable label for error messages.
     * @param list<string> $errors Error message collector (by reference).
     *
     * @return string
     */
    private function handleUpload(
        string $fieldName,
        string $subdir,
        int    $maxWidth,
        string $currentImage,
        string $label,
        array  &$errors,
    ): string
    {
        $file = $this->request->getFile($fieldName);
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return $currentImage;
        }

        $problem = $this->validateImageUpload($file);
        if ($problem !== null) {
            $errors[] = $label . ': ' . $problem . '. Gambar lama dipertahankan.';

            return $currentImage;
        }

        if (!$file->isValid() || $file->hasMoved()) {
            return $currentImage;
        }

        $uploaded = $this->uploadImage($file, $subdir, $maxWidth, $currentImage);
        if ($uploaded === null) {
            $errors[] = $label . ' gagal diproses. Gambar lama dipertahankan.';

            return $currentImage;
        }

        return $uploaded;
    }

    /**
     * Validate the uploaded file before processing.
     *
     * @param UploadedFile $file
     *
     * @return string|null Error message (without a trailing period), or null when valid.
     */
    private function validateImageUpload(UploadedFile $file): ?string
    {
        if (!$file->isValid()) {
            return match ($file->getError()) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'ukuran berkas melebihi batas server',
                UPLOAD_ERR_PARTIAL => 'unggahan tidak selesai (terputus)',
                UPLOAD_ERR_CANT_WRITE => 'gagal menulis berkas di server',
                UPLOAD_ERR_NO_TMP_DIR => 'folder sementara server tidak tersedia',
                UPLOAD_ERR_EXTENSION => 'unggahan dihentikan oleh ekstensi PHP',
                default => 'gagal diunggah (kode ' . $file->getError() . ')',
            };
        }

        if ($file->getSize() > $this->effectiveUploadBytes()) {
            return 'ukuran melebihi batas ' . self::humanBytes($this->effectiveUploadBytes());
        }

        $info = @getimagesize($file->getTempName());
        if ($info === false) {
            return 'berkas bukan gambar yang dikenali';
        }

        if (!in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            return 'format harus JPG, PNG, atau WebP';
        }

        if (($info[0] * $info[1]) > self::MAX_UPLOAD_PIXELS) {
            return 'dimensi terlalu besar (maks ±' . (int)(self::MAX_UPLOAD_PIXELS / 1000000) . ' MP); perkecil dahulu';
        }

        return null;
    }

    /**
     * Resize and save an uploaded image (used by the "About Us" and
     * "Principal" sections — the logic is no longer duplicated).
     *
     * @param UploadedFile $file
     * @param string $subdir FCPATH-relative subfolder.
     * @param int $maxWidth Maximum result width (px).
     * @param string $currentImage Current image URL.
     *
     * @return string|null New image URL, or null on failure.
     */
    private function uploadImage(UploadedFile $file, string $subdir, int $maxWidth, string $currentImage): ?string
    {
        $uploadPath = rtrim(FCPATH . $subdir, '/');
        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true) && !is_dir($uploadPath)) {
            return null;
        }

        $tempPath = $file->getTempName();

        $srcInfo = @getimagesize($tempPath);
        if ($srcInfo === false || !in_array($srcInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            return null;
        }

        [$origWidth, $origHeight, $type] = $srcInfo;
        $newName = $this->buildSafeName($type);

        try {
            if ($origWidth > $maxWidth) {
                $ratio = $maxWidth / $origWidth;
                $newWidth = $maxWidth;
                $newHeight = (int)round($origHeight * $ratio);

                $srcImage = match ($type) {
                    IMAGETYPE_JPEG => @imagecreatefromjpeg($tempPath),
                    IMAGETYPE_PNG => @imagecreatefrompng($tempPath),
                    IMAGETYPE_WEBP => @imagecreatefromwebp($tempPath),
                    default => null,
                };

                // GD failed to decode → save the original file, do not fail silently.
                if (!$srcImage) {
                    $file->move($uploadPath, $newName);
                } else {
                    $dstImage = imagecreatetruecolor($newWidth, $newHeight);

                    if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
                        imagealphablending($dstImage, false);
                        imagesavealpha($dstImage, true);
                    }

                    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

                    $destPath = $uploadPath . '/' . $newName;
                    $written = match ($type) {
                        IMAGETYPE_JPEG => imagejpeg($dstImage, $destPath, 85),
                        IMAGETYPE_PNG => imagepng($dstImage, $destPath, 8),
                        IMAGETYPE_WEBP => imagewebp($dstImage, $destPath, 85),
                        default => false,
                    };

                    imagedestroy($srcImage);
                    imagedestroy($dstImage);

                    if (!$written || !is_file($destPath)) {
                        if (is_file($destPath)) {
                            @unlink($destPath);
                        }
                        if (!$file->hasMoved()) {
                            $file->move($uploadPath, $newName);
                        }
                    }
                }
            } else {
                $file->move($uploadPath, $newName);
            }
        } catch (Throwable $e) {
            log_message('error', 'Gagal menyimpan gambar setting: {msg}', ['msg' => $e->getMessage()]);

            return null;
        }

        // Delete the old image only when it is in the same folder.
        if ($currentImage !== '' && str_starts_with($currentImage, base_url($subdir . '/'))) {
            $oldPath = $uploadPath . '/' . basename($currentImage);
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return base_url($subdir . '/' . $newName);
    }

    /**
     * Encode a setting value to JSON.
     *
     * @param mixed $data
     *
     * @return string
     */
    private function encodeJson(mixed $data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($json !== false) {
            return $json;
        }

        // json_encode() failed (e.g. input is not valid UTF-8) → do not overwrite
        // the old setting with the wrong type; store an empty value of the same shape.
        return is_array($data) && array_is_list($data) ? '[]' : '{}';
    }

    /**
     * Merge new data into the old JSON value (new values win).
     *
     * Used for "About Us" and "Principal" so keys that are not rendered in the
     * form — e.g. leftovers from older versions such as `role_title`,
     * `sambutan`, or `welcome_message` — are not deleted when saving.
     *
     * @param array<string, mixed> $new
     *
     * @return array<string, mixed>
     */
    private function mergeJson(string $key, array $new): array
    {
        $old = $this->settingModel->getJson($key, []);

        return is_array($old) ? array_merge($old, $new) : $new;
    }

    /**
     * Effective upload size limit: the minimum of the application limit and the
     * PHP limit (upload_max_filesize / post_max_size). 0 = unlimited.
     *
     * @return int
     */
    private function effectiveUploadBytes(): int
    {
        $limits = [];
        foreach (['upload_max_filesize', 'post_max_size'] as $ini) {
            $bytes = self::parseIniBytes((string)ini_get($ini));
            if ($bytes > 0) {
                $limits[] = $bytes;
            }
        }

        $limit = $limits === [] ? self::MAX_UPLOAD_BYTES : min($limits);

        return (int)min($limit, self::MAX_UPLOAD_BYTES);
    }

    /**
     * Convert a php.ini shorthand value (e.g. "2M", "512K") to bytes.
     *
     * @param string $value
     *
     * @return int 0 when unlimited / unrecognized.
     */
    private static function parseIniBytes(string $value): int
    {
        $value = trim($value);
        if ($value === '' || $value === '-1') {
            return 0;
        }

        $num = (float)$value;
        $unit = strtolower(substr($value, -1));

        return (int)match ($unit) {
            'g' => $num * 1073741824,
            'm' => $num * 1048576,
            'k' => $num * 1024,
            default => $num,
        };
    }

    /**
     * Format bytes as compact text (e.g. 2 MB, 512 KB).
     *
     * @param float $bytes
     *
     * @return string
     */
    private static function humanBytes(float $bytes): string
    {
        if ($bytes >= 1048576) {
            return rtrim(rtrim(number_format($bytes / 1048576, 1, ',', '.'), '0'), ',') . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0, ',', '.') . ' KB';
        }

        return (int)$bytes . ' B';
    }
}
