<?php
/** @var \Kirby\Cms\Block $block */
$alt     = $block->alt();
$caption = $block->caption();
$crop    = $block->crop()->isTrue();
$link    = $block->link();
$ratio   = $block->ratio()->or('auto');
$src     = null;

if ($block->location() == 'web') {
    $src = $block->src()->esc();
} elseif ($image = $block->image()->toFile()) {
    $alt = $alt->or($image->alt());
    $src = $image->url();
}

$figureStyle = $ratio !== 'auto' ? 'aspect-ratio: ' . str_replace('/', ' / ', $ratio) . ';' : '';
$figureClass = $crop ? 'overflow-hidden' : '';
$imgClass    = 'w-full' . ($crop ? ' h-full object-cover' : '');
?>
<?php if ($src): ?>
<figure class="<?= $figureClass ?>"<?= $figureStyle ? ' style="' . $figureStyle . '"' : '' ?>>
  <?php if ($link->isNotEmpty()): ?>
    <a href="<?= Str::esc($link->toUrl()) ?>">
      <img src="<?= $src ?>" alt="<?= $alt->esc() ?>" class="<?= $imgClass ?>">
    </a>
  <?php else: ?>
    <img src="<?= $src ?>" alt="<?= $alt->esc() ?>" class="<?= $imgClass ?>">
  <?php endif ?>

  <?php if ($caption->isNotEmpty()): ?>
    <figcaption class="text-sm text-neutral-500 mt-2"><?= $caption ?></figcaption>
  <?php endif ?>
</figure>
<?php endif ?>
