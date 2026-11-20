<?php

namespace App\Controllers\Admin;

use App\Models\TestimonialModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Testimonials extends BaseController
{
    protected $testimonialModel;

    /**
     * Initialize the controller and load the TestimonialModel.
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
        $this->testimonialModel = new TestimonialModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Testimoni';
        $this->data['items'] = $this->testimonialModel->orderBy('sort_order', 'ASC')->paginate(10);
        $this->data['pager'] = $this->testimonialModel->pager;

        return view('admin/testimonials/index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Testimoni';
        $this->data['item']  = [];
        return view('admin/testimonials/form', $this->data);
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
            'photo' => $this->request->getPost('photo'),
            'role' => $this->request->getPost('role'),
            'quote' => $this->request->getPost('quote'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int) ($this->request->getPost('sort_order') ?? 0),
        ];

        if (!$this->testimonialModel->insert($data)) {
            $errors = $this->testimonialModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan testimoni.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/testimonials')->with('success', 'Testimoni berhasil ditambahkan.');
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
        $this->data['title'] = 'Edit Testimoni';
        $this->data['item']  = $this->testimonialModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/testimonials')->with('error', 'Testimoni tidak ditemukan.');
        }

        return view('admin/testimonials/form', $this->data);
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
            'photo' => $this->request->getPost('photo'),
            'role' => $this->request->getPost('role'),
            'quote' => $this->request->getPost('quote'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int) ($this->request->getPost('sort_order') ?? 0),
        ];

        if (!$this->testimonialModel->update($id, $data)) {
            $errors = $this->testimonialModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui testimoni.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/testimonials')->with('success', 'Testimoni berhasil diperbarui.');
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

        if (!$this->reorderIdsExist($this->testimonialModel, $ids)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Sebagian ID tidak ditemukan.']);
        }

        // The admin list is paginated (10 per page). Without an offset, page 2
        // would rewrite sort_order 0..9 and collide with the first page,
        // making the order unstable.
        $offset = max(0, (int) ($input['offset'] ?? 0));

        $this->testimonialModel->reorder($ids, $offset);

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
        // delete() returns true even when no row was deleted,
        // so check that the testimonial exists first.
        if (!$this->testimonialModel->find($id)) {
            return redirect()->to('/admin/testimonials')->with('error', 'Testimoni tidak ditemukan.');
        }

        if (!$this->testimonialModel->delete($id)) {
            return redirect()->to('/admin/testimonials')->with('error', 'Gagal menghapus testimoni.');
        }

        return redirect()->to('/admin/testimonials')->with('success', 'Testimoni berhasil dihapus.');
    }
}
