<?php

namespace App\Controllers\Admin;

use App\Models\GalleryModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Galleries extends BaseController
{
    protected $galleryModel;

    /**
     * Initialize controller.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     */
    public function initController($request, $response, $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->galleryModel = new GalleryModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Galeri';
        $this->data['items'] = $this->galleryModel->orderBy('sort_order', 'ASC')->paginate(10);
        $this->data['pager'] = $this->galleryModel->pager;

        return view('admin/gallery/index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Galeri';
        $this->data['item'] = [];
        $this->data['categories'] = $this->galleryModel->getCategories();

        return view('admin/gallery/form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $data = [
            'category' => $this->request->getPost('category'),
            'caption' => $this->request->getPost('caption'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        $file = $this->request->getFile('image');

        // Images are required: the `image` column is NOT NULL, and items without an image
        // never appear on the site (skipped by the theme views).
        if (!$file instanceof UploadedFile || !$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->withInput()->with('error', $this->imageUploadError($file));
        }

        $uploaded = $this->uploadAndResizeImage($file);
        if (!$uploaded) {
            return redirect()->back()->withInput()->with('error', 'File gambar harus berupa gambar (JPG, PNG, atau WEBP).');
        }
        $data['image'] = $uploaded;

        if (!$this->galleryModel->insert($data)) {
            // Do not leave an orphaned file behind if the save fails.
            $this->deleteOldImageFile($uploaded);

            $errors = $this->galleryModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan galeri.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/gallery')->with('success', 'Galeri berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return string|RedirectResponse
     */
    public function edit(int $id): string|RedirectResponse
    {
        $this->data['title'] = 'Edit Galeri';
        $this->data['item'] = $this->galleryModel->find($id);
        $this->data['categories'] = $this->galleryModel->getCategories();

        if (!$this->data['item']) {
            return redirect()->to('/admin/gallery')->with('error', 'Galeri tidak ditemukan.');
        }

        return view('admin/gallery/form', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(int $id): RedirectResponse
    {
        $item = $this->galleryModel->find($id);
        if (!$item) {
            return redirect()->to('/admin/gallery')->with('error', 'Galeri tidak ditemukan.');
        }

        $data = [
            'category' => $this->request->getPost('category'),
            'caption' => $this->request->getPost('caption'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        $file = $this->request->getFile('image');
        if ($file instanceof UploadedFile && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$file->isValid() || $file->hasMoved()) {
                return redirect()->back()->withInput()->with('error', $this->imageUploadError($file));
            }

            // Upload first WITHOUT deleting the old file — prevents data loss if the upload fails.
            $uploaded = $this->uploadAndResizeImage($file);
            if (!$uploaded) {
                return redirect()->back()->withInput()->with('error', 'File gambar harus berupa gambar (JPG, PNG, atau WEBP).');
            }
            $data['image'] = $uploaded;
        }

        if (!$this->galleryModel->update($id, $data)) {
            // DB failed: clean up the new file so it is not orphaned; the old file stays intact.
            if (isset($data['image'])) {
                $this->deleteOldImageFile($data['image']);
            }
            $errors = $this->galleryModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui galeri.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        // DB succeeded: only now delete the old file.
        if (isset($data['image']) && !empty($item['image'])) {
            $this->deleteOldImageFile($item['image']);
        }

        return redirect()->to('/admin/gallery')->with('success', 'Galeri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $item = $this->galleryModel->find($id);
        if (!$item) {
            return redirect()->to('/admin/gallery')->with('error', 'Galeri tidak ditemukan.');
        }

        // Delete the DB row first; the file is removed only after the DB succeeds, so we do not
        // leave a row pointing to a file that is already gone.
        if (!$this->galleryModel->delete($id)) {
            return redirect()->to('/admin/gallery')->with('error', 'Gagal menghapus galeri.');
        }

        $this->deleteOldImageFile($item['image'] ?? null);

        return redirect()->to('/admin/gallery')->with('success', 'Galeri berhasil dihapus.');
    }

    /**
     * Build a user-friendly message for a missing or failed image upload.
     *
     * @param UploadedFile|null $file
     *
     * @return string
     */
    private function imageUploadError(?UploadedFile $file): string
    {
        if ($file instanceof UploadedFile && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            $reason = trim($file->getErrorString());
            if ($reason !== '') {
                return 'Upload gambar gagal: ' . $reason;
            }
        }

        return 'Gambar wajib diunggah (JPG, PNG, atau WEBP).';
    }

    /**
     * Reorder resources via drag-and-drop.
     *
     * @return ResponseInterface
     */
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

        if (!$this->reorderIdsExist($this->galleryModel, $ids)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Sebagian ID tidak ditemukan.']);
        }

        // The admin list is paginated (10 per page). Without an offset, page 2
        // would rewrite sort_order 0..9 and collide with the first page,
        // making the order unstable.
        $offset = max(0, (int)($input['offset'] ?? 0));

        $this->galleryModel->reorder($ids, $offset);

        return $this->response->setJSON(['success' => true, 'csrfHash' => csrf_hash()]);
    }

    /**
     * Upload and resize an image.
     *
     * @param UploadedFile $file
     *
     * @return string|null
     */
    private function uploadAndResizeImage(UploadedFile $file): ?string
    {
        $uploadPath = FCPATH . 'uploads/gallery';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $tempPath = $file->getTempName();

        $srcInfo = @getimagesize($tempPath);
        if ($srcInfo === false || !in_array($srcInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            return null;
        }

        [$origWidth, $origHeight, $type] = $srcInfo;
        $newName = $this->buildSafeName($type);
        $maxWidth = 1600;

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

            // GD failed to decode → fall back to the original file, do not return a broken URL.
            if (!$srcImage) {
                $file->move($uploadPath, $newName);
                return base_url('uploads/gallery/' . $newName);
            }

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
                    return base_url('uploads/gallery/' . $newName);
                }
                return null;
            }
        } else {
            $file->move($uploadPath, $newName);
        }

        return base_url('uploads/gallery/' . $newName);
    }

    /**
     * Delete an old image file from the disk.
     *
     * @param string|null $imageUrl
     */
    private function deleteOldImageFile(?string $imageUrl): void
    {
        if (empty($imageUrl)) {
            return;
        }

        $baseUrl = rtrim(base_url(), '/') . '/';
        if (str_starts_with($imageUrl, $baseUrl . 'uploads/gallery/')) {
            $localPath = str_replace($baseUrl, '', $imageUrl);
            $file = FCPATH . $localPath;
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
