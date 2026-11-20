<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\ContactMessageModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use ReflectionException;

/**
 * Handles the public contact page and the contact form submission.
 *
 * Response message strings are intentionally kept in Indonesian because they
 * are displayed directly to end users; code documentation stays in English.
 */
class Contact extends BaseController
{
    /**
     * Display the contact page.
     *
     * Loads the site, contact, social media, and footer settings, then renders
     * the active theme's contact view.
     *
     * @return string Rendered contact page.
     */
    public function index(): string
    {
        $this->cachePage(5); // cache page for 5 minutes

        $settingModel = new SettingModel();

        $settings = $settingModel->getMany([
            'site_name', 'site_tagline', 'site_description',
            'contact_phone', 'contact_email', 'contact_address', 'contact_hours',
            'social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok',
            'site_logo_text', 'site_logo_icon',
            'footer_description', 'footer_copyright', 'footer_services', 'footer_links',
            'page_banners',
        ]);

        $data = [
            'site_name' => $settings['site_name'] ?? 'SekolahKu',
            'site_tagline' => $settings['site_tagline'] ?? '',
            'site_description' => $settings['site_description'] ?? '',
            'site_logo_text' => $settings['site_logo_text'] ?? 'SekolahKu',
            'site_logo_icon' => $settings['site_logo_icon'] ?? 'graduation-cap',
            'contact_phone' => $settings['contact_phone'] ?? '',
            'contact_email' => $settings['contact_email'] ?? '',
            'contact_address' => $settings['contact_address'] ?? '',
            'contact_hours' => $settings['contact_hours'] ?? '',
            'social_facebook' => $settings['social_facebook'] ?? '',
            'social_instagram' => $settings['social_instagram'] ?? '',
            'social_youtube' => $settings['social_youtube'] ?? '',
            'social_tiktok' => $settings['social_tiktok'] ?? '',
            'footer_description' => $settings['footer_description'] ?? '',
            'footer_copyright' => $settings['footer_copyright'] ?? '',
            'footer_services' => $settings['footer_services'] ?? '',
            'footer_links' => $settings['footer_links'] ?? '',
            'page_banners' => $settings['page_banners'] ?? null,
            'page_title' => 'Kontak - ' . ($settings['site_name'] ?? ''),
            'meta_description' => 'Alamat, jam layanan, dan formulir pesan ' . ($settings['site_name'] ?? ''),
            'og_image' => theme_og(),
            'data_page' => 'contact',
        ];

        return view(theme_view('pages/contact'), $data);
    }

    /**
     * Process the contact form submission.
     *
     * Guards the endpoint with a honeypot field and two throttling layers
     * (per session and per IP), validates the input, stores the message, and
     * responds with JSON for AJAX requests or a redirect otherwise.
     *
     * @return RedirectResponse|ResponseInterface JSON response for AJAX requests, redirect otherwise.
     * @throws ReflectionException
     */
    public function send(): ResponseInterface|RedirectResponse
    {
        // Honeypot check
        if (!empty($this->request->getPost('website'))) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Your message has been sent.',
            ]);
        }

        // Rate limiting per session — max 1 message per 30 seconds
        $last = session()->get('contact_last_sent');
        if ($last && (time() - $last) < 30) {
            $wait = 30 - (time() - $last);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => ['Tunggu ' . $wait . ' detik sebelum mengirim lagi.'],
                ]);
            }
            return redirect()->back()->with('error', 'Tunggu ' . $wait . ' detik sebelum mengirim lagi.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'subject' => 'required|min_length[3]|max_length[200]',
            'message' => 'required|min_length[10]|max_length[5000]',
        ];

        $messages = [
            'name' => [
                'required' => 'Nama wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'subject' => [
                'required' => 'Keperluan wajib dipilih.',
                'min_length' => 'Keperluan minimal 3 karakter.',
                'max_length' => 'Keperluan maksimal 200 karakter.',
            ],
            'message' => [
                'required' => 'Pesan wajib diisi.',
                'min_length' => 'Pesan minimal 10 karakter.',
                'max_length' => 'Pesan maksimal 5000 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->validator->getErrors(),
                ]);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Rate limiting per IP — cannot be bypassed by clearing cookies/session.
        // Maximum 5 stored messages per 10 minutes from a single IP address; only
        // submissions that pass validation consume the quota, so a mistyped form
        // does not use up the allowance.
        // Note: `$proxyIPs` in app/Config/App.php is empty, so behind a proxy/CDN
        // every visitor appears as the same IP — set `$proxyIPs` if the hosting uses a proxy.
        $throttler = service('throttler');
        $ip = $this->request->getIPAddress();
        $ipKey = 'contact-ip-' . md5($ip);

        if ($throttler->check($ipKey, 5, 600) === false) {
            $wait = $throttler->getTokenTime();
            $msg = 'Terlalu banyak pesan dari jaringan ini. Coba lagi dalam ' . $wait . ' detik.';

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => [$msg],
                ]);
            }

            return redirect()->back()->with('error', $msg);
        }

        $contactModel = new ContactMessageModel();
        $contactModel->insert([
            'name' => strip_tags($this->request->getPost('name')),
            'email' => strip_tags($this->request->getPost('email')),
            'subject' => strip_tags($this->request->getPost('subject')),
            'message' => strip_tags($this->request->getPost('message')),
            'is_read' => 0,
        ]);

        session()->set('contact_last_sent', time());

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pesan berhasil dikirim. Kami akan menghubungi Anda segera.',
            ]);
        }

        return redirect()->back()->with('success', 'Pesan berhasil dikirim. Kami akan menghubungi Anda segera.');
    }
}
