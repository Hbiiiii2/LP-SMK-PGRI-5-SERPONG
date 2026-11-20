<?php

namespace App\Controllers\Admin;

use App\Models\DownloadModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Downloads extends BaseController
{
    protected $downloadModel;

    /**
     * Initialize the controller and load the DownloadModel.
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
        $this->downloadModel = new DownloadModel();
    }

    /**
     * Display a paginated list of downloadable files.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Download Center';
        $this->data['items'] = $this->downloadModel->orderBy('sort_order', 'ASC')->paginate(10);
        $this->data['pager'] = $this->downloadModel->pager;

        return view('admin/downloads/index', $this->data);
    }

    /**
     * Show the form for creating a new download entry.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Download';
        $this->data['item'] = [];
        return view('admin/downloads/form', $this->data);
    }

    /**
     * Store a newly created download entry in storage.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $data = [
            'category' => $this->request->getPost('category'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'url' => $this->request->getPost('url'),
            'file_size' => $this->request->getPost('file_size'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        if ($this->isDangerousUrl($data['url'])) {
            return redirect()->back()->withInput()->with('error', 'URL tidak valid (skema tidak diizinkan).');
        }

        if (!$this->downloadModel->insert($data)) {
            $errors = $this->downloadModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan download.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/downloads')->with('success', 'Download berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified download entry.
     *
     * @param int $id
     *
     * @return string|RedirectResponse
     */
    public function edit(int $id): string|RedirectResponse
    {
        $this->data['title'] = 'Edit Download';
        $this->data['item'] = $this->downloadModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/downloads')->with('error', 'Download tidak ditemukan.');
        }

        return view('admin/downloads/form', $this->data);
    }

    /**
     * Update the specified download entry in storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(int $id): RedirectResponse
    {
        $data = [
            'category' => $this->request->getPost('category'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'url' => $this->request->getPost('url'),
            'file_size' => $this->request->getPost('file_size'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        if ($this->isDangerousUrl($data['url'])) {
            return redirect()->back()->withInput()->with('error', 'URL tidak valid (skema tidak diizinkan).');
        }

        if (!$this->downloadModel->update($id, $data)) {
            $errors = $this->downloadModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui download.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/downloads')->with('success', 'Download berhasil diperbarui.');
    }

    /**
     * Reorder downloads via drag-and-drop.
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

        if (!$this->reorderIdsExist($this->downloadModel, $ids)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Sebagian ID tidak ditemukan.']);
        }

        // The admin list is paginated (10 per page). Without an offset, page 2
        // would rewrite sort_order 0..9 and collide with the first page,
        // making the order unstable.
        $offset = max(0, (int)($input['offset'] ?? 0));

        $this->downloadModel->reorder($ids, $offset);

        return $this->response->setJSON(['success' => true, 'csrfHash' => csrf_hash()]);
    }

    /**
     * Delete the specified download entry from storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        if (!$this->downloadModel->delete($id)) {
            return redirect()->to('/admin/downloads')->with('error', 'Gagal menghapus download.');
        }

        return redirect()->to('/admin/downloads')->with('success', 'Download berhasil dihapus.');
    }
}
