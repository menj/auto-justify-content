# Auto Justify Content

**Version:** 3.0.0
**Requires WordPress:** 5.6+
**Requires PHP:** 7.4+
**License:** GPLv2 or later
**Author:** [MENJ](https://github.com/menj)
**Plugin URI:** https://github.com/menj/auto-justify-content

Professional typography toolkit for WordPress. Automatic text justification and decorative drop caps for posts, pages, and Elementor content.

---

## Requirements

| Requirement | Minimum |
|---|---|
| WordPress | 5.6 |
| PHP | 7.4 |
| MySQL | 5.6 / MariaDB 10.1 |

PHP 7.4 is required for typed class properties (`private array`, `private const`) used throughout the plugin class.

---

## Installation

### Via WordPress Admin

1. Download the `.zip` from the [releases page](https://github.com/menj/auto-justify-content/releases).
2. Go to **Plugins → Add New → Upload Plugin**.
3. Upload the `.zip` and click **Install Now**.
4. Activate the plugin.
5. Configure at **Settings → Auto Justify**.

### Via FTP / File Manager

1. Extract the zip and upload the `auto-justify-content/` folder to `/wp-content/plugins/`.
2. Activate from **Plugins → Installed Plugins**.

### Via WP-CLI

```bash
wp plugin install auto-justify-content --activate
```

---

## File Structure

```
auto-justify-content/
├── auto-justify-content.php      # Main plugin class (AutoJustifyContent)
├── uninstall.php                 # Cleans up all DB options on uninstall
├── readme.txt                    # WordPress.org repository readme
├── README.md                     # This file — technical documentation
├── CHANGELOG.md                  # Full version history
├── UPGRADING.md                  # Upgrade notes and future roadmap
├── assets/
│   ├── css/admin.css             # Settings page styles (includes colour picker overrides)
│   └── js/admin.js               # Settings page interactions and live preview
├── templates/
│   └── settings-page.php         # Settings page HTML template
└── languages/
    └── auto-justify-content.pot  # Translation template
```

---

## Database Options

All options are stored in the WordPress `wp_options` table. No custom tables are created.

### Justification

| Option Key | Type | Default | Description |
|---|---|---|---|
| `ajc_enabled` | boolean | `true` | Master justification on/off toggle |
| `ajc_scope` | string | `blog_only` | `blog_only` or `entire_site` |
| `ajc_hyphen` | boolean | `true` | Enable CSS hyphenation |
| `ajc_mobile` | boolean | `false` | Wrap justification in `@media (min-width: 768px)` |
| `ajc_fallback` | boolean | `false` | Add `article p/li` fallback selectors |
| `ajc_exclude` | string | `.elementor-testimonial` | Comma-separated CSS selectors excluded from all typography features |

### Drop Cap

| Option Key | Type | Default | Description |
|---|---|---|---|
| `ajc_dc_enabled` | boolean | `false` | Master drop cap on/off toggle |
| `ajc_dc_style` | string | `drop` | `drop` (descends) or `raised` (sits on baseline) |
| `ajc_dc_lines` | integer | `3` | Line span: `2`, `3`, or `4` |
| `ajc_dc_mobile` | boolean | `false` | Wrap drop cap in `@media (min-width: 768px)` |
| `ajc_dc_font` | string | `playfair` | Font key from `get_drop_cap_fonts()` registry |
| `ajc_dc_custom_font` | string | `''` | Raw CSS font-family value when `ajc_dc_font` is `custom` |
| `ajc_dc_color` | string | `#1e293b` | Hex colour for the drop cap letter |

All 13 options are removed cleanly on plugin uninstall via `uninstall.php`.

---

## CSS Output

The plugin uses `wp_add_inline_style()` throughout — no external stylesheets are loaded by the plugin itself. CSS is generated in PHP and injected inline on each page load.

### Justification (`ajc-justify` handle)

Enqueued when `ajc_enabled` is true and the current page matches the configured scope. `blog_only` fires only on `is_singular('post')`.

**Core selectors (always):**
```css
.entry-content p, .entry-content li,
.wp-block-post-content p, .wp-block-post-content li,
.elementor-widget-text-editor p, .elementor-widget-text-editor li,
.elementor-widget-theme-post-content p, .elementor-widget-theme-post-content li
```

**With `ajc_fallback` enabled, additionally:**
```css
article p, article li
```

**With `ajc_hyphen` enabled:**
```css
-webkit-hyphens: auto; -ms-hyphens: auto; hyphens: auto; overflow-wrap: break-word;
```

**With `ajc_mobile` enabled, all CSS is wrapped in:**
```css
@media (min-width: 768px) { ... }
```

### Drop Cap (`ajc-dropcap` handle)

Enqueued when `ajc_dc_enabled` is true, subject to the same scope rules as justification.

Targets `::first-letter` on the first paragraph in standard WordPress content wrappers, plus a `.ajc-drop-cap` class used by the `[dropcap]` shortcode:

```css
.entry-content > p:first-of-type::first-letter,
.wp-block-post-content > p:first-of-type::first-letter,
... { font-family: ...; font-size: ...; color: ...; float: left; ... }

span.ajc-drop-cap { /* same rules, for shortcode use */ }
```

Font sizes by line span: 2 lines → `2.8em`, 3 lines → `4.0em`, 4 lines → `5.4em`.

Drop style uses `line-height: 0.83`, raised style uses `line-height: 1.0`.

### Google Fonts (frontend)

When a Google Font is selected, the plugin enqueues it on the frontend via:
```php
wp_enqueue_style( 'ajc-dropcap-font', 'https://fonts.googleapis.com/css2?family=...' );
```
Only the selected font is loaded. If `inherit` or `custom` is selected, no external request is made.

### Exclusions

The `ajc_exclude` option applies to both justification and drop caps. Each excluded selector receives:
```css
{selector}, {selector} p, {selector} li {
  text-align: inherit !important;
  hyphens: manual !important;
}
```

---

## Shortcode

```
[dropcap]Word[/dropcap]
```

Wraps the first character of the enclosed text in `<span class="ajc-drop-cap">`. Works in the Classic Editor, text widgets, and anywhere WordPress shortcodes are processed.

The shortcode applies drop cap styling whether or not `ajc_dc_enabled` is on. If drop caps are disabled globally, a minimal inline style is output so the shortcode still renders visibly.

---

## Hooks & Filters

### `ajc_drop_cap_fonts`

Filters the curated font registry before it is used anywhere (settings page dropdown, CSS generation, admin JS data). Use this to add custom fonts or build add-on font packs.

```php
add_filter( 'ajc_drop_cap_fonts', function( array $fonts ): array {
    $fonts['my_font'] = [
        'label'  => 'My Custom Font',
        'google' => null,             // null = no Google Fonts request
        'stack'  => '"My Custom Font", serif',
    ];
    return $fonts;
} );
```

To add a Google Font:
```php
$fonts['eb_garamond'] = [
    'label'  => 'EB Garamond',
    'google' => 'EB+Garamond:wght@700',  // passed directly to the Google Fonts API URL
    'stack'  => '"EB Garamond", Georgia, serif',
];
```

### `wp_resource_hints` (internal use)

When a Google-hosted font is selected, the plugin hooks `add_google_fonts_preconnect()` into `wp_resource_hints` at priority 10. This injects `<link rel="preconnect">` hints for `fonts.googleapis.com` and `fonts.gstatic.com`, overlapping DNS/TLS connection time with HTML parsing. Reduces first-visit font latency by 100–300ms (LCP improvement).

### `wp_head` (internal use)

When a Google-hosted font is selected, `output_google_font_link()` is hooked to `wp_head` at priority 1. It outputs a non-blocking preload tag:

```html
<link rel="preload" href="https://fonts.googleapis.com/css2?family=...&display=optional"
      as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="..."></noscript>
```

The font is fetched at high priority without blocking the critical rendering path. `display=optional` is used instead of `display=swap` to prevent the layout shift a large decorative cap causes when swapping fonts at 3–5em (CLS improvement).

---

## Admin Assets

### Dependencies

| Asset | WordPress Dependency |
|---|---|
| `ajc-admin` (CSS) | `wp-color-picker` |
| `ajc-admin` (JS) | `wp-color-picker`, jQuery (implicit) |

### Localised JS data (`ajcData`)

The admin JS receives current settings and the font registry via `wp_localize_script`:

```js
ajcData = {
    fonts:        { /* font registry object, keyed by font slug */ },
    currentFont:  'playfair',
    currentColor: '#1e293b',
    currentLines: 3,
    currentStyle: 'drop',
    customFont:   ''
}
```

This data drives the live preview on the Drop Cap settings tab.

---

## Internationalization (i18n)

- Text domain: `auto-justify-content`
- `.pot` file: `languages/auto-justify-content.pot`
- Loaded via `load_plugin_textdomain()` on the `init` action

To regenerate the `.pot` file:
```bash
wp i18n make-pot . languages/auto-justify-content.pot
```

---

## Development

### Coding Standards

Targets [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/):

- `!defined('ABSPATH')` guard at top of every PHP file
- `esc_url()`, `esc_attr()`, `esc_html()`, `sanitize_hex_color()`, `sanitize_text_field()` used throughout
- All settings registered via `register_setting()` with explicit `sanitize_callback`
- Admin assets scoped to `settings_page_auto-justify-content` hook only
- No direct DB queries — all data via WordPress options API

### Running PHPCS

```bash
composer require --dev wp-coding-standards/wpcs
./vendor/bin/phpcs --standard=WordPress auto-justify-content.php
```

---

## Support

Open an issue at: https://github.com/menj/auto-justify-content/issues
