# Upgrading & Roadmap

This document covers upgrade paths between major versions and outlines planned future development for **Auto Justify Content**.

---

## Upgrade Guides

### Upgrading to 3.0.0 from 2.0.0

**Your existing justification settings are fully preserved.** All `ajc_*` option keys are unchanged. Nothing will look different on your site after upgrading until you visit **Settings → Auto Justify** and enable the new Drop Cap feature.

**What changed:**
- The settings page has three tabs instead of two. The old Typography tab is now called Justification. Hyphenation and Fallback have moved to the Advanced tab.
- Seven new `ajc_dc_*` database options are created automatically with sensible defaults (drop caps off by default).
- The admin page now loads `wp-color-picker` (bundled with WordPress core — no new external dependency).
- The plugin description and version number have changed, which will show as an update on WordPress.org.

**Steps:**
1. Use the WordPress updater or replace the plugin folder manually.
2. Reactivate if needed — all prior settings carry over automatically.
3. Visit **Settings → Auto Justify → Drop Cap** tab to explore the new feature. It is off by default.

---

### Upgrading to 2.0.0 from 1.x

**All existing settings are preserved.** The 2.0.0 rewrite uses the same `wp_options` keys that were introduced in late 1.x builds, so no data migration is needed.

**What changed:**
- The settings page moved to **Settings → Auto Justify**.
- CSS is injected via `wp_add_inline_style()` rather than echoed into `wp_head`. Custom code hooking into the old output will need updating.
- PHP 7.4 is required. Hosts running PHP 7.3 or below must upgrade PHP first.

**Steps:**
1. Ensure your server runs PHP 7.4+.
2. Deactivate the old version.
3. Replace the plugin folder and reactivate.
4. Visit **Settings → Auto Justify** to review the new options.

---

## Planned Features & Roadmap

The items below represent planned improvements, roughly prioritised. Nothing here is guaranteed or date-committed.

### 3.1.0 — Developer Hooks for Justification

Expose public filters for the justification system so developers can modify selectors and CSS output without editing the plugin:

```php
// Filter the CSS target selectors before output
$selectors = apply_filters( 'ajc_target_selectors', $selectors );

// Filter the complete generated justification CSS string
$css = apply_filters( 'ajc_frontend_css', $css );

// Filter the default option values
$defaults = apply_filters( 'ajc_defaults', $defaults );
```

**Also planned:**
- `ajc_exclude_selectors` filter for programmatic exclusions
- Allow child themes to append to the `ajc-justify` style handle

---

### 3.2.0 — Typography Controls

Extend the plugin's CSS output to give finer control over the reading experience. All four additions are CSS-only — no JS, no new architectural complexity, one new option each. They belong together because they are most meaningful when applied to the same justified text blocks.

**Paragraph spacing** (`ajc_paragraph_spacing`)
Sets `margin-bottom` on paragraph elements. Justified text with tight spacing reads poorly; a modest increase dramatically improves page rhythm. Slider from `0` to `2em` in `0.1em` steps.

**Line height** (`ajc_line_height`)
Sets `line-height` on justified paragraphs. Justified text benefits from slightly more leading than left-aligned text — typically `1.6`–`1.8` vs `1.5`. Slider from `1.0` to `2.5` in `0.1` steps. Outputs nothing if left at the default (no override).

**Maximum line length** (`ajc_max_width`)
Sets `max-width` in `ch` units on the content wrapper. Overly wide justified text blocks (>85–90 characters per line) become difficult to read — the eye struggles to track from line end back to the next line start. A `max-width: 72ch` constraint is the single most impactful readability improvement for justified text on wide screens, and no other WordPress plugin addresses this directly. Free-text field accepting any valid CSS value (`72ch`, `680px`, `100%`).

**Hanging punctuation** (`ajc_hanging_punctuation`)
Applies CSS `hanging-punctuation: first last` to justified paragraphs. When a paragraph opens with a quote character (`"`, `'`, `«`), the punctuation sits inside the margin rather than indenting the first line of text — a mark of professional typesetting common in book and magazine layouts. Boolean toggle; outputs nothing if off.

---

### 3.3.0 — Per-Post / Per-Page Overrides

Add a meta box to the post/page editor for per-content overrides:

- Force justification or drop cap ON for a specific post regardless of global scope
- Force either feature OFF for a specific post
- Per-post hyphenation override

Implementation will use `post_meta` checked inside the enqueue methods.

---

### 3.4.0 — Custom Breakpoint for Mobile

Replace the hardcoded `768px` breakpoint in both the justification and drop cap mobile toggles with a configurable pixel/em value in settings. A single field covers desktop-only, tablet-and-up, and mobile-included scenarios without needing separate toggles per device class.

---

### 3.5.0 — Block Editor Sidebar Panel

Add a **Document sidebar panel** in the Gutenberg editor to surface per-post overrides (from 3.3.0) directly in the editor UI, without a separate meta box.

Requires `@wordpress/plugins` and `@wordpress/edit-post` package dependencies.

---

### 3.6.0 — Extended Builder Support & WP-CLI

**Page builder selector support**

Add content wrapper selectors for Oxygen Builder and Bricks Builder to `$targets` in `generate_justify_css()`. These builders use non-standard content containers that the current selector set doesn't reach. Implementation is a targeted addition to the existing selector arrays — no architectural change.

**WP-CLI commands**

```bash
wp ajc enable          # Enable justification sitewide
wp ajc disable         # Disable justification
wp ajc reset           # Delete all ajc_* options, restoring defaults
wp ajc status          # Output current option values as a table
```

Useful for agencies managing multiple sites and for multisite network administration ahead of the 4.0.0 release.

**Settings reset button**

One-click return to defaults in the settings UI. Calls `delete_option()` on all `ajc_*` keys and redirects back to the settings page. Appears in the Advanced tab footer with appropriate confirmation.

---

### 4.0.0 — Multisite Support

Extend the plugin to support WordPress Multisite (Network) installs:

- Network-level defaults from **Network Admin → Settings**
- Per-site overrides allowed or lockable by the network admin
- `get_site_option()` / `update_site_option()` for network-scoped settings

This requires changes to the settings architecture, hence the major version bump.

---

### Future / Under Consideration

| Idea | Notes |
|---|---|
| RTL language support | Justify behaviour with `dir="rtl"` content |
| Category/tag-based rules | Apply justification only to specific taxonomies; complex — the per-post override in 3.3.0 partially covers this from the other direction |
| WooCommerce product page exclusion | Auto-exclude product descriptions by default |
| REST API settings endpoint | `GET/POST /wp-json/ajc/v1/settings`; useful for headless WordPress setups |
| Export / import settings | JSON export/import of all `ajc_*` options |
| Extended font pack add-on | Additional drop cap fonts via the `ajc_drop_cap_fonts` filter |
| Smart typography | Improve on WordPress's built-in `wptexturize()` — curly quotes, em dashes, ellipses, non-breaking spaces before short words, widow prevention. Applied via `the_content` filter; toggleable and scopeable like justification |
| Compatibility checker | Admin notice if active theme is missing `lang` attribute (affects hyphenation) |
| Unit tests | PHPUnit coverage for sanitization methods and CSS generation |

---

## Versioning Policy

This plugin follows [Semantic Versioning](https://semver.org/):

| Change type | Version bump |
|---|---|
| Backward-incompatible changes (option key removal, PHP requirement bump, hook removal) | **MAJOR** (e.g. 3.x → 4.0.0) |
| New features, new settings, new filters (backward compatible) | **MINOR** (e.g. 3.0 → 3.1.0) |
| Bug fixes, copy corrections, security patches | **PATCH** (e.g. 3.0.0 → 3.0.1) |

---

## Reporting Issues & Feature Requests

Open an issue at: https://github.com/menj/auto-justify-content/issues

Please tag feature requests with the `enhancement` label.
