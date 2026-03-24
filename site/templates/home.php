<?php snippet('header') ?>

<main class="flex-1 w-full max-w-5xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold"><?= $site->tagline() ?></h1>
  <?php if ($site->about()->isNotEmpty()): ?>
    <p class="mt-4 text-lg"><?= $site->about()->kt() ?></p>
  <?php endif ?>
</main>

<?php snippet('footer') ?>
