<?php

namespace App\Controllers\Admin;

use App\Models\EventModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Events extends BaseController
{
    protected $eventModel;

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
        $this->eventModel = new EventModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Acara & Kegiatan';
        $items = $this->eventModel->orderBy('event_date', 'DESC')->orderBy('sort_order', 'ASC')->paginate(10);
        foreach ($items as &$item) {
            // Displayed status follows the date: an "Upcoming" event in the past = "Finished".
            $item['_display_status'] = $this->eventModel->effectiveStatus($item);
        }
        unset($item);
        $this->data['items'] = $items;
        $this->data['pager'] = $this->eventModel->pager;

        return view('admin/events/index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Acara';
        $this->data['item'] = [];
        return view('admin/events/form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $title = trim((string)$this->request->getPost('title'));
        // Whitelist: prevent mass assignment.
        $data = [
            'title' => $title,
            'slug' => $this->eventModel->makeUniqueSlug(url_title($title, '-', true)),
            'description' => $this->request->getPost('description'),
            'location' => $this->request->getPost('location'),
            'event_date' => $this->request->getPost('event_date'),
            'event_time' => $this->request->getPost('event_time'),
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
            'show' => $this->request->getPost('show') ? 1 : 0,
        ];
        $data['status'] = $this->eventModel->normalizeStatus($this->request->getPost('status'), $data['event_date']);

        if (!$this->eventModel->insert($data)) {
            $errors = $this->eventModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan acara.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/events')->with('success', 'Acara berhasil ditambahkan.');
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
        $this->data['title'] = 'Edit Acara';
        $this->data['item'] = $this->eventModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/events')->with('error', 'Acara tidak ditemukan.');
        }

        $this->data['item']['_display_status'] = $this->eventModel->effectiveStatus($this->data['item']);

        return view('admin/events/form', $this->data);
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
        $title = $this->request->getPost('title');
        // Whitelist: prevent mass assignment.
        $data = [
            'title' => $title,
            'description' => $this->request->getPost('description'),
            'location' => $this->request->getPost('location'),
            'event_date' => $this->request->getPost('event_date'),
            'event_time' => $this->request->getPost('event_time'),
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
            'show' => $this->request->getPost('show') ? 1 : 0,
        ];
        $data['status'] = $this->eventModel->normalizeStatus($this->request->getPost('status'), $data['event_date']);

        if ($title) {
            $data['slug'] = $this->eventModel->makeUniqueSlug(url_title((string)$title, '-', true), $id);
        }

        if (!$this->eventModel->update($id, $data)) {
            $errors = $this->eventModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui acara.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/events')->with('success', 'Acara berhasil diperbarui.');
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
        if (!$this->eventModel->delete($id)) {
            return redirect()->to('/admin/events')->with('error', 'Gagal menghapus acara.');
        }

        return redirect()->to('/admin/events')->with('success', 'Acara berhasil dihapus.');
    }
}
