<?php
/**
 * Settings Page Template
 *
 * @package AutoJustifyContent
 * @since 2.0.0
 *
 * Variables available:
 * @var bool   $enabled
 * @var string $scope
 * @var bool   $hyphen
 * @var bool   $mobile
 * @var bool   $fallback
 * @var string $exclude
 * @var string $active_tab
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="ajc-wrap">
    <div class="ajc-container">
        <header class="ajc-header">
            <div class="ajc-logo">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="32" height="32" rx="6" fill="currentColor" fill-opacity="0.1"/>
                    <line x1="8" y1="10" x2="24" y2="10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <line x1="8" y1="16" x2="24" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <line x1="8" y1="22" x2="18" y2="22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="ajc-title-group">
                <h1><?php esc_html_e( 'Auto Justify Content', 'auto-justify-content' ); ?></h1>
                <span class="ajc-version">2.0.0</span>
            </div>
        </header>

        <?php if ( isset( $_GET['settings-updated'] ) ) : ?>
            <div class="ajc-notice ajc-notice-success">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.5-4.5l5-5-1-1-4 4-2-2-1 1 3 3z" fill="currentColor"/>
                </svg>
                <?php esc_html_e( 'Settings saved successfully.', 'auto-justify-content' ); ?>
            </div>
        <?php endif; ?>

        <nav class="ajc-tabs">
            <a href="<?php echo esc_url( admin_url( 'options-general.php?page=auto-justify-content&tab=general' ) ); ?>" 
               class="ajc-tab <?php echo 'general' === $active_tab ? 'ajc-tab-active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                <?php esc_html_e( 'General', 'auto-justify-content' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'options-general.php?page=auto-justify-content&tab=typography' ) ); ?>" 
               class="ajc-tab <?php echo 'typography' === $active_tab ? 'ajc-tab-active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="4 7 4 4 20 4 20 7"/>
                    <line x1="9" y1="20" x2="15" y2="20"/>
                    <line x1="12" y1="4" x2="12" y2="20"/>
                </svg>
                <?php esc_html_e( 'Typography', 'auto-justify-content' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'options-general.php?page=auto-justify-content&tab=advanced' ) ); ?>" 
               class="ajc-tab <?php echo 'advanced' === $active_tab ? 'ajc-tab-active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="4" y1="21" x2="4" y2="14"/>
                    <line x1="4" y1="10" x2="4" y2="3"/>
                    <line x1="12" y1="21" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12" y2="3"/>
                    <line x1="20" y1="21" x2="20" y2="16"/>
                    <line x1="20" y1="12" x2="20" y2="3"/>
                    <line x1="1" y1="14" x2="7" y2="14"/>
                    <line x1="9" y1="8" x2="15" y2="8"/>
                    <line x1="17" y1="16" x2="23" y2="16"/>
                </svg>
                <?php esc_html_e( 'Advanced', 'auto-justify-content' ); ?>
            </a>
        </nav>

        <form method="post" action="options.php" class="ajc-form">
            <?php settings_fields( 'ajc_settings' ); ?>

            <!-- General Tab -->
            <div class="ajc-tab-content <?php echo 'general' === $active_tab ? 'ajc-tab-content-active' : ''; ?>" data-tab="general">
                <div class="ajc-card ajc-card-primary">
                    <div class="ajc-setting-row ajc-setting-main">
                        <div class="ajc-setting-info">
                            <label for="ajc_enabled"><?php esc_html_e( 'Enable Plugin', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Master switch for text justification across your site.', 'auto-justify-content' ); ?></p>
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
                            <label for="ajc_scope"><?php esc_html_e( 'Justification Scope', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Choose where text justification should be applied.', 'auto-justify-content' ); ?></p>
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
                            <svg class="ajc-select-arrow" width="12" height="12" viewBox="0 0 12 12">
                                <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" fill="none"/>
                            </svg>
                        </div>
                    </div>

                    <div class="ajc-setting-row">
                        <div class="ajc-setting-info">
                            <label for="ajc_mobile"><?php esc_html_e( 'Desktop Only', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Disable justification on screens smaller than 768px.', 'auto-justify-content' ); ?></p>
                        </div>
                        <label class="ajc-toggle">
                            <input type="checkbox" id="ajc_mobile" name="ajc_mobile" value="1" <?php checked( $mobile ); ?>>
                            <span class="ajc-toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Typography Tab -->
            <div class="ajc-tab-content <?php echo 'typography' === $active_tab ? 'ajc-tab-content-active' : ''; ?>" data-tab="typography">
                <div class="ajc-card">
                    <h2 class="ajc-card-title"><?php esc_html_e( 'Hyphenation', 'auto-justify-content' ); ?></h2>
                    
                    <div class="ajc-setting-row">
                        <div class="ajc-setting-info">
                            <label for="ajc_hyphen"><?php esc_html_e( 'Enable Hyphenation', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Enable automatic hyphenation for cleaner text in narrow columns.', 'auto-justify-content' ); ?></p>
                            <p class="ajc-hint"><?php esc_html_e( 'Requires proper lang attribute on your HTML element.', 'auto-justify-content' ); ?></p>
                        </div>
                        <label class="ajc-toggle">
                            <input type="checkbox" id="ajc_hyphen" name="ajc_hyphen" value="1" <?php checked( $hyphen ); ?>>
                            <span class="ajc-toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="ajc-card ajc-card-info">
                    <div class="ajc-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                    </div>
                    <div class="ajc-info-content">
                        <h3><?php esc_html_e( 'About Hyphenation', 'auto-justify-content' ); ?></h3>
                        <p><?php esc_html_e( 'CSS hyphenation relies on the browser\'s built-in dictionary for your document\'s language. Ensure your theme outputs the correct lang attribute (e.g., lang="en") on the HTML element for best results.', 'auto-justify-content' ); ?></p>
                    </div>
                </div>
            </div>

            <!-- Advanced Tab -->
            <div class="ajc-tab-content <?php echo 'advanced' === $active_tab ? 'ajc-tab-content-active' : ''; ?>" data-tab="advanced">
                <div class="ajc-card">
                    <h2 class="ajc-card-title"><?php esc_html_e( 'Theme Compatibility', 'auto-justify-content' ); ?></h2>
                    
                    <div class="ajc-setting-row">
                        <div class="ajc-setting-info">
                            <label for="ajc_fallback"><?php esc_html_e( 'Theme Fallback', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'Apply to generic article elements when theme lacks standard wrappers.', 'auto-justify-content' ); ?></p>
                            <p class="ajc-hint"><?php esc_html_e( 'Enable this if justification doesn\'t work with your theme.', 'auto-justify-content' ); ?></p>
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
                            <label for="ajc_exclude"><?php esc_html_e( 'Exclude Selectors', 'auto-justify-content' ); ?></label>
                            <p><?php esc_html_e( 'CSS selectors to exclude from justification (comma-separated).', 'auto-justify-content' ); ?></p>
                        </div>
                        <input 
                            type="text" 
                            id="ajc_exclude" 
                            name="ajc_exclude" 
                            value="<?php echo esc_attr( $exclude ); ?>" 
                            class="ajc-input"
                            placeholder=".no-justify, .testimonial, .widget-area"
                        >
                    </div>
                </div>

                <div class="ajc-card ajc-card-info">
                    <div class="ajc-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                    </div>
                    <div class="ajc-info-content">
                        <h3><?php esc_html_e( 'Selector Examples', 'auto-justify-content' ); ?></h3>
                        <p><?php esc_html_e( 'Common selectors to exclude: .elementor-testimonial, .wp-block-quote, .sidebar, .widget-area, .no-justify', 'auto-justify-content' ); ?></p>
                    </div>
                </div>
            </div>

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
    </div>
</div>
