<?php snippet('header') ?>

<main class="flex-1 w-full max-w-5xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold"><?= $page->title() ?></h1>
  <?= $page->text()->toBlocks() ?>
</main>

<?php snippet('footer') ?>
