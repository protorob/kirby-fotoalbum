<?php snippet('header') ?>

<main class="flex-1 w-full">

    <div class="py-16 text-center border-b border-neutral-200 fade-in">
        <h1 class="font-serif text-3xl tracking-wide fade-in"><?= $page->title() ?></h1>
        <?php if ($page->description()->isNotEmpty()): ?>
        <p class="mt-1 text-sm text-neutral-500 max-w-md mx-auto leading-relaxed fade-in"><?= $page->description()->html() ?></p>
        <?php endif ?>
    </div>

    <?php if ($page->serviceDesc()->isNotEmpty()): ?>
        <div class="max-w-5xl mx-auto px-4 py-12 page-content fade-in prose prose-headings:font-serif">
        <?= $page->serviceDesc()->toBlocks() ?>
        </div>
    <?php endif ?>

</main>

<?php snippet('footer') ?>
