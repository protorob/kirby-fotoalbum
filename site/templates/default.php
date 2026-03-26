<?php snippet('header') ?>

<main class="flex-1 w-full max-w-5xl mx-auto px-4 py-8 fade-in">
  <h1 class="text-2xl font-bold fade-in"><?= $page->title() ?></h1>
  <div class="page-content fade-in">
    <?= $page->text()->toBlocks() ?>
  </div>
</main>

<?php snippet('footer') ?>
