<?php

return [
    'debug'  => true,
    // Set to true to skip sending and write emails to logs/email-debug.log instead
    'fotoalbum.email.debug' => true,
    'panel' =>[
        'install' => true
    ]

    // Email transport — switch to 'smtp' and fill credentials for production
    'email' => [
        'transport' => [
            'type' => 'mail', // use 'smtp' in production
        ],
    ],

    // Sender address used in outgoing selection emails
    // 'fotoalbum.email.from' => 'noreply@yourdomain.com',
];
