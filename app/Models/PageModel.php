<?php

namespace App\Models;

use CodeIgniter\Model;

class PageModel extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['title', 'slug', 'content', 'excerpt', 'image', 'status', 'meta_title', 'meta_description'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|min_length[3]|max_length[255]',
        'content' => 'required|min_length[10]',
        'image' => 'permit_empty|max_length[255]',
        'status' => 'required|in_list[draft,published]',
        'meta_title' => 'permit_empty|max_length[255]',
        'meta_description' => 'permit_empty|max_length[255]',
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Judul halaman wajib diisi.',
            'min_length' => 'Judul halaman minimal 3 karakter.',
            'max_length' => 'Judul halaman maksimal 255 karakter.',
        ],
        'slug' => [
            'required' => 'Slug wajib diisi.',
            'min_length' => 'Slug minimal 3 karakter.',
            'max_length' => 'Slug maksimal 255 karakter.',
        ],
        'content' => [
            'required' => 'Konten halaman wajib diisi.',
            'min_length' => 'Konten halaman minimal 10 karakter.',
        ],
        'image' => ['max_length' => 'URL gambar maksimal 255 karakter.'],
        'status' => ['in_list' => 'Status tidak valid.'],
        'meta_title' => ['max_length' => 'Meta title maksimal 255 karakter.'],
        'meta_description' => ['max_length' => 'Meta description maksimal 255 karakter.'],
    ];

    /**
     * Slugs already used by the application's built-in routes. Static pages must
     * not use them because explicit routes always win over the catch-all, so
     * such a page could never be accessed.
     *
     * @var string[]
     */
    protected array $reservedSlugs = [
        'news', 'downloads', 'contact', 'profile', 'program', 'teachers',
        'achievements', 'extracurriculars', 'testimonials', 'gallery', 'agenda',
        'faq', 'login', 'logout', 'admin', 'sitemap',
    ];

    /**
     * Generate a unique slug, appending -1, -2, ... on collision.
     */
    public function makeUniqueSlug(string $baseSlug, ?int $exceptId = null): string
    {
        $slug = $baseSlug !== '' ? $baseSlug : 'halaman';
        $candidate = $slug;
        $i = 1;
        while (true) {
            if (in_array($candidate, $this->reservedSlugs, true)) {
                $candidate = $slug . '-' . (++$i);
                continue;
            }

            $builder = $this->where('slug', $candidate);
            if ($exceptId) {
                $builder->where('id !=', $exceptId);
            }
            if (!$builder->first()) {
                return $candidate;
            }
            $candidate = $slug . '-' . (++$i);
        }
    }

    /**
     * Retrieve a single published page by its slug.
     *
     * @param string $slug The page slug.
     *
     * @return array|null
     */
    public function getBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)
            ->where('status', 'published')
            ->first();
    }

    /**
     * Retrieve all published pages ordered by creation date descending.
     *
     * @return array
     */
    public function getPublishedAll(): array
    {
        return $this->where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
