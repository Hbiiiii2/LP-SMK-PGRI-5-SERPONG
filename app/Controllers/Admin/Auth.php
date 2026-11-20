<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;
use App\Models\SettingModel;
use CodeIgniter\HTTP\RedirectResponse;
use ReflectionException;

class Auth extends \App\Controllers\BaseController
{
    /**
     * Display the login form.
     *
     * @return string|RedirectResponse
     */
    public function login(): string|RedirectResponse
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getMany([
            'site_name', 'site_tagline', 'site_logo_icon', 'about',
            'contact_phone', 'contact_email', 'contact_address',
            'social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok',
        ]);
        $about = json_decode($settings['about'] ?? '{}', true) ?: [];
        $settings['accreditation'] = $about['accreditation'] ?? '';

        return view('admin/login', ['settings' => $settings]);
    }

    /**
     * Process login form submission.
     *
     * @return RedirectResponse
     * @throws ReflectionException
     */
    public function attempt(): RedirectResponse
    {
        // Anti-brute-force / credential-stuffing throttle (no captcha).
        // Bucket 1: per IP — 10 attempts/minute. Bucket 2: per account — 5 attempts/5 minutes.
        $throttler = service('throttler');
        $ip = $this->request->getIPAddress();
        $ipKey = 'login-ip-' . md5($ip);

        if ($throttler->check($ipKey, 10, 60) === false) {
            $wait = $throttler->getTokenTime();
            log_message('warning', 'Login throttled (IP) dari {ip}, tunggu {wait}s', ['ip' => $ip, 'wait' => $wait]);
            return redirect()->back()->withInput()
                ->with('error', "Terlalu banyak percobaan login. Coba lagi dalam {$wait} detik.");
        }

        $usernameRaw = (string)$this->request->getPost('username');
        $acctNorm = strtolower(trim($usernameRaw));
        $acctKey = $acctNorm !== '' ? 'login-acct-' . md5($acctNorm) : null;

        if ($acctKey && $throttler->check($acctKey, 5, 300) === false) {
            $wait = $throttler->getTokenTime();
            log_message('warning', 'Login throttled (akun) untuk {user} dari {ip}, tunggu {wait}s', ['user' => $acctNorm, 'ip' => $ip, 'wait' => $wait]);
            return redirect()->back()->withInput()
                ->with('error', "Akun terkunci sementara karena terlalu banyak percobaan gagal. Coba lagi dalam {$wait} detik.");
        }

        $validation = service('validation');

        $validation->setRules([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->attemptLogin($username, $password);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        // Successful login: reset the account throttle so a legitimate user is not
        // locked out because of earlier typos. The IP throttle is deliberately NOT
        // reset (every attempt still counts, as DDOS protection).
        if ($acctKey) {
            $throttler->remove($acctKey);
        }

        session()->regenerate();
        session()->set([
            'isLoggedIn' => true,
            'userId' => $user['id'],
            'username' => $user['username'],
            'fullName' => $user['full_name'],
            'role' => $user['role'],
        ]);

        // Record the last login time (the `last_login` column shown in the
        // Users/Profile list). Validation is skipped because this is an internal write.
        $userModel->skipValidation(true)->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        session()->setFlashdata('success', 'Selamat datang, ' . $user['full_name'] . '!');

        return redirect()->to('/admin/dashboard');
    }

    /**
     * Destroy the user session and redirect to login.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda berhasil logout.');
    }
}
