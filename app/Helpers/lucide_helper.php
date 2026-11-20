<?php

/**
 * Lucide SVG icon helper and FontAwesome compatibility mapper.
 *
 * Uses a local static SVG sprite at public/assets/icons/lucide-sprite.svg so
 * both public website and admin CMS render crisp, uniform stroke icons without
 * mixing external icon font libraries.
 */

if (! function_exists('lucide_icon_map')) {
    /**
     * Authoritative FontAwesome -> Lucide icon symbol lookup table.
     *
     * @return array<string, string>
     */
    function lucide_icon_map(): array
    {
        return [
            'activity'                   => 'activity',
            'running'                    => 'activity',
            'alert-triangle'             => 'alert-triangle',
            'exclamation-triangle'       => 'alert-triangle',
            'triangle-exclamation'       => 'alert-triangle',
            'arrow-left'                 => 'arrow-left',
            'arrow-right'                => 'arrow-right',
            'arrow-up'                   => 'arrow-up',
            'arrow-up-right'             => 'arrow-up-right',
            'at'                         => 'at-sign',
            'at-sign'                    => 'at-sign',
            'award'                      => 'award',
            'medal'                      => 'award',
            'bar-chart-2'                => 'bar-chart-2',
            'chart-bar'                  => 'bar-chart-2',
            'chart-simple'               => 'bar-chart-2',
            'book'                       => 'book-open',
            'book-open'                  => 'book-open',
            'briefcase'                  => 'briefcase',
            'calculator'                 => 'calculator',
            'calendar'                   => 'calendar',
            'calendar-alt'               => 'calendar',
            'calendar-days'              => 'calendar',
            'calendar-plus'              => 'calendar',
            'check'                      => 'check',
            'check-circle'               => 'check-circle',
            'check-circle-2'             => 'check-circle',
            'circle-check'               => 'check-circle',
            'chevron-down'               => 'chevron-down',
            'chevron-left'               => 'chevron-left',
            'chevron-right'              => 'chevron-right',
            'circle'                     => 'circle',
            'clock'                      => 'clock',
            'compass'                    => 'compass',
            'copyright'                  => 'copyright',
            'corner-up-left'             => 'corner-up-left',
            'level-up-alt'               => 'corner-up-left',
            'cpu'                        => 'cpu',
            'microchip'                  => 'cpu',
            'download'                   => 'download',
            'file-arrow-down'            => 'download',
            'external-link'              => 'external-link',
            'external-link-alt'          => 'external-link',
            'arrow-up-right-from-square' => 'external-link',
            'eye'                        => 'eye',
            'eye-off'                    => 'eye-off',
            'eye-slash'                  => 'eye-off',
            'facebook'                   => 'facebook',
            'facebook-f'                 => 'facebook',
            'file'                       => 'file-text',
            'file-alt'                   => 'file-text',
            'file-text'                  => 'file-text',
            'flask'                      => 'flask-conical',
            'flask-conical'              => 'flask-conical',
            'folder'                     => 'folder',
            'folder-open'                => 'folder',
            'gauge'                      => 'gauge',
            'tachometer-alt'             => 'gauge',
            'globe'                      => 'globe',
            'graduation-cap'             => 'graduation-cap',
            'user-graduate'              => 'graduation-cap',
            'grip-vertical'              => 'grip-vertical',
            'handshake'                  => 'handshake',
            'hand-holding-heart'         => 'heart-handshake',
            'heart-handshake'            => 'heart-handshake',
            'help-circle'                => 'help-circle',
            'question'                   => 'help-circle',
            'question-circle'            => 'help-circle',
            'circle-question'            => 'help-circle',
            'home'                       => 'home',
            'house'                      => 'home',
            'image'                      => 'image',
            'images'                     => 'images',
            'info'                       => 'info',
            'info-circle'                => 'info',
            'circle-info'                => 'info',
            'instagram'                  => 'instagram',
            'key'                        => 'key',
            'laptop'                     => 'laptop',
            'laptop-code'                => 'laptop',
            'code'                       => 'laptop',
            'layers'                     => 'layers',
            'layer-group'                => 'layers',
            'leaf'                       => 'leaf',
            'link'                       => 'link-2',
            'link-2'                     => 'link-2',
            'linkedin'                   => 'linkedin',
            'linkedin-in'                => 'linkedin',
            'list-ol'                    => 'list-ordered',
            'list-ordered'               => 'list-ordered',
            'loader-2'                   => 'loader-2',
            'spinner'                    => 'loader-2',
            'lock'                       => 'lock',
            'log-out'                    => 'log-out',
            'sign-out-alt'               => 'log-out',
            'envelope'                   => 'mail',
            'envelope-open'              => 'mail',
            'mail'                       => 'mail',
            'location-dot'               => 'map-pin',
            'map-marker-alt'             => 'map-pin',
            'map-pin'                    => 'map-pin',
            'bars'                       => 'menu',
            'menu'                       => 'menu',
            'comment-dots'               => 'message-circle',
            'comments'                   => 'message-circle',
            'message-circle'             => 'message-circle',
            'music'                      => 'music',
            'network'                    => 'network',
            'sitemap'                    => 'network',
            'newspaper'                  => 'newspaper',
            'paint-brush'                => 'palette',
            'palette'                    => 'palette',
            'edit'                       => 'pencil',
            'pen'                        => 'pencil',
            'pencil'                     => 'pencil',
            'mobile-screen'              => 'phone',
            'phone'                      => 'phone',
            'plus'                       => 'plus',
            'quote'                      => 'quote',
            'quote-left'                 => 'quote',
            'quote-right'                => 'quote',
            'save'                       => 'save',
            'building-columns'           => 'school',
            'school'                     => 'school',
            'magnifying-glass'           => 'search',
            'search'                     => 'search',
            'paper-plane'                => 'send',
            'send'                       => 'send',
            'server'                     => 'cpu',
            'cog'                        => 'settings',
            'settings'                   => 'settings',
            'sliders'                    => 'settings',
            'sliders-h'                  => 'settings',
            'share-2'                    => 'share-2',
            'share-alt'                  => 'share-2',
            'shield-alt'                 => 'shield-check',
            'shield-check'               => 'shield-check',
            'shield-halved'              => 'shield-check',
            'icons'                      => 'sparkles',
            'sparkles'                   => 'sparkles',
            'star'                       => 'star',
            'tag'                        => 'tag',
            'bullseye'                   => 'target',
            'target'                     => 'target',
            'trash'                      => 'trash-2',
            'trash-2'                    => 'trash-2',
            'futbol'                     => 'trophy',
            'trophy'                     => 'trophy',
            'font'                       => 'type',
            'heading'                    => 'type',
            'type'                       => 'type',
            'cloud-upload-alt'           => 'upload',
            'upload'                     => 'upload',
            'user'                       => 'user',
            'user-circle'                => 'user',
            'user-check'                 => 'user-check',
            'user-tie'                   => 'user-check',
            'signature'                  => 'user-pen',
            'user-pen'                   => 'user-pen',
            'chalkboard-teacher'         => 'users',
            'chalkboard-user'            => 'users',
            'users'                      => 'users',
            'tiktok'                     => 'video',
            'video'                      => 'video',
            'wifi'                       => 'globe',
            'times'                      => 'x',
            'x'                          => 'x',
            'xmark'                      => 'x',
            'youtube'                    => 'youtube',
            'bolt'                       => 'zap',
            'zap'                        => 'zap',
        ];
    }
}

if (! function_exists('lucide_resolve_name')) {
    /**
     * Resolve a Lucide name or legacy FontAwesome class string into a sprite symbol ID.
     */
    function lucide_resolve_name(string $name): string
    {
        $clean = strtolower(trim($name));
        if ($clean === '') {
            return 'star';
        }

        // Strip modifier classes and prefixes so "fas fa-laptop-code fa-lg" -> "laptop-code"
        $tokens = preg_split('/\s+/', $clean) ?: [$clean];
        $candidate = '';
        foreach ($tokens as $token) {
            if (in_array($token, ['fa', 'fas', 'far', 'fab', 'fa-solid', 'fa-regular', 'fa-brands', 'fa-xs', 'fa-sm', 'fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-spin', 'fa-fw'], true)) {
                continue;
            }
            if (str_starts_with($token, 'fa-rotate-') || str_starts_with($token, 'me-') || str_starts_with($token, 'ms-') || str_starts_with($token, 'text-')) {
                continue;
            }
            $token = preg_replace('/^(lucide-|fa-)/', '', $token) ?? $token;
            if ($token !== '') {
                $candidate = $token;
                break;
            }
        }

        $map = lucide_icon_map();
        if (isset($map[$candidate])) {
            return $map[$candidate];
        }

        $sanitized = preg_replace('/[^a-z0-9-]/', '', $candidate);
        return $map[$sanitized ?? ''] ?? 'circle';
    }
}

if (! function_exists('lucide_icon')) {
    /**
     * Render a crisp inline <svg><use .../></svg> referencing the shared Lucide sprite.
     */
    function lucide_icon(string $name, string $class = '', int $size = 18): string
    {
        $symbol = lucide_resolve_name($name);
        $href   = (function_exists('base_url') ? base_url('assets/icons/lucide-sprite.svg') : '/assets/icons/lucide-sprite.svg')
            . '?v=2#lucide-' . $symbol;

        $classes = trim('lucide-icon' . ($class !== '' ? ' ' . $class : ''));

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round" class="%s" aria-hidden="true"><use href="%s"></use></svg>',
            $size,
            $size,
            htmlspecialchars($classes, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($href, ENT_QUOTES, 'UTF-8')
        );
    }
}

if (! function_exists('lucide_sprite_url')) {
    /**
     * Return the versioned sprite base URL for client-side dynamic previews.
     */
    function lucide_sprite_url(): string
    {
        return (function_exists('base_url') ? base_url('assets/icons/lucide-sprite.svg') : '/assets/icons/lucide-sprite.svg') . '?v=2';
    }
}
