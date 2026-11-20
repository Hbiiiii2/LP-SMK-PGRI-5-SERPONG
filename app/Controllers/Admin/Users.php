<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Users extends BaseController
{
    protected $userModel;

    /**
     * Initialize the controller, load the user model, and restrict to superadmin only.
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
        $this->userModel = new UserModel();

        if (($this->data['user']['role'] ?? '') !== 'superadmin') {
            redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak. Hanya superadmin.')->send();
            exit;
        }
    }

    /**
     * Display a paginated list of non-superadmin users.
     *
     * @return string
     */
    public function index(): string
    {
        $this->data['title'] = 'Pengguna';
        // Include rows with a NULL role (treated as editor) so they do not "disappear"
        // from the list and sort explicitly so pagination stays stable.
        $this->data['items'] = $this->userModel
            ->groupStart()
            ->where('role !=', 'superadmin')
            ->orWhere('role', null)
            ->groupEnd()
            ->orderBy('id', 'ASC')
            ->paginate(10);
        $this->data['pager'] = $this->userModel->pager;

        return view('admin/users/index', $this->data);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return string
     */
    public function new(): string
    {
        $this->data['title'] = 'Tambah Pengguna';
        $this->data['item'] = [];
        return view('admin/users/form', $this->data);
    }

    /**
     * Process user creation.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $role = (string)$this->request->getPost('role');
        if ($role === 'superadmin') {
            return redirect()->back()->withInput()->with('error', 'Tidak dapat membuat akun Superadmin.');
        }

        $password = (string)$this->request->getPost('password');
        if ($password === '') {
            return redirect()->back()->withInput()->with('error', 'Password wajib diisi (minimal 6 karakter).');
        }

        if ($password !== (string)$this->request->getPost('password_confirm')) {
            return redirect()->back()->withInput()->with('error', 'Konfirmasi sandi tidak cocok.');
        }

        // Whitelist: prevent mass assignment (avatar, last_login, etc. are rejected).
        $data = [
            'username' => trim((string)$this->request->getPost('username')),
            'email' => trim((string)$this->request->getPost('email')),
            'full_name' => trim((string)$this->request->getPost('full_name')),
            'role' => $role,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        if (new UserModel()->where('username', $data['username'])->first()) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }
        if (new UserModel()->where('email', $data['email'])->first()) {
            return redirect()->back()->withInput()->with('error', 'Email sudah digunakan.');
        }

        try {
            $saved = $this->userModel->insert($data);
        } catch (DatabaseException $e) {
            // A race can slip past the manual check above — the DB unique index catches it.
            log_message('warning', 'Gagal menyimpan pengguna baru: {msg}', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pengguna. Pastikan username dan email belum digunakan.');
        }

        if (!$saved) {
            $errors = $this->userModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal menyimpan pengguna.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a user.
     *
     * @param int $id
     *
     * @return string|RedirectResponse
     */
    public function edit(int $id): string|RedirectResponse
    {
        $this->data['title'] = 'Edit Pengguna';
        $this->data['item'] = $this->userModel->find($id);

        if (!$this->data['item']) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        if (($this->data['item']['role'] ?? '') === 'superadmin') {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat mengedit akun Superadmin.');
        }

        return view('admin/users/form', $this->data);
    }

    /**
     * Process user update.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(int $id): RedirectResponse
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        if (($user['role'] ?? '') === 'superadmin') {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat mengedit akun Superadmin.');
        }

        $role = (string)$this->request->getPost('role');
        if ($role === 'superadmin') {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat mengedit akun Superadmin.');
        }

        // Whitelist: prevent mass assignment.
        $data = [
            'username' => trim((string)$this->request->getPost('username')),
            'email' => trim((string)$this->request->getPost('email')),
            'full_name' => trim((string)$this->request->getPost('full_name')),
            'role' => $role,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $password = (string)$this->request->getPost('password');
        if ($password !== '') {
            if ($password !== (string)$this->request->getPost('password_confirm')) {
                return redirect()->back()->withInput()->with('error', 'Konfirmasi sandi tidak cocok.');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (new UserModel()->where('username', $data['username'])->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }
        if (new UserModel()->where('email', $data['email'])->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()->with('error', 'Email sudah digunakan.');
        }

        try {
            $saved = $this->userModel->update($id, $data);
        } catch (DatabaseException $e) {
            log_message('warning', 'Gagal memperbarui pengguna {id}: {msg}', ['id' => $id, 'msg' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengguna. Pastikan username dan email belum digunakan.');
        }

        if (!$saved) {
            $errors = $this->userModel->errors();
            $msg = $errors ? implode(' ', $errors) : 'Gagal memperbarui pengguna.';
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $msg);
        }

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Delete a user by its ID.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        if ($id == session()->get('userId')) {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        if (($user['role'] ?? '') === 'superadmin') {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat menghapus akun Superadmin.');
        }

        if (!$this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('error', 'Gagal menghapus pengguna.');
        }

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil dihapus.');
    }
}
