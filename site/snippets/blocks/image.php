<?php
/** @var \Kirby\Cms\Block $block */
$alt     = $block->alt();
$caption = $block->caption();
$crop    = $block->crop()->isTrue();
$link    = $block->link();
$ratio   = $block->ratio()->or('auto');
$src     = null;
$image   = null;

$srcset = null;
$lqip   = null;

if ($block->location() == 'web') {
    $src = $block->src()->esc();
} elseif ($image = $block->image()->toFile()) {
    $alt    = $alt->or($image->alt());
    $src    = $image->resize(1024)->url();
    $srcset = $image->srcset([640, 1024, 1600]);
    $lqip   = $image->lqip();
}

// "fill" mode absolutely positions the image over a fixed aspect-ratio box —
// used whenever we know the box size in advance (explicit ratio, or crop),
// which is also what lets us layer the blurred placeholder underneath.
$fill = $image && ($crop || $ratio === 'auto');

$figureStyle = match (true) {
    $ratio !== 'auto' => 'aspect-ratio: ' . str_replace('/', ' / ', $ratio) . ';',
    $image !== null    => 'aspect-ratio: ' . $image->width() . ' / ' . $image->height() . ';',
    default            => '',
};
$figureClass = ($crop || $fill) ? 'relative overflow-hidden' : '';
$imgClass    = $fill
    ? 'js-progressive absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 [&.loaded]:opacity-100'
    : 'w-full' . ($crop ? ' h-full object-cover' : '');
?>
<?php if ($src): ?>
<figure class="<?= $figureClass ?>"<?= $figureStyle ? ' style="' . $figureStyle . '"' : '' ?>>
  <?php if ($lqip): ?>
    <img src="<?= $lqip ?>" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover scale-110 blur-xl">
  <?php endif ?>

  <?php if ($link->isNotEmpty()): ?>
    <a href="<?= Str::esc($link->toUrl()) ?>">
      <img src="<?= $src ?>"<?= $srcset ? ' srcset="' . $srcset . '" sizes="(min-width: 1024px) 1024px, 100vw"' : '' ?> alt="<?= $alt->esc() ?>" loading="lazy" class="<?= $imgClass ?>">
    </a>
  <?php else: ?>
    <img src="<?= $src ?>"<?= $srcset ? ' srcset="' . $srcset . '" sizes="(min-width: 1024px) 1024px, 100vw"' : '' ?> alt="<?= $alt->esc() ?>" loading="lazy" class="<?= $imgClass ?>">
  <?php endif ?>

  <?php if ($caption->isNotEmpty()): ?>
    <figcaption class="text-sm text-neutral-500 mt-2"><?= $caption ?></figcaption>
  <?php endif ?>
</figure>
<?php endif ?>
