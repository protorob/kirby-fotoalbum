<?php snippet('header') ?>

<main class="flex-1 w-full">

    <div class="py-16 text-center border-b border-neutral-200 fade-in">
        <p class="text-xs tracking-widest uppercase text-neutral-400 mb-1 fade-in">Portfolio</p>
        <h1 class="font-serif text-3xl tracking-wide fade-in"><?= $page->title() ?></h1>
        <?php if ($page->description()->isNotEmpty()): ?>
            <p class="mt-1 text-sm text-neutral-500 max-w-md mx-auto leading-relaxed fade-in"><?= $page->description()->html() ?></p>
        <?php endif ?>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-24 grid grid-cols-1 md:grid-cols-3 gap-12">
        <?php $i = 0; foreach ($page->children()->listed() as $service): ?>
        <?php $cover = $service->coverImage()->toFile() ?>
        <div class="flex flex-col gap-4">
            <a href="<?= $service->url() ?>"
            class="fade-in block overflow-hidden group relative aspect-[4/5]"
            style="transition-delay: <?= ($i % 2) * 100 ?>ms">
                <?php if ($cover): ?>
                    <img
                    src="<?= $cover->url() ?>"
                    alt="<?= $service->title() ?>"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <?php else: ?>
                    <div class="bg-neutral-500 aspect-[4/5]"></div>
                <?php endif ?>
            </a>
            <p class="text-xs tracking-widest uppercase"><?= $service->title() ?></p>
            <p class="text-sm text-neutral-500 leading-relaxed"><?= $service->description()->html()?></p>
        </div>
        <?php $i++; endforeach ?>
    </div>

</main>

<?php snippet('footer') ?>
