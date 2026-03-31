=== Auto Justify Content ===
Contributors: MENJ
Plugin URI: https://github.com/menj/auto-justify-content
Tags: justify, typography, text alignment, drop cap, initial letter, elementor, gutenberg
Requires at least: 5.6
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 3.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author URI: https://github.com/menj

Automatically justifies text and adds decorative drop caps to WordPress posts and pages, with font controls, hyphenation, and responsive options.

== Description ==

Have you ever noticed how text in books and newspapers lines up neatly on both the left and right sides, and how the first letter of a chapter is often large and decorative? **Auto Justify Content** brings both of those classic print typography techniques to your WordPress site — automatically, with no code required.

**Text Justification**

* Turn justification on or off with a single click
* Choose whether it applies only to your blog posts or across your entire website
* Enable automatic word-breaking (hyphenation) so long words don't leave awkward gaps
* Prevent justification from applying on phones and tablets, where it can sometimes look cramped
* Use a fallback option if your theme is a bit unusual and text isn't being picked up
* Exclude specific areas of your site (such as testimonials or sidebars)

**Drop Caps**

* Automatically style the first letter of each post as a large decorative initial — no shortcode needed
* Choose from five hand-picked fonts: Playfair Display, Cormorant Garamond, Cinzel, UnifrakturMaguntia, and Dancing Script, or use your own
* Pick between Drop style (letter descends into the text) and Raised style (letter sits on the baseline)
* Control how many lines tall the drop cap should be (2, 3, or 4)
* Set any colour for the initial letter
* Disable on mobile if preferred
* Use the [dropcap] shortcode for manual control in the Classic Editor, text widgets, and anywhere shortcodes work

**Different from Gutenberg's built-in drop cap**

WordPress's block editor includes a basic drop cap toggle, but it relies entirely on your active theme for how it looks — switch themes and it may disappear or break. This plugin's drop cap works independently of your theme, gives you real font and colour controls, and applies automatically across your posts without touching each one individually.

**Works with the editors you already use:**

* The default WordPress block editor (Gutenberg)
* The Classic Editor
* Elementor (Text widgets and Post Content widgets)
* Most other themes and page builders

== Installation ==

1. In your WordPress dashboard, go to **Plugins → Add New**
2. Search for **Auto Justify Content**
3. Click **Install Now**, then **Activate**
4. Go to **Settings → Auto Justify** to configure your preferences

That's it — your content will start looking like a professionally typeset publication.

== Frequently Asked Questions ==

= Will this change all my existing posts? =

Yes, but only visually — it doesn't edit your content at all. It simply changes how text is displayed to your visitors. If you deactivate the plugin, everything goes back to how it looked before.

= Why isn't the word-breaking (hyphenation) working? =

Hyphenation is handled by the browser, and it needs to know what language your site is in to break words correctly. WordPress sets this up automatically for most sites, so it should just work. If it doesn't, your theme may not be including the standard language information — you can ask your theme developer about it, or simply leave hyphenation turned off.

= How is this drop cap different from the one in the WordPress block editor? =

The Gutenberg drop cap is a manual toggle you apply one paragraph at a time, and its appearance depends entirely on your theme — there are no font, colour, or size controls. This plugin applies drop caps automatically to every post, works independently of your theme, and gives you full control over the font family, letter colour, and how many lines tall the cap should be.

= Can I use a drop cap without it applying everywhere automatically? =

Yes. If you turn off the automatic drop cap, you can still use the [dropcap]Word[/dropcap] shortcode to place a drop cap exactly where you want it — in a specific post, a text widget, or anywhere shortcodes are supported.

= Do the drop cap fonts slow down my site? =

Only the font you select is loaded, and only on pages where drop caps appear. The fonts come from Google Fonts, so they are typically served from the user's browser cache if they've visited any other site using the same font. You can also choose "Inherit from Theme" to use no external font at all, or "Custom Font" to use one already loaded by your theme.

= Can I stop justification or drop caps from applying to certain parts of my site? =

Yes. The Advanced tab has an Exclude field where you can list CSS selectors for areas you want left alone — like a testimonials section, a pull quote, or a sidebar. Both justification and drop caps respect this list. If you're not sure what to type there, a web developer can help you identify the right names for those areas.

= Does it work with my page builder? =

If you're using Elementor, yes — it's specifically supported. For other page builders, the plugin works with the standard content areas used by most WordPress themes, so it will very likely work for you too.

== Changelog ==

= 3.0.0 =
* NEW: Drop Cap feature — automatically style the first letter of posts as a decorative initial
* NEW: Five curated drop cap fonts including Playfair Display, Cormorant Garamond, Cinzel, UnifrakturMaguntia, and Dancing Script
* NEW: Drop cap style choice — Drop (descends into text) or Raised (sits on baseline)
* NEW: Line span control — set the drop cap to span 2, 3, or 4 lines of text
* NEW: Letter colour picker for the drop cap
* NEW: [dropcap] shortcode for manual drop cap placement, works independently of the automatic setting
* NEW: Live preview panel on the Drop Cap settings tab — see font, colour, and size changes instantly
* NEW: Google Fonts loaded on demand — only the selected font is requested, only on pages where drop caps appear
* NEW: ajc_drop_cap_fonts filter for developers to add custom fonts or build add-ons
* NEW: Desktop-only toggle for drop caps (separate from the justification desktop-only setting)
* IMPROVED: Settings page reorganised into three tabs — Justification, Drop Cap, and Advanced
* IMPROVED: Justification tab copy updated to plain language
* IMPROVED: Hyphenation and fallback settings moved to the Advanced tab for a cleaner layout

= 2.0.0 =
* Completely rebuilt from the ground up for a better, more reliable experience
* NEW: A proper settings page under Settings → Auto Justify
* NEW: Clean, easy-to-use toggle switches for all options
* NEW: Quick "Settings" link added directly on the Plugins page
* NEW: Ready for translation into other languages
* NEW: Cleans up after itself completely if you ever uninstall it
* IMPROVED: More reliable way of applying styles to your site
* IMPROVED: Better notice about language requirements for hyphenation

= 1.6.8 =
* Minor behind-the-scenes update to plugin information

== Upgrade Notice ==

= 3.0.0 =
Major update adding the Drop Cap feature. Your existing justification settings are fully preserved — nothing will change on your site until you visit Settings → Auto Justify and explore the new Drop Cap tab.

= 2.0.0 =
Big update! There's now a proper settings page and a cleaner interface. Your previous settings have been kept — just head to Settings → Auto Justify to explore the new options.
