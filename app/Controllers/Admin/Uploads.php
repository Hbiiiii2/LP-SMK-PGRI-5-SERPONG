<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;
use FilesystemIterator;

class Uploads extends BaseController
{
    private array $directories = ['about', 'gallery', 'news', 'principal', 'programs', 'spmb', 'teachers'];

    /**
     * Display a paginated list of uploaded files, optionally filtered by directory.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Upload Manager';

        $filterDir = $this->request->getGet('directory');
        $dirsToScan = $filterDir && in_array($filterDir, $this->directories)
            ? [$filterDir]
            : $this->directories;

        $files = [];
        foreach ($dirsToScan as $dir) {
            $path = FCPATH . 'uploads/' . $dir;
            if (!is_dir($path)) {
                continue;
            }
            $iterator = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isFile()) {
                    $files[] = [
                        'name' => $fileInfo->getFilename(),
                        'rel_path' => $dir . '/' . $fileInfo->getFilename(),
                        'dir' => $dir,
                        'size' => $fileInfo->getSize(),
                        'type' => $this->getFileType($fileInfo->getExtension()),
                        'modified' => $fileInfo->getMTime(),
                    ];
                }
            }
        }

        usort($files, fn($a, $b) => $b['modified'] - $a['modified']);

        $perPage = 20;
        $total = count($files);
        $pageCount = max(1, (int)ceil($total / $perPage));
        $currentPage = (int)($this->request->getGet('page') ?? 1);
        // Validate page: reject 0/negative/non-numeric values and clamp to the last page.
        if ($currentPage < 1) {
            $currentPage = 1;
        } elseif ($currentPage > $pageCount) {
            $currentPage = $pageCount;
        }
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($files, $offset, $perPage);

        $pager = Services::pager();
        $pager->setPath('admin/uploads');
        // Preserve ?directory= when moving between pages.
        $pager->only(['directory']);
        $links = $pager->makeLinks($currentPage, $perPage, $total);

        $this->data['items'] = $items;
        $this->data['pager'] = $links;
        $this->data['total'] = $total;
        $this->data['perPage'] = $perPage;
        $this->data['currentPage'] = $currentPage;
        $this->data['filterDir'] = $filterDir;
        $this->data['directories'] = $this->directories;

        return view('admin/uploads/index', $this->data);
    }

    /**
     * Delete an uploaded file after validating the path is within the uploads' directory.
     *
     * @return RedirectResponse
     */
    public function delete(): RedirectResponse
    {
        $relPath = $this->request->getPost('rel_path');

        if (empty($relPath)) {
            return redirect()->to('/admin/uploads')->with('error', 'Parameter file tidak valid.');
        }

        // Prevent directory traversal: resolve the path and verify it is inside the upload root.
        $uploadsRoot = realpath(FCPATH . 'uploads');
        $filePath = realpath(FCPATH . 'uploads/' . $relPath);

        if ($uploadsRoot === false || $filePath === false || !str_starts_with($filePath, $uploadsRoot . DIRECTORY_SEPARATOR)) {
            return redirect()->to('/admin/uploads')->with('error', 'Path file tidak valid.');
        }

        if (!is_file($filePath)) {
            return redirect()->to('/admin/uploads')->with('error', 'File tidak ditemukan.');
        }

        if (unlink($filePath)) {
            return redirect()->to('/admin/uploads')->with('success', 'File berhasil dihapus.');
        }

        return redirect()->to('/admin/uploads')->with('error', 'Gagal menghapus file.');
    }

    /**
     * Categorize a file extension as an image, document, or other.
     *
     * @param string $extension The file extension.
     *
     * @return string
     */
    private function getFileType(string $extension): string
    {
        // SVG is deliberately NOT treated as an image: SVG files can contain JS, and
        // views render image types inline via <img> — categorize it as others.
        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $docExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];

        $ext = strtolower($extension);

        if (in_array($ext, $imageExts)) return 'image';
        if (in_array($ext, $docExts)) return 'document';

        return 'other';
    }
}
