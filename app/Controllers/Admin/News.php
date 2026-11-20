<?php

namespace App\Controllers\Admin;

use App\Models\NewsModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class News extends BaseController
{
    protected $newsModel;

    /**
     * Initialize the controller.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function initController($request, $response, $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->newsModel = new NewsModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Berita';
        $this->data['items'] = $this->newsModel->orderBy('created_at', 'DESC')->paginate(10);
        $this->data['pager'] = $this->newsModel->pager;

        return view('admin/news/index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Berita';
        $this->data['item'] = [];
        return view('admin/news/form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $baseSlug = url_title((string)$this->request->getPost('title'), '-', true);
        $slug = $this->newsModel->makeUniqueSlug($baseSlug);

        $tags = $this->request->getPost('tags');
        if (is_string($tags)) {
            $tags = array_map('trim', explode(',', $tags));
        }

        $data = [
            'category' => $this->normalizeCategory($this->request->getPost('category')),
            'tags' => $tags ? json_encode($tags) : null,
            'title' => $this->request->getPost('title'),
            'slug' => $slug,
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'author' => $this->request->getPost('author'),
            'published_at' => $this->request->getPost('status') === 'published' ? date('Y-m-d H:i:s') : null,
            'status' => $this->request->getPost('status'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
        ];

        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploaded = $this->uploadAndResizeImage($file);
            if ($uploaded) {
                $data['image'] = $uploaded;
            } else {
                return redirect()->back()->withInput()->with('error', 'File gambar harus berupa gambar (JPG, PNG, atau WEBP).');
            }
        }

        if (!$this->newsModel->insert($data)) {
            $errors = $this->newsModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan berita.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/news')->with('success', 'Berita berhasil ditambahkan.');
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
        $this->data['title'] = 'Edit Berita';
        $this->data['item'] = $this->newsModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/news')->with('error', 'Berita tidak ditemukan.');
        }

        return view('admin/news/form', $this->data);
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
        $data = [
            'category' => $this->normalizeCategory($this->request->getPost('category')),
            'title' => $this->request->getPost('title'),
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'author' => $this->request->getPost('author'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
        ];

        $tags = $this->request->getPost('tags');
        if (is_string($tags)) {
            $data['tags'] = explode(',', $tags)
                    |> (fn($x) => array_map('trim', $x))
                    |> json_encode(...);
        }

        $title = $this->request->getPost('title');
        if ($title) {
            $baseSlug = url_title((string)$title, '-', true);
            $data['slug'] = $this->newsModel->makeUniqueSlug($baseSlug, $id);
        }

        $status = $this->request->getPost('status');
        if ($status) {
            $data['status'] = $status;
            if ($status === 'published') {
                $old = $this->newsModel->find($id);
                if (empty($old['published_at'])) {
                    $data['published_at'] = date('Y-m-d H:i:s');
                }
            }
        }

        $file = $this->request->getFile('image');
        $oldImage = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Upload first WITHOUT deleting the old file — prevents data loss if the upload fails.
            $oldImage = ($this->newsModel->find($id)['image'] ?? null);
            $uploaded = $this->uploadAndResizeImage($file);
            if ($uploaded) {
                $data['image'] = $uploaded;
            } else {
                return redirect()->back()->withInput()->with('error', 'File gambar harus berupa gambar (JPG, PNG, atau WEBP).');
            }
        }

        if (!$this->newsModel->update($id, $data)) {
            // DB failed: clean up the new file so it is not orphaned; the old file stays intact.
            if (isset($data['image'])) {
                $this->deleteOldImageFile($data['image']);
            }
            $errors = $this->newsModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui berita.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        // DB succeeded: only now delete the old file.
        if (isset($data['image']) && $oldImage) {
            $this->deleteOldImageFile($oldImage);
        }

        return redirect()->to('/admin/news')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Quickly update a news item via AJAX.
     *
     * @param int $id
     *
     * @return ResponseInterface
     */
    public function quickUpdate(int $id): ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $item = $this->newsModel->find($id);
        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Berita tidak ditemukan.']);
        }

        $data = [];

        $title = $this->request->getPost('title');
        if ($title !== null) {
            $title = trim((string)$title);
            if ($title === '' || mb_strlen($title) < 3) {
                return $this->response->setJSON(['error' => 'Judul minimal 3 karakter.', 'success' => false]);
            }
            $data['title'] = $title;
            $data['slug'] = $this->newsModel->makeUniqueSlug(url_title($title, '-', true), $id);
        }

        $status = $this->request->getPost('status');
        if (in_array($status, ['draft', 'published', 'archived'])) {
            $data['status'] = $status;
            if ($status === 'published' && $item['status'] !== 'published') {
                $data['published_at'] = date('Y-m-d H:i:s');
            }
        }

        $category = $this->request->getPost('category');
        if ($category !== null) {
            $data['category'] = $this->normalizeCategory($category);
        }

        $author = $this->request->getPost('author');
        if ($author !== null) {
            $data['author'] = $author;
        }

        $isFeatured = $this->request->getPost('is_featured');
        if ($isFeatured !== null) {
            $data['is_featured'] = $isFeatured ? 1 : 0;
        }

        if (empty($data)) {
            return $this->response->setJSON(['error' => 'Tidak ada data yang diubah.', 'success' => false]);
        }

        if (!$this->newsModel->update($id, $data)) {
            $errors = $this->newsModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui berita.';
            return $this->response->setJSON(['error' => $msg, 'success' => false]);
        }

        $item = $this->newsModel->find($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Berita berhasil diperbarui.',
            'item' => $item,
            'csrfHash' => csrf_hash(),
        ]);
    }

    /**
     * Upload and resize an image to a maximum width of 1200px.
     *
     * @param UploadedFile $file
     *
     * @return string|null
     */
    private function uploadAndResizeImage(UploadedFile $file): ?string
    {
        $uploadPath = FCPATH . 'uploads/news';
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
        $maxWidth = 1200;

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

            // GD failed to decode (corrupt file even with a valid header) → fall back to the original file, do not return a broken URL.
            if (!$srcImage) {
                $file->move($uploadPath, $newName);
                return base_url('uploads/news/' . $newName);
            }

            $dstImage = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG/WebP
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
                    return base_url('uploads/news/' . $newName);
                }
                return null;
            }
        } else {
            $file->move($uploadPath, $newName);
        }

        return base_url('uploads/news/' . $newName);
    }

    /**
     * Normalize a comma-separated category string by trimming and filtering empty values.
     *
     * @param string|null $category The raw category input.
     *
     * @return string|null
     */
    private function normalizeCategory(?string $category): ?string
    {
        if ($category === null || trim($category) === '') {
            return null;
        }

        $parts = array_map('trim', explode(',', $category));
        $parts = array_filter($parts, fn($p) => $p !== '');

        return !empty($parts) ? implode(',', $parts) : null;
    }

    /**
     * Delete an old uploaded image file from the disk if it is a local upload.
     *
     * @param string|null $imageUrl The image URL to delete.
     *
     * @return void
     */
    private function deleteOldImageFile(?string $imageUrl): void
    {
        if (empty($imageUrl)) {
            return;
        }

        $baseUrl = rtrim(base_url(), '/') . '/';
        if (str_starts_with($imageUrl, $baseUrl . 'uploads/news/')) {
            $localPath = str_replace($baseUrl, '', $imageUrl);
            $file = FCPATH . $localPath;
            if (is_file($file)) {
                unlink($file);
            }
        }
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
        $item = $this->newsModel->find($id);
        if ($item) {
            $this->deleteOldImageFile($item['image'] ?? null);
        }

        if (!$this->newsModel->delete($id)) {
            return redirect()->to('/admin/news')->with('error', 'Gagal menghapus berita.');
        }

        return redirect()->to('/admin/news')->with('success', 'Berita berhasil dihapus.');
    }
}
