<?php

use Kirby\Cms\Response;

Kirby::plugin('kirby-fotoalbum/theme-colors', [
    'snippets' => [
        'theme-colors/style'  => __DIR__ . '/snippets/style.php',
        'theme-colors/widget' => __DIR__ . '/snippets/widget.php',
    ],
    'routes' => [
        [
            // Saves the colours picked in the front-end widget to site.txt.
            // Empty values clear the override and fall back to main.css.
            'pattern' => 'theme-colors',
            'method'  => 'POST',
            'action'  => function () {
                $kirby = kirby();

                if ($kirby->user()?->isAdmin() !== true) {
                    return Response::json(['error' => 'Forbidden'], 403);
                }

                if (csrf(get('csrf')) !== true) {
                    return Response::json(['error' => 'Invalid CSRF token'], 403);
                }

                $data = [];
                foreach (['bgColor', 'textColor'] as $key) {
                    $value = trim((string) get($key, ''));
                    if ($value !== '' && !preg_match('/^#[0-9a-fA-F]{6}$/', $value)) {
                        return Response::json(['error' => "Invalid colour for $key"], 400);
                    }
                    $data[$key] = $value;
                }

                $kirby->site()->update($data);

                return Response::json(['ok' => true]);
            }
        ]
    ]
]);
