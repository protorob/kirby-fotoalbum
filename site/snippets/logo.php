<?php
/**
 * Site logo.
 *
 * SVG logos are inlined with fill="currentColor", so they follow the header's
 * text colour (darkbrown / cream over the hero) and the theme-colors picker.
 * Raster logos fall back to a dark/light <img> pair crossfaded via the
 * .logo-dark-img / .logo-light-img rules in main.css.
 *
 * @var \Kirby\Cms\File|null $logo      dark variant (used for SVG)
 * @var \Kirby\Cms\File|null $logoLight light variant (raster only)
 * @var string               $class     size classes, e.g. 'h-9 w-auto'
 * @var string               $textClass classes for the text fallback
 */
$logo      = $logo ?? null;
$logoLight = $logoLight ?? null;
$class     = $class ?? '';
$textClass = $textClass ?? 'font-serif text-xl tracking-widest uppercase';
$alt       = site()->title()->esc();
$svgFile   = $logo?->extension() === 'svg' ? $logo : ($logoLight?->extension() === 'svg' ? $logoLight : null);
?>
<?php if ($svgFile && $svg = svg($svgFile)): ?>
  <?php
  // Strip Illustrator leftovers: its global <style> (.st0{fill:…}) would leak
  // across the page and duplicate ids would clash when two logos are inlined
  $svg = trim(preg_replace([
      '/<\?xml.*?\?>/s',
      '/<!--.*?-->/s',
      '/<style\b.*?<\/style>/s',
      '/\s(class|id)="[^"]*"/',
  ], '', $svg));

  // Rebuild the root tag: keep only viewBox, add sizing + currentColor.
  // Explicit width/height from the viewBox so `h-* w-auto` keeps the ratio.
  echo preg_replace_callback('/<svg\b[^>]*>/', function ($tag) use ($class, $alt) {
      preg_match('/viewBox="([^"]*)"/', $tag[0], $vb);
      $box  = preg_split('/[\s,]+/', trim($vb[1] ?? ''));
      $size = count($box) === 4 ? ' width="' . $box[2] . '" height="' . $box[3] . '"' : '';
      return '<svg xmlns="http://www.w3.org/2000/svg"'
          . (isset($vb[1]) ? ' viewBox="' . $vb[1] . '"' : '')
          . $size . ' class="' . $class . '" fill="currentColor" role="img" aria-label="' . $alt . '">';
  }, $svg, 1);
  ?>
<?php elseif ($logo || $logoLight): ?>
  <div class="relative flex items-center">
    <?php if ($logoLight): ?>
      <img src="<?= $logoLight->url() ?>" alt="<?= $alt ?>" class="logo-light-img <?= $class ?>">
    <?php endif ?>
    <?php if ($logo): ?>
      <img src="<?= $logo->url() ?>" alt="<?= $alt ?>" class="logo-dark-img <?= $class ?><?= $logoLight ? ' absolute top-0 left-0' : '' ?>">
    <?php endif ?>
  </div>
<?php else: ?>
  <span class="<?= $textClass ?>"><?= $alt ?></span>
<?php endif ?>
