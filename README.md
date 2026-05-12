# WCAG WP — WordPress theme + Docker dev environment

Full WCAG 2.1 **Level AA** compliant WordPress theme. Hybrid: classic PHP templates + Elementor compatibility + custom WCAG-aware Elementor widgets.

## Quick start

```bash
# Spin up MariaDB + WordPress (port 8088) + wp-cli helper
docker compose up -d

# First run: complete the WP installer at http://localhost:8088 (any DB values are pre-configured)
# Then activate "WCAG WP" under Appearance → Themes.
```

Or fully automated (the theme is auto-installed and activated):

```bash
docker compose up -d
./scripts/bootstrap.sh
```

After that visit:

- Frontend: <http://localhost:8088>
- Admin: <http://localhost:8088/wp-admin> (user `admin`, password `admin`)

Theme docs: `wp-content/themes/wcag-wp/readme.md`.

## Layout

```
wcag-wp/
├── docker-compose.yml
├── scripts/bootstrap.sh
├── README.md
└── wp-content/themes/wcag-wp/   ← the theme
```

The theme directory is bind-mounted into the WordPress container, so edits on disk show up immediately at <http://localhost:8088>.

## Useful commands

```bash
# Tail WordPress / PHP logs
docker compose logs -f wordpress

# Run WP-CLI
docker compose exec wpcli wp --info
docker compose exec wpcli wp plugin install elementor --activate
docker compose exec wpcli wp theme activate wcag-wp

# Stop & remove
docker compose down            # keeps DB
docker compose down -v         # removes DB + WP files volume
```

## What's inside the theme

- Skip link, ARIA landmarks, accessible nav walker, accessible search/comments forms.
- WCAG-compliant defaults: ≥4.5:1 contrast, 44 px tap targets, 3 px focus ring, reflow @ 320 px, `prefers-reduced-motion`.
- **Customizer → WCAG accessibility** panel: colors with live AA/AAA contrast badges, typography, skip-link text, widget toggle.
- **Frontend accessibility widget**: text size up to 200%, contrast modes, underline links, stronger focus, readable font, pause motion, larger cursor — persisted in `localStorage`.
- **Custom Elementor widgets** (category "WCAG"): Button, Accordion (APG pattern), Tabs (APG pattern), Image (forces alt or decorative flag), Heading (semantic ≠ visual), Skip link.
- **Hardening CSS** for default Elementor widgets so they still meet AA without rebuilding pages.

Full SC coverage table: see `wp-content/themes/wcag-wp/readme.md`.

## Automated audit results

Verified against three independent engines on every standard template (home, single post with comments, search results, regular page).

| Page | Lighthouse a11y | axe-core (WCAG 2.0/2.1 A + AA) | pa11y (WCAG2AA htmlcs) |
|---|---|---|---|
| `/` (home) | **100/100** — 27 audits passed | **0 violations** | **0 issues** |
| `/hello-world/` (single + comments) | **100/100** — 31 audits passed | **0 violations** | **0 issues** |
| `/?s=hello` (search) | **100/100** — 27 audits passed | **0 violations** | **0 issues** |
| `/sample-page/` (page) | **100/100** — 24 audits passed | **0 violations** | **0 issues** |

That's **12 audits across 4 pages — 0 errors total**.

### What Lighthouse verified (passing audits)

`aria-allowed-attr`, `aria-conditional-attr`, `aria-deprecated-role`, `aria-hidden-body`, `aria-hidden-focus`, `aria-prohibited-attr`, `aria-required-attr`, `aria-roles`, `aria-valid-attr`, `aria-valid-attr-value`, `button-name`, **`color-contrast`**, `document-title`, `heading-order`, `html-has-lang`, `html-lang-valid`, `label`, `label-content-name-mismatch`, `landmark-one-main`, `link-in-text-block`, `link-name`, `list`, `listitem`, `meta-viewport`, `skip-link`, `tabindex`, **`target-size`**.

### Reproduce locally

```bash
# Lighthouse
npx lighthouse http://localhost:8088/ --only-categories=accessibility \
  --chrome-flags="--headless=new --no-sandbox --disable-gpu" --view

# axe-core CLI
npx @axe-core/cli http://localhost:8088/ \
  --tags wcag2a,wcag2aa,wcag21a,wcag21aa \
  --chrome-path /usr/bin/google-chrome \
  --chrome-options="headless,no-sandbox,disable-gpu" --exit

# pa11y
npx pa11y http://localhost:8088/ --standard WCAG2AA --runner htmlcs
```

Note: automated tools catch only ~30–50% of WCAG 2.1 SC. Manual keyboard walkthrough and screen-reader testing (NVDA + Firefox, VoiceOver + Safari) is still required for full conformance, and editorial SCs (alt text quality, link purpose, captions, heading hierarchy in content) remain the responsibility of content authors.
