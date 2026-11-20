<?php

namespace App\Controllers\Admin;

use App\Models\NewsCommentModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Comments extends BaseController
{
    protected $commentModel;

    /**
     * Initialize controller, load the comment model.
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
        $this->commentModel = new NewsCommentModel();
    }

    /**
     * Display a paginated list of comments.
     *
     * @return string
     */
    public function index(): string
    {
        // Status filter: '' (all) | 'pending' | 'approved'
        $status = $this->statusParam();

        // JOIN news for the article title + slug (the "News" column used to show
        // only the news_id number because the view read a non-existent news_title).
        $list = new NewsCommentModel();
        $list->select('news_comments.*, news.title AS news_title, news.slug AS news_slug')
            ->join('news', 'news.id = news_comments.news_id', 'left');

        if ($status === 'pending') {
            $list->where('news_comments.is_approved', 0);
        } elseif ($status === 'approved') {
            $list->where('news_comments.is_approved', 1);
        }

        $this->data['title'] = 'Komentar';
        // created_at only has second precision, so id is used as a tie-breaker:
        // without it, row order across pages is nondeterministic (rows may be duplicated/skipped).
        // Columns are qualified because `news` also has created_at/id.
        $this->data['items'] = $list
            ->orderBy('news_comments.created_at', 'DESC')
            ->orderBy('news_comments.id', 'DESC')
            ->paginate(10);

        $this->data['pager'] = $list->pager;
        $this->data['status'] = $status;
        $this->data['counts'] = [
            'all' => (new NewsCommentModel())->countAllResults(),
            'pending' => (new NewsCommentModel())->where('is_approved', 0)->countAllResults(),
            'approved' => (new NewsCommentModel())->where('is_approved', 1)->countAllResults(),
        ];

        return view('admin/comments/index', $this->data);
    }

    /**
     * Approve a comment by its ID.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function approve(int $id): RedirectResponse
    {
        $status = $this->statusParam();

        // update() returns true even when no row is affected,
        // so check that the comment exists first.
        if (!$this->commentModel->find($id)) {
            return redirect()->to($this->listUrl($status))->with('error', 'Komentar tidak ditemukan.');
        }

        if (!$this->commentModel->update($id, ['is_approved' => 1])) {
            return redirect()->to($this->listUrl($status))->with('error', 'Gagal menyetujui komentar.');
        }

        return redirect()->to($this->listUrl($status))->with('success', 'Komentar berhasil disetujui.');
    }

    /**
     * Reject (delete) a comment by its ID.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function reject(int $id): RedirectResponse
    {
        $status = $this->statusParam();

        if (!$this->commentModel->find($id)) {
            return redirect()->to($this->listUrl($status))->with('error', 'Komentar tidak ditemukan.');
        }

        if (!$this->commentModel->delete($id)) {
            return redirect()->to($this->listUrl($status))->with('error', 'Gagal menolak komentar.');
        }

        return redirect()->to($this->listUrl($status))->with('success', 'Komentar berhasil ditolak dan dihapus.');
    }

    /**
     * Delete a comment by its ID.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $status = $this->statusParam();

        if (!$this->commentModel->find($id)) {
            return redirect()->to($this->listUrl($status))->with('error', 'Komentar tidak ditemukan.');
        }

        if (!$this->commentModel->delete($id)) {
            return redirect()->to($this->listUrl($status))->with('error', 'Gagal menghapus komentar.');
        }

        return redirect()->to($this->listUrl($status))->with('success', 'Komentar berhasil dihapus.');
    }

    /**
     * Approve or delete multiple selected comments (POST).
     *
     * @return RedirectResponse
     */
    public function bulk(): RedirectResponse
    {
        $action = (string)$this->request->getPost('action');
        $status = $this->statusParam();

        $ids = $this->request->getPost('ids');
        $ids = is_array($ids)
            ? array_values(array_unique(array_filter(array_map('intval', $ids), static fn($id) => $id > 0)))
            : [];

        if ($ids === []) {
            return redirect()->to($this->listUrl($status))->with('error', 'Tidak ada komentar yang dipilih.');
        }

        // delete()/update() return true even when no row is affected,
        // so count the rows that actually exist first.
        $total = (new NewsCommentModel())->whereIn('id', $ids)->countAllResults();

        if ($total === 0) {
            return redirect()->to($this->listUrl($status))->with('error', 'Komentar tidak ditemukan.');
        }

        if ($action === 'approve') {
            foreach ($ids as $id) {
                (new NewsCommentModel())->update($id, ['is_approved' => 1]);
            }

            return redirect()->to($this->listUrl($status))
                ->with('success', $total . ' komentar berhasil disetujui.');
        }

        if ($action === 'delete') {
            (new NewsCommentModel())->whereIn('id', $ids)->delete();

            return redirect()->to($this->listUrl($status))
                ->with('success', $total . ' komentar berhasil dihapus.');
        }

        return redirect()->to($this->listUrl($status))->with('error', 'Aksi tidak dikenal.');
    }

    /**
     * Read and validate the active status filter from the query string.
     *
     * @return string '' | 'pending' | 'approved'
     */
    private function statusParam(): string
    {
        $status = (string)$this->request->getGet('status');

        return in_array($status, ['pending', 'approved'], true) ? $status : '';
    }

    /**
     * Build the comment list URL while keeping the active status filter.
     *
     * @param string $status
     *
     * @return string
     */
    private function listUrl(string $status): string
    {
        return '/admin/comments'
            . (in_array($status, ['pending', 'approved'], true) ? '?status=' . $status : '');
    }
}
