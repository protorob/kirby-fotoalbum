# Kirby CMS - Photographer Gallery Project

## Requirements Summary
- Per-gallery password protection (custom, not kirby-password-guard)
- Image selection ("likes") by visitor
- Anonymous submission (no visitor name/email required)
- Admin receives email with list of selected filenames
- One gallery per client session

---

## Architecture

### Password Protection: `kirby-locked-pages`
Use [johannschopplich/kirby-locked-pages](https://github.com/johannschopplich/kirby-locked-pages) instead of building a custom solution.

**Why this fits perfectly:**
- Designed specifically for per-page password protection
- Uses Kirby's native session system — tracks which pages each visitor has unlocked
- Panel integration via blueprint field group: `security: fields/locked-pages`
- Customizable login template at `site/templates/locked-pages-login.php`
- Installed via Composer: `composer require johannschopplich/kirby-locked-pages`

**Config options used:**
- `slug`: URL for the login form (e.g. `"locked"`)
- `template`: custom login template name
- `error.password`: custom error message

### Gallery Page Structure
Each gallery is a Kirby page with:
- `security` field group from kirby-locked-pages (handles password in Panel)
- `images` files section
- `title` and optional `description`

### Custom Plugin: `gallery-likes`
Small focused plugin that only handles the selection + email feature:
1. Exposes `POST /api/gallery-likes` route → validates filenames → sends email

### Frontend
- `gallery.php` template: image grid with click-to-select UI
- `likes-toolbar.php` snippet: sticky bar with count + "Submit Selection" button
- Vanilla JS (no framework): toggles selection state, POSTs to API

---

## Data Flow

```
[Visitor hits /galleries/client-x]
        ↓
[Plugin checks session: is client-x unlocked?]
        ↓ NO                          ↓ YES
[Show password form]            [Render gallery]
        ↓ correct PW                  ↓
[Session: unlocked[] += client-x]  [Visitor selects images]
[Redirect back to gallery]            ↓
                              [Clicks "Send Selection"]
                                      ↓
                              [POST /api/gallery-likes
                               { gallery: "client-x",
                                 images: ["a.jpg","b.jpg"] }]
                                      ↓
                              [Plugin validates + sends email]
                                      ↓
                              [JSON: { success: true }]
                                      ↓
                              [Frontend shows confirmation]
```

---

## File Map

```
site/
  blueprints/
    pages/
      gallery.yml              ← security field group + images section
  plugins/
    kirby-locked-pages/        ← installed via Composer (handles auth)
    gallery-likes/
      index.php                ← POST route + email logic only
  templates/
    gallery.php                ← image grid + selection UI
    locked-pages-login.php     ← custom login form (overrides plugin default)
  snippets/
    likes-toolbar.php          ← sticky selection bar
  config/
    config.php                 ← admin email, smtp, locked-pages config
content/
  galleries/
    client-x/
      gallery.txt              ← Title + password managed via Panel
      photo1.jpg
      photo2.jpg
```

---

## Build Order

1. Scaffold Kirby via Composer + install `johannschopplich/kirby-locked-pages`
2. `gallery.yml` blueprint (security field group + images section)
3. `locked-pages-login.php` template (custom login form, no nav/header)
4. `gallery-likes/index.php` plugin (POST route + email logic)
5. `gallery.php` template (image grid + selection JS)
6. `likes-toolbar.php` snippet (sticky bar)
7. `config.php` (admin email, SMTP, locked-pages slug/messages)

---

## Email Output (admin receives)

```
Subject: New selection from gallery: Client X

Gallery: Client X
Date: 2026-03-23 14:32

Selected images:
- photo1.jpg
- photo3.jpg
- photo7.jpg
```

---

## Notes
- kirby-locked-pages handles all password hashing, session management, and CSRF internally — no custom auth code needed
- Panel UI: add `security: fields/locked-pages` to the blueprint to manage passwords from the Kirby admin
- Logout can be triggered with `kirby()->trigger('locked-pages.logout')` if needed
- `gallery-likes` plugin only needs to: receive POST, validate filenames against the page's actual files, send email
- Image validation in route: check submitted filenames exist in the gallery page's files collection
- SMTP config needed for email (or PHP mail() for local testing)
