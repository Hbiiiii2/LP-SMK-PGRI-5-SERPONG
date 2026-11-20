<?php

namespace App\Models;

use CodeIgniter\Model;

class FaqModel extends Model
{
    protected $table = 'faq';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['question', 'answer', 'category', 'sort_order', 'show'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'question' => 'required|min_length[5]|max_length[255]',
        'answer' => 'required|min_length[5]',
    ];

    protected $validationMessages = [
        'question' => [
            'required' => 'Pertanyaan wajib diisi.',
            'min_length' => 'Pertanyaan minimal 5 karakter.',
            'max_length' => 'Pertanyaan maksimal 255 karakter.',
        ],
        'answer' => [
            'required' => 'Jawaban wajib diisi.',
            'min_length' => 'Jawaban minimal 5 karakter.',
        ],
    ];

    /**
     * Retrieve unique FAQ category names present in the data.
     *
     * @return array<int, string>
     */
    public function getCategories(): array
    {
        $rows = $this->select('category')->findAll();

        $names = [];
        foreach ($rows as $row) {
            $name = trim((string)($row['category'] ?? ''));
            if ($name !== '') {
                $names[$name] = true;
            }
        }

        $categories = array_keys($names);
        natcasesort($categories);

        return array_values($categories);
    }

    /**
     * Retrieve visible FAQ items ordered by sort order.
     *
     * @param int $limit Maximum number of results.
     *
     * @return array
     */
    public function getVisible(int $limit = 50): array
    {
        return $this->where('show', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll($limit);
    }

    /**
     * Batch update sort order for FAQ items.
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
