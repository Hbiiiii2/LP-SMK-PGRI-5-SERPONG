<?php

namespace App\Models;

use CodeIgniter\Model;

class ExtracurricularModel extends Model
{
    protected $table = 'extracurriculars';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['title', 'description', 'icon', 'icon_color', 'sort_order', 'show'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
    ];

    protected $validationMessages = [
        'title' => ['required' => 'Judul ekstrakurikuler wajib diisi.'],
    ];

    /**
     * Retrieve extracurriculars ordered by sort order.
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
     * Batch update sort order for extracurricular items.
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
