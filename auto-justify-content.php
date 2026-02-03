<?php
/**
 * Plugin Name: Auto Justify Content
 * Plugin URI: https://github.com/menj
 * Description: Automatically justifies text for Blog Posts or the Entire Site. Works with Elementor and most themes.
 * Version: 2.0.0
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

    private const VERSION = '2.0.0';
    private const TEXT_DOMAIN = 'auto-justify-content';

    private array $options = [
        'enabled'  => 'ajc_enabled',
        'scope'    => 'ajc_scope',
        'hyphen'   => 'ajc_hyphen',
        'mobile'   => 'ajc_mobile',
        'fallback' => 'ajc_fallback',
        'exclude'  => 'ajc_exclude',
    ];

    private array $defaults = [
        'ajc_enabled'  => true,
        'ajc_scope'    => 'blog_only',
        'ajc_hyphen'   => true,
        'ajc_mobile'   => false,
        'ajc_fallback' => false,
        'ajc_exclude'  => '.elementor-testimonial',
    ];

    public function __construct() {
        add_action( 'init', [ $this, 'load_textdomain' ] );
        add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_styles' ] );
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'add_settings_link' ] );
    }

    /**
     * Load plugin text domain for translations.
     */
    public function load_textdomain(): void {
        load_plugin_textdomain(
            self::TEXT_DOMAIN,
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages'
        );
    }

    /**
     * Register the settings page under Settings menu.
     */
    public function add_settings_page(): void {
        add_options_page(
            __( 'Auto Justify Content', self::TEXT_DOMAIN ),
            __( 'Auto Justify', self::TEXT_DOMAIN ),
            'manage_options',
            'auto-justify-content',
            [ $this, 'render_settings_page' ]
        );
    }

    /**
     * Add settings link to plugins page.
     */
    public function add_settings_link( array $links ): array {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url( admin_url( 'options-general.php?page=auto-justify-content' ) ),
            __( 'Settings', self::TEXT_DOMAIN )
        );
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Enqueue admin CSS and JS on our settings page only.
     */
    public function enqueue_admin_assets( string $hook ): void {
        if ( 'settings_page_auto-justify-content' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'ajc-admin',
            plugin_dir_url( __FILE__ ) . 'assets/css/admin.css',
            [],
            self::VERSION
        );

        wp_enqueue_script(
            'ajc-admin',
            plugin_dir_url( __FILE__ ) . 'assets/js/admin.js',
            [],
            self::VERSION,
            true
        );
    }

    /**
     * Register all plugin settings.
     */
    public function register_settings(): void {
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
    }

    /**
     * Render the settings page.
     */
    public function render_settings_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $enabled    = (bool) get_option( $this->options['enabled'], $this->defaults['ajc_enabled'] );
        $scope      = get_option( $this->options['scope'], $this->defaults['ajc_scope'] );
        $hyphen     = (bool) get_option( $this->options['hyphen'], $this->defaults['ajc_hyphen'] );
        $mobile     = (bool) get_option( $this->options['mobile'], $this->defaults['ajc_mobile'] );
        $fallback   = (bool) get_option( $this->options['fallback'], $this->defaults['ajc_fallback'] );
        $exclude    = get_option( $this->options['exclude'], $this->defaults['ajc_exclude'] );
        $active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';

        include plugin_dir_path( __FILE__ ) . 'templates/settings-page.php';
    }

    /**
     * Enqueue frontend justification styles.
     */
    public function enqueue_frontend_styles(): void {
        if ( ! get_option( $this->options['enabled'], $this->defaults['ajc_enabled'] ) ) {
            return;
        }

        $scope = get_option( $this->options['scope'], $this->defaults['ajc_scope'] );
        if ( 'blog_only' === $scope && ! is_singular( 'post' ) ) {
            return;
        }

        wp_register_style( 'ajc-justify', false, [], self::VERSION );
        wp_enqueue_style( 'ajc-justify' );
        wp_add_inline_style( 'ajc-justify', $this->generate_frontend_css() );
    }

    /**
     * Generate the frontend justification CSS based on settings.
     */
    private function generate_frontend_css(): string {
        $hyphen   = (bool) get_option( $this->options['hyphen'], $this->defaults['ajc_hyphen'] );
        $mobile   = (bool) get_option( $this->options['mobile'], $this->defaults['ajc_mobile'] );
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

    /**
     * Sanitize scope option.
     */
    public function sanitize_scope( $value ): string {
        return in_array( $value, [ 'blog_only', 'entire_site' ], true ) ? $value : 'blog_only';
    }

    /**
     * Sanitize exclude selectors option.
     */
    public function sanitize_exclude_selectors( $value ): string {
        $value = (string) $value;
        $value = str_replace( [ "\r", "\n", "\t" ], ' ', $value );
        $value = preg_replace( '/\s+/', ' ', $value );
        $value = trim( $value );

        if ( '' === $value ) {
            return '';
        }

        $value = preg_replace( '/[^a-zA-Z0-9\.\#\-\_\s\,\:\[\]\=\(\)\"\']/', '', $value );
        $parts = array_filter( array_map( 'trim', explode( ',', $value ) ) );

        return implode( ', ', $parts );
    }

    /**
     * Get all option names (for uninstall).
     */
    public static function get_option_names(): array {
        return [
            'ajc_enabled',
            'ajc_scope',
            'ajc_hyphen',
            'ajc_mobile',
            'ajc_fallback',
            'ajc_exclude',
        ];
    }
}

new AutoJustifyContent();
