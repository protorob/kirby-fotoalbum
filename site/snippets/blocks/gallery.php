<?php
/** @var \Kirby\Cms\Block $block */
$caption = $block->caption();
$images  = $block->images()->toFiles();
?>
<?php if ($images->count()): ?>
<figure class="not-prose">
  <div class="columns-2 sm:columns-3 gap-3 space-y-3">
    <?php foreach ($images as $image): ?>
      <?php $full = $image->resize(2400) ?>
      <div class="break-inside-avoid relative overflow-hidden group cursor-pointer"
           style="aspect-ratio: <?= $image->width() ?> / <?= $image->height() ?>;"
           data-gallery="<?= $block->id() ?>"
           data-pswp-src="<?= $full->url() ?>"
           data-pswp-width="<?= $full->width() ?>"
           data-pswp-height="<?= $full->height() ?>">
        <img src="<?= $image->lqip() ?>" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover scale-110 blur-xl">
        <img
          src="<?= $image->resize(1200)->url() ?>"
          srcset="<?= $image->srcset([400, 800, 1200]) ?>"
          sizes="(min-width: 640px) 33vw, 50vw"
          alt="<?= $image->alt()->or($image->filename()) ?>"
          loading="lazy"
          class="js-progressive absolute inset-0 w-full h-full object-cover opacity-0 transition-all duration-500 group-hover:scale-[1.02] [&.loaded]:opacity-100">
      </div>
    <?php endforeach ?>
  </div>

  <?php if ($caption->isNotEmpty()): ?>
    <figcaption class="text-sm text-neutral-500 mt-4 text-center"><?= $caption ?></figcaption>
  <?php endif ?>
</figure>
<?php endif ?>
