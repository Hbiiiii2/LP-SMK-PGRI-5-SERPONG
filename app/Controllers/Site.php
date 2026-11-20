<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\ProgramModel;
use App\Models\ExtracurricularModel;
use App\Models\TeacherModel;
use App\Models\AchievementModel;
use App\Models\TestimonialModel;
use App\Models\EventModel;
use App\Models\GalleryModel;
use App\Models\FaqModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Site extends BaseController
{
    /**
     * Load shared settings for public pages.
     *
     *
     * @return array
     */
    private function baseSettings(): array
    {
        $extraKeys = [];
        $keys = array_unique(array_merge([
            'site_name', 'site_tagline', 'site_description',
            'site_logo_text', 'site_logo_icon',
            'contact_phone', 'contact_email', 'contact_address', 'contact_hours',
            'social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok',
            'footer_description', 'footer_copyright', 'footer_services', 'footer_links',
            'hero_badge', 'hero_title', 'hero_subtitle',
            'hero_btn_primary_text', 'hero_btn_primary_url',
            'hero_btn_secondary_text', 'hero_btn_secondary_url',
            'counter_stats', 'hero_stats', 'principal', 'about',
        ], $extraKeys));

        $settings = new SettingModel()->getMany($keys);

        $siteName = $settings['site_name'] ?? 'SekolahKu';

        return [
            'site_name' => $siteName,
            'site_tagline' => $settings['site_tagline'] ?? '',
            'site_description' => $settings['site_description'] ?? '',
            'site_logo_text' => $settings['site_logo_text'] ?? $siteName,
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
            'hero_badge' => $settings['hero_badge'] ?? '',
            'hero_title' => $settings['hero_title'] ?? '',
            'hero_subtitle' => $settings['hero_subtitle'] ?? '',
            'hero_btn_primary_text' => $settings['hero_btn_primary_text'] ?? '',
            'hero_btn_primary_url' => $settings['hero_btn_primary_url'] ?? '',
            'hero_btn_secondary_text' => $settings['hero_btn_secondary_text'] ?? '',
            'hero_btn_secondary_url' => $settings['hero_btn_secondary_url'] ?? '',
            'counter_stats' => json_decode($settings['counter_stats'] ?? '[]', true) ?: [],
            'hero_stats' => json_decode($settings['hero_stats'] ?? '[]', true) ?: [],
            'principal' => json_decode($settings['principal'] ?? '{}', true) ?: [],
            'about' => json_decode($settings['about'] ?? '{}', true) ?: [],
            'og_image' => theme_og(),
        ];
    }

    /**
     * Display the school profile page.
     *
     * @return string
     */
    public function profile(): string
    {
        $data = $this->baseSettings();
        $data['page_title'] = 'Profil — ' . $data['site_name'];
        $data['meta_description'] = 'Sejarah, visi-misi, dan budaya ' . $data['site_name'] . '. ' . ($data['site_description'] ?: $data['site_tagline']);
        $data['data_page'] = 'profile';
        if (!empty($data['about']['image'])) {
            $data['og_image'] = $data['about']['image'];
        }

        return view(theme_view('pages/profile'), $data);
    }

    /**
     * Display the program page.
     *
     * @return string
     */
    public function program(): string
    {
        $data = $this->baseSettings();
        $data['page_title'] = 'Program Unggulan — ' . $data['site_name'];
        $data['meta_description'] = 'Program unggulan, kompetensi keahlian, dan fokus materi belajar di ' . $data['site_name'];
        $data['data_page'] = 'program';
        $data['programs'] = new ProgramModel()->getOrdered();
        $data['active_program_slug'] = trim((string)$this->request->getGet('fokus'));

        return view(theme_view('pages/program'), $data);
    }

    /**
     * Display focused detail page for a single program / major.
     *
     * @param string $slugOrId
     *
     * @return string
     */
    public function programDetail(string $slugOrId): string
    {
        $programModel = new ProgramModel();
        $program = $programModel->findBySlugOrId($slugOrId);

        if (!$program) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = $this->baseSettings();
        $desc = trim(strip_tags((string)($program['description'] ?? '')));
        $data['program'] = $program;
        $data['programs'] = $programModel->getOrdered();
        $data['page_title'] = $program['title'] . ' — Program Keahlian — ' . $data['site_name'];
        $data['meta_description'] = $desc !== ''
            ? $desc
            : 'Informasi fokus materi belajar dan prospek lulusan ' . $program['title'] . ' di ' . $data['site_name'];
        $data['data_page'] = 'program-detail';

        helper('theme');
        if (function_exists('program_image_url')) {
            $progImg = program_image_url($program);
            if ($progImg !== '') {
                $data['og_image'] = $progImg;
            }
        }

        $detailView = APPPATH . 'Views/themes/' . active_theme() . '/pages/program_detail.php';
        if (is_file($detailView)) {
            return view(theme_view('pages/program_detail'), $data);
        }

        $data['active_program_slug'] = (string)($program['slug'] ?? $program['id']);
        return view(theme_view('pages/program'), $data);
    }

    /**
     * Display the teacher's page.
     *
     * @return string
     */
    public function teachers(): string
    {
        $data = $this->baseSettings();
        $data['page_title'] = 'Pengajar — ' . $data['site_name'];
        $data['meta_description'] = 'Guru dan tenaga kependidikan ' . $data['site_name'];
        $data['data_page'] = 'teachers';
        $data['teachers'] = new TeacherModel()->getOrdered(100);

        return view(theme_view('pages/teachers'), $data);
    }

    /**
     * Display the achievement page.
     *
     * @return string
     */
    public function achievements(): string
    {
        $data = $this->baseSettings();
        $data['page_title'] = 'Prestasi — ' . $data['site_name'];
        $data['meta_description'] = 'Prestasi siswa ' . $data['site_name'];
        $data['data_page'] = 'achievements';
        $data['achievements'] = new AchievementModel()->getOrdered(100);

        return view(theme_view('pages/achievements'), $data);
    }

    /**
     * Display the extracurricular page.
     *
     * @return string
     */
    public function extracurriculars(): string
    {
        $data = $this->baseSettings();
        $data['page_title'] = 'Ekstrakurikuler — ' . $data['site_name'];
        $data['meta_description'] = 'Kegiatan ekstrakurikuler di ' . $data['site_name'];
        $data['data_page'] = 'extracurriculars';
        $data['extracurriculars'] = new ExtracurricularModel()->getOrdered();

        return view(theme_view('pages/extracurriculars'), $data);
    }

    /**
     * Display the testimonial page.
     *
     * @return string
     */
    public function testimonials(): string
    {
        $data = $this->baseSettings();
        $data['page_title'] = 'Testimoni — ' . $data['site_name'];
        $data['meta_description'] = 'Kata alumni, orang tua, dan siswa tentang ' . $data['site_name'];
        $data['data_page'] = 'testimonials';
        $data['testimonials'] = new TestimonialModel()->getOrdered();

        return view(theme_view('pages/testimonials'), $data);
    }

    /**
     * Display the gallery page.
     *
     * @return string
     */
    public function gallery(): string
    {
        $data = $this->baseSettings();
        $gallery = new GalleryModel();
        $category = trim((string)$this->request->getGet('category'));

        $data['page_title'] = 'Galeri — ' . $data['site_name'];
        $data['meta_description'] = 'Galeri foto kegiatan ' . $data['site_name'];
        $data['data_page'] = 'gallery';
        $data['galleries'] = $gallery->getVisible(100, $category !== '' ? $category : null);
        $data['categories'] = $gallery->getCategories(true);
        $data['category'] = $category !== '' ? $category : null;

        return view(theme_view('pages/gallery'), $data);
    }

    /**
     * Display the agenda page.
     *
     * @return string
     */
    public function agenda(): string
    {
        $data = $this->baseSettings();
        $data['page_title'] = 'Agenda — ' . $data['site_name'];
        $data['meta_description'] = 'Kalender agenda dan kegiatan ' . $data['site_name'];
        $data['data_page'] = 'agenda';
        $data['events'] = new EventModel()->getUpcoming(50);

        return view(theme_view('pages/agenda'), $data);
    }

    /**
     * Display the FAQ page.
     *
     * @return string
     */
    public function faq(): string
    {
        $data = $this->baseSettings();
        $data['page_title'] = 'FAQ — ' . $data['site_name'];
        $data['meta_description'] = 'Pertanyaan yang sering ditanyakan seputar ' . $data['site_name'];
        $data['data_page'] = 'faq';
        $data['faq'] = new FaqModel()->getVisible(100);

        return view(theme_view('pages/faq'), $data);
    }
}
