<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Sample Theme
 */

// Includes the files needed for the theme updater
if ( ! class_exists( 'ExtendThemes_EDD_Theme_Updater_Admin' ) ) {
    include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}


function mesmerize_get_theme_name() {
    $theme = wp_get_theme();

    if ( $theme->get( 'Template' ) ) {
        $theme = wp_get_theme( $theme->get( 'Template' ) );
    }

    $theme_name = $theme->get( 'Name' );

    return $theme_name;
}

// Loads the updater classes
$updater = new ExtendThemes_EDD_Theme_Updater_Admin(
    $config = array(
        'remote_api_url' => apply_filters( 'extendthemes_updater_endpoint_url', 'http://members.extendthemes.com' ),
        'item_name'      => mesmerize_get_theme_name(),
        'theme_slug'     => apply_filters( 'extendthemes_theme_updater_slug', get_template() ),
        'version'        => mesmerize_get_version(),
        'author'         => 'Extend Themes',
    ),

    $strings = array(
        'theme-license'             => __( 'Theme License', 'mesmerize-pro' ),
        'enter-key'                 => __( 'Enter your theme license key.', 'mesmerize-pro' ),
        'license-key'               => __( 'License Key', 'mesmerize-pro' ),
        'license-action'            => __( 'License Action', 'mesmerize-pro' ),
        'deactivate-license'        => __( 'Deactivate License', 'mesmerize-pro' ),
        'activate-license'          => __( 'Activate License', 'mesmerize-pro' ),
        'status-unknown'            => __( 'License status is unknown.', 'mesmerize-pro' ),
        'renew'                     => __( 'Renew license', 'mesmerize-pro' ),
        'unlimited'                 => __( 'unlimited', 'mesmerize-pro' ),
        'license-key-is-active'     => __( 'License key is active.', 'mesmerize-pro' ),
        'expires%s'                 => __( 'Expires %s.', 'mesmerize-pro' ),
        'expires-never'             => __( 'Lifetime License.', 'mesmerize-pro' ),
        '%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'mesmerize-pro' ),
        'license-key-expired-%s'    => __( 'License key expired %s.', 'mesmerize-pro' ),
        'license-key-expired'       => __( 'License key has expired.', 'mesmerize-pro' ),
        'license-keys-do-not-match' => __( 'License keys do not match.', 'mesmerize-pro' ),
        'license-is-inactive'       => __( 'License is inactive.', 'mesmerize-pro' ),
        'license-key-is-disabled'   => __( 'License key is disabled.', 'mesmerize-pro' ),
        'site-is-inactive'          => __( 'Site is inactive.', 'mesmerize-pro' ),
        'license-status-unknown'    => __( 'License status is unknown.', 'mesmerize-pro' ),
        'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.",
            'mesmerize-pro' ),
    )

);
