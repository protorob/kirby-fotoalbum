<?php $socialItems = $site->social_items()->toStructure() ?>

<footer class="border-t border-neutral-200 mt-auto">
  <div class="max-w-5xl mx-auto px-4 py-8 text-xs tracking-widest uppercase text-neutral-400">
    <?php if ($socialItems->isNotEmpty()): ?>
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <?php if ($site->email()->isNotEmpty()): ?>
          <a href="mailto:<?= $site->email() ?>" class="hover:text-neutral-800 transition-colors"><?= $site->email() ?></a>
        <?php endif ?>
        <div class="flex items-center gap-5">
          <?php foreach ($socialItems as $item): ?>
            <?php $icon = $item->icon()->toFile() ?>
            <?php if (!$icon) continue ?>
            <a href="<?= $item->url() ?>"
               <?= $item->inblank()->isTrue() ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
               title="<?= $item->label()->html() ?>"
               class="opacity-40 hover:opacity-100 transition-opacity">
              <img src="<?= $icon->url() ?>" alt="<?= $item->label()->html() ?>" class="w-5 h-5">
            </a>
          <?php endforeach ?>
        </div>
      </div>
    <?php else: ?>
      <?php if ($site->email()->isNotEmpty()): ?>
        <div class="text-center">
          <a href="mailto:<?= $site->email() ?>" class="hover:text-neutral-800 transition-colors"><?= $site->email() ?></a>
        </div>
      <?php endif ?>
    <?php endif ?>
  </div>
</footer>

<?php snippet('seo/schemas') ?>
<script type="module" src="<?= url('assets/js/main.js') ?>"></script>
</body>
</html>
