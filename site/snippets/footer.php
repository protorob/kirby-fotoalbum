<footer class="border-t mt-auto">
  <div class="max-w-5xl mx-auto px-4 py-6 text-sm text-gray-500">
    <?php if ($site->email()->isNotEmpty()): ?>
      <a href="mailto:<?= $site->email() ?>" class="hover:underline"><?= $site->email() ?></a>
    <?php endif ?>
  </div>
</footer>

<?php snippet('seo/schemas') ?>
<script type="module" src="<?= url('assets/js/main.js') ?>"></script>
</body>
</html>
