<?php

namespace App\Controllers\Admin;

use App\Models\ContactMessageModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Messages extends BaseController
{
    protected $messageModel;

    /**
     * Initialize the controller and load the ContactMessageModel.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     */
    public function initController($request, $response, $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->messageModel = new ContactMessageModel();
    }

    /**
     * Display a paginated list of contact messages.
     *
     * @return string
     */
    public function index(): string
    {
        // Status filter: '' (all) | 'unread' | 'read'
        $status = (string)$this->request->getGet('status');

        if (!in_array($status, ['unread', 'read'], true)) {
            $status = '';
        }

        if ($status === 'unread') {
            $this->messageModel->where('is_read', 0);
        } elseif ($status === 'read') {
            $this->messageModel->where('is_read', 1);
        }

        $this->data['title'] = 'Pesan Masuk';
        // created_at only has second precision, so id is used as a tie-breaker:
        // without it, row order across pages is nondeterministic (rows may be duplicated/skipped).
        $this->data['items'] = $this->messageModel
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $this->data['pager'] = $this->messageModel->pager;
        $this->data['status'] = $status;
        $this->data['counts'] = [
            'all' => new ContactMessageModel()->countAllResults(),
            'unread' => new ContactMessageModel()->where('is_read', 0)->countAllResults(),
            'read' => new ContactMessageModel()->where('is_read', 1)->countAllResults(),
        ];

        return view('admin/messages/index', $this->data);
    }

    /**
     * Show a single message detail and mark it as read.
     *
     * @param int $id
     *
     * @return string|RedirectResponse
     */
    public function show(int $id): string|RedirectResponse
    {
        $message = $this->messageModel->find($id);

        if (!$message) {
            return redirect()->to('/admin/messages')->with('error', 'Pesan tidak ditemukan.');
        }

        if (empty($message['is_read'])) {
            $this->messageModel->update($id, ['is_read' => 1]);
            // Sync the displayed data so the badge does not go stale.
            $message['is_read'] = 1;
        }

        $this->data['title'] = 'Detail Pesan';
        $this->data['item'] = $message;

        return view('admin/messages/show', $this->data);
    }

    /**
     * Mark a message as read (POST).
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function markRead(int $id): RedirectResponse
    {
        return $this->setReadStatus($id, 1);
    }

    /**
     * Mark a message as unread (POST).
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function markUnread(int $id): RedirectResponse
    {
        return $this->setReadStatus($id, 0);
    }

    /**
     * Delete multiple selected messages (POST).
     *
     * @return RedirectResponse
     */
    public function bulkDelete(): RedirectResponse
    {
        $status = (string)$this->request->getPost('status');
        $ids = $this->request->getPost('ids');

        $ids = is_array($ids)
            ? array_map('intval', $ids)
                |> (fn($x) => array_filter($x, static fn($id) => $id > 0))
                |> array_unique(...)
                |> array_values(...)
            : [];

        if ($ids === []) {
            return redirect()->to($this->listUrl($status))->with('error', 'Tidak ada pesan yang dipilih.');
        }

        // delete() returns true even when no row was deleted,
        // so count the rows that actually exist first.
        $total = $this->messageModel->whereIn('id', $ids)->countAllResults();

        if ($total === 0) {
            return redirect()->to($this->listUrl($status))->with('error', 'Pesan tidak ditemukan.');
        }

        $this->messageModel->whereIn('id', $ids)->delete();

        return redirect()->to($this->listUrl($status))
            ->with('success', $total . ' pesan berhasil dihapus.');
    }

    /**
     * Delete a contact message by ID.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        // delete() returns true even when no row was deleted,
        // so check that the message exists first.
        if (!$this->messageModel->find($id)) {
            return redirect()->to('/admin/messages')->with('error', 'Pesan tidak ditemukan.');
        }

        if (!$this->messageModel->delete($id)) {
            return redirect()->to('/admin/messages')->with('error', 'Gagal menghapus pesan.');
        }

        return redirect()->to('/admin/messages')->with('success', 'Pesan berhasil dihapus.');
    }

    /**
     * Update the read status of a single message.
     *
     * @param int $id
     * @param int $isRead
     *
     * @return RedirectResponse
     */
    private function setReadStatus(int $id, int $isRead): RedirectResponse
    {
        $status = (string)$this->request->getPost('status');

        if (!$this->messageModel->find($id)) {
            return redirect()->to($this->listUrl($status))->with('error', 'Pesan tidak ditemukan.');
        }

        if (!$this->messageModel->update($id, ['is_read' => $isRead])) {
            return redirect()->to($this->listUrl($status))->with('error', 'Gagal memperbarui status pesan.');
        }

        return redirect()->to($this->listUrl($status))
            ->with('success', $isRead ? 'Pesan ditandai sudah dibaca.' : 'Pesan ditandai belum dibaca.');
    }

    /**
     * Build the message list URL while keeping the active status filter.
     *
     * @param string $status
     *
     * @return string
     */
    private function listUrl(string $status): string
    {
        return '/admin/messages'
            . (in_array($status, ['unread', 'read'], true) ? '?status=' . $status : '');
    }
}
