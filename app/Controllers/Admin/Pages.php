<?php

namespace App\Controllers\Admin;

use App\Models\PageModel;
use App\Models\MenuItemModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;

class Pages extends BaseController
{
    protected $pageModel;

    /**
     * Initialize the controller and load the PageModel.
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
        $this->pageModel = new PageModel();
    }

    /**
     * Display a paginated list of static pages.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Halaman Statis';
        $this->data['items'] = $this->pageModel->orderBy('created_at', 'DESC')->paginate(10);
        $this->data['pager'] = $this->pageModel->pager;

        // Flag pages whose link is used in the navigation menu.
        $menuPageIds = new MenuItemModel()
            ->select('page_id')
            ->where('page_id IS NOT NULL', null, false)
            ->findAll();
        $this->data['menu_page_ids'] = array_map(
            static fn(array $row): int => (int)$row['page_id'],
            $menuPageIds
        );

        return view('admin/pages/index', $this->data);
    }

    /**
     * Derive the desired slug from the submitted value, falling back to the
     * page title when the field is left empty.
     *
     * @param mixed $slugInput Raw `slug` value from the form.
     * @param string $title Page title.
     *
     * @return string
     */
    private function slugFromInput(mixed $slugInput, string $title): string
    {
        $source = trim((string)$slugInput) !== '' ? (string)$slugInput : $title;
        return url_title($source, '-', true);
    }

    /**
     * Show the form for creating a new static page.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Halaman';
        $this->data['item'] = [];

        return view('admin/pages/form', $this->data);
    }

    /**
     * Store a newly created static page in storage.
     *
     * @return RedirectResponse
     * @throws ReflectionException
     */
    public function create(): RedirectResponse
    {
        $title = trim((string)$this->request->getPost('title'));
        $slug = $this->slugFromInput($this->request->getPost('slug'), $title);
        // Whitelist: prevent mass assignment (add_to_menu is handled manually, not passed to the model).
        $data = [
            'title' => $title,
            'slug' => $this->pageModel->makeUniqueSlug($slug),
            'content' => $this->request->getPost('content'),
            'excerpt' => $this->request->getPost('excerpt'),
            'image' => $this->request->getPost('image'),
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'status' => $this->request->getPost('status') ?? 'draft',
        ];
        $addToMenu = !empty($this->request->getPost('add_to_menu'));

        if (!$this->pageModel->insert($data)) {
            $errors = $this->pageModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan halaman.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        $pageId = $this->pageModel->getInsertID();

        if ($addToMenu) {
            $menuModel = new MenuItemModel();
            $menuModel->insert([
                'title' => $data['title'],
                'url' => $data['slug'],
                'target' => '_self',
                'sort_order' => 0,
                'status' => $data['status'] === 'published' ? 1 : 0,
                'type' => 'page',
                'page_id' => $pageId,
            ]);
        }

        return redirect()->to('/admin/pages')->with('success', 'Halaman berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified static page.
     *
     * @param int $id
     *
     * @return string|RedirectResponse
     */
    public function edit(int $id): string|RedirectResponse
    {
        $this->data['title'] = 'Edit Halaman';
        $this->data['item'] = $this->pageModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/pages')->with('error', 'Halaman tidak ditemukan.');
        }

        $menuModel = new MenuItemModel();
        $this->data['menuItem'] = $menuModel->getByPageId($id);

        return view('admin/pages/form', $this->data);
    }

    /**
     * Update the specified static page in storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     * @throws ReflectionException
     */
    public function update(int $id): RedirectResponse
    {
        $item = $this->pageModel->find($id);
        if (!$item) {
            return redirect()->to('/admin/pages')->with('error', 'Halaman tidak ditemukan.');
        }

        $title = trim((string)$this->request->getPost('title'));
        // Whitelist: prevent mass assignment.
        $data = [
            'title' => $title,
            'content' => $this->request->getPost('content'),
            'excerpt' => $this->request->getPost('excerpt'),
            'image' => $this->request->getPost('image'),
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'status' => $this->request->getPost('status') ?? 'draft',
        ];
        $addToMenu = !empty($this->request->getPost('add_to_menu'));

        // The slug only changes when edited explicitly in the form, so old URLs keep
        // working even when the title is updated (empty slug = derive from the title).
        $desiredSlug = $this->slugFromInput($this->request->getPost('slug'), $title);
        $data['slug'] = ($desiredSlug === '' || $desiredSlug === $item['slug'])
            ? $item['slug']
            : $this->pageModel->makeUniqueSlug($desiredSlug, (int)$id);

        if (!$this->pageModel->update($id, $data)) {
            $errors = $this->pageModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui halaman.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        $menuModel = new MenuItemModel();
        $menuItem = $menuModel->getByPageId($id);

        if ($addToMenu) {
            if ($menuItem) {
                $menuModel->update($menuItem['id'], [
                    'title' => $data['title'],
                    'url' => $data['slug'],
                    'status' => $data['status'] === 'published' ? 1 : 0,
                ]);
            } else {
                $menuModel->insert([
                    'title' => $data['title'],
                    'url' => $data['slug'],
                    'target' => '_self',
                    'sort_order' => 0,
                    'status' => $data['status'] === 'published' ? 1 : 0,
                    'type' => 'page',
                    'page_id' => $id,
                ]);
            }
        } elseif ($menuItem) {
            // Toggle turned off: actually detach the page link from the menu.
            // Child menu items are promoted to its parent so they are not deleted with it.
            foreach ($menuModel->where('parent_id', $menuItem['id'])->findAll() as $child) {
                $menuModel->update($child['id'], ['parent_id' => $menuItem['parent_id']]);
            }
            $menuModel->delete($menuItem['id']);
        }

        return redirect()->to('/admin/pages')->with('success', 'Halaman berhasil diperbarui.');
    }

    /**
     * Delete the specified static page and reset its menu link.
     *
     * @param int $id
     *
     * @return RedirectResponse
     * @throws ReflectionException
     */
    public function delete(int $id): RedirectResponse
    {
        $page = $this->pageModel->find($id);
        if (!$page) {
            return redirect()->to('/admin/pages')->with('error', 'Halaman tidak ditemukan.');
        }

        $menuModel = new MenuItemModel();
        $menuItem = $menuModel->getByPageId($id);

        if ($menuItem) {
            // Promote the deleted menu item's children to its parent so they are not orphaned,
            // then delete the page menu item (do not leave a dead link).
            $children = $menuModel->where('parent_id', $menuItem['id'])->findAll();
            foreach ($children as $child) {
                $menuModel->update($child['id'], ['parent_id' => $menuItem['parent_id']]);
            }
            if ($menuModel->countChildren($menuItem['id']) > 0) {
                return redirect()->to('/admin/pages')->with('error', 'Gagal menghapus menu terkait halaman.');
            }
            $menuModel->delete($menuItem['id']);
        }

        if (!$this->pageModel->delete($id)) {
            return redirect()->to('/admin/pages')->with('error', 'Gagal menghapus halaman.');
        }

        return redirect()->to('/admin/pages')->with('success', 'Halaman berhasil dihapus.');
    }
}
