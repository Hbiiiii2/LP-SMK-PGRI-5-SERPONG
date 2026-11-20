<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['username', 'email', 'password', 'full_name', 'role', 'is_active', 'last_login'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'username' => 'required|alpha_numeric|min_length[3]|max_length[30]',
        'email' => 'required|valid_email|max_length[100]',
        'password' => 'permit_empty|min_length[6]|max_length[255]',
        'full_name' => 'required|min_length[3]|max_length[100]',
        'role' => 'required|in_list[admin,editor]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username wajib diisi.',
            'alpha_numeric' => 'Username hanya boleh huruf dan angka.',
            'min_length' => 'Username minimal 3 karakter.',
            'max_length' => 'Username maksimal 30 karakter.',
            'is_unique' => 'Username sudah digunakan.',
        ],
        'email' => [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'max_length' => 'Email maksimal 100 karakter.',
            'is_unique' => 'Email sudah digunakan.',
        ],
        'password' => [
            'min_length' => 'Password minimal 6 karakter.',
            'max_length' => 'Password maksimal 255 karakter.',
        ],
        'full_name' => [
            'required' => 'Nama lengkap wajib diisi.',
            'min_length' => 'Nama lengkap minimal 3 karakter.',
            'max_length' => 'Nama lengkap maksimal 100 karakter.',
        ],
        'role' => [
            'required' => 'Peran wajib dipilih.',
            'in_list' => 'Peran tidak valid.',
        ],
        'is_active' => [
            'in_list' => 'Status akun tidak valid.',
        ],
    ];

    /**
     * Attempt to authenticate a user by username/email and password.
     *
     * @param string $username The username or email address.
     * @param string $password The plain-text password.
     *
     * @return array|null The user record on success, or null on failure.
     */
    public function attemptLogin(string $username, string $password): ?array
    {
        $user = $this->groupStart()
            ->where('username', $username)
            ->orWhere('email', $username)
            ->groupEnd()
            ->where('is_active', 1)
            ->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }
}
