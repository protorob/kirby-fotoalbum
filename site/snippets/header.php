<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php snippet('seo/head', slots: true) ?>
    <link rel="stylesheet" href="<?= url('assets/css/main.css') ?>">
  <?php endsnippet() ?>
</head>
<body class="min-h-screen flex flex-col">

<header class="border-b">
  <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">

    <a href="<?= $site->url() ?>" class="flex items-center gap-2">
      <?php if ($logo = $site->logo()->toFile()): ?>
        <img src="<?= $logo->url() ?>" alt="<?= $site->title() ?>" class="h-8 w-auto">
      <?php else: ?>
        <span class="font-semibold text-lg"><?= $site->title() ?></span>
      <?php endif ?>
    </a>

    <nav class="hidden sm:flex gap-6 text-sm">
      <?php foreach ($site->children()->listed() as $item): ?>
        <a href="<?= $item->url() ?>" class="hover:underline <?= $item->isActive() ? 'font-semibold' : '' ?>">
          <?= $item->title() ?>
        </a>
      <?php endforeach ?>
    </nav>

    <button id="menu-toggle" class="sm:hidden p-2" aria-label="Toggle menu">
      <span class="block w-5 h-px bg-current mb-1.5"></span>
      <span class="block w-5 h-px bg-current mb-1.5"></span>
      <span class="block w-5 h-px bg-current"></span>
    </button>
  </div>

  <nav id="mobile-menu" class="hidden border-t sm:hidden">
    <div class="max-w-5xl mx-auto px-4 py-3 flex flex-col gap-3 text-sm">
      <?php foreach ($site->children()->listed() as $item): ?>
        <a href="<?= $item->url() ?>" class="<?= $item->isActive() ? 'font-semibold' : '' ?>">
          <?= $item->title() ?>
        </a>
      <?php endforeach ?>
    </div>
  </nav>
</header>
