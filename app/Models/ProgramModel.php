<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table = 'programs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'title',
        'slug',
        'description',
        'content',
        'learning_points',
        'career_prospects',
        'icon',
        'media_type',
        'image',
        'link_url',
        'link_text',
        'sort_order',
        'show',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'title'       => 'required|min_length[3]|max_length[255]',
        'slug'        => 'permit_empty|max_length[220]',
        'description' => 'permit_empty|max_length[5000]',
        'media_type'  => 'permit_empty|in_list[icon,image]',
    ];

    protected $validationMessages = [
        'title' => ['required' => 'Judul program wajib diisi.'],
    ];

    /**
     * Retrieve programs ordered by sort order.
     *
     * @param int $limit Maximum number of results.
     *
     * @return array
     */
    public function getOrdered(int $limit = 50): array
    {
        return $this->where('show', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll($limit);
    }

    /**
     * Batch update sort order for program items.
     *
     * @param array $ids Ordered array of item IDs.
     * @param int $offset Initial sort_order value (admin page offset).
     *
     * @return bool
     */
    public function reorder(array $ids, int $offset = 0): bool
    {
        $case = '';
        foreach ($ids as $i => $id) {
            $id = (int)$id;
            $sort = $offset + $i;
            $case .= "WHEN id = {$id} THEN {$sort} ";
        }
        $idsList = implode(',', array_map('intval', $ids));
        $this->db->query("UPDATE {$this->table} SET sort_order = CASE {$case} END WHERE id IN ({$idsList})");
        return true;
    }

    /**
     * Generate a unique URL-safe slug for a program title.
     */
    public function generateSlug(string $title, ?int $excludeId = null): string
    {
        $base = strtolower(trim((string) preg_replace('/[^a-z0-9]+/i', '-', $title), '-'));
        if ($base === '') {
            $base = 'program';
        }

        $candidate = $base;
        $suffix = 2;
        while (true) {
            $builder = $this->where('slug', $candidate);
            if ($excludeId !== null) {
                $builder->where('id !=', $excludeId);
            }
            if ($builder->countAllResults() === 0) {
                return $candidate;
            }
            $candidate = $base . '-' . $suffix++;
        }
    }

    /**
     * Find an active program by slug, numeric ID, or slugified title fallback.
     */
    public function findBySlugOrId(string $slugOrId): ?array
    {
        $needle = trim($slugOrId);
        if ($needle === '') {
            return null;
        }

        $row = $this->where('show', 1)->where('slug', $needle)->first();
        if ($row) {
            return $row;
        }

        if (ctype_digit($needle)) {
            $byId = $this->where('show', 1)->find((int) $needle);
            if ($byId) {
                return $byId;
            }
        }

        $rows = $this->getOrdered();
        $target = strtolower(trim((string) preg_replace('/[^a-z0-9]+/i', '-', $needle), '-'));
        foreach ($rows as $item) {
            $itemSlug = strtolower(trim((string) preg_replace('/[^a-z0-9]+/i', '-', (string) ($item['title'] ?? '')), '-'));
            if ($itemSlug !== '' && $itemSlug === $target) {
                return $item;
            }
        }

        return null;
    }
}
