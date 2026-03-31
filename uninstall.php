<?php
/**
 * Uninstall Auto Justify Content
 *
 * Removes all plugin options from the database when uninstalled.
 *
 * @package AutoJustifyContent
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$options = [
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

foreach ( $options as $option ) {
    delete_option( $option );
}
