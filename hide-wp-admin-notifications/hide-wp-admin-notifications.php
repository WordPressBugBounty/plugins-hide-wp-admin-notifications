<?php
/**
 * Plugin Name: Hide WP Admin Notifications
 * Description: Hides WordPress admin notices until you choose to show them again from Settings.
 * Version: 0.2.3
 * Author: Yoda Of WP
 * Requires at least: 5.2
 * Tested up to: 7.0
 * Requires PHP: 7.0
 * Text Domain: hide-wp-admin-notifications
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

define( 'HWAN_PLUGIN_VERSION', '0.2.3' );
define( 'HWAN_OPTION_SHOW_NOTICES', 'hwan_show_dashboard_notices' );
define( 'HWAN_OPTION_PLUGIN_VERSION', 'hwan_plugin_version' );
define( 'HWAN_SETTINGS_PAGE', 'hide-wp-admin-notifications' );
define( 'HWAN_SETTINGS_GROUP', 'hwan_settings' );

// Ensure option exists for seamless version updates
function hwan_ensure_option_exists() {
    if ( false === get_option( HWAN_OPTION_SHOW_NOTICES ) ) {
        add_option( HWAN_OPTION_SHOW_NOTICES, 'no', '', false );
    }

    if ( false === get_option( HWAN_OPTION_PLUGIN_VERSION ) ) {
        add_option( HWAN_OPTION_PLUGIN_VERSION, HWAN_PLUGIN_VERSION, '', false );
    }
}
register_activation_hook( __FILE__, 'hwan_ensure_option_exists' );

// Maintain existing setting during updates
function hwan_update_check() {
    if ( get_option( HWAN_OPTION_PLUGIN_VERSION ) !== HWAN_PLUGIN_VERSION ) {
        hwan_ensure_option_exists();
        update_option( HWAN_OPTION_PLUGIN_VERSION, HWAN_PLUGIN_VERSION, false );
    }
}
add_action( 'plugins_loaded', 'hwan_update_check' );

// Get the current notice visibility setting.
function hwan_get_show_dashboard_notices() {
    return get_option( HWAN_OPTION_SHOW_NOTICES, 'no' );
}

// Remove admin notices based on the saved setting.
function hwan_remove_admin_notices() {
    $show_notices = hwan_get_show_dashboard_notices();

    if ( hwan_is_settings_page() ) {
        return;
    }

    if ( 'yes' !== $show_notices ) {
        global $wp_filter;

        foreach ( array( 'user_admin_notices', 'admin_notices', 'all_admin_notices', 'network_admin_notices' ) as $hook_name ) {
            if ( isset( $wp_filter[ $hook_name ] ) && $wp_filter[ $hook_name ] instanceof WP_Hook ) {
                $wp_filter[ $hook_name ]->remove_all_filters();
            }
        }
    }
}
add_action( 'in_admin_header', 'hwan_remove_admin_notices', 0 );

// Check whether the current admin screen is this plugin's settings page.
function hwan_is_settings_page() {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

    return $screen && 'settings_page_' . HWAN_SETTINGS_PAGE === $screen->id;
}

// Add plugin settings page
function hwan_admin_menu() {
    add_options_page(
        __( 'Hide WP Admin Notifications', 'hide-wp-admin-notifications' ),
        __( 'Hide WP Admin Notifications', 'hide-wp-admin-notifications' ),
        'manage_options',
        HWAN_SETTINGS_PAGE,
        'hwan_options_page'
    );
}
add_action( 'admin_menu', 'hwan_admin_menu' );

// Register settings and fields
function hwan_settings_init() {
    register_setting(
        HWAN_SETTINGS_GROUP,
        HWAN_OPTION_SHOW_NOTICES,
        array(
            'sanitize_callback' => 'hwan_sanitize_checkbox',
            'default'           => 'no',
        )
    );

    add_settings_section(
        'hwan_settings_section',
        '',
        null,
        HWAN_SETTINGS_PAGE
    );

    add_settings_field(
        HWAN_OPTION_SHOW_NOTICES,
        __( 'Show admin notices', 'hide-wp-admin-notifications' ),
        'hwan_show_dashboard_notices_cb',
        HWAN_SETTINGS_PAGE,
        'hwan_settings_section'
    );
}
add_action( 'admin_init', 'hwan_settings_init' );

// Sanitize checkbox value
function hwan_sanitize_checkbox( $value ) {
    return ( 'yes' === $value ) ? 'yes' : 'no';
}

// Show dashboard notices setting field
function hwan_show_dashboard_notices_cb() {
    $show_notices = hwan_get_show_dashboard_notices();
    ?>
    <fieldset class="hwan-setting-field">
        <input type="hidden" name="<?php echo esc_attr( HWAN_OPTION_SHOW_NOTICES ); ?>" value="no" />
        <label class="hwan-checkbox-label" for="<?php echo esc_attr( HWAN_OPTION_SHOW_NOTICES ); ?>">
            <input
                id="<?php echo esc_attr( HWAN_OPTION_SHOW_NOTICES ); ?>"
                name="<?php echo esc_attr( HWAN_OPTION_SHOW_NOTICES ); ?>"
                type="checkbox"
                value="yes"
                <?php checked( $show_notices, 'yes' ); ?>
            />
            <span class="checkmark" aria-hidden="true"></span>
            <?php esc_html_e( 'Show admin notices again', 'hide-wp-admin-notifications' ); ?>
        </label>
        <p class="description">
            <?php esc_html_e( 'Check this box to show admin notices temporarily. Uncheck it to hide them again.', 'hide-wp-admin-notifications' ); ?>
        </p>
    </fieldset>
    <?php
}

// Display the plugin settings page
function hwan_options_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die(
            esc_html__(
                'You do not have sufficient permissions to access this page.',
                'hide-wp-admin-notifications'
            )
        );
    }
    ?>
    <div class="hwan-settings-container">
        <div class="hwan-header">
            <svg class="hwan-logo" width="48" height="48" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                <rect width="256" height="256" rx="52" fill="#111111" />
                <path d="M58 176 C82 176 78 150 80 120 C82 92 100 60 128 60 C156 60 174 92 176 120 C178 150 174 176 198 176 Z" fill="#ffffff" />
                <rect x="119" y="42" width="18" height="20" rx="9" fill="#ffffff" />
                <path d="M112 182 C112 202 144 202 144 182 Z" fill="#ffffff" />
                <line x1="50" y1="44" x2="210" y2="204" stroke="#111111" stroke-width="34" stroke-linecap="round" />
                <line x1="50" y1="44" x2="210" y2="204" stroke="#ff5600" stroke-width="16" stroke-linecap="round" />
            </svg>
            <div>
                <p class="hwan-eyebrow"><?php esc_html_e( 'Plugin settings', 'hide-wp-admin-notifications' ); ?></p>
                <h1><?php esc_html_e( 'Hide WP Admin Notifications', 'hide-wp-admin-notifications' ); ?></h1>
            </div>
        </div>
        <div class="hwan-message">
            <p>
                <?php esc_html_e( 'It is still a good idea to review admin notices from time to time so you do not miss security, update, or maintenance messages.', 'hide-wp-admin-notifications' ); ?>
            </p>
        </div>
        <form method="post" action="options.php">
            <?php
            settings_fields( HWAN_SETTINGS_GROUP );
            do_settings_sections( HWAN_SETTINGS_PAGE );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Add settings link to the plugin action links
function hwan_add_settings_link( $links ) {
    $settings_link = sprintf(
        '<a href="%s">%s</a>',
        esc_url( admin_url( 'options-general.php?page=' . HWAN_SETTINGS_PAGE ) ),
        esc_html__( 'Settings', 'hide-wp-admin-notifications' )
    );

    array_unshift( $links, $settings_link );

    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'hwan_add_settings_link' );

// Enqueue admin styles for the plugin settings page
function hwan_enqueue_admin_styles( $hook ) {
    $is_settings_page = 'settings_page_' . HWAN_SETTINGS_PAGE === $hook;

    if ( ! $is_settings_page ) {
        $screen           = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        $is_settings_page = $screen && 'settings_page_' . HWAN_SETTINGS_PAGE === $screen->id;
    }

    if ( ! $is_settings_page ) {
        return;
    }

    $stylesheet_path    = plugin_dir_path( __FILE__ ) . 'admin.css';
    $stylesheet_version = file_exists( $stylesheet_path ) ? filemtime( $stylesheet_path ) : HWAN_PLUGIN_VERSION;

    wp_enqueue_style(
        'hwan-admin-styles',
        plugins_url( 'admin.css', __FILE__ ),
        array(),
        $stylesheet_version
    );
}
add_action( 'admin_enqueue_scripts', 'hwan_enqueue_admin_styles' );
