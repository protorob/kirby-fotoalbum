<?php
/** @var \Kirby\Cms\Block $block */
$caption = $block->caption();
$images  = $block->images()->toFiles();
?>
<?php if ($images->count()): ?>
<figure>
  <div class="columns-2 sm:columns-3 gap-3 space-y-3">
    <?php foreach ($images as $image): ?>
      <div class="break-inside-avoid relative group cursor-pointer"
           data-gallery="<?= $block->id() ?>"
           data-pswp-src="<?= $image->url() ?>"
           data-pswp-width="<?= $image->width() ?>"
           data-pswp-height="<?= $image->height() ?>">
        <img
          src="<?= $image->url() ?>"
          alt="<?= $image->alt()->or($image->filename()) ?>"
          class="w-full object-cover transition-transform duration-500 group-hover:scale-[1.02]">
      </div>
    <?php endforeach ?>
  </div>

  <?php if ($caption->isNotEmpty()): ?>
    <figcaption class="text-sm text-neutral-500 mt-4 text-center"><?= $caption ?></figcaption>
  <?php endif ?>
</figure>
<?php endif ?>
