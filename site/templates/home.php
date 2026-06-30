<?php
  $slides = $site->slideshow()->toFiles();
?>
<?php snippet('header', ['heroHeader' => true]) ?>

<main class="w-full">

  <div class="relative h-screen overflow-hidden">

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
      </div>
      <div class="absolute inset-0 bg-black/40 pointer-events-none z-10"></div>
    <?php endif ?>

    <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center px-8 <?= $slides->isNotEmpty() ? 'text-white [text-shadow:0_2px_12px_rgba(0,0,0,0.4)]' : 'text-neutral-800' ?>">
      <?php if ($site->tagline()->isNotEmpty()): ?>
        <h1 class="font-serif text-3xl md:text-5xl tracking-wide fade-in"><?= $site->tagline()->html() ?></h1>
      <?php endif ?>
      <?php if ($site->about()->isNotEmpty()): ?>
        <p class="mt-6 text-sm tracking-wide max-w-md leading-relaxed opacity-80 fade-in"><?= $site->about()->kt() ?></p>
      <?php endif ?>
    </div>

  </div>

  <div class="max-w-5xl mx-auto px-4 py-24 grid grid-cols-1 md:grid-cols-3 gap-12">
    <?php foreach (['Editorial', 'Portrait', 'Commercial', 'Another one'] as $label): ?>
      <div class="flex flex-col gap-4">
        <div class="bg-neutral-500 aspect-[4/5]"></div>
        <p class="text-xs tracking-widest uppercase"><?= $label ?></p>
        <p class="text-sm text-neutral-500 leading-relaxed">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p>
      </div>
    <?php endforeach ?>
  </div>

  <div class="border-t border-neutral-200 max-w-5xl mx-auto px-4 py-24 text-center">
    <h2 class="font-serif text-3xl tracking-wide mb-6">Selected Work</h2>
    <p class="text-sm text-neutral-500 max-w-md mx-auto leading-relaxed">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4">
      <?php foreach (range(1, 8) as $i): ?>
        <div class="bg-neutral-500 aspect-square"></div>
      <?php endforeach ?>
    </div>
  </div>

</main>

<?php snippet('footer') ?>
