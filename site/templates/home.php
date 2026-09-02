<?php
  $slides        = $site->slideshow()->toFiles();
  $servicesPage  = $site->find('servizi');
  $services      = $servicesPage ? $servicesPage->children()->listed() : null;
?>
<?php snippet('header', ['heroHeader' => true]) ?>

<main class="w-full">

  <div class="relative h-screen overflow-hidden">

    <?php if ($slides->isNotEmpty()): ?>
      <div id="hero-splide" class="splide h-full">
        <div class="splide__track h-full">
          <ul class="splide__list h-full">
            <?php foreach ($slides as $slide): ?>
              <li class="splide__slide overflow-hidden">
                <img src="<?= $slide->lqip() ?>" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover scale-110 blur-xl">
                <img
                  src="<?= $slide->resize(1400)->url() ?>"
                  srcset="<?= $slide->srcset([400, 800, 1400, 2000]) ?>"
                  sizes="100vw"
                  alt=""
                  class="js-progressive absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-700 [&.loaded]:opacity-100">
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

  <?php if ($services && $services->count() > 0): ?>
  <div class="max-w-5xl mx-auto px-4 py-24">

    <?php if ($services->count() <= 3): ?>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <?php foreach ($services as $service): ?>
          <?php $cover = $service->coverImage()->toFile() ?>
          <div class="flex flex-col gap-4">
            <a href="<?= $service->url() ?>" class="fade-in block overflow-hidden group relative aspect-[4/5]">
              <?php if ($cover): ?>
                <img src="<?= $cover->lqip() ?>" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover scale-110 blur-xl">
                <img
                  src="<?= $cover->resize(800)->url() ?>"
                  srcset="<?= $cover->srcset([400, 800, 1200]) ?>"
                  sizes="(min-width: 768px) 33vw, 100vw"
                  loading="lazy"
                  alt="<?= $service->title() ?>" class="js-progressive absolute inset-0 w-full h-full object-cover opacity-0 transition-all duration-700 group-hover:scale-105 [&.loaded]:opacity-100">
              <?php else: ?>
                <div class="bg-neutral-200 absolute inset-0"></div>
              <?php endif ?>
            </a>
            <p class="text-xs tracking-widest uppercase"><?= $service->title() ?></p>
            <p class="text-sm text-neutral-500 leading-relaxed"><?= $service->description()->html() ?></p>
          </div>
        <?php endforeach ?>
      </div>

    <?php else: ?>
      <div id="services-splide" class="splide">
        <div class="splide__track">
          <ul class="splide__list">
            <?php foreach ($services as $service): ?>
              <?php $cover = $service->coverImage()->toFile() ?>
              <li class="splide__slide">
                <div class="flex flex-col gap-4">
                  <a href="<?= $service->url() ?>" class="fade-in block overflow-hidden group relative aspect-[4/5]">
                    <?php if ($cover): ?>
                      <img src="<?= $cover->lqip() ?>" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover scale-110 blur-xl">
                      <img
                        src="<?= $cover->resize(800)->url() ?>"
                        srcset="<?= $cover->srcset([400, 800, 1200]) ?>"
                        sizes="(min-width: 768px) 33vw, (min-width: 640px) 50vw, 100vw"
                        loading="lazy"
                        alt="<?= $service->title() ?>" class="js-progressive absolute inset-0 w-full h-full object-cover opacity-0 transition-all duration-700 group-hover:scale-105 [&.loaded]:opacity-100">
                    <?php else: ?>
                      <div class="bg-neutral-200 absolute inset-0"></div>
                    <?php endif ?>
                  </a>
                  <p class="text-xs tracking-widest uppercase"><?= $service->title() ?></p>
                  <p class="text-sm text-neutral-500 leading-relaxed"><?= $service->description()->html() ?></p>
                </div>
              </li>
            <?php endforeach ?>
          </ul>
        </div>
      </div>
    <?php endif ?>

  </div>
  <?php endif ?>

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
