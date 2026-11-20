<?php

use App\Models\MenuItemModel;
use App\Models\PageModel;
use App\Models\SettingModel;

if (!function_exists('resolve_menu_url')) {
    function resolve_menu_url(string $url): string
    {
        if (preg_match('/^\{(\w+)\}$/', $url, $m)) {
            $setting = new SettingModel()->get($m[1]);
            return $setting ?: '#';
        }
        return $url;
    }
}

if (!function_exists('is_dangerous_url')) {
    /**
     * Detect URL schemes that can execute code (javascript:, data:, vbscript:).
     *
     * Shared by Admin\BaseController::isDangerousUrl() and frontend menu rendering.
     * The frontend check is still required because menu URLs may contain a
     * {key} placeholder — the actual setting value is only known at render time,
     * long after the save-time validation has run.
     *
     * @param string|null $url
     *
     * @return bool
     */
    function is_dangerous_url(?string $url): bool
    {
        if ($url === null || $url === '') {
            return false;
        }

        return (bool)preg_match('#^(javascript|data|vbscript)\s*:#i', ltrim($url));
    }
}

if (!function_exists('is_safe_link_url')) {
    /**
     * Validate link URLs entered by admins (social media, hero buttons, SPMB,
     * footer links). Besides dangerous schemes, it also rejects any unknown
     * scheme using an allowlist: only relative paths, anchors (#...), or the
     * http/https/mailto/tel schemes are accepted.
     *
     * @param string|null $url
     *
     * @return bool true when the URL may be stored/displayed.
     */
    function is_safe_link_url(?string $url): bool
    {
        $url = trim((string)$url);
        if ($url === '') {
            return true;
        }

        // Anchors (#...) and paths (absolute/relative) have no scheme.
        if (str_starts_with($url, '#') || str_starts_with($url, '/')) {
            return true;
        }

        if (preg_match('#^([a-z][a-z0-9+.\-]*):#i', $url, $m)) {
            return in_array(strtolower($m[1]), ['http', 'https', 'mailto', 'tel'], true);
        }

        // Relative path without a scheme, e.g. "program" or "#spmb".
        return true;
    }
}

if (!function_exists('get_extra_nav_items')) {
    /**
     * Extra navigation items from the database (type page/custom, active,
     * top-level) that are NOT among the theme's 13 built-in pages in v3.2.0.
     * Theme headers insert them after the static menu, so Admin → Menus still
     *  affect the frontend navigation.
     *
     * Child items (dropdowns) are not rendered — the themes have no dropdown CSS.
     * Items whose URL uses a dangerous scheme (e.g. `javascript:`) are skipped too.
     *
     * @return array<int, array{title: string, url: string, target: string, active: bool}>
     */
    function get_extra_nav_items(): array
    {
        $items = new MenuItemModel()
            ->where('status', 1)
            ->where('parent_id', null)
            ->whereNotIn('type', ['system'])
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        if ($items === []) {
            return [];
        }

        // Hide page-type items when the related page is not published
        // or has been deleted.
        $pageIds = array_filter(array_column($items, 'page_id'));
        $publishedIds = [];
        if ($pageIds !== []) {
            $publishedIds = array_map('intval', array_column(
                new PageModel()
                    ->whereIn('id', $pageIds)
                    ->where('status', 'published')
                    ->findAll(),
                'id',
            ));
        }

        // Mapping of legacy section_key (anchor) -> v3.2.0 page URL.
        // Only used as a fallback when the item's URL column is empty.
        $sectionUrls = [
            'profile' => 'profile',
            'programs' => 'program',
            'extracurriculars' => 'extracurriculars',
            'teachers' => 'teachers',
            'achievements' => 'achievements',
            'testimonials' => 'testimonials',
            'news' => 'news',
            'events' => 'agenda',
            'gallery' => 'gallery',
            'faq' => 'faq',
            'downloads' => 'downloads',
            'contact' => 'contact',
        ];

        // The theme's 13 built-in pages — do not display them twice.
        $builtin = array_merge(['', 'home'], array_values($sectionUrls));

        $current = ltrim(service('uri')->getPath(), '/');
        $out = [];
        foreach ($items as $item) {
            if (!empty($item['page_id'])
                && !in_array((int)$item['page_id'], $publishedIds, true)) {
                continue;
            }

            // The URL comes from the `url` column (required); `section_key` is only
            // a fallback for legacy menu data that has no URL yet.
            $url = trim(resolve_menu_url((string)($item['url'] ?? '')), '/');
            if ($url === '' && !empty($item['section_key'])) {
                $url = $sectionUrls[$item['section_key']] ?? trim($item['section_key'], '#/');
            }
            if ($url === '' || in_array($url, $builtin, true) || is_dangerous_url($url)) {
                continue;
            }

            // External URLs are used as-is; otherwise prepend the baseURL.
            $href = preg_match('#^[a-z][a-z0-9+.-]*:#i', $url) || str_starts_with($url, '//')
                ? $url
                : base_url($url);

            $out[] = [
                'title' => (string)$item['title'],
                'url' => $href,
                'target' => ($item['target'] ?? '_self') === '_blank' ? '_blank' : '_self',
                'active' => $current !== ''
                    && ($current === $url || str_starts_with($current, $url . '/')),
            ];
        }

        return $out;
    }
}
