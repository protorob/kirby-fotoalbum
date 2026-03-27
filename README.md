# kirby-fotoalbum

A Kirby CMS project for photographer client galleries with per-gallery password protection and image selection via email.

## Requirements

- PHP 8.3+ with extensions: `mbstring`, `xml`, `gd`, `curl`, `zip`, `intl`
- Composer

## Installing PHP 8.3 (Ubuntu / WSL2)

```bash
sudo apt update && sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install -y php8.3 php8.3-cli php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-intl
```

Make `php` point to 8.3 if needed:

```bash
sudo update-alternatives --set php /usr/bin/php8.3
```

## Installing Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

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

## Deploying to a live server

A deploy script is included to push the site to any server via SSH/rsync.

### First-time setup (local)

```bash
cp deploy-example.sh deploy.sh
chmod +x deploy.sh
```

Open `deploy.sh` and fill in your server details:

```bash
SSH_USER="your-user"
SSH_HOST="your-server.com"
REMOTE_PATH="/var/www/your-site"
SSH_PORT=22
PHP_BIN="/usr/bin/php"       # path to PHP on the server
COMPOSER_BIN="~/composer"   # path to Composer on the server
```

`deploy.sh` is gitignored — your credentials will never be committed.

### First-time setup (server)

`vendor/` and `kirby/` are never uploaded — Composer runs on the server after each deploy so dependencies are always built for the server's PHP version. You need Composer installed on the server once:

```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar ~/composer
```

On **DreamHost** the default CLI `php` may differ from the web PHP version. Find the right binary:

```bash
ls /usr/local/php*/bin/php
```

Then set `PHP_BIN` in `deploy.sh` accordingly (e.g. `/usr/local/php83/bin/php`).

Also create Kirby's writable directories if they don't exist yet:

```bash
mkdir -p ~/your-site/site/cache ~/your-site/site/sessions ~/your-site/site/accounts
```

### Running a deploy

```bash
./deploy.sh
```

This will:
1. Run `bun run build` to compile CSS and JS
2. Upload all required files via rsync (only changed files are transferred)
3. Run `composer install` on the server to build `vendor/` and `kirby/`
4. Set correct write permissions on Kirby's data directories

### What is excluded from the upload

- `.git`, `.gitignore`, `node_modules`, `src/`
- `vendor/`, `kirby/` — installed on the server via Composer
- `deploy.sh`, `deploy-example.sh`, `CLAUDE.md`, `PLAN.md`
- `site/accounts`, `site/sessions`, `site/cache`, `logs/`

### Before the first deploy

- Set `'fotoalbum.email.debug' => false` in `site/config/config.php`
- Configure your SMTP transport in the same file
- Make sure PHP 8.1+ is installed on the server with extensions: `mbstring`, `gd` (or `imagick`), `curl`, `zip`
- For Nginx servers, add a rewrite rule to route all requests through `index.php` (Apache is handled automatically via Kirby's `.htaccess`)

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

## Private galleries and image selection

### 1. Password-protect a gallery

In the Panel, open a gallery → **Security** tab. Enable **Page Protection** and set a password. Share the URL and password with the client directly.

### 2. Open the gallery for image selection

On the same **Security** tab, enable the **Image selection** toggle. This shows a selection UI to anyone who has access to the gallery. The client can click images to select them, optionally add a name, email and message, then submit.

### 3. After the client submits

- The **Image selection** toggle is automatically disabled, preventing further submissions.
- The submitted images are highlighted in the gallery (full opacity + checkmark). Non-selected images are dimmed.
- A message is shown prompting the client to contact the photographer to request a new selection.
- The submission is logged in the **Submissions** tab of the gallery in the Panel (date, name, email, message, list of selected filenames).

### 4. To allow a new selection

Re-enable the **Image selection** toggle in the Security tab. Previous submission logs are preserved.

### 5. Email configuration

Outgoing emails use the **Contact email** set in the Panel under **Site settings**.

In `site/config/config.php`:

```php
// Set to true to skip sending and write to logs/email-debug.log instead
'fotoalbum.email.debug' => false,

// Sender address (defaults to noreply@yourdomain.com)
'fotoalbum.email.from' => 'noreply@yourdomain.com',

// Switch to SMTP for production
'email' => [
    'transport' => [
        'type'     => 'smtp',
        'host'     => 'smtp.yourdomain.com',
        'port'     => 587,
        'security' => 'tls',
        'auth'     => true,
        'username' => 'you@yourdomain.com',
        'password' => 'yourpassword',
    ],
],
```

During local development, keep `fotoalbum.email.debug` set to `true` and inspect `logs/email-debug.log` to verify submissions without needing a working mail server.

## Gallery lightbox

Clicking any image in a gallery opens a fullscreen lightbox powered by [PhotoSwipe v5](https://photoswipe.com/).

**Navigation**
- Previous / next image: click the arrows or use the keyboard arrow keys
- Close: click the `×` button or press `Escape`

**Zoom**
- Double-click to zoom in / out
- Pinch-to-zoom on mobile / trackpad

**Animations**
- Opening and closing animate from/to the thumbnail position in the grid

**Image selection (private galleries)**
- When selection is open, a **Select** button appears at the bottom of the lightbox
- Clicking it toggles the image's checkbox in the underlying form
- The button updates to **✓ Selected** when the image is selected

## Private gallery layout

When a gallery has password protection (`lockedPagesEnable`) enabled — whether or not image selection is currently open — it uses a uniform square-grid layout instead of masonry. This keeps the visual experience consistent for the client across both the selection and review phases.

Public galleries always use the masonry layout.

## SEO

SEO is handled by the [kirby-seo](https://github.com/tobimori/kirby-seo) plugin (`tobimori/kirby-seo`). It provides an **SEO tab** on every page and on the site, with fields for:

- Meta title and description
- Open Graph image and tags
- Twitter Card tags
- Robots directives (per-page noindex, nofollow, etc.)
- JSON-LD / Schema.org markup

The plugin also automatically exposes:
- `/sitemap.xml` — pages are included only if their robots settings allow indexing
- `/robots.txt` — generated from panel settings

The SEO `<head>` tags are output in `site/snippets/header.php` via `snippet('seo/head', slots: true)`. The stylesheet is passed as the slot content so that priority tags (`<title>`, canonical) render before it. JSON-LD schemas are output in `footer.php` via `snippet('seo/schemas')`.

No extra configuration is required for basic use. To disable the sitemap, set `'tobimori.seo.sitemap.active' => false` in `site/config/config.php`.

## Notes

- `vendor/` and `kirby/` are not committed — they are restored by `composer install`
- Never commit `site/accounts/`, `site/sessions/`, or `site/cache/`
- `logs/` is gitignored — it only contains local debug output
