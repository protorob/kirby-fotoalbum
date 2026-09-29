<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php snippet('page-hero', ['eyebrow' => 'Portfolio', 'subtitle' => $page->description()]) ?>

  <div class="max-w-5xl mx-auto px-4 py-12">
    <div class="grid grid-cols-2 gap-3">
      <?php $i = 0; foreach ($page->children()->listed() as $gallery): ?>
        <?php $cover = $gallery->coverImage()->toFile() ?>
        <a href="<?= $gallery->url() ?>"
           class="fade-in block overflow-hidden group relative aspect-4/5"
           style="transition-delay: <?= ($i % 2) * 100 ?>ms">
          <?php if ($cover): ?>
            <img src="<?= $cover->lqip() ?>" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover scale-110 blur-xl">
            <img
              src="<?= $cover->resize(800)->url() ?>"
              srcset="<?= $cover->srcset([400, 800, 1200]) ?>"
              sizes="50vw"
              alt="<?= $gallery->title() ?>"
              loading="lazy"
              class="js-progressive absolute inset-0 w-full h-full object-cover opacity-0 transition-all duration-700 group-hover:scale-105 [&.loaded]:opacity-100">
          <?php else: ?>
            <div class="absolute inset-0 bg-neutral-100"></div>
          <?php endif ?>
          <div class="absolute inset-0 bg-black/20 group-hover:bg-black/35 transition-colors duration-300"></div>
          <div class="absolute inset-0 flex items-center justify-center">
            <h2 class="text-white text-lg tracking-wide uppercase"><?= $gallery->title() ?></h2>
          </div>
        </a>
      <?php $i++; endforeach ?>
    </div>
  </div>

</main>

<?php snippet('footer') ?>
