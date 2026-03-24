<?php snippet('header') ?>

<main class="flex-1 w-full max-w-5xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold"><?= $page->title() ?></h1>
  <?php if ($page->description()->isNotEmpty()): ?>
    <p class="mt-2 text-base"><?= $page->description()->html() ?></p>
  <?php endif ?>

  <?php if ($sent): ?>

    <div class="mt-8 flex flex-col gap-4 items-start">
      <p class="border px-4 py-3 text-sm">Your selection has been sent successfully. Thank you!</p>
      <a href="<?= $page->url() ?>" class="border px-4 py-2 text-sm hover:bg-black hover:text-white transition-colors">
        Back to gallery
      </a>
    </div>

  <?php elseif ($page->selectionOpen()->isTrue()): ?>

    <form method="post" id="selection-form" class="mt-8">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">

      <p class="text-sm text-gray-500 mb-4">Click images to select them, then fill in your details and submit.</p>

      <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
        <?php foreach ($page->images() as $image): ?>
          <label class="relative cursor-pointer block">
            <input type="checkbox" name="images[]" value="<?= $image->filename() ?>" class="sr-only peer">
            <img src="<?= $image->url() ?>" alt="<?= $image->filename() ?>" class="w-full object-cover aspect-square">
            <div class="absolute inset-0 ring-2 ring-transparent peer-checked:ring-black pointer-events-none transition-all"></div>
            <span class="absolute top-2 right-2 w-6 h-6 rounded-full bg-black text-white text-xs items-center justify-center opacity-0 peer-checked:opacity-100 hidden peer-checked:flex transition-opacity">✓</span>
          </label>
        <?php endforeach ?>
      </div>

      <?php if ($error): ?>
        <p class="mt-4 text-sm text-red-600"><?= $error ?></p>
      <?php endif ?>

      <div class="mt-8 max-w-sm flex flex-col gap-4">
        <div class="flex flex-col gap-1">
          <label for="sender" class="text-sm">Your name <span class="text-red-500">*</span></label>
          <input type="text" id="sender" name="sender" required
            value="<?= esc(get('sender', '')) ?>"
            class="border px-3 py-2 text-sm rounded focus:outline-none focus:ring-1 focus:ring-current">
        </div>
        <div class="flex flex-col gap-1">
          <label for="senderEmail" class="text-sm">Your email</label>
          <input type="email" id="senderEmail" name="senderEmail"
            value="<?= esc(get('senderEmail', '')) ?>"
            class="border px-3 py-2 text-sm rounded focus:outline-none focus:ring-1 focus:ring-current">
        </div>
        <div class="flex flex-col gap-1">
          <label for="message" class="text-sm">Message</label>
          <textarea id="message" name="message" rows="3"
            class="border px-3 py-2 text-sm rounded focus:outline-none focus:ring-1 focus:ring-current"><?= esc(get('message', '')) ?></textarea>
        </div>

        <button type="submit" id="submit-btn"
          class="border px-4 py-2 text-sm hover:bg-black hover:text-white transition-colors">
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
    ?>


<div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
  
  <?php foreach ($page->images() as $image): ?>
    <?php $isSubmitted = in_array($image->filename(), $submittedImages) ?>
    <div class="relative">
      <img src="<?= $image->url() ?>" alt="<?= $image->filename() ?>" class="w-full object-cover aspect-square <?= $isSubmitted ? '' : 'opacity-40' ?>">
      <?php if ($isSubmitted): ?>
        <div class="absolute inset-0 ring-2 ring-black pointer-events-none"></div>
        <span class="absolute top-2 right-2 w-6 h-6 rounded-full bg-black text-white text-xs flex items-center justify-center">✓</span>
        <?php endif ?>
      </div>
      <?php endforeach ?>
    </div>
    
    <?php if ($isPrivate && $lastSubmission): ?>
      <p class="mt-6 text-sm text-gray-500">
        The images have already been submitted. To request a new selection, please
        <?php if ($site->email()->isNotEmpty()): ?>
          <a href="mailto:<?= $site->email() ?>" class="underline">contact the photographer</a>.
        <?php else: ?>
          contact the photographer.
        <?php endif ?>
      </p>
    <?php endif ?>

  <?php endif ?>
</main>

<?php snippet('footer') ?>
