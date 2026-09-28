<?php snippet('header') ?>

<main class="flex-1 w-full">

  <div class="py-16 text-center border-b border-darkbrown/15 fade-in">
    <h1 class="font-serif text-3xl tracking-wide fade-in"><?= $page->title() ?></h1>
    <?php if ($page->tagline()->isNotEmpty()): ?>
      <p class="mt-1 text-sm text-darkbrown/70 max-w-md mx-auto leading-relaxed fade-in"><?= $page->tagline()->html() ?></p>
    <?php endif ?>
  </div>

  <?php $contacts = $page->contacts()->toStructure() ?>
  <?php if ($contacts->isNotEmpty()): ?>
    <div class="max-w-xl mx-auto px-4 py-16 flex flex-col gap-4">
      <?php $i = 0; foreach ($contacts as $contact): ?>
        <?php
          $icon     = $contact->icon()->toFile();
          $url      = $contact->link()->isNotEmpty() ? $contact->link()->toUrl() : null;
          $external = $url && str_starts_with($url, 'http') && !str_starts_with($url, $site->url());
          $tag      = $url ? 'a' : 'div';
        ?>
        <div class="fade-in" style="transition-delay: <?= ($i % 4) * 100 ?>ms">
        <<?= $tag ?>
          <?php if ($url): ?>
            href="<?= $url ?>"
            <?= $external ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
          <?php endif ?>
          class="group flex items-center gap-5 bg-white/50 border border-white/60 p-6 transition-all duration-300<?= $url ? ' hover:bg-white/70 hover:text-terracotta hover:shadow-lg hover:shadow-darkbrown/10 hover:-translate-y-0.5' : '' ?>"
        >
          <?php if ($icon): ?>
            <span class="contact-icon shrink-0 w-8 h-8 flex items-center justify-center">
              <?php if ($icon->extension() === 'svg'): ?>
                <?= svg($icon) ?>
              <?php else: ?>
                <img src="<?= $icon->resize(96)->url() ?>" alt="" class="w-8 h-8 object-contain">
              <?php endif ?>
            </span>
          <?php endif ?>

          <span class="flex-1 text-sm leading-relaxed"><?= $contact->description()->html() ?></span>

          <?php if ($url): ?>
            <span class="shrink-0 text-darkbrown/50 group-hover:text-terracotta group-hover:translate-x-1 transition-all" aria-hidden="true">→</span>
          <?php endif ?>
        </<?= $tag ?>>
        </div>
      <?php $i++; endforeach ?>
    </div>
  <?php endif ?>

</main>

<?php snippet('footer') ?>
