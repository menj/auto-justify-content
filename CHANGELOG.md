# Changelog

All notable changes to **Auto Justify Content** are documented here.

Format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
Versioning follows [Semantic Versioning](https://semver.org/): `MAJOR.MINOR.PATCH`

---

## [3.0.0] — 2026-03-09

Typography toolkit expansion. Drop cap feature merged in. All existing justification settings and behaviour are unchanged.

### Added
- **Drop Cap feature** — automatically applies a decorative initial letter to the first paragraph of each post
- `ajc_dc_enabled` — master toggle for drop caps, off by default (non-breaking for existing installs)
- `ajc_dc_style` — `drop` (letter descends into text) or `raised` (sits on baseline, rises above first line)
- `ajc_dc_lines` — line span control: `2`, `3`, or `4` lines tall (maps to `2.8em`, `4.0em`, `5.4em`)
- `ajc_dc_font` — curated font registry with 5 Google Fonts options plus inherit and custom
- `ajc_dc_custom_font` — free-text CSS font-family field for custom/theme-loaded fonts
- `ajc_dc_color` — hex colour for the drop cap letter, via WordPress colour picker (Iris)
- `ajc_dc_mobile` — separate desktop-only toggle for drop caps, independent of justification's mobile setting
- `[dropcap]` shortcode — wraps first character in `.ajc-drop-cap` span; works whether auto mode is on or off
- `ajc_drop_cap_fonts` filter — filterable font registry for developer extensibility and future add-ons
- Live preview panel on Drop Cap settings tab — updates font, colour, size, and style in real time
- Google Font loaded dynamically in preview via Promise-based `<link>` injection with loading state
- Google Fonts GDPR notice shown contextually in preview when a Google-hosted font is selected
- `wp-color-picker` (Iris) enqueued as admin asset dependency
- `ajcData` JS object passed via `wp_localize_script` containing font registry and current saved values
- Visual style picker tiles for Drop/Raised selection (replaces standard radio buttons)
- Visual line span picker tiles for 2/3/4 line selection
- Custom font field conditionally shown/hidden based on font selector value

### Changed
- Plugin description updated to reflect typography toolkit scope
- Settings page reorganised: three tabs — **Justification**, **Drop Cap**, **Advanced**
- Hyphenation and Theme Fallback settings moved from Typography tab to Advanced tab
- Settings tab formerly named "Typography" renamed to "Justification" for clarity
- Admin JS refactored: colour picker init, font selector logic, and preview system added alongside existing tab and toggle code
- Admin CSS extended with preview card, style picker, lines picker, and colour picker wrapper styles
- Version constant bumped to `3.0.0`
- Plugin header description updated
- All 13 options (6 justification + 7 drop cap) covered in `uninstall.php` and `get_option_names()`

### Not changed
- All justification CSS logic and selectors — identical to 2.0.0
- All existing `ajc_*` option keys — no migration needed
- Scope logic shared between justification and drop cap (both respect `ajc_scope` and `ajc_exclude`)
- PHP 7.4 minimum requirement unchanged
- WordPress 5.6 minimum requirement unchanged

---

## [2.0.0] — 2024-01-01

Complete rewrite. All legacy code from 1.x replaced.

### Added
- Dedicated settings page under **Settings → Auto Justify**
- Modernist-minimalist settings UI with CSS toggle switches
- Settings link on the Plugins list page
- Full internationalization (i18n) support with `.pot` translation template
- Proper uninstall cleanup via `uninstall.php` — all options removed on delete
- Scope control: apply justification to blog posts only or the entire site
- Hyphenation toggle: CSS `hyphens: auto` with vendor prefixes
- Mobile disable toggle: wraps all CSS in `@media (min-width: 768px)`
- Theme fallback toggle: adds `article p/li` selectors for non-standard themes
- Exclude selectors field: comma-separated CSS selectors to opt elements out
- Admin assets scoped to the settings page hook only
- `AutoJustifyContent` class with `private const VERSION` and typed properties

### Changed
- Plugin rewritten as a single OOP class
- CSS output switched from `wp_head` echo to `wp_add_inline_style()`
- Settings registered via `register_setting()` with `sanitize_callback` for all fields
- Minimum PHP raised to **7.4**
- Minimum WordPress set to **5.6**

### Removed
- All legacy procedural code from 1.x
- Direct `echo` of CSS into `<head>`

---

## [1.6.8] — (prior release)

### Added
- `Author URI` and `Plugin URI` metadata to the plugin header

---

## [1.x] — (legacy)

Early versions provided basic text justification via a `wp_head` action. No settings page or granular controls were available.

---

> **Note:** Dates for versions prior to 2.0.0 are approximate. Exact release dates were not recorded.
