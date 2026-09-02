<?php
/** @var \Kirby\Cms\Page $item */
$children = $item->intendedTemplate()->name() === 'services'
  ? $item->children()->listed()
  : null;
?>
<?php if ($children && $children->count()): ?>
  <div class="relative group">
    <a href="<?= $item->url() ?>" class="hover:opacity-60 transition-opacity <?= $item->isActive() ? 'font-medium' : '' ?>">
      <?= $item->title() ?>
    </a>
    <div class="absolute left-0 top-full pt-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-focus-within:opacity-100 group-focus-within:visible transition-all duration-150 z-10">
      <div class="bg-cream border border-neutral-200 py-2 flex flex-col min-w-40 shadow-sm">
        <?php foreach ($children as $child): ?>
          <a href="<?= $child->url() ?>" class="px-4 py-2 whitespace-nowrap hover:opacity-60 transition-opacity <?= $child->isActive() ? 'font-medium' : '' ?>">
            <?= $child->title() ?>
          </a>
        <?php endforeach ?>
      </div>
    </div>
  </div>
<?php else: ?>
  <a href="<?= $item->url() ?>" class="hover:opacity-60 transition-opacity <?= $item->isActive() ? 'font-medium' : '' ?>">
    <?= $item->title() ?>
  </a>
<?php endif ?>
