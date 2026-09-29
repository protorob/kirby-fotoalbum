<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php snippet('page-hero', ['subtitle' => $page->tagline()]) ?>

  <?php if ($page->text()->isNotEmpty()): ?>
    <div class="max-w-5xl mx-auto px-4 py-12 page-content fade-in prose prose-headings:font-serif">
      <?= $page->text()->toBlocks() ?>
    </div>
  <?php endif ?>

  <?php if ($page->id() === 'prenota'): ?>
    <div class="max-w-5xl mx-auto px-4 py-12 fade-in">
      <?php snippet('calendly') ?>
    </div>
  <?php endif ?>
</main>

<?php snippet('footer') ?>
