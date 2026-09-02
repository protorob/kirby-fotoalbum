<?php
/** @var \Kirby\Cms\Block $block */
$code = trim((string) $block->code());
?>
<?php if ($code !== ''): ?>
<div class="not-prose">
  <?= $code ?>
</div>
<?php endif ?>
