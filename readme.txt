=== Auto Justify Content ===
Contributors: menj
Tags: justify, typography, text alignment, elementor, gutenberg, blog layout
Requires at least: 5.6
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author URI: https://github.com/menj

Automatically justifies text in WordPress posts, pages, and Elementor content with optional hyphenation and responsive controls.

== Description ==

Auto Justify Content applies professional text justification to your WordPress content using modern CSS.

**Features:**

* Toggle justification on/off with a single click
* Apply to blog posts only or entire site
* Automatic hyphenation for cleaner narrow columns
* Responsive control — disable on mobile devices
* Theme fallback for non-standard content wrappers
* Exclude specific elements via CSS selectors
* Clean, modern settings interface

**Supported editors/builders:**

* Gutenberg blocks
* Classic Editor
* Elementor Text widgets
* Elementor Pro Post Content widgets

**Note:** Hyphenation requires a proper `lang` attribute on your HTML element (WordPress handles this automatically for most sites).

== Installation ==

1. Upload the `auto-justify-content` folder to `/wp-content/plugins/`
2. Activate the plugin from the WordPress Plugins screen
3. Go to Settings → Auto Justify
4. Configure your preferences

== Frequently Asked Questions ==

= Why isn't hyphenation working? =

CSS hyphenation requires the document to have a proper `lang` attribute. Check that your theme or WordPress is outputting `<html lang="en">` (or your language code) correctly.

= Can I exclude certain elements? =

Yes! Use the "Exclude Selectors" field to add comma-separated CSS selectors like `.no-justify, .testimonial-widget, .sidebar`.

= Does this work with page builders? =

Yes. The plugin specifically targets Elementor widgets and also works with standard WordPress content wrappers used by most themes and page builders.

== Screenshots ==

1. Clean, modern settings interface
2. Toggle switches for easy configuration

== Changelog ==

= 2.0.0 =
* Complete rewrite with modern PHP 7.4+ syntax
* NEW: Dedicated settings page under Settings → Auto Justify
* NEW: Modernist-minimalist settings UI with toggle switches
* NEW: Settings link on plugins page
* NEW: Full internationalization (i18n) support
* NEW: Proper uninstall cleanup (removes all options)
* IMPROVED: Uses wp_add_inline_style() for safer CSS output
* IMPROVED: Added hyphenation language requirement notice
* IMPROVED: Better code organization and WordPress coding standards

= 1.6.8 =
* Added Author URI + Plugin URI metadata

== Upgrade Notice ==

= 2.0.0 =
Major update with new dedicated settings page, modern UI, and proper cleanup on uninstall. All existing settings will be preserved.
