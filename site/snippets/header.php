<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;1,400&family=Montserrat:wght@300;400;500&display=optional" rel="stylesheet">
  <?php snippet('seo/head', slots: true) ?>
    <link rel="stylesheet" href="<?= url('assets/css/main.css') ?>">
  <?php endsnippet() ?>
</head>
<body class="min-h-screen flex flex-col bg-cream font-sans text-neutral-800">

<header class="border-b border-neutral-200 bg-cream">
  <div class="max-w-8/10 mx-auto px-4 h-16 relative flex items-center justify-between sm:justify-normal">

    <?php
      $navItems = $site->children()->listed();
      $half     = (int) ceil($navItems->count() / 2);
      $leftNav  = $navItems->slice(0, $half);
      $rightNav = $navItems->slice($half);
    ?>

    <nav class="hidden sm:flex gap-6 text-xs tracking-widest uppercase flex-1">
      <?php foreach ($leftNav as $item): ?>
        <a href="<?= $item->url() ?>" class="hover:opacity-60 transition-opacity <?= $item->isActive() ? 'font-medium' : '' ?>">
          <?= $item->title() ?>
        </a>
      <?php endforeach ?>
    </nav>

    <a href="<?= $site->url() ?>" class="sm:absolute sm:left-1/2 sm:-translate-x-1/2 flex items-center">
      <?php if ($logo = $site->logo()->toFile()): ?>
        <img src="<?= $logo->url() ?>" alt="<?= $site->title() ?>" class="h-8 w-auto">
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

  <nav id="mobile-menu" class="hidden border-t border-neutral-200 sm:hidden">
    <div class="max-w-5xl mx-auto px-4 py-4 flex flex-col gap-4 text-xs tracking-widest uppercase">
      <?php foreach ($site->children()->listed() as $item): ?>
        <a href="<?= $item->url() ?>" class="<?= $item->isActive() ? 'font-medium' : '' ?>">
          <?= $item->title() ?>
        </a>
      <?php endforeach ?>
    </div>
  </nav>
</header>
