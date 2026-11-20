<?php

namespace App\Controllers\Admin;

use App\Models\ProgramModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Programs extends BaseController
{
    protected $programModel;

    public function initController($request, $response, $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->programModel = new ProgramModel();
    }

    public function index(): string
    {
        $this->data['title'] = 'Program Unggulan';
        $this->data['items'] = $this->programModel->orderBy('sort_order', 'ASC')->paginate(10);
        $this->data['pager'] = $this->programModel->pager;

        return view('admin/programs/index', $this->data);
    }

    public function new(): string
    {
        $this->data['title'] = 'Tambah Program';
        $this->data['item'] = [];
        return view('admin/programs/form', $this->data);
    }

    public function create(): RedirectResponse
    {
        [$data, $errorMsg] = $this->buildPayloadFromPost(null);
        if ($errorMsg !== null) {
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        $file = $this->request->getFile('image_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploaded = $this->uploadAndResizeImage($file);
            if ($uploaded === '') {
                return redirect()->back()->withInput()->with('error', 'File gambar harus berupa JPG, PNG, atau WEBP.');
            }
            $data['image'] = $uploaded;
        }

        if (!$this->programModel->insert($data)) {
            if (!empty($data['image']) && !preg_match('#^https?://#i', $data['image'])) {
                $this->deleteOldImageFile($data['image']);
            }
            $errors = $this->programModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan program.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/programs')->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(int $id): string|RedirectResponse
    {
        $this->data['title'] = 'Edit Program';
        $this->data['item'] = $this->programModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/programs')->with('error', 'Program tidak ditemukan.');
        }

        return view('admin/programs/form', $this->data);
    }

    public function update(int $id): RedirectResponse
    {
        $existing = $this->programModel->find($id);
        if (!$existing) {
            return redirect()->to('/admin/programs')->with('error', 'Program tidak ditemukan.');
        }

        [$data, $errorMsg] = $this->buildPayloadFromPost($id);
        if ($errorMsg !== null) {
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        $oldImage = $existing['image'] ?? null;
        $newUploadedFile = null;
        $removeImage = (bool)$this->request->getPost('remove_image');

        if ($removeImage) {
            $data['image'] = null;
        }

        $file = $this->request->getFile('image_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploaded = $this->uploadAndResizeImage($file);
            if ($uploaded === '') {
                return redirect()->back()->withInput()->with('error', 'File gambar harus berupa JPG, PNG, atau WEBP.');
            }
            $newUploadedFile = $uploaded;
            $data['image'] = $uploaded;
        }

        if (!$this->programModel->update($id, $data)) {
            if ($newUploadedFile !== null) {
                $this->deleteOldImageFile($newUploadedFile);
            }
            $errors = $this->programModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui program.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        if (($removeImage || $newUploadedFile !== null) && !empty($oldImage) && $oldImage !== ($data['image'] ?? '')) {
            $this->deleteOldImageFile($oldImage);
        }

        return redirect()->to('/admin/programs')->with('success', 'Program berhasil diperbarui.');
    }

    public function reorder(): ResponseInterface
    {
        $input = json_decode($this->request->getBody(), true);
        if (!is_array($input)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Payload tidak valid.']);
        }

        $ids = $this->sanitizeReorderIds($input['ids'] ?? null);
        if ($ids === null) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Daftar ID tidak valid.']);
        }

        if (!$this->reorderIdsExist($this->programModel, $ids)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Sebagian ID tidak ditemukan.']);
        }

        $offset = max(0, (int)($input['offset'] ?? 0));
        $this->programModel->reorder($ids, $offset);

        return $this->response->setJSON(['success' => true, 'csrfHash' => csrf_hash()]);
    }

    public function delete(int $id): RedirectResponse
    {
        $existing = $this->programModel->find($id);
        if (!$this->programModel->delete($id)) {
            return redirect()->to('/admin/programs')->with('error', 'Gagal menghapus program.');
        }

        if ($existing && !empty($existing['image'])) {
            $this->deleteOldImageFile($existing['image']);
        }

        return redirect()->to('/admin/programs')->with('success', 'Program berhasil dihapus.');
    }

    /**
     * Collect and validate program fields from POST request.
     *
     * @return array{0: array, 1: ?string}
     */
    private function buildPayloadFromPost(?int $excludeId): array
    {
        $title = trim((string)($this->request->getPost('title') ?? ''));
        $rawSlug = trim((string)($this->request->getPost('slug') ?? ''));
        $slugSource = $rawSlug !== '' ? $rawSlug : $title;
        $slug = $this->programModel->generateSlug($slugSource !== '' ? $slugSource : 'program', $excludeId);

        $mediaType = $this->request->getPost('media_type') === 'image' ? 'image' : 'icon';
        $linkUrl = trim((string)($this->request->getPost('link_url') ?? ''));

        if ($this->isDangerousUrl($linkUrl)) {
            return [[], 'URL tautan tidak valid (skema tidak diizinkan).'];
        }

        $existingImg = trim((string)($this->request->getPost('existing_image') ?? ''));
        if ($existingImg !== '' && $this->isDangerousUrl($existingImg)) {
            return [[], 'URL foto tidak valid (skema tidak diizinkan).'];
        }

        $data = [
            'title'            => $title,
            'slug'             => $slug,
            'description'      => $this->request->getPost('description'),
            'content'          => $this->request->getPost('content'),
            'learning_points'  => $this->request->getPost('learning_points'),
            'career_prospects' => $this->request->getPost('career_prospects'),
            'icon'             => trim((string)($this->request->getPost('icon') ?? 'star')) ?: 'star',
            'media_type'       => $mediaType,
            'link_url'         => $linkUrl !== '' ? $linkUrl : null,
            'link_text'        => trim((string)($this->request->getPost('link_text') ?? 'Selengkapnya')) ?: 'Selengkapnya',
            'show'             => $this->request->getPost('show') ? 1 : 0,
            'sort_order'       => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        if ($existingImg !== '') {
            $data['image'] = $existingImg;
        } elseif ($excludeId === null) {
            $data['image'] = null;
        }

        return [$data, null];
    }

    private function deleteOldImageFile(?string $image): void
    {
        $img = trim((string)$image);
        if ($img === '') {
            return;
        }
        if (preg_match('#^https?://#i', $img)) {
            $parsedPath = parse_url($img, PHP_URL_PATH) ?: '';
            if (!str_contains($parsedPath, '/uploads/programs/')) {
                return;
            }
            $img = basename($parsedPath);
        } elseif (str_contains($img, '/')) {
            if (!str_starts_with(ltrim($img, '/'), 'uploads/programs/')) {
                return;
            }
            $img = basename($img);
        }

        $file = FCPATH . 'uploads/programs/' . basename($img);
        if (is_file($file)) {
            @unlink($file);
        }
    }

    private function uploadAndResizeImage(UploadedFile $file): string
    {
        $uploadPath = FCPATH . 'uploads/programs';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $srcPath = $file->getTempName();
        $srcInfo = @getimagesize($srcPath);
        if ($srcInfo === false || !in_array($srcInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            return '';
        }

        [$srcWidth, $srcHeight, $type] = $srcInfo;
        $newName = $this->buildSafeName($type);
        $maxWidth = 800;

        if ($srcWidth > $maxWidth) {
            $newHeight = (int)round($srcHeight * ($maxWidth / $srcWidth));
            $dst = imagecreatetruecolor($maxWidth, $newHeight);

            if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }

            $src = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($srcPath),
                IMAGETYPE_PNG  => @imagecreatefrompng($srcPath),
                IMAGETYPE_WEBP => @imagecreatefromwebp($srcPath),
                default        => null,
            };

            if (!$src) {
                unset($dst);
                $file->move($uploadPath, $newName);
                return $newName;
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $newHeight, $srcWidth, $srcHeight);

            $destPath = $uploadPath . '/' . $newName;
            $written = match ($type) {
                IMAGETYPE_JPEG => imagejpeg($dst, $destPath, 85),
                IMAGETYPE_PNG  => imagepng($dst, $destPath, 8),
                IMAGETYPE_WEBP => imagewebp($dst, $destPath, 85),
                default        => false,
            };

            unset($src, $dst);

            if (!$written || !is_file($destPath)) {
                if (is_file($destPath)) {
                    @unlink($destPath);
                }
                if (!$file->hasMoved()) {
                    $file->move($uploadPath, $newName);
                    return $newName;
                }
                return '';
            }
        } else {
            $file->move($uploadPath, $newName);
        }

        return $newName;
    }
}
