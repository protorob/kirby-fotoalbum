<?php snippet('header') ?>

<main class="flex-1 w-full">

    <?php snippet('page-hero', ['subtitle' => $page->description()]) ?>

    <?php if ($page->serviceDesc()->isNotEmpty()): ?>
        <div class="max-w-5xl mx-auto px-4 py-12 page-content fade-in prose prose-headings:font-serif">
        <?= $page->serviceDesc()->toBlocks() ?>
        </div>
    <?php endif ?>

</main>

<?php snippet('footer') ?>
