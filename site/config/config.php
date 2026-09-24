<?php

return [
    'debug'  => true,
    // Set to true to skip sending and write emails to logs/email-debug.log instead
    'fotoalbum.email.debug' => true,
    'panel' => [
        'install' => true,
        'vue' => [
            'compiler' => true,
        ],
        ],

    // Long random strings — do not reuse between projects
    'content' => [
        'salt' => '404524f5dbb8aca98387567297165cfacc9e11bf9e5eadec237541c874d9817b',
    ],
    'cookie' => [
        'key' => '184cccb9ac8f03c85461af4c4ccaafa7249e51d1ad258d1a0634e98117a69699',
    ],

    // Email transport — switch to 'smtp' and fill credentials for production
    'email' => [
        'transport' => [
            'type' => 'mail', // use 'smtp' in production
        ]
    ],

    // Sender address used in outgoing selection emails
    // 'fotoalbum.email.from' => 'noreply@yourdomain.com',
];
