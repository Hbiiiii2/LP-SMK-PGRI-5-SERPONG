<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['title', 'slug', 'description', 'location', 'event_date', 'event_time', 'sort_order', 'status', 'show'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|min_length[3]|max_length[255]',
        'event_date' => 'required|valid_date',
        'status' => 'required|in_list[upcoming,ongoing,completed,cancelled]',
    ];

    protected $validationMessages = [
        'title' => ['required' => 'Judul acara wajib diisi.'],
        'slug' => ['required' => 'Slug wajib diisi.'],
        'event_date' => [
            'required' => 'Tanggal acara wajib diisi.',
            'valid_date' => 'Tanggal acara tidak valid.',
        ],
        'status' => ['in_list' => 'Status acara tidak valid.'],
    ];

    /**
     * Generate a unique slug, appending -1, -2, ... on collision.
     */
    public function makeUniqueSlug(string $baseSlug, ?int $exceptId = null): string
    {
        $slug = $baseSlug !== '' ? $baseSlug : 'acara';
        $candidate = $slug;
        $i = 1;
        while (true) {
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
     * Retrieve upcoming or ongoing events.
     *
     * Filters by date only (not the `status` column, which is only computed
     * on save), so past events do not keep appearing as "upcoming".
     * Order: date, then `sort_order` (for events on the same day).
     *
     * @param int $limit Maximum number of results.
     *
     * @return array
     */
    public function getUpcoming(int $limit = 4): array
    {
        return $this->where('show', 1)
            ->where('event_date >=', date('Y-m-d'))
            ->groupStart()
            ->where('status !=', 'cancelled')
            ->orWhere('status IS NULL', null, false)
            ->groupEnd()
            ->orderBy('event_date', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->findAll($limit);
    }

    /**
     * Retrieve event data for XML sitemap generation.
     *
     * @return array
     */
    public function getForSitemap(): array
    {
        return $this->where('show', 1)
            ->select('slug, updated_at, event_date')
            ->findAll();
    }

    /**
     * Derive the status purely from the event date (realtime):
     * past date = "completed", today = "ongoing", future = "upcoming".
     * The stored status column is kept in sync on save but never trusted
     * for display. Empty date falls back to the stored value.
     * An explicitly "canceled" event is always preserved.
     */
    public function normalizeStatus(?string $status, $eventDate): string
    {
        if (($status ?? '') === 'cancelled') {
            return 'cancelled';
        }
        $date = substr((string)($eventDate ?? ''), 0, 10);
        $today = date('Y-m-d');
        if ($date === '' || $date === '0000-00-00') {
            return $status ?? 'upcoming';
        }
        if ($date < $today) {
            return 'completed';
        }
        if ($date === $today) {
            return 'ongoing';
        }
        return 'upcoming';
    }

    /**
     * Effective display status for one event row (same rule as normalizeStatus).
     */
    public function effectiveStatus(array $item): string
    {
        return $this->normalizeStatus($item['status'] ?? null, $item['event_date'] ?? null);
    }
}
