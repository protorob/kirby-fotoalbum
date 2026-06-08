<?php
  $isHero    = $heroHeader ?? false;
  $navItems  = $site->children()->listed();
  $half      = (int) ceil($navItems->count() / 2);
  $leftNav   = $navItems->slice(0, $half);
  $rightNav  = $navItems->slice($half);
  $logoLight       = $site->logo_light()->toFile();
  $logoDark        = $site->logo()->toFile();
  $logoMobile      = $site->logo_mobile()->toFile();
  $logoMobileLight = $site->logo_mobile_light()->toFile();
  $smallLogoDark   = $logoMobile ?: $logoDark;
  $smallLogoLight  = $logoMobileLight ?: $logoLight;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?= url('assets/css/main.css') ?>">
  <?php snippet('seo/head'); ?>
</head>
<body class="min-h-screen flex flex-col bg-cream font-sans text-neutral-800 <?= $bodyClass ?? '' ?>">

<header
  id="site-header"
  class="fixed top-0 left-0 right-0 z-50<?= $isHero ? ' header--hero' : ' bg-cream border-b border-neutral-200' ?>"
>
  <div class="max-w-5xl md:max-w-8/10 mx-auto px-4 h-20 relative flex items-center justify-between sm:justify-normal">

    <nav class="hidden sm:flex gap-6 text-xs tracking-widest uppercase flex-1">
      <?php foreach ($leftNav as $item): ?>
        <a href="<?= $item->url() ?>" class="hover:opacity-60 transition-opacity <?= $item->isActive() ? 'font-medium' : '' ?>">
          <?= $item->title() ?>
        </a>
      <?php endforeach ?>
    </nav>

    <a href="<?= $site->url() ?>" id="logo-small" class="sm:absolute sm:left-1/2 sm:-translate-x-1/2 flex items-center">
      <?php if ($smallLogoLight || $smallLogoDark): ?>
        <div class="relative flex items-center h-10">
          <?php if ($smallLogoLight): ?>
            <img src="<?= $smallLogoLight->url() ?>" alt="<?= $site->title() ?>" class="logo-light-img h-9 w-auto">
          <?php endif ?>
          <?php if ($smallLogoDark): ?>
            <img src="<?= $smallLogoDark->url() ?>" alt="<?= $site->title() ?>" class="logo-dark-img h-9 w-auto<?= $smallLogoLight ? ' absolute top-0 left-0' : '' ?>">
          <?php endif ?>
        </div>
      <?php else: ?>
        <span class="font-serif text-xl tracking-widest uppercase"><?= $site->title() ?></span>
      <?php endif ?>
    </a>

    <nav class="hidden sm:flex gap-6 text-xs tracking-widest uppercase flex-1 justify-end">
      <?php foreach ($rightNav as $item): ?>
        <a href="<?= $item->url() ?>" class="hover:opacity-60 transition-opacity <?= $item->isActive() ? 'font-medium' : '' ?>">
          <?= $item->title() ?>
        </a>
      <?php endforeach ?>
    </nav>

    <button id="menu-toggle" class="sm:hidden p-2 ml-auto" aria-label="Toggle menu">
      <span class="block w-5 h-px bg-current mb-1.5"></span>
      <span class="block w-5 h-px bg-current mb-1.5"></span>
      <span class="block w-5 h-px bg-current"></span>
    </button>
  </div>

  <?php if ($isHero): ?>
  <a href="<?= $site->url() ?>" id="logo-large" class="hidden sm:flex absolute top-8 left-1/2 -translate-x-1/2">
    <?php $heroLogo = $logoLight ?: $logoDark; ?>
    <?php if ($heroLogo): ?>
      <img src="<?= $heroLogo->url() ?>" alt="<?= $site->title() ?>" class="h-28 w-auto">
    <?php else: ?>
      <span class="font-serif text-4xl tracking-widest uppercase"><?= $site->title() ?></span>
    <?php endif ?>
  </a>
  <?php endif ?>

  <nav id="mobile-menu" class="absolute top-full left-0 right-0 z-50 bg-cream border-b border-neutral-200 sm:hidden opacity-0 -translate-y-1 pointer-events-none transition-all duration-200">
    <div class="max-w-5xl mx-auto px-4 py-4 flex flex-col gap-4 text-xs tracking-widest uppercase">
      <?php foreach ($site->children()->listed() as $item): ?>
        <a href="<?= $item->url() ?>" class="h-8 <?= $item->isActive() ? 'font-medium' : '' ?>">
          <?= $item->title() ?>
        </a>
      <?php endforeach ?>
    </div>
  </nav>
</header>

<?php if (!$isHero): ?>
<div class="h-20"></div>
<?php endif ?>
