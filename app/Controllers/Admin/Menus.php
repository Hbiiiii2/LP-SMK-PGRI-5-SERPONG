<?php

namespace App\Controllers\Admin;

use App\Models\MenuItemModel;
use App\Models\PageModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Menus extends BaseController
{
    /**
     * Valid `section_key` values — the single source for both the form and validation.
     * Values outside this list are ignored when saving.
     */
    public const array SECTION_KEYS = [
        'profile' => 'Profil',
        'programs' => 'Program',
        'extracurriculars' => 'Ekstrakurikuler',
        'teachers' => 'Guru',
        'achievements' => 'Prestasi',
        'testimonials' => 'Testimoni',
        'news' => 'Berita',
        'events' => 'Agenda',
        'gallery' => 'Galeri',
        'faq' => 'FAQ',
        'downloads' => 'Download',
        'contact' => 'Kontak',
    ];

    protected $menuModel;

    /**
     * Initialize the controller and load the MenuItemModel.
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
        $this->menuModel = new MenuItemModel();
    }

    /**
     * Display the navigation menu management page.
     *
     * @return string
     */
    public function index(): string
    {
        $pages = new PageModel()->findAll();
        $pageTitles = [];
        foreach ($pages as $page) {
            $pageTitles[$page['id']] = $page['title'];
        }

        $this->data['title'] = 'Menu Navigasi';
        $allItems = $this->menuModel->getFlatTree();
        $this->data['allItems'] = $allItems;
        $this->data['pageTitles'] = $pageTitles;
        // Items unreachable from the main menu (circular structure caused by
        // legacy data) will not appear in the list — notify the admin.
        $this->data['orphanCount'] = max(0, $this->menuModel->countAllResults() - count($allItems));

        return view('admin/menus/index', $this->data);
    }

    /**
     * Show the form for creating a new menu item.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Menu';
        $this->data['item'] = [];
        $this->data['parents'] = $this->menuModel->getRootItems();
        $this->data['sectionKeys'] = self::SECTION_KEYS;

        return view('admin/menus/form', $this->data);
    }

    /**
     * Store a newly created menu item in storage.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $parentId = $this->request->getPost('parent_id');
        // Whitelist: prevent mass assignment (type is forced to custom).
        $data = [
            'parent_id' => $parentId ? (int)$parentId : null,
            'title' => $this->request->getPost('title'),
            'url' => $this->request->getPost('url'),
            'target' => $this->request->getPost('target') ?? '_self',
            'icon' => $this->request->getPost('icon'),
            'section_key' => $this->sanitizeSectionKey($this->request->getPost('section_key')),
            'status' => $this->request->getPost('status') ? 1 : 0,
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
            'type' => 'custom',
        ];

        if ($data['parent_id'] && !$this->menuModel->find($data['parent_id'])) {
            return redirect()->back()->withInput()->with('error', 'Induk menu tidak valid.');
        }

        if ($this->isDangerousUrl($data['url'] ?? null)) {
            return redirect()->back()->withInput()->with('error', 'URL menu tidak valid (skema tidak diizinkan).');
        }

        if ($this->hasDuplicateUrl((string)($data['url'] ?? ''))) {
            return redirect()->back()->withInput()->with('error', 'URL menu sudah dipakai item menu lain.');
        }

        if (!$this->menuModel->insert($data)) {
            $errors = $this->menuModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan menu.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/menus')->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified menu item.
     *
     * @param int $id
     *
     * @return string|RedirectResponse
     */
    public function edit(int $id): string|RedirectResponse
    {
        $this->data['title'] = 'Edit Menu';
        $this->data['item'] = $this->menuModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/menus')->with('error', 'Menu tidak ditemukan.');
        }

        $excludeIds = $this->menuModel->getDescendantIds($id);
        $excludeIds[] = $id;

        $this->data['parents'] = $this->menuModel->getAvailableParents($excludeIds);
        $this->data['locked'] = $this->data['item']['type'] !== 'custom';
        $this->data['sectionKeys'] = self::SECTION_KEYS;

        return view('admin/menus/form', $this->data);
    }

    /**
     * Update the specified menu item in storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(int $id): RedirectResponse
    {
        $item = $this->menuModel->find($id);

        if (!$item) {
            return redirect()->to('/admin/menus')->with('error', 'Menu tidak ditemukan.');
        }

        $rawParentId = $this->request->getPost('parent_id');
        $parentId = $rawParentId ? (int)$rawParentId : null;

        // Prevent cycles: a parent cannot be the item itself or one of its descendants.
        if ($parentId) {
            if ($parentId === (int)$id) {
                return redirect()->back()->withInput()->with('error', 'Induk menu tidak boleh diri sendiri.');
            }
            $descendants = $this->menuModel->getDescendantIds($id);
            if (in_array($parentId, array_map('intval', $descendants), true)) {
                return redirect()->back()->withInput()->with('error', 'Induk menu tidak boleh anak dari menu ini.');
            }
            if (!$this->menuModel->find($parentId)) {
                return redirect()->back()->withInput()->with('error', 'Induk menu tidak valid.');
            }
        }

        if ($item['type'] !== 'custom') {
            $data = [
                'parent_id' => $parentId,
                'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
            ];
        } else {
            // Whitelist for custom items.
            $data = [
                'parent_id' => $parentId,
                'title' => $this->request->getPost('title'),
                'url' => $this->request->getPost('url'),
                'target' => $this->request->getPost('target') ?? '_self',
                'icon' => $this->request->getPost('icon'),
                'section_key' => $this->sanitizeSectionKey($this->request->getPost('section_key')),
                'status' => $this->request->getPost('status') ? 1 : 0,
                'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
            ];

            if ($this->isDangerousUrl($data['url'] ?? null)) {
                return redirect()->back()->withInput()->with('error', 'URL menu tidak valid (skema tidak diizinkan).');
            }

            if ($this->hasDuplicateUrl((string)($data['url'] ?? ''), (int)$id)) {
                return redirect()->back()->withInput()->with('error', 'URL menu sudah dipakai item menu lain.');
            }
        }

        if (!$this->menuModel->update($id, $data)) {
            $errors = $this->menuModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui menu.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/menus')->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Delete the specified menu item from storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $item = $this->menuModel->find($id);

        if (!$item) {
            return redirect()->to('/admin/menus')->with('error', 'Menu tidak ditemukan.');
        }

        if ($item['type'] !== 'custom') {
            return redirect()->to('/admin/menus')->with('error', 'Menu bawaan tidak dapat dihapus.');
        }

        if ($this->menuModel->countChildren($id) > 0) {
            return redirect()->to('/admin/menus')->with('error', 'Hapus menu anak terlebih dahulu.');
        }

        if (!$this->menuModel->delete($id)) {
            return redirect()->to('/admin/menus')->with('error', 'Gagal menghapus menu.');
        }

        return redirect()->to('/admin/menus')->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Reorder menu items via drag-and-drop.
     *
     * @return ResponseInterface
     */
    public function reorder(): ResponseInterface
    {
        $input = json_decode($this->request->getBody(), true);
        if (!is_array($input)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Payload tidak valid.']);
        }

        $items = $input['items'] ?? [];
        if (!is_array($items) || $items === [] || count($items) > 500) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Daftar menu tidak valid.']);
        }

        // Validate and sanitize each item.
        $clean = [];
        $ids = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Item menu tidak valid.']);
            }

            $rawId = $item['id'] ?? null;
            if (is_int($rawId)) {
                $id = $rawId;
            } elseif (is_string($rawId) && ctype_digit($rawId)) {
                $id = (int)$rawId;
            } else {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'ID menu tidak valid.']);
            }
            if ($id <= 0 || isset($ids[$id])) {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'ID menu tidak valid atau duplikat.']);
            }
            $ids[$id] = true;

            $rawParent = $item['parent_id'] ?? null;
            if ($rawParent === null || $rawParent === '') {
                $parentId = null;
            } elseif (is_int($rawParent)) {
                $parentId = $rawParent;
            } elseif (is_string($rawParent) && ctype_digit($rawParent)) {
                $parentId = (int)$rawParent;
            } else {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Induk menu tidak valid.']);
            }
            if ($parentId !== null && $parentId <= 0) {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Induk menu tidak valid.']);
            }

            $rawSort = $item['sort_order'] ?? 0;
            if (is_int($rawSort)) {
                $sortOrder = $rawSort;
            } elseif (is_string($rawSort) && preg_match('/^-?\d+$/', $rawSort)) {
                $sortOrder = (int)$rawSort;
            } else {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Urutan menu tidak valid.']);
            }

            $clean[] = ['id' => $id, 'parent_id' => $parentId, 'sort_order' => $sortOrder];
        }

        // Ensure every ID exists in the table.
        $idList = array_column($clean, 'id');
        if (!$this->reorderIdsExist($this->menuModel, $idList)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Sebagian menu tidak ditemukan.']);
        }

        // Ensure the parent exists and prevent cycles (parent = itself / descendant).
        $idSet = array_fill_keys($idList, true);
        foreach ($clean as $row) {
            $pid = $row['parent_id'];
            if ($pid === null) {
                continue;
            }
            if ($pid === $row['id']) {
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Menu tidak boleh menjadi anak dari dirinya sendiri.']);
            }
            if (!isset($idSet[$pid]) && !$this->menuModel->find($pid)) {
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Induk menu tidak ditemukan.']);
            }
        }

        // Prevent cycles in the **proposed** arrangement, not just in the current database state.
        // The per-row check above uses getDescendantIds(), which reads the old data,
        // so a payload containing several items at once (A→B, B→C, C→A) could pass
        // and be stored as a circular structure — menus stop rendering and child
        // traversal loops forever.
        $proposed = [];
        foreach (new MenuItemModel()->findAll() as $existing) {
            $pid = $existing['parent_id'];
            $proposed[(int)$existing['id']] = ($pid === null || $pid === '') ? null : (int)$pid;
        }
        foreach ($clean as $row) {
            $proposed[$row['id']] = $row['parent_id'];
        }

        if ($this->menuModel->hasCycle($proposed)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'error' => 'Struktur menu tidak valid: sebuah menu tidak boleh berada di bawah turunannya sendiri.']);
        }

        $success = $this->menuModel->reorderItems($clean);

        if (!$success) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => 'Gagal menyimpan urutan menu.']);
        }

        return $this->response->setJSON(['success' => true, 'csrfHash' => csrf_hash()]);
    }

    /**
     * Return `section_key` only when it is one of the valid keys.
     *
     * The form control is a <select>, but the create/update endpoints are plain
     * POST, so any value can be submitted. Without this whitelist, arbitrary input
     * (≤100 characters) could be stored — one such value used to be rendered raw
     * in the locked-menu branch of the form.
     *
     * @param mixed $value Raw value from POST.
     *
     * @return string|null
     */
    private function sanitizeSectionKey(mixed $value): ?string
    {
        return is_string($value) && array_key_exists($value, self::SECTION_KEYS) ? $value : null;
    }

    /**
     * Normalize a menu URL for duplicate comparison.
     *
     * @param string $url
     *
     * @return string
     */
    private function normalizeMenuUrl(string $url): string
    {
        return $url
                |> trim(...)
                |> (fn($x) => trim($x, '/'))
                |> strtolower(...);
    }

    /**
     * Check whether the URL is already used by another menu item (custom/page).
     *
     * Built-in menus (`type = 'system'`) are not counted because they are not
     * rendered from this table, and some of them intentionally share a URL (e.g. `#`).
     *
     * @param string $url URL to be saved.
     * @param int|null $excludeId ID to exclude (when updating an item).
     *
     * @return bool
     */
    private function hasDuplicateUrl(string $url, ?int $excludeId = null): bool
    {
        $needle = $this->normalizeMenuUrl($url);

        if ($needle === '') {
            return false;
        }

        $others = new MenuItemModel()
            ->groupStart()
            ->where('type !=', 'system')
            ->orWhere('type', null)
            ->groupEnd()
            ->findAll();

        foreach ($others as $other) {
            if ((int)$other['id'] === $excludeId) {
                continue;
            }

            if ($this->normalizeMenuUrl((string)$other['url']) === $needle) {
                return true;
            }
        }

        return false;
    }
}
