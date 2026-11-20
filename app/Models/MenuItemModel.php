<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class MenuItemModel extends Model
{
    protected $table = 'menu_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['parent_id', 'title', 'url', 'target', 'icon', 'section_key', 'sort_order', 'status', 'type', 'page_id'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'title' => 'required|min_length[2]|max_length[100]',
        'url' => 'required|max_length[255]',
        'target' => 'required|in_list[_self,_blank]',
    ];

    protected $validationMessages = [
        'title' => ['required' => 'Judul menu wajib diisi.'],
        'url' => ['required' => 'URL menu wajib diisi.'],
        'target' => [
            'required' => 'Target menu wajib dipilih.',
            'in_list' => 'Target menu tidak valid (hanya Same Tab atau New Tab).',
        ],
    ];

    /**
     * Build a nested tree structure from a flat array of items.
     *
     * @param array $items Flat array of menu items.
     * @param int|null $parentId The parent ID to filter by.
     *
     * @return array
     */
    public function buildTree(array $items, ?int $parentId = null): array
    {
        $branch = [];
        foreach ($items as $item) {
            if ($item['parent_id'] === $parentId) {
                $children = $this->buildTree($items, $item['id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $branch[] = $item;
            }
        }
        return $branch;
    }

    /**
     * Retrieve all menu items as a flattened tree with indentation metadata.
     *
     * @return array
     */
    public function getFlatTree(): array
    {
        $items = $this->orderBy('sort_order', 'ASC')->findAll();
        $tree = $this->buildTree($items);
        $flat = [];
        $this->flattenTree($tree, $flat);
        return $flat;
    }

    /**
     * Recursively flatten a tree structure into a single-dimensional array.
     *
     * @param array $tree The tree structure to flatten.
     * @param array &$flat The output flat array (passed by reference).
     *
     * @return void
     */
    private function flattenTree(array $tree, array &$flat): void
    {
        foreach ($tree as $item) {
            $children = $item['children'] ?? [];
            unset($item['children']);
            $flat[] = $item;
            if (!empty($children)) {
                $this->flattenTree($children, $flat);
            }
        }
    }

    /**
     * Retrieve all descendant IDs of a given menu item.
     *
     * Traversal is iterative (not recursive) and keeps a `visited` list so that
     * already-circular data (a cycle) cannot make the process run forever —
     * the previous unguarded recursion could exhaust server memory.
     *
     * @param int $id The parent menu item ID.
     *
     * @return array
     */
    public function getDescendantIds($id): array
    {
        $start = (int)$id;
        $ids = [];
        $visited = [$start => true];
        $queue = [$start];

        while ($queue !== []) {
            $current = array_shift($queue);
            $children = $this->where('parent_id', $current)->findAll();

            foreach ($children as $child) {
                $childId = (int)$child['id'];

                if (isset($visited[$childId])) {
                    continue;
                }

                $visited[$childId] = true;
                $ids[] = $childId;
                $queue[] = $childId;
            }
        }

        return $ids;
    }

    /**
     * Detect whether a proposed parent map contains a cycle.
     *
     * Used by `Admin\Menus::reorder()` to check the **proposed** menu arrangement
     * as a whole: the old validation only looked at the database state, so a
     * payload containing several items (A→B, B→C, C→A) could pass and store a
     * circular structure.
     *
     * @param array<int, int|null> $parentMap Map of `id => parent_id` (null = top-level menu).
     *
     * @return bool
     */
    public function hasCycle(array $parentMap): bool
    {
        foreach (array_keys($parentMap) as $start) {
            $seen = [];
            $current = $start;

            while ($current !== null) {
                if (isset($seen[$current])) {
                    return true;
                }

                $seen[$current] = true;
                $current = $parentMap[$current] ?? null;
            }
        }

        return false;
    }

    /**
     * Retrieve root-level menu items (no parent).
     *
     * @return array
     */
    public function getRootItems(): array
    {
        return $this->where('parent_id', null)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }

    /**
     * Retrieve root-level menu items excluding specified IDs.
     *
     * @param array $excludeIds Array of IDs to exclude.
     *
     * @return array
     */
    public function getAvailableParents(array $excludeIds): array
    {
        return $this->where('parent_id', null)
            ->whereNotIn('id', $excludeIds)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }

    /**
     * Retrieve a menu item by its associated page ID.
     *
     * @param int $pageId The page ID.
     *
     * @return array|null
     */
    public function getByPageId(int $pageId): ?array
    {
        return $this->where('page_id', $pageId)->first();
    }

    /**
     * Count the number of child menu items for a given parent.
     *
     * @param int $parentId The parent menu item ID.
     *
     * @return int
     */
    public function countChildren(int $parentId): int
    {
        return $this->where('parent_id', $parentId)
            ->countAllResults();
    }

    /**
     * Batch reorder menu items with parent and sort order updates inside a transaction.
     *
     * @param array $items Array of items with id, parent_id, and sort_order.
     *
     * @return bool
     */
    public function reorderItems(array $items): bool
    {
        $db = Database::connect();
        $db->transBegin();

        foreach ($items as $item) {
            $id = (int)$item['id'];
            $parentId = !empty($item['parent_id']) ? (int)$item['parent_id'] : null;
            $sortOrder = (int)$item['sort_order'];

            $db->table($this->table)
                ->where('id', $id)
                ->update([
                    'parent_id' => $parentId,
                    'sort_order' => $sortOrder,
                ]);
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            return false;
        }

        $db->transCommit();
        return true;
    }
}
