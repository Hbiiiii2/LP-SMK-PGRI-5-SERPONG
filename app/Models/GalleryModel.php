<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryModel extends Model
{
    protected $table = 'galleries';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['category', 'image', 'caption', 'is_featured', 'sort_order', 'show'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    protected $validationRules = [
        'caption' => 'required|min_length[3]|max_length[255]',
        'category' => 'permit_empty|max_length[100]',
    ];

    protected $validationMessages = [
        'caption' => ['required' => 'Keterangan galeri wajib diisi.'],
    ];

    /**
     * Retrieve gallery categories with their item counts, sorted by name.
     *
     * @param bool $onlyVisible Only count categories with visible items.
     *
     * @return array<int, array{name: string, count: int}>
     */
    public function getCategories(bool $onlyVisible = false): array
    {
        if ($onlyVisible) {
            $this->where('show', 1);
        }

        $rows = $this->select('category')->findAll();

        $counts = [];
        foreach ($rows as $row) {
            $name = trim((string)($row['category'] ?? ''));
            if ($name === '') {
                continue;
            }
            $counts[$name] = ($counts[$name] ?? 0) + 1;
        }

        ksort($counts, SORT_NATURAL | SORT_FLAG_CASE);

        $categories = [];
        foreach ($counts as $name => $count) {
            $categories[] = ['name' => $name, 'count' => $count];
        }

        return $categories;
    }

    /**
     * Retrieve visible gallery items.
     *
     * Featured images (is_featured) always appear first; within each group the
     * order still follows sort_order.
     *
     * @param int $limit Maximum number of results.
     * @param string|null $category Optional category filter.
     *
     * @return array
     */
    public function getVisible(int $limit = 50, ?string $category = null): array
    {
        $this->where('show', 1)
            ->orderBy('is_featured', 'DESC')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC');

        if ($category !== null && $category !== '') {
            $this->where('category', $category);
        }

        return $this->findAll($limit);
    }

    /**
     * Retrieve gallery data for XML sitemap generation.
     *
     * @return array
     */
    public function getForSitemap(): array
    {
        return $this->select('updated_at')
            ->where('show', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }

    /**
     * Batch update sort order for gallery items.
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
}
