# kirby-fotoalbum

A Kirby CMS project for photographer client galleries with per-gallery password protection and image selection via email.

## Requirements

- PHP 8.3+ with extensions: `mbstring`, `xml`, `gd`, `curl`, `zip`, `intl`
- Composer

## Setup

```bash
git clone <repo-url>
cd kirby-fotoalbum
composer install
```

## Run locally

In two separate terminals:

```bash
# Terminal 1 — PHP dev server
php -S localhost:8888 kirby/router.php

# Terminal 2 — CSS/JS watch mode
bun run dev
```

Then open `http://localhost:8888` in your browser.

The Kirby Panel is available at `http://localhost:8888/panel` — you will be prompted to create an admin account on first visit.

## Frontend build

```bash
bun run build   # production build → assets/css/ and assets/js/
bun run dev     # watch mode, rebuilds on changes
```

## Project structure

```
content/        ← pages and uploaded files
site/
  blueprints/   ← Panel field definitions
  config/       ← config.php (email, SMTP, plugin settings)
  plugins/      ← custom and third-party plugins
  templates/    ← PHP templates
  snippets/     ← reusable template partials
```

## Notes

- `vendor/` and `kirby/` are not committed — they are restored by `composer install`
- Never commit `site/accounts/`, `site/sessions/`, or `site/cache/`
- Copy `site/config/config.php` settings for SMTP and admin email before running in production
