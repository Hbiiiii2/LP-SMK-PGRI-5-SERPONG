<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $table = 'teachers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'photo', 'role', 'experience', 'education', 'social_facebook', 'social_instagram', 'social_linkedin', 'sort_order', 'show'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'role' => 'permit_empty|max_length[100]',
    ];

    protected $validationMessages = [
        'name' => ['required' => 'Nama guru wajib diisi.'],
    ];

    /**
     * Retrieve teachers ordered by sort order.
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
     * Batch update sort order for teacher items.
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
