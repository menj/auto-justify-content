<?php
/**
 * Settings Page Template
 *
 * @package AutoJustifyContent
 * @since 3.0.0
 *
 * Variables available from render_settings_page():
 * @var bool   $enabled
 * @var string $scope
 * @var bool   $hyphen
 * @var bool   $mobile
 * @var bool   $fallback
 * @var string $exclude
 * @var bool   $dc_enabled
 * @var string $dc_style
 * @var int    $dc_lines
 * @var bool   $dc_mobile
 * @var string $dc_font
 * @var string $dc_custom_font
 * @var string $dc_color
 * @var string $active_tab
 * @var array  $fonts
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="ajc-wrap">
    <div class="ajc-container">

        <header class="ajc-header">
            <div class="ajc-logo">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="36" height="36" rx="8" fill="currentColor" fill-opacity="0.1"/>
                    <line x1="9" y1="11" x2="27" y2="11" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    <line x1="9" y1="18" x2="27" y2="18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    <line x1="9" y1="25" x2="20" y2="25" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    <text x="22" y="28" font-size="10" fill="currentColor" font-weight="800" font-family="serif" opacity="0.9">T</text>
                </svg>
            </div>
            <div class="ajc-title-group">
                <h1><?php esc_html_e( 'Auto Justify Content', 'auto-justify-content' ); ?></h1>
                <span class="ajc-version">3.0.0</span>
            </div>
        </header>

        <?php if ( isset( $_GET['settings-updated'] ) ) : ?>
            <div class="ajc-notice ajc-notice-success">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.5-4.5l5-5-1-1-4 4-2-2-1 1 3 3z" fill="currentColor"/>
                </svg>
                <?php esc_html_e( 'Settings saved successfully.', 'auto-justify-content' ); ?>
            </div>
        <?php endif; ?>

        <nav class="ajc-tabs">
            <a href="<?php echo esc_url( admin_url( 'options-general.php?page=auto-justify-content&tab=general' ) ); ?>"
               class="ajc-tab <?php echo 'general' === $active_tab ? 'ajc-tab-active' : ''; ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="14" y2="18"/>
                </svg>
                <?php esc_html_e( 'Justification', 'auto-justify-content' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'options-general.php?page=auto-justify-content&tab=dropcap' ) ); ?>"
               class="ajc-tab <?php echo 'dropcap' === $active_tab ? 'ajc-tab-active' : ''; ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 7V4h8v3M8 4v16M12 20H4"/><path d="M17 4h3l-3 8h3"/>
                </svg>
                <?php esc_html_e( 'Drop Cap', 'auto-justify-content' ); ?>
                <?php if ( $dc_enabled ) : ?>
                    <span class="ajc-tab-badge"><?php esc_html_e( 'On', 'auto-justify-content' ); ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'options-general.php?page=auto-justify-content&tab=advanced' ) ); ?>"
               class="ajc-tab <?php echo 'advanced' === $active_tab ? 'ajc-tab-active' : ''; ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                <?php esc_html_e( 'Advanced', 'auto-justify-content' ); ?>
            </a>
        </nav>

        <form method="post" action="options.php" class="ajc-form">
            <?php settings_fields( 'ajc_settings' ); ?>

            <!-- ============================================================ -->
            <!-- TAB: Justification                                            -->
            <!-- ============================================================ -->
            <div class="ajc-tab-content <?php echo 'general' === $active_tab ? 'ajc-tab-content-active' : ''; ?>" data-tab="general">

                <div class="ajc-card ajc-card-primary">
                    <div class="ajc-setting-row ajc-setting-main">
                        <div class="ajc-setting-info">
                            <label for="ajc_enabled"><?php esc_html_e( 'Enable Justification', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Align text neatly on both the left and right sides, like a book or newspaper.', 'auto-justify-content' ); ?></p>
                        </div>
                        <label class="ajc-toggle">
                            <input type="checkbox" id="ajc_enabled" name="ajc_enabled" value="1" <?php checked( $enabled ); ?>>
                            <span class="ajc-toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="ajc-card">
                    <h2 class="ajc-card-title"><?php esc_html_e( 'Scope', 'auto-justify-content' ); ?></h2>

                    <div class="ajc-setting-row">
                        <div class="ajc-setting-info">
                            <label for="ajc_scope"><?php esc_html_e( 'Apply Justification To', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Choose whether justification applies only to blog posts or across your entire site.', 'auto-justify-content' ); ?></p>
                        </div>
                        <div class="ajc-select-wrap">
                            <select id="ajc_scope" name="ajc_scope">
                                <option value="blog_only" <?php selected( $scope, 'blog_only' ); ?>>
                                    <?php esc_html_e( 'Blog Posts Only', 'auto-justify-content' ); ?>
                                </option>
                                <option value="entire_site" <?php selected( $scope, 'entire_site' ); ?>>
                                    <?php esc_html_e( 'Entire Site', 'auto-justify-content' ); ?>
                                </option>
                            </select>
                            <svg class="ajc-select-arrow" width="12" height="12" viewBox="0 0 12 12"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                        </div>
                    </div>

                    <div class="ajc-setting-row">
                        <div class="ajc-setting-info">
                            <label for="ajc_mobile"><?php esc_html_e( 'Desktop Only', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Disable justification on phones and tablets (screens narrower than 768px).', 'auto-justify-content' ); ?></p>
                        </div>
                        <label class="ajc-toggle">
                            <input type="checkbox" id="ajc_mobile" name="ajc_mobile" value="1" <?php checked( $mobile ); ?>>
                            <span class="ajc-toggle-slider"></span>
                        </label>
                    </div>
                </div>

            </div><!-- /general tab -->

            <!-- ============================================================ -->
            <!-- TAB: Drop Cap                                                 -->
            <!-- ============================================================ -->
            <div class="ajc-tab-content <?php echo 'dropcap' === $active_tab ? 'ajc-tab-content-active' : ''; ?>" data-tab="dropcap">

                <!-- Live Preview -->
                <div class="ajc-preview-card" id="ajcPreviewCard">
                    <div class="ajc-preview-label">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <?php esc_html_e( 'Live Preview', 'auto-justify-content' ); ?>
                    </div>
                    <div class="ajc-preview-body" id="ajcPreviewBody">
                        <p class="ajc-preview-text" id="ajcPreviewText">
                            <span class="ajc-preview-cap" id="ajcPreviewCap">T</span><?php esc_html_e( 'ypography is the art of arranging type to make written language legible, readable, and visually appealing. When done well, it becomes invisible — the reader simply experiences the words.', 'auto-justify-content' ); ?>
                        </p>
                    </div>
                    <div class="ajc-preview-gdpr" id="ajcPreviewGdpr">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <?php esc_html_e( 'Google Fonts are loaded from Google\'s servers. If your site serves EU visitors, confirm your privacy policy covers third-party font requests.', 'auto-justify-content' ); ?>
                    </div>
                </div>

                <!-- Master toggle -->
                <div class="ajc-card ajc-card-primary">
                    <div class="ajc-setting-row ajc-setting-main">
                        <div class="ajc-setting-info">
                            <label for="ajc_dc_enabled"><?php esc_html_e( 'Enable Drop Cap', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Automatically style the first letter of each post as a large decorative initial — independent of the Gutenberg block editor\'s built-in drop cap.', 'auto-justify-content' ); ?></p>
                        </div>
                        <label class="ajc-toggle">
                            <input type="checkbox" id="ajc_dc_enabled" name="ajc_dc_enabled" value="1" <?php checked( $dc_enabled ); ?>>
                            <span class="ajc-toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Style controls -->
                <div class="ajc-card">
                    <h2 class="ajc-card-title"><?php esc_html_e( 'Style', 'auto-justify-content' ); ?></h2>

                    <div class="ajc-setting-row">
                        <div class="ajc-setting-info">
                            <label><?php esc_html_e( 'Cap Style', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Drop cap descends into the text; raised cap sits on the baseline and rises above the first line.', 'auto-justify-content' ); ?></p>
                        </div>
                        <div class="ajc-style-picker">
                            <label class="ajc-style-option <?php echo 'drop' === $dc_style ? 'ajc-style-active' : ''; ?>">
                                <input type="radio" name="ajc_dc_style" value="drop" <?php checked( $dc_style, 'drop' ); ?>>
                                <span class="ajc-style-icon">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                        <text x="2" y="26" font-size="26" fill="currentColor" font-family="serif" font-weight="bold">D</text>
                                        <line x1="14" y1="8" x2="30" y2="8" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                                        <line x1="14" y1="14" x2="30" y2="14" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                                        <line x1="14" y1="20" x2="30" y2="20" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                                        <line x1="2" y1="26" x2="30" y2="26" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                                    </svg>
                                </span>
                                <span class="ajc-style-label"><?php esc_html_e( 'Drop', 'auto-justify-content' ); ?></span>
                            </label>
                            <label class="ajc-style-option <?php echo 'raised' === $dc_style ? 'ajc-style-active' : ''; ?>">
                                <input type="radio" name="ajc_dc_style" value="raised" <?php checked( $dc_style, 'raised' ); ?>>
                                <span class="ajc-style-icon">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                        <text x="2" y="26" font-size="26" fill="currentColor" font-family="serif" font-weight="bold">R</text>
                                        <line x1="14" y1="14" x2="30" y2="14" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                                        <line x1="14" y1="20" x2="30" y2="20" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                                        <line x1="2" y1="26" x2="30" y2="26" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                                    </svg>
                                </span>
                                <span class="ajc-style-label"><?php esc_html_e( 'Raised', 'auto-justify-content' ); ?></span>
                            </label>
                        </div>
                    </div>

                    <div class="ajc-setting-row">
                        <div class="ajc-setting-info">
                            <label for="ajc_dc_lines"><?php esc_html_e( 'Line Span', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'How many lines of text the drop cap letter should span in height.', 'auto-justify-content' ); ?></p>
                        </div>
                        <div class="ajc-lines-picker">
                            <?php foreach ( [ 2, 3, 4 ] as $n ) : ?>
                                <label class="ajc-lines-option <?php echo $dc_lines === $n ? 'ajc-lines-active' : ''; ?>">
                                    <input type="radio" name="ajc_dc_lines" value="<?php echo esc_attr( $n ); ?>" <?php checked( $dc_lines, $n ); ?>>
                                    <span><?php echo esc_html( $n ); ?></span>
                                    <small><?php esc_html_e( 'lines', 'auto-justify-content' ); ?></small>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="ajc-setting-row">
                        <div class="ajc-setting-info">
                            <label for="ajc_dc_mobile"><?php esc_html_e( 'Desktop Only', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Disable drop cap on phones and tablets.', 'auto-justify-content' ); ?></p>
                        </div>
                        <label class="ajc-toggle">
                            <input type="checkbox" id="ajc_dc_mobile" name="ajc_dc_mobile" value="1" <?php checked( $dc_mobile ); ?>>
                            <span class="ajc-toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Font controls -->
                <div class="ajc-card">
                    <h2 class="ajc-card-title"><?php esc_html_e( 'Font', 'auto-justify-content' ); ?></h2>

                    <div class="ajc-setting-row">
                        <div class="ajc-setting-info">
                            <label for="ajc_dc_font"><?php esc_html_e( 'Drop Cap Font', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Choose a typeface for the initial letter. Fonts marked with ✦ are loaded from Google Fonts.', 'auto-justify-content' ); ?></p>
                        </div>
                        <div class="ajc-select-wrap">
                            <select id="ajc_dc_font" name="ajc_dc_font">
                                <?php foreach ( $fonts as $key => $font ) : ?>
                                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $dc_font, $key ); ?>>
                                        <?php
                                        $gf_marker = ( ! empty( $font['google'] ) ) ? ' ✦' : '';
                                        echo esc_html( $font['label'] . $gf_marker );
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <svg class="ajc-select-arrow" width="12" height="12" viewBox="0 0 12 12"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                        </div>
                    </div>

                    <div class="ajc-setting-row ajc-custom-font-row <?php echo 'custom' !== $dc_font ? 'ajc-hidden' : ''; ?>" id="ajcCustomFontRow">
                        <div class="ajc-setting-info">
                            <label for="ajc_dc_custom_font"><?php esc_html_e( 'Custom Font Family', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Enter a CSS font-family value. You are responsible for loading this font via your theme or another plugin.', 'auto-justify-content' ); ?></p>
                        </div>
                        <input
                            type="text"
                            id="ajc_dc_custom_font"
                            name="ajc_dc_custom_font"
                            value="<?php echo esc_attr( $dc_custom_font ); ?>"
                            class="ajc-input ajc-input-sm"
                            placeholder="&quot;My Font&quot;, serif"
                        >
                    </div>

                    <div class="ajc-setting-row">
                        <div class="ajc-setting-info">
                            <label for="ajc_dc_color"><?php esc_html_e( 'Letter Colour', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'The colour of the drop cap letter. Defaults to your body text colour.', 'auto-justify-content' ); ?></p>
                        </div>
                        <div class="ajc-color-wrap">
                            <input
                                type="text"
                                id="ajc_dc_color"
                                name="ajc_dc_color"
                                value="<?php echo esc_attr( $dc_color ); ?>"
                                class="ajc-color-picker"
                                data-default-color="<?php echo esc_attr( $this->defaults['ajc_dc_color'] ?? '#1e293b' ); ?>"
                            >
                        </div>
                    </div>
                </div>

                <!-- Shortcode info card -->
                <div class="ajc-card ajc-card-info">
                    <div class="ajc-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    </div>
                    <div class="ajc-info-content">
                        <h3><?php esc_html_e( 'Manual Drop Cap Shortcode', 'auto-justify-content' ); ?></h3>
                        <p><?php esc_html_e( 'You can also apply a drop cap anywhere using the shortcode:', 'auto-justify-content' ); ?></p>
                        <code class="ajc-code">[dropcap]Word[/dropcap]</code>
                        <p><?php esc_html_e( 'This works in the Classic Editor, text widgets, and anywhere shortcodes are supported — even if automatic drop caps are turned off.', 'auto-justify-content' ); ?></p>
                    </div>
                </div>

            </div><!-- /dropcap tab -->

            <!-- ============================================================ -->
            <!-- TAB: Advanced                                                 -->
            <!-- ============================================================ -->
            <div class="ajc-tab-content <?php echo 'advanced' === $active_tab ? 'ajc-tab-content-active' : ''; ?>" data-tab="advanced">

                <div class="ajc-card">
                    <h2 class="ajc-card-title"><?php esc_html_e( 'Hyphenation', 'auto-justify-content' ); ?></h2>

                    <div class="ajc-setting-row ajc-setting-main">
                        <div class="ajc-setting-info">
                            <label for="ajc_hyphen"><?php esc_html_e( 'Enable Hyphenation', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Let long words break across lines with a hyphen, preventing large gaps in justified text.', 'auto-justify-content' ); ?></p>
                            <p class="ajc-hint"><?php esc_html_e( 'Requires a correct lang attribute on your HTML element — WordPress sets this automatically for most sites.', 'auto-justify-content' ); ?></p>
                        </div>
                        <label class="ajc-toggle">
                            <input type="checkbox" id="ajc_hyphen" name="ajc_hyphen" value="1" <?php checked( $hyphen ); ?>>
                            <span class="ajc-toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="ajc-card">
                    <h2 class="ajc-card-title"><?php esc_html_e( 'Theme Compatibility', 'auto-justify-content' ); ?></h2>

                    <div class="ajc-setting-row ajc-setting-main">
                        <div class="ajc-setting-info">
                            <label for="ajc_fallback"><?php esc_html_e( 'Theme Fallback', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Apply styles to generic article elements when your theme doesn\'t use standard WordPress content wrappers.', 'auto-justify-content' ); ?></p>
                            <p class="ajc-hint"><?php esc_html_e( 'Enable this if justification or drop caps are not appearing with your theme.', 'auto-justify-content' ); ?></p>
                        </div>
                        <label class="ajc-toggle">
                            <input type="checkbox" id="ajc_fallback" name="ajc_fallback" value="1" <?php checked( $fallback ); ?>>
                            <span class="ajc-toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="ajc-card">
                    <h2 class="ajc-card-title"><?php esc_html_e( 'Exclusions', 'auto-justify-content' ); ?></h2>

                    <div class="ajc-setting-row ajc-setting-vertical">
                        <div class="ajc-setting-info">
                            <label for="ajc_exclude"><?php esc_html_e( 'Exclude CSS Selectors', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Areas listed here will be excluded from both justification and drop caps. Separate multiple selectors with commas.', 'auto-justify-content' ); ?></p>
                        </div>
                        <input
                            type="text"
                            id="ajc_exclude"
                            name="ajc_exclude"
                            value="<?php echo esc_attr( $exclude ); ?>"
                            class="ajc-input"
                            placeholder=".no-justify, .testimonial-widget, .sidebar"
                        >
                    </div>
                </div>

                <div class="ajc-card ajc-card-info">
                    <div class="ajc-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    </div>
                    <div class="ajc-info-content">
                        <h3><?php esc_html_e( 'Common Exclusion Selectors', 'auto-justify-content' ); ?></h3>
                        <p><?php esc_html_e( 'Frequently used: .elementor-testimonial, .wp-block-quote, .sidebar, .widget-area, .no-justify, .pullquote', 'auto-justify-content' ); ?></p>
                    </div>
                </div>

            </div><!-- /advanced tab -->

            <div class="ajc-actions">
                <?php submit_button( __( 'Save Settings', 'auto-justify-content' ), 'ajc-button-primary', 'submit', false ); ?>
            </div>

        </form>

        <footer class="ajc-footer">
            <p>
                <?php
                printf(
                    /* translators: %s: author name */
                    esc_html__( 'Developed by %s', 'auto-justify-content' ),
                    '<a href="https://github.com/menj" target="_blank" rel="noopener">MENJ</a>'
                );
                ?>
            </p>
        </footer>

    </div><!-- /.ajc-container -->
</div><!-- /.ajc-wrap -->
