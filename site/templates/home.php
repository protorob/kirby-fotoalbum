<?php
  $slides = $site->slideshow()->toFiles();
?>
<?php snippet('header') ?>

<main class="flex-1 w-full relative overflow-hidden">

  <?php if ($slides->isNotEmpty()): ?>
    <div class="absolute inset-0">
      <?php foreach ($slides as $i => $slide): ?>
        <div class="slide absolute inset-0 transition-opacity duration-1000 <?= $i === 0 ? 'opacity-100' : 'opacity-0' ?>">
          <img
            src="<?= $slide->url() ?>"
            srcset="<?= $slide->srcset([400, 800, 1400, 2000]) ?>"
            sizes="100vw"
            alt=""
            class="w-full h-full object-cover"
            <?php if ($i === 0): ?>id="hero-first-img" fetchpriority="high"<?php else: ?>loading="lazy"<?php endif ?>>
        </div>
      <?php endforeach ?>
    </div>
    <div id="hero-overlay" class="absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-700"></div>
  <?php endif ?>

  <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center px-8 <?= $slides->isNotEmpty() ? 'text-white' : 'text-neutral-800' ?>">
    <?php if ($site->tagline()->isNotEmpty()): ?>
      <h1 class="font-serif text-3xl md:text-5xl tracking-wide fade-in"><?= $site->tagline()->html() ?></h1>
    <?php endif ?>
    <?php if ($site->about()->isNotEmpty()): ?>
      <p class="mt-6 text-sm tracking-wide max-w-md leading-relaxed opacity-80 fade-in"><?= $site->about()->kt() ?></p>
    <?php endif ?>
  </div>

</main>

<?php snippet('footer') ?>
