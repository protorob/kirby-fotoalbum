<?php snippet('header') ?>

<main class="flex-1 w-full max-w-5xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold"><?= $page->title() ?></h1>

  <div class="mt-8 grid gap-4">
    <?php foreach ($page->children()->listed() as $gallery): ?>
      <a href="<?= $gallery->url() ?>" class="block p-4 border">
        <h2 class="text-xl font-semibold"><?= $gallery->title() ?></h2>
        <?php if ($gallery->description()->isNotEmpty()): ?>
          <p class="mt-1 text-sm"><?= $gallery->description()->html() ?></p>
        <?php endif ?>
      </a>
    <?php endforeach ?>
  </div>
</main>

<?php snippet('footer') ?>
