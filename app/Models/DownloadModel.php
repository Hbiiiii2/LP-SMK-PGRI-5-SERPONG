<?php

namespace App\Models;

use CodeIgniter\Model;

class DownloadModel extends Model
{
    protected $table = 'downloads';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['category', 'title', 'description', 'url', 'file_size', 'sort_order', 'show'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'url' => 'required|max_length[500]',
    ];

    protected $validationMessages = [
        'title' => ['required' => 'Judul download wajib diisi.'],
        'url' => ['required' => 'URL/file download wajib diisi.'],
    ];

    /**
     * Retrieve visible download items ordered by sort order.
     *
     * @return array
     */
    public function getVisible(): array
    {
        return $this->where('show', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }

    /**
     * Batch update sort order for download items.
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
