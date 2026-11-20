<?php

namespace App\Controllers\Admin;

use App\Models\TeacherModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Teachers extends BaseController
{
    protected $teacherModel;

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
        $this->teacherModel = new TeacherModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Guru & Tenaga Pendidik';
        $this->data['items'] = $this->teacherModel->orderBy('sort_order', 'ASC')->paginate(10);
        $this->data['pager'] = $this->teacherModel->pager;

        return view('admin/teachers/index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Guru';
        $this->data['item'] = [];
        return view('admin/teachers/form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'experience' => $this->request->getPost('experience'),
            'education' => $this->request->getPost('education'),
            'social_facebook' => $this->request->getPost('social_facebook'),
            'social_instagram' => $this->request->getPost('social_instagram'),
            'social_linkedin' => $this->request->getPost('social_linkedin'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploaded = $this->uploadAndResizePhoto($file);
            if ($uploaded) {
                $data['photo'] = $uploaded;
            } else {
                return redirect()->back()->withInput()->with('error', 'File foto harus berupa gambar (JPG, PNG, atau WEBP).');
            }
        }

        if (!$this->teacherModel->insert($data)) {
            $errors = $this->teacherModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan data guru.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/teachers')->with('success', 'Data guru berhasil ditambahkan.');
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
        $this->data['title'] = 'Edit Guru';
        $this->data['item'] = $this->teacherModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/teachers')->with('error', 'Data guru tidak ditemukan.');
        }

        return view('admin/teachers/form', $this->data);
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
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'experience' => $this->request->getPost('experience'),
            'education' => $this->request->getPost('education'),
            'social_facebook' => $this->request->getPost('social_facebook'),
            'social_instagram' => $this->request->getPost('social_instagram'),
            'social_linkedin' => $this->request->getPost('social_linkedin'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        $file = $this->request->getFile('photo');
        $oldPhoto = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Upload first WITHOUT deleting the old file — prevents data loss if the upload fails.
            $oldPhoto = ($this->teacherModel->find($id)['photo'] ?? null);
            $uploaded = $this->uploadAndResizePhoto($file);
            if ($uploaded) {
                $data['photo'] = $uploaded;
            } else {
                return redirect()->back()->withInput()->with('error', 'File foto harus berupa gambar (JPG, PNG, atau WEBP).');
            }
        }

        if (!$this->teacherModel->update($id, $data)) {
            // DB failed: clean up the new file so it is not orphaned; the old file stays intact.
            if (isset($data['photo'])) {
                $this->deleteOldPhotoFile($data['photo']);
            }
            $errors = $this->teacherModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui data guru.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        // DB succeeded: only now delete the old file.
        if (isset($data['photo']) && $oldPhoto) {
            $this->deleteOldPhotoFile($oldPhoto);
        }

        return redirect()->to('/admin/teachers')->with('success', 'Data guru berhasil diperbarui.');
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

        if (!$this->reorderIdsExist($this->teacherModel, $ids)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Sebagian ID tidak ditemukan.']);
        }

        // The admin list is paginated (10 per page). Without an offset, page 2
        // would rewrite sort_order 0..9 and collide with the first page,
        // making the order unstable.
        $offset = max(0, (int)($input['offset'] ?? 0));

        $this->teacherModel->reorder($ids, $offset);

        return $this->response->setJSON(['success' => true, 'csrfHash' => csrf_hash()]);
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
        $teacher = $this->teacherModel->find($id);
        if ($teacher) {
            $this->deleteOldPhotoFile($teacher['photo'] ?? null);
        }

        if (!$this->teacherModel->delete($id)) {
            return redirect()->to('/admin/teachers')->with('error', 'Gagal menghapus data guru.');
        }

        return redirect()->to('/admin/teachers')->with('success', 'Data guru berhasil dihapus.');
    }

    /**
     * Delete an old teacher photo, accepting both stored filename and legacy full URL.
     */
    private function deleteOldPhotoFile(?string $photo): void
    {
        if (empty($photo)) {
            return;
        }
        // Legacy full URL (e.g. https://domain/uploads/teachers/x.jpg) → take the basename.
        if (preg_match('#^https?://#', $photo)) {
            $photo = basename(parse_url($photo, PHP_URL_PATH) ?: $photo);
        }
        // Reject traversal: only the basename is allowed.
        $photo = basename($photo);
        $file = FCPATH . 'uploads/teachers/' . $photo;
        if (is_file($file)) {
            @unlink($file);
        }
    }

    /**
     * Upload and resize a photo.
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    private function uploadAndResizePhoto(UploadedFile $file): string
    {
        $uploadPath = FCPATH . 'uploads/teachers';
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
        $maxWidth = 400;

        if ($srcWidth > $maxWidth) {
            $newHeight = (int)round($srcHeight * ($maxWidth / $srcWidth));
            $dst = imagecreatetruecolor($maxWidth, $newHeight);

            // Preserve transparency for PNG/WebP (previously lost → black background).
            if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }

            $src = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($srcPath),
                IMAGETYPE_PNG => @imagecreatefrompng($srcPath),
                IMAGETYPE_WEBP => @imagecreatefromwebp($srcPath),
                default => null,
            };

            if (!$src) {
                imagedestroy($dst);
                $file->move($uploadPath, $newName);
                return $newName;
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $newHeight, $srcWidth, $srcHeight);

            $destPath = $uploadPath . '/' . $newName;
            $written = match ($type) {
                IMAGETYPE_JPEG => imagejpeg($dst, $destPath, 85),
                IMAGETYPE_PNG => imagepng($dst, $destPath, 8),
                IMAGETYPE_WEBP => imagewebp($dst, $destPath, 85),
                default => false,
            };

            imagedestroy($src);
            imagedestroy($dst);

            // If the GD writing fails, fall back to the original file instead of returning a broken URL.
            if (!$written || !is_file($destPath)) {
                if (is_file($destPath)) {
                    @unlink($destPath);
                }
                // Temp file still exists (not moved yet) → move the original.
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
