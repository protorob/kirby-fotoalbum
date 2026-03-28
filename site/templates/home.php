<?php
  $slides = $site->slideshow()->toFiles();
?>
<?php snippet('header', ['bodyClass' => 'h-svh overflow-hidden']) ?>

<main class="flex-1 min-h-0 w-full relative overflow-hidden">

  <?php if ($slides->isNotEmpty()): ?>
    <div id="hero-splide" class="splide h-full">
      <div class="splide__track h-full">
        <ul class="splide__list h-full">
          <?php foreach ($slides as $slide): ?>
            <li class="splide__slide">
              <img
                src="<?= $slide->url() ?>"
                srcset="<?= $slide->srcset([400, 800, 1400, 2000]) ?>"
                sizes="100vw"
                alt=""
                class="absolute inset-0 w-full h-full object-cover">
            </li>
          <?php endforeach ?>
        </ul>
      </div>
      <div class="absolute inset-0 bg-black/40 pointer-events-none z-10"></div>
    </div>
  <?php endif ?>

  <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center px-8 <?= $slides->isNotEmpty() ? 'text-white [text-shadow:0_2px_12px_rgba(0,0,0,0.4)]' : 'text-neutral-800' ?>">
    <?php if ($site->tagline()->isNotEmpty()): ?>
      <h1 class="font-serif text-3xl md:text-5xl tracking-wide fade-in"><?= $site->tagline()->html() ?></h1>
    <?php endif ?>
    <?php if ($site->about()->isNotEmpty()): ?>
      <p class="mt-6 text-sm tracking-wide max-w-md leading-relaxed opacity-80 fade-in"><?= $site->about()->kt() ?></p>
    <?php endif ?>
  </div>

</main>

<?php snippet('footer') ?>
