<footer class="border-t border-neutral-200 mt-auto">
  <div class="max-w-5xl mx-auto px-4 py-8 text-xs tracking-widest uppercase text-neutral-400 text-center">
    <?php if ($site->email()->isNotEmpty()): ?>
      <a href="mailto:<?= $site->email() ?>" class="hover:text-neutral-800 transition-colors"><?= $site->email() ?></a>
    <?php endif ?>
  </div>
</footer>

<?php snippet('seo/schemas') ?>
<script type="module" src="<?= url('assets/js/main.js') ?>"></script>
</body>
</html>
