<?php

return function ($page, $kirby, $site) {
    $error = null;
    $sent = false;

    if ($kirby->request()->is('POST') && $page->selectionOpen()->isTrue()) {
        if (!csrf(get('csrf'))) {
            $error = 'Invalid request. Please try again.';
        } else {
            $sender = trim(get('sender', ''));
            $senderEmail = trim(get('senderEmail', ''));
            $message = trim(get('message', ''));
            $images = (array)(get('images') ?? []);

            if (empty($sender)) {
                $error = 'Please enter your name.';
            } elseif (empty($images)) {
                $error = 'Please select at least one image.';
            } else {
                $imageLines = implode("\n", array_map(function ($filename) use ($page) {
                    $file = $page->file($filename);
                    return $file ? $file->url() : $filename;
                }, $images));

                $body  = "Gallery: {$page->title()}\n";
                $body .= "From: {$sender}";
                if ($senderEmail) $body .= " <{$senderEmail}>";
                $body .= "\n\n";
                if ($message) $body .= "Message:\n{$message}\n\n";
                $body .= "Selected images:\n{$imageLines}";

                try {
                    $subject = "Image selection — {$page->title()} — {$sender}";

                    if (option('fotoalbum.email.debug', false)) {
                        $logDir  = kirby()->root('index') . '/logs';
                        $logFile = $logDir . '/email-debug.log';
                        if (!is_dir($logDir)) mkdir($logDir, 0755, true);
                        $entry  = str_repeat('-', 60) . "\n";
                        $entry .= 'Date:    ' . date('Y-m-d H:i:s') . "\n";
                        $entry .= 'To:      ' . $site->email()->value() . "\n";
                        $entry .= 'Subject: ' . $subject . "\n\n";
                        $entry .= $body . "\n\n";
                        file_put_contents($logFile, $entry, FILE_APPEND);
                    } else {
                        $kirby->email([
                            'from'    => option('fotoalbum.email.from', 'noreply@' . parse_url($site->url(), PHP_URL_HOST)),
                            'to'      => $site->email()->value(),
                            'replyTo' => $senderEmail ?: $site->email()->value(),
                            'subject' => $subject,
                            'text'    => $body,
                        ]);
                    }

                    // Append to submissions log
                    $existing = [];
                    if ($page->selections()->isNotEmpty()) {
                        foreach ($page->selections()->toStructure() as $entry) {
                            $existing[] = [
                                'date'        => $entry->date()->value(),
                                'sender'      => $entry->sender()->value(),
                                'senderEmail' => $entry->senderEmail()->value(),
                                'message'     => $entry->message()->value(),
                                'images'      => $entry->images()->value(),
                            ];
                        }
                    }
                    $existing[] = [
                        'date'        => date('Y-m-d H:i'),
                        'sender'      => $sender,
                        'senderEmail' => $senderEmail,
                        'message'     => $message,
                        'images'      => implode(', ', $images),
                    ];

                    $page->update([
                        'selectionOpen' => false,
                        'selections'    => $existing,
                    ]);

                    $sent = true;
                } catch (Exception $e) {
                    $error = 'Could not send the email. Please try again later.';
                }
            }
        }
    }

    return compact('error', 'sent');
};
