<?php
// Overrides the @theme colour variables from main.css with the values saved
// in site.yml. Every bg-cream / text-darkbrown(/xx) / border-darkbrown utility
// reads these variables, so this recolours the whole site without a rebuild.
$vars = array_filter([
    '--color-cream'     => site()->bgColor()->value(),
    '--color-darkbrown' => site()->textColor()->value(),
], fn ($value) => preg_match('/^#[0-9a-fA-F]{6}$/', (string) $value));
?>
<style id="theme-colors"><?php if ($vars): ?>:root{<?php foreach ($vars as $name => $value): ?><?= $name ?>:<?= $value ?>;<?php endforeach ?>}<?php endif ?></style>
