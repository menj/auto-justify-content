<?php
/**
 * Plugin Name: Auto Justify Content
 * Plugin URI: https://github.com/menj/auto-justify-content
 * Description: Professional typography toolkit for WordPress. Automatic text justification and decorative drop caps for posts, pages, and Elementor content.
 * Version: 3.0.0
 * Author: MENJ
 * Author URI: https://github.com/menj
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: auto-justify-content
 * Requires at least: 5.6
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AutoJustifyContent {

    private const VERSION     = '3.0.0';
    private const TEXT_DOMAIN = 'auto-justify-content';

    // -------------------------------------------------------------------------
    // Option keys
    // -------------------------------------------------------------------------

    private array $options = [
        // Justification
        'enabled'        => 'ajc_enabled',
        'scope'          => 'ajc_scope',
        'hyphen'         => 'ajc_hyphen',
        'mobile'         => 'ajc_mobile',
        'fallback'       => 'ajc_fallback',
        'exclude'        => 'ajc_exclude',
        // Drop cap
        'dc_enabled'     => 'ajc_dc_enabled',
        'dc_style'       => 'ajc_dc_style',
        'dc_lines'       => 'ajc_dc_lines',
        'dc_mobile'      => 'ajc_dc_mobile',
        'dc_font'        => 'ajc_dc_font',
        'dc_custom_font' => 'ajc_dc_custom_font',
        'dc_color'       => 'ajc_dc_color',
    ];

    private array $defaults = [
        // Justification
        'ajc_enabled'        => true,
        'ajc_scope'          => 'blog_only',
        'ajc_hyphen'         => true,
        'ajc_mobile'         => false,
        'ajc_fallback'       => false,
        'ajc_exclude'        => '.elementor-testimonial',
        // Drop cap
        'ajc_dc_enabled'     => false,
        'ajc_dc_style'       => 'drop',
        'ajc_dc_lines'       => 3,
        'ajc_dc_mobile'      => false,
        'ajc_dc_font'        => 'playfair',
        'ajc_dc_custom_font' => '',
        'ajc_dc_color'       => '#1e293b',
    ];

    /**
     * Stores the Google Fonts API string for the selected drop cap font.
     * Populated in maybe_enqueue_drop_cap_styles(), consumed in
     * add_google_fonts_preconnect() and output_google_font_link().
     */
    private string $google_font_param = '';

    // -------------------------------------------------------------------------
    // Boot
    // -------------------------------------------------------------------------

    public function __construct() {
        add_action( 'init',                    [ $this, 'load_textdomain' ] );
        add_action( 'init',                    [ $this, 'register_shortcode' ] );
        add_action( 'admin_menu',              [ $this, 'add_settings_page' ] );
        add_action( 'admin_init',              [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts',   [ $this, 'enqueue_admin_assets' ] );
        add_action( 'wp_enqueue_scripts',      [ $this, 'enqueue_frontend_styles' ] );
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'add_settings_link' ] );
    }

    // -------------------------------------------------------------------------
    // i18n
    // -------------------------------------------------------------------------

    public function load_textdomain(): void {
        load_plugin_textdomain(
            self::TEXT_DOMAIN,
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages'
        );
    }

    // -------------------------------------------------------------------------
    // Admin menu
    // -------------------------------------------------------------------------

    public function add_settings_page(): void {
        add_options_page(
            __( 'Auto Justify Content', self::TEXT_DOMAIN ),
            __( 'Auto Justify', self::TEXT_DOMAIN ),
            'manage_options',
            'auto-justify-content',
            [ $this, 'render_settings_page' ]
        );
    }

    public function add_settings_link( array $links ): array {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url( admin_url( 'options-general.php?page=auto-justify-content' ) ),
            __( 'Settings', self::TEXT_DOMAIN )
        );
        array_unshift( $links, $settings_link );
        return $links;
    }

    // -------------------------------------------------------------------------
    // Admin assets
    // -------------------------------------------------------------------------

    public function enqueue_admin_assets( string $hook ): void {
        if ( 'settings_page_auto-justify-content' !== $hook ) {
            return;
        }

        // WordPress colour picker
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        wp_enqueue_style(
            'ajc-admin',
            plugin_dir_url( __FILE__ ) . 'assets/css/admin.css',
            [ 'wp-color-picker' ],
            self::VERSION
        );

        wp_enqueue_script(
            'ajc-admin',
            plugin_dir_url( __FILE__ ) . 'assets/js/admin.js',
            [ 'wp-color-picker' ],
            self::VERSION,
            true
        );

        // Pass font data and current settings to JS for the live preview
        wp_localize_script( 'ajc-admin', 'ajcData', [
            'fonts'       => $this->get_drop_cap_fonts(),
            'currentFont' => get_option( $this->options['dc_font'],        $this->defaults['ajc_dc_font'] ),
            'currentColor'=> get_option( $this->options['dc_color'],       $this->defaults['ajc_dc_color'] ),
            'currentLines'=> (int) get_option( $this->options['dc_lines'], $this->defaults['ajc_dc_lines'] ),
            'currentStyle'=> get_option( $this->options['dc_style'],       $this->defaults['ajc_dc_style'] ),
            'customFont'  => get_option( $this->options['dc_custom_font'], $this->defaults['ajc_dc_custom_font'] ),
        ] );
    }

    // -------------------------------------------------------------------------
    // Settings registration
    // -------------------------------------------------------------------------

    public function register_settings(): void {

        // --- Justification settings -----------------------------------------

        register_setting( 'ajc_settings', $this->options['enabled'], [
            'type'              => 'boolean',
            'default'           => $this->defaults['ajc_enabled'],
            'sanitize_callback' => 'rest_sanitize_boolean',
        ] );

        register_setting( 'ajc_settings', $this->options['scope'], [
            'type'              => 'string',
            'default'           => $this->defaults['ajc_scope'],
            'sanitize_callback' => [ $this, 'sanitize_scope' ],
        ] );

        register_setting( 'ajc_settings', $this->options['hyphen'], [
            'type'              => 'boolean',
            'default'           => $this->defaults['ajc_hyphen'],
            'sanitize_callback' => 'rest_sanitize_boolean',
        ] );

        register_setting( 'ajc_settings', $this->options['mobile'], [
            'type'              => 'boolean',
            'default'           => $this->defaults['ajc_mobile'],
            'sanitize_callback' => 'rest_sanitize_boolean',
        ] );

        register_setting( 'ajc_settings', $this->options['fallback'], [
            'type'              => 'boolean',
            'default'           => $this->defaults['ajc_fallback'],
            'sanitize_callback' => 'rest_sanitize_boolean',
        ] );

        register_setting( 'ajc_settings', $this->options['exclude'], [
            'type'              => 'string',
            'default'           => $this->defaults['ajc_exclude'],
            'sanitize_callback' => [ $this, 'sanitize_exclude_selectors' ],
        ] );

        // --- Drop cap settings ----------------------------------------------

        register_setting( 'ajc_settings', $this->options['dc_enabled'], [
            'type'              => 'boolean',
            'default'           => $this->defaults['ajc_dc_enabled'],
            'sanitize_callback' => 'rest_sanitize_boolean',
        ] );

        register_setting( 'ajc_settings', $this->options['dc_style'], [
            'type'              => 'string',
            'default'           => $this->defaults['ajc_dc_style'],
            'sanitize_callback' => [ $this, 'sanitize_dc_style' ],
        ] );

        register_setting( 'ajc_settings', $this->options['dc_lines'], [
            'type'              => 'integer',
            'default'           => $this->defaults['ajc_dc_lines'],
            'sanitize_callback' => [ $this, 'sanitize_dc_lines' ],
        ] );

        register_setting( 'ajc_settings', $this->options['dc_mobile'], [
            'type'              => 'boolean',
            'default'           => $this->defaults['ajc_dc_mobile'],
            'sanitize_callback' => 'rest_sanitize_boolean',
        ] );

        register_setting( 'ajc_settings', $this->options['dc_font'], [
            'type'              => 'string',
            'default'           => $this->defaults['ajc_dc_font'],
            'sanitize_callback' => [ $this, 'sanitize_dc_font' ],
        ] );

        register_setting( 'ajc_settings', $this->options['dc_custom_font'], [
            'type'              => 'string',
            'default'           => $this->defaults['ajc_dc_custom_font'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        register_setting( 'ajc_settings', $this->options['dc_color'], [
            'type'              => 'string',
            'default'           => $this->defaults['ajc_dc_color'],
            'sanitize_callback' => 'sanitize_hex_color',
        ] );
    }

    // -------------------------------------------------------------------------
    // Render settings page
    // -------------------------------------------------------------------------

    public function render_settings_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Justification
        $enabled    = (bool) get_option( $this->options['enabled'],  $this->defaults['ajc_enabled'] );
        $scope      = get_option( $this->options['scope'],           $this->defaults['ajc_scope'] );
        $hyphen     = (bool) get_option( $this->options['hyphen'],   $this->defaults['ajc_hyphen'] );
        $mobile     = (bool) get_option( $this->options['mobile'],   $this->defaults['ajc_mobile'] );
        $fallback   = (bool) get_option( $this->options['fallback'], $this->defaults['ajc_fallback'] );
        $exclude    = get_option( $this->options['exclude'],         $this->defaults['ajc_exclude'] );

        // Drop cap
        $dc_enabled     = (bool) get_option( $this->options['dc_enabled'],     $this->defaults['ajc_dc_enabled'] );
        $dc_style       = get_option( $this->options['dc_style'],              $this->defaults['ajc_dc_style'] );
        $dc_lines       = (int) get_option( $this->options['dc_lines'],        $this->defaults['ajc_dc_lines'] );
        $dc_mobile      = (bool) get_option( $this->options['dc_mobile'],      $this->defaults['ajc_dc_mobile'] );
        $dc_font        = get_option( $this->options['dc_font'],               $this->defaults['ajc_dc_font'] );
        $dc_custom_font = get_option( $this->options['dc_custom_font'],        $this->defaults['ajc_dc_custom_font'] );
        $dc_color       = get_option( $this->options['dc_color'],              $this->defaults['ajc_dc_color'] );

        $active_tab     = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';
        $fonts          = $this->get_drop_cap_fonts();

        include plugin_dir_path( __FILE__ ) . 'templates/settings-page.php';
    }

    // -------------------------------------------------------------------------
    // Drop cap font registry
    // -------------------------------------------------------------------------

    /**
     * Returns the curated list of drop cap fonts.
     * Filterable via ajc_drop_cap_fonts for future expansion or add-ons.
     *
     * Each entry:
     *   label  – Human-readable name shown in the settings dropdown
     *   google – Google Fonts API family string (null = no external request)
     *   stack  – Full CSS font-family value used in generated CSS
     */
    public function get_drop_cap_fonts(): array {
        $fonts = [
            'inherit'    => [
                'label'  => __( 'Inherit from Theme', self::TEXT_DOMAIN ),
                'google' => null,
                'stack'  => 'inherit',
            ],
            'playfair'   => [
                'label'  => 'Playfair Display',
                'google' => 'Playfair+Display:wght@700',
                'stack'  => '"Playfair Display", Georgia, serif',
            ],
            'cormorant'  => [
                'label'  => 'Cormorant Garamond',
                'google' => 'Cormorant+Garamond:wght@600',
                'stack'  => '"Cormorant Garamond", "Times New Roman", serif',
            ],
            'cinzel'     => [
                'label'  => 'Cinzel',
                'google' => 'Cinzel:wght@700',
                'stack'  => '"Cinzel", "Times New Roman", serif',
            ],
            'unifraktur' => [
                'label'  => 'UnifrakturMaguntia',
                'google' => 'UnifrakturMaguntia',
                'stack'  => '"UnifrakturMaguntia", serif',
            ],
            'dancing'    => [
                'label'  => 'Dancing Script',
                'google' => 'Dancing+Script:wght@700',
                'stack'  => '"Dancing Script", cursive',
            ],
            'custom'     => [
                'label'  => __( 'Custom Font…', self::TEXT_DOMAIN ),
                'google' => null,
                'stack'  => null, // resolved at render time from ajc_dc_custom_font
            ],
        ];

        return apply_filters( 'ajc_drop_cap_fonts', $fonts );
    }

    // -------------------------------------------------------------------------
    // Frontend styles
    // -------------------------------------------------------------------------

    public function enqueue_frontend_styles(): void {
        $this->maybe_enqueue_justify_styles();
        $this->maybe_enqueue_drop_cap_styles();
    }

    private function maybe_enqueue_justify_styles(): void {
        if ( ! get_option( $this->options['enabled'], $this->defaults['ajc_enabled'] ) ) {
            return;
        }

        $scope = get_option( $this->options['scope'], $this->defaults['ajc_scope'] );
        if ( 'blog_only' === $scope && ! is_singular( 'post' ) ) {
            return;
        }

        wp_register_style( 'ajc-justify', false, [], self::VERSION );
        wp_enqueue_style( 'ajc-justify' );
        wp_add_inline_style( 'ajc-justify', $this->generate_justify_css() );
    }

    private function maybe_enqueue_drop_cap_styles(): void {
        if ( ! get_option( $this->options['dc_enabled'], $this->defaults['ajc_dc_enabled'] ) ) {
            return;
        }

        $scope = get_option( $this->options['scope'], $this->defaults['ajc_scope'] );
        if ( 'blog_only' === $scope && ! is_singular( 'post' ) ) {
            return;
        }

        // Load Google Font non-blocking when a Google-hosted font is selected.
        //
        // We deliberately avoid wp_enqueue_style() here because WordPress outputs
        // enqueued styles as standard <link rel="stylesheet"> in <head>, which is
        // render-blocking. For a decorative drop cap font this is unnecessary — the
        // page content is fully readable without it.
        //
        // Instead we:
        //   1. Inject <link rel="preconnect"> hints via wp_resource_hints so the
        //      browser starts the DNS/TLS handshake early (saves 100–300ms on cold visits).
        //   2. Output a preload <link> that converts itself to a stylesheet via onload,
        //      keeping the resource off the critical rendering path entirely.
        //   3. Use display=optional rather than display=swap: the font is used from
        //      cache on repeat visits; on first visit the fallback renders and never
        //      swaps, eliminating the CLS that a swap of a large decorative cap would cause.
        $font_key = get_option( $this->options['dc_font'], $this->defaults['ajc_dc_font'] );
        $fonts    = $this->get_drop_cap_fonts();

        if ( isset( $fonts[ $font_key ]['google'] ) && $fonts[ $font_key ]['google'] ) {
            $this->google_font_param = $fonts[ $font_key ]['google'];
            add_filter( 'wp_resource_hints', [ $this, 'add_google_fonts_preconnect' ], 10, 2 );
            add_action( 'wp_head',           [ $this, 'output_google_font_link' ],     1 );
        }

        wp_register_style( 'ajc-dropcap', false, [], self::VERSION );
        wp_enqueue_style( 'ajc-dropcap' );
        wp_add_inline_style( 'ajc-dropcap', $this->generate_drop_cap_css() );
    }

    // -------------------------------------------------------------------------
    // Google Fonts — non-blocking delivery helpers
    // -------------------------------------------------------------------------

    /**
     * Injects preconnect resource hints for Google Fonts domains.
     *
     * Hooked to wp_resource_hints (priority 10) only when a Google font is active.
     * Preconnect starts the DNS lookup and TLS handshake before the browser has
     * parsed the <link> tag, overlapping that work with HTML parsing.
     *
     * Two origins are needed:
     *   fonts.googleapis.com — serves the CSS @font-face declaration
     *   fonts.gstatic.com    — serves the actual .woff2 file (crossorigin required)
     *
     * CWV impact: LCP — reduces cross-origin connection latency by 100–300ms on
     * first visit depending on network conditions.
     *
     * @param array  $urls          Existing resource hint URLs.
     * @param string $relation_type The hint type (preconnect, dns-prefetch, etc.).
     * @return array
     */
    public function add_google_fonts_preconnect( array $urls, string $relation_type ): array {
        if ( 'preconnect' !== $relation_type ) {
            return $urls;
        }
        $urls[] = [ 'href' => 'https://fonts.googleapis.com' ];
        $urls[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'crossorigin' ];
        return $urls;
    }

    /**
     * Outputs the Google Fonts <link> as a non-blocking preload.
     *
     * Hooked to wp_head (priority 1) only when a Google font is active.
     *
     * Technique:
     *   <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
     *
     * The browser fetches the font CSS at high priority but does NOT block rendering
     * on it. The onload handler promotes rel to "stylesheet" once the file arrives,
     * so the font is applied immediately after it loads without ever blocking paint.
     *
     * A <noscript> fallback ensures the font still loads for the small number of
     * users or crawlers with JavaScript disabled.
     *
     * display=optional is used instead of display=swap:
     *   - swap:     browser always shows the Google Font, but swaps from fallback on
     *               load, causing CLS. At 3–5em the drop cap glyph shift is large.
     *   - optional: font used from cache on repeat visits (zero CLS); on first visit
     *               the fallback renders and stays — no repaint, no shift.
     *
     * CWV impact: LCP — removes Google Fonts from the critical rendering path.
     *             CLS — eliminates layout shift caused by large decorative font swap.
     */
    public function output_google_font_link(): void {
        if ( '' === $this->google_font_param ) {
            return;
        }

        $url = esc_url(
            'https://fonts.googleapis.com/css2?family=' . $this->google_font_param . '&display=optional'
        );

        printf(
            '<link rel="preload" href="%1$s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n" .
            '<noscript><link rel="stylesheet" href="%1$s"></noscript>' . "\n",
            $url
        );
    }

    // -------------------------------------------------------------------------
    // CSS generation — justification
    // -------------------------------------------------------------------------

    private function generate_justify_css(): string {
        $hyphen   = (bool) get_option( $this->options['hyphen'],   $this->defaults['ajc_hyphen'] );
        $mobile   = (bool) get_option( $this->options['mobile'],   $this->defaults['ajc_mobile'] );
        $fallback = (bool) get_option( $this->options['fallback'], $this->defaults['ajc_fallback'] );
        $exclude  = trim( (string) get_option( $this->options['exclude'], $this->defaults['ajc_exclude'] ) );

        $targets = [
            '.entry-content p',
            '.entry-content li',
            '.wp-block-post-content p',
            '.wp-block-post-content li',
            '.elementor-widget-text-editor p',
            '.elementor-widget-text-editor li',
            '.elementor-widget-theme-post-content p',
            '.elementor-widget-theme-post-content li',
        ];

        if ( $fallback ) {
            $targets[] = 'article p';
            $targets[] = 'article li';
        }

        $selectors = implode( ', ', $targets );

        $last_line_targets = [
            '.entry-content p',
            '.wp-block-post-content p',
            '.elementor-widget-text-editor p',
            '.elementor-widget-theme-post-content p',
        ];

        if ( $fallback ) {
            $last_line_targets[] = 'article p';
        }

        $last_line = implode( ', ', $last_line_targets );

        $css = sprintf(
            '%1$s { text-align: justify !important; text-justify: inter-word !important; }
%2$s { text-align-last: left !important; }',
            $selectors,
            $last_line
        );

        if ( $hyphen ) {
            $css .= sprintf(
                ' %s { -webkit-hyphens: auto !important; -ms-hyphens: auto !important; hyphens: auto !important; overflow-wrap: break-word !important; }',
                $selectors
            );
        }

        if ( '' !== $exclude ) {
            $css .= sprintf(
                ' %1$s, %1$s p, %1$s li { text-align: inherit !important; text-justify: auto !important; -webkit-hyphens: manual !important; -ms-hyphens: manual !important; hyphens: manual !important; }',
                $exclude
            );
        }

        if ( $mobile ) {
            $css = sprintf( '@media (min-width: 768px) { %s }', $css );
        }

        return $css;
    }

    // -------------------------------------------------------------------------
    // CSS generation — drop cap
    // -------------------------------------------------------------------------

    private function generate_drop_cap_css(): string {
        $dc_style       = get_option( $this->options['dc_style'],  $this->defaults['ajc_dc_style'] );
        $dc_lines       = (int) get_option( $this->options['dc_lines'], $this->defaults['ajc_dc_lines'] );
        $dc_mobile      = (bool) get_option( $this->options['dc_mobile'], $this->defaults['ajc_dc_mobile'] );
        $dc_font        = get_option( $this->options['dc_font'],   $this->defaults['ajc_dc_font'] );
        $dc_custom_font = trim( (string) get_option( $this->options['dc_custom_font'], '' ) );
        $dc_color       = sanitize_hex_color(
            get_option( $this->options['dc_color'], $this->defaults['ajc_dc_color'] )
        ) ?: $this->defaults['ajc_dc_color'];

        // Resolve font stack
        $fonts      = $this->get_drop_cap_fonts();
        $font_stack = 'inherit';
        if ( 'custom' === $dc_font && '' !== $dc_custom_font ) {
            $font_stack = $dc_custom_font;
        } elseif ( isset( $fonts[ $dc_font ]['stack'] ) && null !== $fonts[ $dc_font ]['stack'] ) {
            $font_stack = $fonts[ $dc_font ]['stack'];
        }

        // Font size by line-span (empirically tuned values)
        $sizes = [ 2 => '2.8em', 3 => '4.0em', 4 => '5.4em' ];
        $size  = $sizes[ $dc_lines ] ?? '4.0em';

        // Style-specific properties
        if ( 'raised' === $dc_style ) {
            $line_height = '1.0';
            $margin      = '0 0.1em 0 0';
        } else {
            // drop — classic descending cap
            $line_height = '0.83';
            $margin      = '0.04em 0.1em -0.05em 0';
        }

        // Selectors for auto drop cap (first paragraph only)
        $auto_selectors = [
            '.entry-content > p:first-of-type::first-letter',
            '.wp-block-post-content > p:first-of-type::first-letter',
            '.elementor-widget-text-editor > .elementor-widget-container > p:first-of-type::first-letter',
            '.elementor-widget-theme-post-content > .elementor-widget-container > p:first-of-type::first-letter',
        ];

        // Selectors for shortcode span
        $shortcode_selector = 'span.ajc-drop-cap';

        $shared_rules = sprintf(
            'font-family: %1$s; font-size: %2$s; font-weight: 700; color: %3$s; float: left; line-height: %4$s; margin: %5$s; padding: 0 0.05em 0 0;',
            $font_stack,
            $size,
            $dc_color,
            $line_height,
            $margin
        );

        $css = sprintf(
            '%1$s { %2$s }' . "\n" .
            '%3$s { %2$s }',
            implode( ', ', $auto_selectors ),
            $shared_rules,
            $shortcode_selector
        );

        // Clear float after first paragraph
        $clear_selectors = [
            '.entry-content > p:first-of-type',
            '.wp-block-post-content > p:first-of-type',
        ];
        $css .= sprintf(
            "\n" . '%s { overflow: hidden; }',
            implode( ', ', $clear_selectors )
        );

        if ( $dc_mobile ) {
            $css = sprintf( '@media (min-width: 768px) { %s }', $css );
        }

        return $css;
    }

    // -------------------------------------------------------------------------
    // Shortcode — [dropcap]Word[/dropcap]
    // -------------------------------------------------------------------------

    public function register_shortcode(): void {
        add_shortcode( 'dropcap', [ $this, 'dropcap_shortcode' ] );
    }

    /**
     * Transforms the first character of the wrapped text into a drop cap span.
     * Works even when the drop cap auto-mode is off, giving manual control.
     */
    public function dropcap_shortcode( $atts, ?string $content = '' ): string {
        if ( '' === $content || null === $content ) {
            return '';
        }

        // Always apply the shortcode styling, even if auto-mode is off,
        // so manual use always works.
        $first = mb_substr( $content, 0, 1 );
        $rest  = mb_substr( $content, 1 );

        // If the drop cap feature is enabled the CSS is already enqueued.
        // If it's off, output a minimal inline style so the shortcode
        // still does something visible.
        $dc_enabled = (bool) get_option( $this->options['dc_enabled'], $this->defaults['ajc_dc_enabled'] );

        if ( ! $dc_enabled ) {
            $dc_color = sanitize_hex_color(
                get_option( $this->options['dc_color'], $this->defaults['ajc_dc_color'] )
            ) ?: $this->defaults['ajc_dc_color'];

            // Resolve font stack from saved setting so the shortcode respects
            // the configured font even when auto drop caps are turned off.
            $dc_font        = get_option( $this->options['dc_font'], $this->defaults['ajc_dc_font'] );
            $dc_custom_font = trim( (string) get_option( $this->options['dc_custom_font'], '' ) );
            $fonts          = $this->get_drop_cap_fonts();
            $font_stack     = 'inherit';

            if ( 'custom' === $dc_font && '' !== $dc_custom_font ) {
                $font_stack = $dc_custom_font;
            } elseif ( isset( $fonts[ $dc_font ]['stack'] ) && null !== $fonts[ $dc_font ]['stack'] ) {
                $font_stack = $fonts[ $dc_font ]['stack'];
            }

            // If it's a Google Font, trigger the non-blocking load so the
            // shortcode benefits from the configured font on this page.
            if ( isset( $fonts[ $dc_font ]['google'] ) && $fonts[ $dc_font ]['google']
                && '' === $this->google_font_param ) {
                $this->google_font_param = $fonts[ $dc_font ]['google'];
                add_filter( 'wp_resource_hints', [ $this, 'add_google_fonts_preconnect' ], 10, 2 );
                add_action( 'wp_head',           [ $this, 'output_google_font_link' ],     1 );
            }

            return sprintf(
                '<span class="ajc-drop-cap" style="float:left;font-size:3em;line-height:0.83;margin:0.04em 0.1em 0 0;font-family:%s;font-weight:700;color:%s;">%s</span>%s',
                esc_attr( $font_stack ),
                esc_attr( $dc_color ),
                esc_html( $first ),
                $rest
            );
        }

        return sprintf(
            '<span class="ajc-drop-cap">%s</span>%s',
            esc_html( $first ),
            $rest
        );
    }

    // -------------------------------------------------------------------------
    // Sanitizers
    // -------------------------------------------------------------------------

    public function sanitize_scope( $value ): string {
        return in_array( $value, [ 'blog_only', 'entire_site' ], true ) ? $value : 'blog_only';
    }

    public function sanitize_dc_style( $value ): string {
        return in_array( $value, [ 'drop', 'raised' ], true ) ? $value : 'drop';
    }

    public function sanitize_dc_lines( $value ): int {
        $int = (int) $value;
        return in_array( $int, [ 2, 3, 4 ], true ) ? $int : 3;
    }

    public function sanitize_dc_font( $value ): string {
        $allowed = array_keys( $this->get_drop_cap_fonts() );
        return in_array( $value, $allowed, true ) ? $value : 'playfair';
    }

    public function sanitize_exclude_selectors( $value ): string {
        $value = (string) $value;
        $value = str_replace( [ "\r", "\n", "\t" ], ' ', $value );
        $value = preg_replace( '/\s+/', ' ', $value );
        $value = trim( $value );

        if ( '' === $value ) {
            return '';
        }

        $value = preg_replace( '/[^a-zA-Z0-9\.\#\-\_\s\,\:\[\]\=\(\)\"\']/u', '', $value );
        $parts = array_filter( array_map( 'trim', explode( ',', $value ) ) );

        return implode( ', ', $parts );
    }

    // -------------------------------------------------------------------------
    // Option names (used by uninstall.php)
    // -------------------------------------------------------------------------

    public static function get_option_names(): array {
        return [
            // Justification
            'ajc_enabled',
            'ajc_scope',
            'ajc_hyphen',
            'ajc_mobile',
            'ajc_fallback',
            'ajc_exclude',
            // Drop cap
            'ajc_dc_enabled',
            'ajc_dc_style',
            'ajc_dc_lines',
            'ajc_dc_mobile',
            'ajc_dc_font',
            'ajc_dc_custom_font',
            'ajc_dc_color',
        ];
    }
}

new AutoJustifyContent();
