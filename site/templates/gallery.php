<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php if ($sent): ?>

    <div class="max-w-5xl mx-auto px-4 py-16 flex flex-col gap-4 items-start">
      <p class="border border-neutral-300 px-4 py-3 text-sm">Your selection has been sent successfully. Thank you!</p>
      <a href="<?= $page->url() ?>" class="border border-neutral-800 px-4 py-2 text-xs tracking-widest uppercase hover:bg-black hover:text-white transition-colors">
        Back to gallery
      </a>
    </div>

  <?php elseif ($page->selectionOpen()->isTrue()): ?>

    <div class="py-16 text-center border-b border-neutral-200">
      <h1 class="font-serif text-3xl tracking-wide"><?= $page->title() ?></h1>
      <?php if ($page->description()->isNotEmpty()): ?>
        <p class="mt-4 text-sm text-neutral-500 max-w-md mx-auto leading-relaxed"><?= $page->description()->html() ?></p>
      <?php endif ?>
    </div>

    <form method="post" id="selection-form" class="max-w-5xl mx-auto px-4 py-12">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">

      <p class="text-xs tracking-widest uppercase text-neutral-400 mb-8 text-center">Click images to select them</p>

      <div class="columns-2 sm:columns-3 gap-3 space-y-3">
        <?php foreach ($page->images() as $image): ?>
          <label class="fade-in break-inside-avoid block relative cursor-pointer group">
            <input type="checkbox" name="images[]" value="<?= $image->filename() ?>" class="sr-only peer">
            <img src="<?= $image->url() ?>" alt="<?= $image->filename() ?>" class="w-full object-cover transition-transform duration-500 group-hover:scale-[1.02]">
            <div class="absolute inset-0 ring-2 ring-transparent peer-checked:ring-black pointer-events-none transition-all"></div>
            <span class="absolute top-2 right-2 w-6 h-6 rounded-full bg-black text-white text-xs items-center justify-center opacity-0 peer-checked:opacity-100 hidden peer-checked:flex transition-opacity">✓</span>
          </label>
        <?php endforeach ?>
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

    <?php
      $isPrivate       = $page->lockedPagesEnable()->isTrue();
      $lastSubmission  = $page->selections()->toStructure()->last();
      $submittedImages = [];
      if ($isPrivate && $lastSubmission) {
          $submittedImages = array_map('trim', explode(',', $lastSubmission->images()->value()));
      }
      $images = $page->images();
    ?>

    <div class="py-16 text-center border-b border-neutral-200">
      <h1 class="font-serif text-3xl tracking-wide"><?= $page->title() ?></h1>
      <?php if ($page->description()->isNotEmpty()): ?>
        <p class="mt-4 text-sm text-neutral-500 max-w-md mx-auto leading-relaxed"><?= $page->description()->html() ?></p>
      <?php endif ?>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-12">
      <div class="columns-2 sm:columns-3 gap-3 space-y-3">
        <?php $i = 0; foreach ($images as $image): ?>
          <?php $isSubmitted = in_array($image->filename(), $submittedImages) ?>
          <div class="fade-in break-inside-avoid relative group cursor-pointer"
               style="transition-delay: <?= ($i % 3) * 80 ?>ms"
               data-lightbox="<?= $i ?>">
            <img
              src="<?= $image->url() ?>"
              alt="<?= $image->filename() ?>"
              class="w-full object-cover transition-transform duration-500 group-hover:scale-[1.02] <?= ($isPrivate && $lastSubmission && !$isSubmitted) ? 'opacity-40' : '' ?>">
            <?php if ($isSubmitted): ?>
              <div class="absolute inset-0 ring-2 ring-black pointer-events-none"></div>
              <span class="absolute top-2 right-2 w-6 h-6 rounded-full bg-black text-white text-xs flex items-center justify-center">✓</span>
            <?php endif ?>
          </div>
        <?php $i++; endforeach ?>
      </div>
    </div>

    <div id="lightbox">
      <button id="lightbox-close" aria-label="Close">&times;</button>
      <button id="lightbox-prev" aria-label="Previous">←</button>
      <button id="lightbox-next" aria-label="Next">→</button>
      <div id="lightbox-swiper" class="swiper">
        <div class="swiper-wrapper">
          <?php foreach ($images as $image): ?>
            <div class="swiper-slide">
              <div class="swiper-zoom-container">
                <img src="<?= $image->url() ?>" alt="<?= $image->filename() ?>">
              </div>
            </div>
          <?php endforeach ?>
        </div>
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
</main>

<?php snippet('footer') ?>
