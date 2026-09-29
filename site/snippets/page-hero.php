<?php
  $subtitle = $subtitle ?? null;
?>
<div class="py-16 text-center border-b border-darkbrown/15 fade-in">
  <h1 class="font-serif text-3xl tracking-wide fade-in"><?= $page->title() ?></h1>
  <?php if ($subtitle && $subtitle->isNotEmpty()): ?>
    <p class="mt-1 text-sm text-darkbrown/70 max-w-md mx-auto leading-relaxed fade-in"><?= $subtitle->html() ?></p>
  <?php endif ?>
</div>
