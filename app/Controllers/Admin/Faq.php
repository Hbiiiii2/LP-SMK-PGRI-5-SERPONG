<?php

namespace App\Controllers\Admin;

use App\Models\FaqModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Faq extends BaseController
{
    protected $faqModel;

    /**
     * Initialize the controller and load the FaqModel.
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
        $this->faqModel = new FaqModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'FAQ';
        $this->data['items'] = $this->faqModel->orderBy('sort_order', 'ASC')->paginate(10);
        $this->data['pager'] = $this->faqModel->pager;

        return view('admin/faq/index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah FAQ';
        $this->data['item'] = [];
        $this->data['categories'] = $this->faqModel->getCategories();
        return view('admin/faq/form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $data = [
            'question' => $this->request->getPost('question'),
            'answer' => $this->request->getPost('answer'),
            'category' => $this->request->getPost('category'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        if (!$this->faqModel->insert($data)) {
            $errors = $this->faqModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan FAQ.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/faq')->with('success', 'FAQ berhasil ditambahkan.');
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
        $this->data['title'] = 'Edit FAQ';
        $this->data['item'] = $this->faqModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/faq')->with('error', 'FAQ tidak ditemukan.');
        }

        $this->data['categories'] = $this->faqModel->getCategories();

        return view('admin/faq/form', $this->data);
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
        if (!$this->faqModel->find($id)) {
            return redirect()->to('/admin/faq')->with('error', 'FAQ tidak ditemukan.');
        }

        $data = [
            'question' => $this->request->getPost('question'),
            'answer' => $this->request->getPost('answer'),
            'category' => $this->request->getPost('category'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        if (!$this->faqModel->update($id, $data)) {
            $errors = $this->faqModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui FAQ.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/faq')->with('success', 'FAQ berhasil diperbarui.');
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

        if (!$this->reorderIdsExist($this->faqModel, $ids)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Sebagian ID tidak ditemukan.']);
        }

        // The admin list is paginated (10 per page). Without an offset, page 2
        // would rewrite sort_order 0..9 and collide with the first page,
        // making the order unstable.
        $offset = max(0, (int)($input['offset'] ?? 0));

        $this->faqModel->reorder($ids, $offset);

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
        if (!$this->faqModel->find($id)) {
            return redirect()->to('/admin/faq')->with('error', 'FAQ tidak ditemukan.');
        }

        if (!$this->faqModel->delete($id)) {
            return redirect()->to('/admin/faq')->with('error', 'Gagal menghapus FAQ.');
        }

        return redirect()->to('/admin/faq')->with('success', 'FAQ berhasil dihapus.');
    }
}
