<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\NewsModel;
use App\Models\NewsCommentModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use ReflectionException;

/**
 * Handles the public news listing, article detail, search, and comment
 * submission endpoints.
 *
 * Response message strings are intentionally kept in Indonesian because they
 * are displayed directly to end users; code documentation stays in English.
 */
class News extends BaseController
{
    /**
     * Display the news listing page.
     *
     * Supports keyword search and category/month filters, all preserved by the
     * pager, then renders the active theme's news view.
     *
     * @return string Rendered news listing page.
     */
    public function index(): string
    {
        $this->cachePage(1);

        $settingModel = new SettingModel();
        $newsModel = new NewsModel();

        $settings = $settingModel->getMany([
            'site_name', 'site_tagline', 'site_description',
            'contact_phone', 'contact_email', 'contact_address', 'contact_hours',
            'social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok',
            'site_logo_text', 'site_logo_icon',
            'footer_description', 'footer_copyright', 'footer_services', 'footer_links',
            'page_banners',
        ]);

        $search = $this->queryParam('search');
        $category = $this->queryParam('category');
        $month = $this->queryParam('month');

        service('pager')->only(['search', 'category', 'month']);

        if ($search) {
            $news = $newsModel->search($search);
        } else {
            $news = $newsModel->getFiltered($category, $month);
        }

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
            'page_title' => 'Berita - ' . ($settings['site_name'] ?? ''),
            'meta_description' => 'Kabar kampus, prestasi, dan pengumuman ' . ($settings['site_name'] ?? ''),
            'og_image' => theme_og(),
            'data_page' => 'news',
            'news' => $news,
            'pager' => $newsModel->pager,
            'categories' => $newsModel->getPublishedCategories(),
            'recent_news' => $newsModel->getRecent(),
            'tags' => $newsModel->getAllTags(),
            'archive' => $newsModel->getArchive(),
            'search' => $search,
            'category' => $category,
            'month' => $month,
        ];

        return view(theme_view('pages/news'), $data);
    }

    /**
     * Display a single news article.
     *
     * @param string $slug Article slug.
     *
     * @return string|RedirectResponse Rendered article page.
     */
    public function detail(string $slug): string|RedirectResponse
    {
        // Deliberately no cachePage(): the page cache is keyed by URI only (cookies,
        // session, and flashdata are ignored), so (1) comment validation errors
        // disappear when the page is served from cache and (2) form contents
        // (a previous visitor's name/email) could be cached and served to
        // other visitors.

        $settingModel = new SettingModel();
        $newsModel = new NewsModel();
        $commentModel = new NewsCommentModel();

        $settings = $settingModel->getMany([
            'site_name', 'site_tagline', 'site_description',
            'contact_phone', 'contact_email', 'contact_address', 'contact_hours',
            'social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok',
            'site_logo_text', 'site_logo_icon',
            'footer_description', 'footer_copyright', 'footer_services', 'footer_links',
            'page_banners',
        ]);

        $post = $newsModel->getBySlug($slug);

        if (!$post) {
            throw PageNotFoundException::forPageNotFound();
        }

        $newsModel->incrementViews($post['id']);

        $comments = $commentModel->getByNews($post['id']);
        $related = $newsModel->getRelated($post);

        $prevPost = $newsModel->getPrevPost($post['id'], $post['published_at']);
        $nextPost = $newsModel->getNextPost($post['id'], $post['published_at']);

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
            'page_title' => $post['title'] . ' - ' . ($settings['site_name'] ?? ''),
            'meta_description' => $post['excerpt'] ?? mb_strimwidth(strip_tags($post['content'] ?? ''), 0, 160),
            'og_type' => 'article',
            'og_image' => !empty($post['image']) ? (preg_match('#^https?://#', $post['image']) ? $post['image'] : base_url(ltrim($post['image'], '/'))) : theme_og(),
            'data_page' => 'news',
            'ldjson' => '<script type="application/ld+json">' . json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'NewsArticle',
                    'headline' => $post['title'],
                    'description' => $post['excerpt'] ?? mb_strimwidth(strip_tags($post['content'] ?? ''), 0, 160),
                    'datePublished' => $post['published_at'] ?? $post['created_at'] ?? '',
                    'inLanguage' => 'id',
                    'author' => ['@type' => 'Person', 'name' => $post['author'] ?? 'Admin'],
                    'publisher' => ['@type' => 'Organization', 'name' => $settings['site_name'] ?? ''],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . '</script>',
            'post' => $post,
            'comments' => $comments,
            'related' => $related,
            'prev_post' => $prevPost,
            'next_post' => $nextPost,
            'categories' => $newsModel->getPublishedCategories(),
            'recent_news' => $newsModel->getRecent(),
            'tags' => $newsModel->getAllTags(),
            'archive' => $newsModel->getArchive(),
            'search' => '',
        ];

        return view(theme_view('pages/single_post'), $data);
    }

    /**
     * Search news articles via AJAX.
     *
     * @return ResponseInterface JSON list of matching articles.
     */
    public function search(): ResponseInterface
    {
        $query = $this->queryParam('q');

        $this->response->setHeader('X-Content-Type-Options', 'nosniff');

        if ($query === null) {
            return $this->response->setJSON([]);
        }

        $results = new NewsModel()->searchByQuery($query);

        return $this->response->setJSON($results);
    }

    /**
     * Submit a comment on a news article.
     *
     * Applies a honeypot field, a per-session throttle, and a per-IP throttle,
     * validates the input, normalizes the optional parent comment, and stores
     * the comment as unapproved.
     *
     * @param string $slug Article slug.
     *
     * @return RedirectResponse Redirect back to the article with a status message.
     * @throws ReflectionException
     */
    public function comment(string $slug): RedirectResponse
    {
        if (!empty($this->request->getPost('website'))) {
            return redirect()->back()->with('success', 'Komentar berhasil dikirim dan menunggu persetujuan.');
        }

        $last = session()->get('comment_last_sent');
        if ($last && (time() - $last) < 30) {
            $wait = 30 - (time() - $last);
            return redirect()->back()->with('error', 'Tunggu ' . $wait . ' detik sebelum mengirim komentar lagi.');
        }

        $newsModel = new NewsModel();
        // Published articles only — draft articles also return 404 on the detail
        // page, so they must not accept comments.
        $post = $newsModel->getBySlug($slug);

        if (!$post) {
            throw PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'message' => 'required|min_length[10]|max_length[2000]',
        ];

        // Custom Indonesian messages (the application default locale is en).
        $messages = [
            'name' => [
                'required' => 'Nama wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'max_length' => 'Email maksimal 100 karakter.',
            ],
            'message' => [
                'required' => 'Komentar wajib diisi.',
                'min_length' => 'Komentar minimal 10 karakter.',
                'max_length' => 'Komentar maksimal 2000 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Per-IP limit — cannot be bypassed by clearing cookies/session.
        // Only submissions that pass validation consume the quota, so a mistyped
        // form does not use up the allowance. Note: `$proxyIPs` in
        // app/Config/App.php is empty, so behind a proxy/CDN every visitor
        // appears as the same IP.
        $throttler = service('throttler');
        $ip = $this->request->getIPAddress();
        $ipKey = 'comment-ip-' . md5($ip);

        if ($throttler->check($ipKey, 5, 600) === false) {
            $wait = $throttler->getTokenTime();
            return redirect()->back()->with('error', 'Terlalu banyak komentar dari jaringan ini. Coba lagi dalam ' . $wait . ' detik.');
        }

        // parent_id is only accepted when the comment exists, is already approved,
        // and belongs to the same article — this prevents a foreign key error
        // (HTTP 500) as well as cross-article replies.
        $parentId = (int)($this->request->getPost('parent_id') ?: 0);
        $parent = $parentId > 0
            ? new NewsCommentModel()->where('id', $parentId)->where('news_id', $post['id'])->where('is_approved', 1)->first()
            : null;

        $commentModel = new NewsCommentModel();
        $commentModel->insert([
            'news_id' => $post['id'],
            'parent_id' => $parent ? $parentId : null,
            'name' => strip_tags($this->request->getPost('name')),
            'email' => strip_tags($this->request->getPost('email')),
            'comment' => strip_tags($this->request->getPost('message')),
            'is_approved' => 0,
        ]);

        session()->set('comment_last_sent', time());

        return redirect()->back()->with('success', 'Komentar berhasil dikirim dan menunggu persetujuan.');
    }

    /**
     * Read a query string value as a trimmed string (or null when absent).
     *
     * Array input (`?search[]=...`) is discarded instead of being cast to the
     * string "Array", which would trigger a TypeError in the models.
     */
    private function queryParam(string $name): ?string
    {
        $value = $this->request->getGet($name);

        if (!is_scalar($value)) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
