<?php snippet('header') ?>

<main class="flex-1 w-full">

  <div class="py-16 text-center border-b border-neutral-200 fade-in">
    <p class="text-xs tracking-widest uppercase text-neutral-400 mb-3 fade-in">Portfolio</p>
    <h1 class="font-serif text-3xl tracking-wide fade-in"><?= $page->title() ?></h1>
    <?php if ($page->description()->isNotEmpty()): ?>
      <p class="mt-4 text-sm text-neutral-500 max-w-md mx-auto leading-relaxed fade-in"><?= $page->description()->html() ?></p>
    <?php endif ?>
  </div>

  <div class="max-w-5xl mx-auto px-4 py-12">
    <div class="grid grid-cols-2 gap-3">
      <?php $i = 0; foreach ($page->children()->listed() as $gallery): ?>
        <?php $cover = $gallery->coverImage() ?>
        <a href="<?= $gallery->url() ?>"
           class="fade-in block overflow-hidden group relative aspect-[4/5]"
           style="transition-delay: <?= ($i % 2) * 100 ?>ms">
          <?php if ($cover): ?>
            <img
              src="<?= $cover->toFile()->url() ?>"
              alt="<?= $gallery->title() ?>"
              class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
          <?php else: ?>
            <div class="absolute inset-0 bg-neutral-100"></div>
          <?php endif ?>
          <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
          <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-2 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
            <h2 class="font-serif text-white text-lg tracking-wide"><?= $gallery->title() ?></h2>
          </div>
        </a>
      <?php $i++; endforeach ?>
    </div>
  </div>

</main>

<?php snippet('footer') ?>
