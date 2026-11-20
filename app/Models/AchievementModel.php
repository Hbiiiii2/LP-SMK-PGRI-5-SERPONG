<?php

namespace App\Models;

use CodeIgniter\Model;

class AchievementModel extends Model
{
    protected $table = 'achievements';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['student_name', 'photo', 'class_name', 'achievement', 'level', 'year', 'medal', 'sort_order', 'show'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'student_name' => 'required|min_length[3]|max_length[100]',
        'achievement' => 'required|min_length[3]|max_length[255]',
    ];

    protected $validationMessages = [
        'student_name' => ['required' => 'Nama siswa wajib diisi.'],
        'achievement' => ['required' => 'Prestasi wajib diisi.'],
    ];

    /**
     * Retrieve achievements ordered by sort order.
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
     * Batch update sort order for achievement items.
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
