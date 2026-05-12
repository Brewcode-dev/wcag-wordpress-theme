# WCAG WP

A WordPress theme designed to be **fully WCAG 2.1 Level AA compliant** out of the box, with Elementor compatibility and dedicated WCAG-aware Elementor widgets.

## Highlights

- **Semantic HTML5 + ARIA landmarks** in every template (`<header role="banner">`, `<main>`, `<nav aria-label>`, `<aside role="complementary">`, `<footer role="contentinfo">`).
- **Skip link** to main content (WCAG 2.4.1).
- **Visible focus indicator** — 3 px outline + 2 px offset, never removed (WCAG 2.4.7).
- **Sufficient color contrast** — default palette ≥ 4.5:1 (WCAG 1.4.3). Real-time contrast badges in the Customizer.
- **Resizable text up to 200%** without loss of content (WCAG 1.4.4). Reflow works at 320 CSS px (WCAG 1.4.10).
- **Minimum 44 × 44 px tap targets** on every interactive control.
- **prefers-reduced-motion** respected globally + user-toggleable "Pause animations" in the widget.
- **Accessible nav walker** — submenus use `aria-expanded`/`aria-controls` toggle buttons with stable IDs.
- **`aria-current="page"`** automatically applied to the current menu item.
- **Search form** with explicit `<label>` and `role="search"`.
- **Comment form** with proper `<label>`, `aria-required`, and `autocomplete` tokens (WCAG 1.3.5).
- **Frontend Accessibility Widget** (toggleable in Customizer):
    - Increase / decrease / reset text size (up to 200%).
    - High contrast, negative contrast, grayscale.
    - Underline links.
    - Stronger focus indicator.
    - Readable font (OpenDyslexic fallback chain).
    - Pause animations.
    - Larger cursor.
    - Reset all. Settings persist via `localStorage`.
- **Customizer WCAG panel**:
    - Color controls with live contrast ratio badges (AAA / AA / AA Large / Fail).
    - Typography: base font size (14–22 px), line-height (1.4–2.0).
    - Skip link text override (selective refresh).
- **Custom Elementor widgets** in the "WCAG (accessible)" category:
    - WCAG Button (real `<a>` or `<button>`, optional `aria-label`).
    - WCAG Accordion (WAI-ARIA APG pattern, keyboard support).
    - WCAG Tabs (manual activation pattern, keyboard support).
    - WCAG Image (forces alt OR explicit decorative flag).
    - WCAG Heading (separate semantic level from visual size).
    - WCAG Skip link.
- **Override CSS** that hardens default Elementor widgets to meet AA (focus rings, tap target size, etc.).

## Installation

Theme lives in `wp-content/themes/wcag-wp/`. With the provided Docker Compose stack:

```bash
docker compose up -d
```

Then open `http://localhost:8088` and complete the WordPress installer. Activate **WCAG WP** under *Appearance → Themes*.

## File layout

```
wp-content/themes/wcag-wp/
├── style.css
├── functions.php
├── index.php
├── header.php
├── footer.php
├── sidebar.php
├── single.php
├── page.php
├── archive.php
├── search.php
├── 404.php
├── comments.php
├── template-parts/
│   ├── content.php
│   ├── content-single.php
│   ├── content-page.php
│   ├── content-search.php
│   └── content-none.php
├── inc/
│   ├── setup.php
│   ├── enqueue.php
│   ├── template-tags.php
│   ├── template-functions.php
│   ├── nav-walker.php
│   ├── customizer.php
│   ├── accessibility-widget.php
│   ├── elementor-compat.php
│   └── elementor-widgets/
│       ├── class-wcag-elementor-base.php
│       ├── class-button.php
│       ├── class-accordion.php
│       ├── class-tabs.php
│       ├── class-image.php
│       ├── class-skip-link.php
│       └── class-heading.php
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   ├── accessibility.css
│   │   ├── elementor-overrides.css
│   │   ├── editor.css
│   │   └── admin.css
│   └── js/
│       ├── skip-link-focus-fix.js
│       ├── navigation.js
│       ├── accessibility-widget.js
│       ├── customizer-preview.js
│       └── customizer-controls.js
└── languages/
```

## WCAG 2.1 AA — success-criteria coverage

| Principle | SC | Implementation |
| --- | --- | --- |
| Perceivable | 1.1.1 Non-text content | `wcag_wp_filter_img_attributes()` enforces alt or `role="presentation"`; WCAG Image widget requires an explicit decorative flag. |
| | 1.3.1 Info & Relationships | Semantic HTML5 landmarks, `<label>` for every input, headings render with correct hierarchy, ARIA used only where native elements don't suffice. |
| | 1.3.2 Meaningful sequence | DOM order matches visual order; templates avoid CSS reordering for content. |
| | 1.3.5 Identify input purpose | `autocomplete` tokens on comment form (`name`, `email`, `url`). |
| | 1.4.1 Use of color | Links are underlined by default; status uses both color and icon/text. |
| | 1.4.3 Contrast (min) | Default palette ≥ 4.5:1. Customizer shows live ratio badges. |
| | 1.4.4 Resize text | All sizes in `rem`/`em`; widget allows 200% scaling. |
| | 1.4.10 Reflow | Layouts wrap at 320 CSS px (no horizontal scrolling). |
| | 1.4.11 Non-text contrast | 2 px borders on inputs/buttons; 3 px focus outline against background. |
| | 1.4.12 Text spacing | Tokens computed via CSS vars, not enforced with `!important`. |
| | 1.4.13 Content on hover/focus | Submenus dismissible via Esc, hover and focus alike. |
| Operable | 2.1.1 Keyboard | All interactive controls are native or fully keyboard-operable. |
| | 2.1.2 No keyboard trap | No focus-trapping logic except modal (none here). |
| | 2.4.1 Bypass blocks | Skip link to `#main`. |
| | 2.4.2 Page titled | `add_theme_support('title-tag')` + filter that guarantees a non-empty title. |
| | 2.4.3 Focus order | Order matches DOM; submenu triggers come after their link. |
| | 2.4.4 Link purpose | Read-more links use `aria-label` with the post title; "(opens in new window)" announced for `_blank`. |
| | 2.4.5 Multiple ways | Search form + nav menu + 404 page recent posts list. |
| | 2.4.6 Headings & labels | Form labels and section headings on every screen. |
| | 2.4.7 Focus visible | 3 px outline outlined globally. |
| | 2.5.3 Label in name | Visible text matches accessible name on every WCAG widget. |
| | 2.5.5 Target size (AAA, supported) | `--wcag-target-min: 44px` on all controls. |
| Understandable | 3.1.1 Language of page | `<html lang>` set by WP. |
| | 3.1.2 Language of parts | `post_lang` post-meta filter appends `lang=""` to `<html>` per post when set. |
| | 3.2.1 / 3.2.2 On focus / on input | No automatic context changes. |
| | 3.2.3 Consistent navigation | Same nav rendered from one menu location across templates. |
| | 3.3.1 / 3.3.2 / 3.3.3 / 3.3.4 Error identification & prevention | Comment form fields use `aria-required`, `required`, and `aria-describedby`. |
| Robust | 4.1.1 Parsing | Valid HTML5, unique IDs via `wp_unique_id`. |
| | 4.1.2 Name, role, value | All custom widgets use ARIA APG patterns. |
| | 4.1.3 Status messages | Search result count uses `role="status"`; widget feedback in `aria-live="polite"`. |

## Automated audit results

Verified on home, single post with comments, search results and a regular page.

| Page | Lighthouse a11y | axe-core (WCAG 2.0/2.1 A+AA) | pa11y (WCAG2AA) |
|---|---|---|---|
| `/` | **100/100** (27 passed) | **0 violations** | **0 issues** |
| `/hello-world/` | **100/100** (31 passed) | **0 violations** | **0 issues** |
| `/?s=hello` | **100/100** (27 passed) | **0 violations** | **0 issues** |
| `/sample-page/` | **100/100** (24 passed) | **0 violations** | **0 issues** |

12 audits × 4 pages = **0 errors**.

## Testing recommendation

Automated tools detect ~30–50% of SC. Combine the audits above with:

- Manual keyboard-only walkthrough (Tab order, Esc on dialogs, submenu toggles).
- Screen reader: NVDA + Firefox, VoiceOver + Safari.
- `WAVE` (WebAIM) for an additional second opinion.

## License

GPL-2.0-or-later.
