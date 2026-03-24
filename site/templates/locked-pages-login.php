<?php snippet('header') ?>

<main class="flex-1 flex items-center justify-center px-4 py-8">
  <div class="w-full max-w-sm">

    <h1 class="text-xl font-semibold mb-6">This page is protected</h1>

    <form method="post" class="flex flex-col gap-4">
      <div class="flex flex-col gap-1">
        <label for="password" class="text-sm">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          value="<?= esc(get('password', '')) ?>"
          class="border px-3 py-2 text-sm rounded focus:outline-none focus:ring-1 focus:ring-current"
          autofocus
        >
        <?php if ($error): ?>
          <p class="text-sm text-red-600"><?= $error ?></p>
        <?php endif ?>
      </div>

      <input type="hidden" name="csrf" value="<?= csrf() ?>">

      <button type="submit" class="border px-4 py-2 text-sm hover:bg-black hover:text-white transition-colors">
        Open page
      </button>
    </form>

  </div>
</main>

<?php snippet('footer') ?>
