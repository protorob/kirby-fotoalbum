<?php

use Kirby\Filesystem\F;

Kirby::plugin('kirby-fotoalbum/lqip', [
    'fileMethods' => [
        // Tiny blurred base64 preview of an image, inlined as a loading
        // placeholder so galleries don't pop/jump in while photos load.
        'lqip' => function (int $width = 24) {
            $thumb = $this->resize($width);
            return 'data:' . $thumb->mime() . ';base64,' . base64_encode(F::read($thumb->root()));
        }
    ]
]);
