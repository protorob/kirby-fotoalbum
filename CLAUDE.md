# kirby-fotoalbum — Claude Code context

## What this project is

A Kirby CMS site for a photographer. Clients receive a private, password-protected gallery link and can select images they want. The selection is emailed to the photographer and logged in the Panel. After submission, selection is automatically disabled until the photographer re-enables it.

## Tech stack

- **Kirby CMS 5** — flat-file CMS, no database
- **Tailwind CSS v4** via `@tailwindcss/vite`
- **Vite** for asset bundling (entry: `src/main.js`, output: `assets/`)
- **Bun** as package manager and script runner
- **Splide.js** — hero slideshow on home page (fade, autoplay, no arrows/pagination)
- **PhotoSwipe v5** — lightbox for gallery images

## Running locally

```bash
# Terminal 1 — PHP dev server
php -S localhost:8888 kirby/router.php

# Terminal 2 — CSS/JS watch
bun run dev
```

Panel: `http://localhost:8888/panel`

Always run `bun run build` after changing CSS classes or JS.

## Project structure

```
site/
  blueprints/pages/   ← Panel field definitions per template
  config/config.php   ← email transport, debug flag
  controllers/        ← PHP controllers (same name as template)
  plugins/            ← kirby-locked-pages (password protection), kirby-seo (SEO/meta)
  snippets/           ← header.php, footer.php
  templates/          ← one .php per page type
src/
  main.js             ← JS entry (imports main.css, mobile menu, selection counter, PhotoSwipe lightbox)
  main.css            ← @import "tailwindcss"
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

**Email debug mode** (`site/config/config.php`):
- `'fotoalbum.email.debug' => true` — writes to `logs/email-debug.log` instead of sending
- Set to `false` for production and configure SMTP transport

## SEO plugin (tobimori/kirby-seo)

Installed as a composer dependency (`^2.0.0-beta`). Requires Kirby 5.

- `snippet('seo/head')` in `header.php` — outputs `<title>`, meta, OG, canonical tags. The main stylesheet is loaded directly before this snippet (not via a slot) to ensure it is render-blocking from the first byte and avoids FOUC.
- `snippet('seo/schemas')` in `footer.php` — outputs JSON-LD Schema.org markup.
- Every page blueprint and `site.yml` has a `seo` tab added via `extends: seo`.
- Automatically handles `/sitemap.xml` and `/robots.txt` routes — no extra config needed.

## Site-level fields (site.yml)

- `tagline`, `about` — shown on home page
- `slideshow` — files field; images used as the full-screen hero slideshow on the home page
- `email` — used as recipient for selection emails and in footer
- `logo` — shown in header (falls back to site title text)
- `social_items` — structure field (icon file, label, url, inblank toggle); rendered as icon links in the footer

## Home page layout

The home page uses a full-viewport hero slideshow (Splide.js, fade mode) that fills the space between header and footer with no scrollbar.

- `header.php` accepts an optional `$bodyClass` variable on `<body>`: `<?php snippet('header', ['bodyClass' => 'h-svh overflow-y-auto']) ?>`
- `h-svh` locks the body to the small viewport height; `overflow-y-auto` allows scroll if accessibility zoom causes overflow
- `<main>` uses `flex-1 min-h-0` to fill remaining space between header and footer
- Splide CSS is imported as `@splidejs/splide/css/core` (minimal, no theme chrome); slide images use `absolute inset-0 object-cover` inside `position: relative` slides
