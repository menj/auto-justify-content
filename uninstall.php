<?php
/**
 * Uninstall Auto Justify Content
 *
 * Removes all plugin options from the database when uninstalled.
 *
 * @package AutoJustifyContent
 */

// Exit if not called by WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove all plugin options.
$options = [
    'ajc_enabled',
    'ajc_scope',
    'ajc_hyphen',
    'ajc_mobile',
    'ajc_fallback',
    'ajc_exclude',
];

foreach ( $options as $option ) {
    delete_option( $option );
}
