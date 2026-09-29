# kirby-fotoalbum — Claude Code context

## What this project is

A Kirby CMS site for a photographer. Clients receive a private, password-protected gallery link and can select images they want. The selection is emailed to the photographer and logged in the Panel. After submission, selection is automatically disabled until the photographer re-enables it.

## Tech stack

- **Kirby CMS 5** — flat-file CMS, no database
- **Tailwind CSS v4** via `@tailwindcss/vite`
- **Vite** for asset bundling (entry: `src/main.js`, output: `assets/`)
- **npm** as package manager and script runner
- **Splide.js** — hero slideshow on home page (fade, autoplay, no arrows/pagination); services carousel on home page (loop, perPage 3→2→1)
- **PhotoSwipe v5** — lightbox for gallery images
- **`@tailwindcss/typography`** — loaded via `@plugin` in `main.css`; used for `.prose` blocks on service detail pages

## Running locally

```bash
# Terminal 1 — PHP dev server
composer start

# Terminal 2 — CSS/JS watch
npm run dev
```

`composer start` serves on `http://localhost:8000` (defined in `composer.json`'s `start` script — not 8888). Panel: `http://localhost:8000/panel`

Always run `npm run build` after changing CSS classes or JS.

## Project structure

```
site/
  blueprints/pages/   ← Panel field definitions per template
  config/config.php   ← email transport, debug flag
  controllers/        ← PHP controllers (same name as template)
  plugins/            ← kirby-locked-pages (password protection), kirby-seo (SEO/meta), lqip (blurred image placeholders)
  snippets/           ← header.php, footer.php, page-hero.php (shared title block: title + subtitle, used by every template except home/login)
  templates/          ← one .php per page type
src/
  main.js             ← JS entry (imports main.css, scroll-aware header, mobile menu, selection counter, PhotoSwipe lightbox, Splide carousels, progressive image fade-in)
  main.css            ← @import "tailwindcss" + @plugin "@tailwindcss/typography"
assets/               ← Vite build output (gitignored)
logs/                 ← email-debug.log when debug mode is on (gitignored)
```

## Key conventions

- **Blueprint section keys must be unique** across the entire blueprint file, including across columns. Duplicate keys cause Kirby to merge sections and render fields in multiple places.
- **Column names** (`sidebar`, `main`, etc.) are fine to reuse — only section keys must be unique.
- Layout container: `max-w-5xl mx-auto px-4` — used in header, footer, and all `<main>` elements to keep everything aligned.
- Button style: `border px-4 py-2 text-sm hover:bg-black hover:text-white transition-colors`
- Input style: `border px-3 py-2 text-sm rounded focus:outline-none focus:ring-1 focus:ring-current`

## Private gallery feature

**Blueprint fields on `gallery.yml`:**
- `lockedPagesEnable` / `lockedPagesPassword` — from kirby-locked-pages plugin (Security tab)
- `selectionOpen` (toggle) — enables the image selection UI (Security tab)
- `selections` (structure) — logs of past submissions (Submissions tab)

**Flow:**
1. Admin enables `selectionOpen` in Panel
2. Client visits password-protected gallery, selects images, submits form
3. Controller (`site/controllers/gallery.php`) sends email, sets `selectionOpen = false`, appends to `selections` log
4. Gallery shows submitted images highlighted; non-selected images dimmed; contact message shown
5. Admin re-enables `selectionOpen` to allow a new round

**Layout:** while `lockedPagesEnable` is on (whether or not selection is currently open), `gallery.php` renders a uniform square-grid (`grid grid-cols-2 sm:grid-cols-3`) instead of the masonry `columns-2 sm:columns-3` layout used by public galleries — keeps the grid consistent across the selection and review phases.

**Email debug mode** (`site/config/config.php`):
- `'fotoalbum.email.debug' => true` — writes to `logs/email-debug.log` instead of sending
- Set to `false` for production and configure SMTP transport

## Known gotchas

### `$page->update()` from unauthenticated front-end context

Calling `$page->update()` in a front-end controller (e.g. after a visitor submits the gallery selection form) triggers Kirby to instantiate all fields on the page for validation — including SEO fields. The kirby-seo plugin checks `App::instance()->user()->role()` during field instantiation, which throws `Call to a member function role() on null` because no Panel user is logged in.

**Fix:** always wrap `$page->update()` in `$kirby->impersonate('kirby', ...)` when called from a front-end/unauthenticated context:

```php
$kirby->impersonate('kirby', function () use ($kirby, $page, $data) {
    $page->update($data);
});
```

This is already applied in `site/controllers/gallery.php`. Apply the same pattern anywhere else a controller needs to write content without a logged-in Panel user.

## SEO plugin (tobimori/kirby-seo)

Installed as a composer dependency (`^2.0.0-beta`). Requires Kirby 5.

- `snippet('seo/head')` in `header.php` — outputs `<title>`, meta, OG, canonical tags. The main stylesheet is loaded directly before this snippet (not via a slot) to ensure it is render-blocking from the first byte and avoids FOUC.
- `snippet('seo/schemas')` in `footer.php` — outputs JSON-LD Schema.org markup.
- Every page blueprint and `site.yml` has a `seo` tab added via `extends: seo`.
- Automatically handles `/sitemap.xml` and `/robots.txt` routes — no extra config needed.

## lqip plugin (site/plugins/lqip)

Adds a `lqip($width = 24)` file method that returns a tiny resized image inlined as a base64 data URI. Used across templates as the blurred placeholder image (`scale-110 blur-xl`, absolutely positioned behind the real `<img>`) so galleries and the hero don't pop in while photos load — paired with the `js-progressive` class and `.loaded` opacity transition set up in `main.js`.

## Site-level fields (site.yml)

- `tagline`, `about` — overlay text centered on the home page hero
- `slideshow` — files field; images used as the full-screen hero slideshow on the home page
- `email` — used as recipient for selection emails and in footer
- `logo` / `logo_light` — desktop header logo, dark-on-light and light-on-dark variants (large centered logo on the hero header)
- `logo_mobile` / `logo_mobile_light` — small header logo shown once scrolled/on mobile; falls back to `logo` / `logo_light` if not set
- `social_items` — structure field (icon file, label, url, inblank toggle); rendered as icon links in the footer

## Home page layout

`home.php` renders a full-viewport (`h-screen`) hero slideshow (Splide.js, fade mode) with `$site->tagline()`/`$site->about()` centered on top of it, followed by a conditional services section and the footer. The `home` template also has `intro` (textarea) and `hero` (single file) fields on its blueprint, but `home.php` does not currently read them.

- `header.php` takes an optional `heroHeader` param: `<?php snippet('header', ['heroHeader' => true]) ?>` (only `home.php` passes this)
- The header is `fixed`, transparent over the hero (`header--hero` class, no background/border), and gains a cream background + border once the page scrolls past 80px (`is-scrolled`, toggled in `main.js`) or the mobile menu opens
- On the hero, a large centered logo (`#logo-large`, uses `logo`/`logo_light`) appears below the nav; it fades out and the normal small header logo (`#logo-small`) fades in once scrolled — see the `header--hero`/`is-scrolled` rules in `main.css`
- Non-hero pages skip `heroHeader` and get the plain solid header plus a `<div class="h-20">` spacer since the header is `fixed`
- Splide CSS is imported as `@splidejs/splide/css/core` (minimal, no theme chrome); slide images use `absolute inset-0 object-cover` inside `position: relative` slides, with a blurred `lqip()` placeholder image (see the `lqip` plugin) underneath that fades out via the shared `js-progressive`/`.loaded` pattern used across the site

### Services section on home page

Pulled from `$site->find('servizi')->children()->listed()`. Renders conditionally:

- **≤ 3 services** → static `grid grid-cols-1 md:grid-cols-3 gap-12` (same card layout as `services.php`)
- **≥ 4 services** → Splide carousel (`#services-splide`), `type: loop`, `perPage: 3` (drops to 2 at 768px, 1 at 640px), `gap: 3rem`

Arrow styles for `#services-splide` are in `main.css`: round, `--color-darkbrown` background, cream chevrons at 0.7 opacity (1 on hover). The prev arrow needs an explicit `transform: scaleX(-1)` on its SVG — Splide's minimal `core` CSS (see Tech stack) ships no default arrow styling or mirroring, unlike its full theme CSS.

## Services pages

**Templates:** `services.php` (listing) and `service.php` (detail)

**Blueprints:** `services.yml` and `service.yml`

**`service.yml` fields:**
- `serviceDesc` (blocks) — rich content for the detail page body; rendered via `$page->serviceDesc()->toBlocks()` with `.prose` class
- `coverImage` (files, maxFiles: 1) — used as the card thumbnail in listings
- `description` (textarea) — short text shown below the card thumbnail and in the page header

**Blocks available in `serviceDesc`:** default Kirby blocks + custom `gallery` (`site/snippets/blocks/gallery.php`) and `image` (`site/snippets/blocks/image.php`) block snippets. The gallery block renders a masonry-style columns layout with PhotoSwipe lightbox support.
