<?php

namespace App\Controllers\Admin;

use App\Models\AchievementModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Achievements extends BaseController
{
    protected $achievementModel;

    /**
     * Initialize the controller and load the AchievementModel.
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
        $this->achievementModel = new AchievementModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Prestasi';
        // Order follows the "Order" column (sort_order) — the year is only
        // informational, keeping drag-and-drop reordering consistent with the public view.
        $this->data['items'] = $this->achievementModel->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->paginate(10);
        $this->data['pager'] = $this->achievementModel->pager;

        return view('admin/achievements/index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Prestasi';
        $this->data['item'] = [];
        return view('admin/achievements/form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $data = [
            'student_name' => $this->request->getPost('student_name'),
            'photo' => $this->request->getPost('photo'),
            'class_name' => $this->request->getPost('class_name'),
            'achievement' => $this->request->getPost('achievement'),
            'level' => $this->request->getPost('level'),
            'year' => ((int)$this->request->getPost('year')) ?: (int)date('Y'),
            'medal' => $this->request->getPost('medal'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        if (!$this->achievementModel->insert($data)) {
            $errors = $this->achievementModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan prestasi.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/achievements')->with('success', 'Prestasi berhasil ditambahkan.');
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
        $this->data['title'] = 'Edit Prestasi';
        $this->data['item'] = $this->achievementModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/achievements')->with('error', 'Prestasi tidak ditemukan.');
        }

        return view('admin/achievements/form', $this->data);
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
            'student_name' => $this->request->getPost('student_name'),
            'photo' => $this->request->getPost('photo'),
            'class_name' => $this->request->getPost('class_name'),
            'achievement' => $this->request->getPost('achievement'),
            'level' => $this->request->getPost('level'),
            'year' => ((int)$this->request->getPost('year')) ?: (int)date('Y'),
            'medal' => $this->request->getPost('medal'),
            'show' => $this->request->getPost('show') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
        ];

        if (!$this->achievementModel->update($id, $data)) {
            $errors = $this->achievementModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui prestasi.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/achievements')->with('success', 'Prestasi berhasil diperbarui.');
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

        if (!$this->reorderIdsExist($this->achievementModel, $ids)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Sebagian ID tidak ditemukan.']);
        }

        // The admin list is paginated (10 per page). Without an offset, page 2
        // would rewrite sort_order 0..9 and collide with the first page,
        // making the order unstable.
        $offset = max(0, (int)($input['offset'] ?? 0));

        $this->achievementModel->reorder($ids, $offset);

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
        if (!$this->achievementModel->delete($id)) {
            return redirect()->to('/admin/achievements')->with('error', 'Gagal menghapus prestasi.');
        }

        return redirect()->to('/admin/achievements')->with('success', 'Prestasi berhasil dihapus.');
    }
}
