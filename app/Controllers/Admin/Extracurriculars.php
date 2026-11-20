<?php

namespace App\Controllers\Admin;

use App\Models\ExtracurricularModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Extracurriculars extends BaseController
{
    protected $extracurricularModel;

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
        $this->extracurricularModel = new ExtracurricularModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Ekstrakurikuler';
        $this->data['items'] = $this->extracurricularModel->orderBy('sort_order', 'ASC')->paginate(10);
        $this->data['pager'] = $this->extracurricularModel->pager;

        return view('admin/extracurriculars/index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Ekstrakurikuler';
        $this->data['item'] = [];
        return view('admin/extracurriculars/form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'icon' => $this->request->getPost('icon'),
            'icon_color' => $this->request->getPost('icon_color'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        if (!$this->extracurricularModel->insert($data)) {
            $errors = $this->extracurricularModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan ekstrakurikuler.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/extracurriculars')->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
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
        $this->data['title'] = 'Edit Ekstrakurikuler';
        $this->data['item'] = $this->extracurricularModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/extracurriculars')->with('error', 'Ekstrakurikuler tidak ditemukan.');
        }

        return view('admin/extracurriculars/form', $this->data);
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
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'icon' => $this->request->getPost('icon'),
            'icon_color' => $this->request->getPost('icon_color'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        if (!$this->extracurricularModel->update($id, $data)) {
            $errors = $this->extracurricularModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui ekstrakurikuler.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/extracurriculars')->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    /**
     * Reorder extracurriculars via AJAX.
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

        if (!$this->reorderIdsExist($this->extracurricularModel, $ids)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Sebagian ID tidak ditemukan.']);
        }

        // The admin list is paginated (10 per page). Without an offset, page 2
        // would rewrite sort_order 0..9 and collide with the first page,
        // making the order unstable.
        $offset = max(0, (int)($input['offset'] ?? 0));

        $this->extracurricularModel->reorder($ids, $offset);

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
        if (!$this->extracurricularModel->delete($id)) {
            return redirect()->to('/admin/extracurriculars')->with('error', 'Gagal menghapus ekstrakurikuler.');
        }

        return redirect()->to('/admin/extracurriculars')->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }
}
