<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php if ($sent): ?>

    <div class="max-w-5xl mx-auto px-4 py-16 flex flex-col gap-4 items-center fade-in">
      <p class="border border-neutral-300 px-4 py-3 text-sm fade-in">Your selection has been sent successfully. Thank you!</p>
      <a href="<?= $page->url() ?>" class="border border-neutral-800 px-4 py-2 text-xs tracking-widest uppercase hover:bg-black hover:text-white transition-colors fade-in">
        Back to gallery
      </a>
    </div>

  <?php else: ?>

    <?php
      $selectionOpen   = $page->selectionOpen()->isTrue();
      $isPrivate       = $page->lockedPagesEnable()->isTrue();
      $lastSubmission  = $page->selections()->toStructure()->last();
      $submittedImages = [];
      if ($isPrivate && $lastSubmission) {
        $submittedImages = array_map('trim', explode(',', $lastSubmission->images()->value()));
      }
      $images = $page->galleryImages()->toFiles();
    ?>

    <div class="py-16 text-center border-b border-neutral-200 fade-in">
      <h1 class="font-serif text-3xl tracking-wide fade-in"><?= $page->title() ?></h1>
      <?php if ($page->description()->isNotEmpty()): ?>
        <p class="mt-1 text-sm text-neutral-500 max-w-md mx-auto leading-relaxed fade-in"><?= $page->description()->html() ?></p>
      <?php endif ?>
    </div>

    <?php if ($selectionOpen): ?>

      <form method="post" id="selection-form" class="max-w-5xl mx-auto px-4 py-12">
        <input type="hidden" name="csrf" value="<?= csrf() ?>">

        <p class="text-xs tracking-widest uppercase text-neutral-400 mb-8 text-center">
          Click an image to preview &middot; use + to select
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
          <?php $i = 0; foreach ($images as $image): ?>
            <div class="group/item group relative fade-in" style="transition-delay: <?= ($i % 3) * 80 ?>ms">
              <input type="checkbox" id="img-<?= $i ?>" name="images[]" value="<?= $image->filename() ?>" class="sr-only peer">

              <div class="overflow-hidden cursor-pointer"
                   data-lightbox="<?= $i ?>"
                   data-pswp-src="<?= $image->url() ?>"
                   data-pswp-width="<?= $image->width() ?>"
                   data-pswp-height="<?= $image->height() ?>"
                   data-filename="<?= $image->filename() ?>">
                <img src="<?= $image->url() ?>" alt="<?= $image->filename() ?>"
                  class="w-full aspect-square object-cover transition-transform duration-500 group-hover/item:scale-[1.02]">
              </div>

              <div class="absolute inset-0 ring-2 ring-transparent peer-checked:ring-black pointer-events-none transition-all"></div>

              <label for="img-<?= $i ?>"
                class="absolute top-2 right-2 z-10 cursor-pointer
                       w-7 h-7 rounded-full flex items-center justify-center
                       bg-white/70 border border-white/80 shadow-sm select-none
                       peer-checked:bg-black peer-checked:border-black
                       hover:bg-white transition-all">
                <span class="text-neutral-600 text-base leading-none group-has-[:checked]/item:hidden">+</span>
                <span class="text-white text-xs hidden group-has-[:checked]/item:flex items-center justify-center">✓</span>
              </label>
            </div>
          <?php $i++; endforeach ?>
        </div>

        <?php if ($error): ?>
          <p class="mt-6 text-sm text-red-600"><?= $error ?></p>
        <?php endif ?>

        <div class="mt-12 max-w-sm mx-auto flex flex-col gap-4">
          <div class="flex flex-col gap-1">
            <label for="sender" class="text-xs tracking-widest uppercase text-neutral-500">Your name <span class="text-red-500">*</span></label>
            <input type="text" id="sender" name="sender" required
              value="<?= esc(get('sender', '')) ?>"
              class="border border-neutral-300 px-3 py-2 text-sm bg-transparent focus:outline-none focus:border-neutral-800">
          </div>
          <div class="flex flex-col gap-1">
            <label for="senderEmail" class="text-xs tracking-widest uppercase text-neutral-500">Your email</label>
            <input type="email" id="senderEmail" name="senderEmail"
              value="<?= esc(get('senderEmail', '')) ?>"
              class="border border-neutral-300 px-3 py-2 text-sm bg-transparent focus:outline-none focus:border-neutral-800">
          </div>
          <div class="flex flex-col gap-1">
            <label for="message" class="text-xs tracking-widest uppercase text-neutral-500">Message</label>
            <textarea id="message" name="message" rows="3"
              class="border border-neutral-300 px-3 py-2 text-sm bg-transparent focus:outline-none focus:border-neutral-800"><?= esc(get('message', '')) ?></textarea>
          </div>

          <button type="submit" id="submit-btn"
            class="border border-neutral-800 px-4 py-3 text-xs tracking-widest uppercase hover:bg-black hover:text-white transition-colors">
            Send selection (<span id="selection-count">0</span> selected)
          </button>
        </div>
      </form>

    <?php else: ?>

      <div class="max-w-5xl mx-auto px-4 py-12">
        <div class="<?= $isPrivate ? 'grid grid-cols-2 sm:grid-cols-3 gap-3' : 'columns-2 sm:columns-3 gap-3 space-y-3' ?>">
          <?php $i = 0; foreach ($images as $image): ?>
            <?php $isSubmitted = in_array($image->filename(), $submittedImages) ?>
            <div class="fade-in <?= $isPrivate ? 'relative group cursor-pointer' : 'break-inside-avoid relative group cursor-pointer' ?>"
                 style="transition-delay: <?= ($i % 3) * 80 ?>ms"
                 data-lightbox="<?= $i ?>"
                 data-pswp-src="<?= $image->url() ?>"
                 data-pswp-width="<?= $image->width() ?>"
                 data-pswp-height="<?= $image->height() ?>">
              <img
                src="<?= $image->url() ?>"
                alt="<?= $image->filename() ?>"
                class="w-full <?= $isPrivate ? 'aspect-square object-cover' : 'object-cover' ?> transition-transform duration-500 group-hover:scale-[1.02] <?= ($isPrivate && $lastSubmission && !$isSubmitted) ? 'opacity-40' : '' ?>">
              <?php if ($isSubmitted): ?>
                <div class="absolute inset-0 ring-2 ring-black pointer-events-none"></div>
                <span class="absolute top-2 right-2 w-6 h-6 rounded-full bg-black text-white text-xs flex items-center justify-center">✓</span>
              <?php endif ?>
            </div>
          <?php $i++; endforeach ?>
        </div>
      </div>

      <?php if ($isPrivate && $lastSubmission): ?>
        <div class="max-w-5xl mx-auto px-4 pb-12">
          <p class="text-sm text-neutral-400 text-center">
            The images have already been submitted. To request a new selection, please
            <?php if ($site->email()->isNotEmpty()): ?>
              <a href="mailto:<?= $site->email() ?>" class="underline">contact the photographer</a>.
            <?php else: ?>
              contact the photographer.
            <?php endif ?>
          </p>
        </div>
      <?php endif ?>

    <?php endif ?>

  <?php endif ?>
</main>

<?php snippet('footer') ?>
