<?php
/**
 * Theme updater admin page and functions.
 *
 * @package EDD Sample Theme
 */

class ExtendThemes_EDD_Theme_Updater_Admin {

    /**
     * Variables required for the theme updater
     *
     * @since 1.0.0
     * @type string
     */
    protected $remote_api_url = null;
    protected $theme_slug = null;
    protected $version = null;
    protected $author = null;
    protected $download_id = null;
    protected $renew_url = null;
    protected $strings = null;

    /**
     * Initialize the class.
     *
     * @param array $config
     * @param array $strings
     *
     * @since 1.0.0
     */
    function __construct( $config = array(), $strings = array() ) {

        $config = wp_parse_args( $config, array(
            'remote_api_url' => 'http://easydigitaldownloads.com',
            'theme_slug'     => get_template(),
            'item_name'      => '',
            'license'        => '',
            'version'        => '',
            'author'         => '',
            'download_id'    => '',
            'renew_url'      => 'https://members.extendthemes.com',
            'beta'           => false,
        ) );

        /**
         * Fires after the theme $config is setup.
         *
         * @param array $config Array of EDD SL theme data.
         *
         * @since x.x.x
         *
         */
        do_action( 'post_edd_sl_theme_updater_setup', $config );

        // Set config arguments
        $this->remote_api_url = $config['remote_api_url'];
        $this->item_name      = $config['item_name'];
        $this->theme_slug     = sanitize_key( $config['theme_slug'] );
        $this->version        = $config['version'];
        $this->author         = $config['author'];
        $this->download_id    = $config['download_id'];
        $this->renew_url      = $config['renew_url'];
        $this->beta           = $config['beta'];

        // Populate version fallback
        if ( '' == $config['version'] ) {
            $theme         = wp_get_theme( $this->theme_slug );
            $this->version = $theme->get( 'Version' );
        }

        // Strings passed in from the updater config
        $this->strings = $strings;

        add_action( 'init', array( $this, 'updater' ) );
        add_action( 'admin_init', array( $this, 'register_option' ) );
        add_action( 'admin_init', array( $this, 'license_action' ) );
        add_action( 'admin_menu', array( $this, 'license_menu' ) );
        add_action( 'update_option_' . $this->theme_slug . '_license_key', array( $this, 'activate_license' ), 10, 2 );
        add_filter( 'http_request_args', array( $this, 'disable_wporg_request' ), 5, 2 );
        add_action( 'admin_notices', array( $this, 'license_notice' ), 0 );

        add_action( 'wp_ajax_extendthemes_edd_maybe_retrieve_license',
            array( $this, 'maybe_retrive_license_action' ) );

        add_action( 'wp_ajax_extendthemes_edd_handle_check_lincese_action',
            array( $this, 'handle_check_license_action' ) );
        $this->add_retrieve_license_tries();
        $this->add_check_license_action();

    }

    function add_retrieve_license_tries() {
        $tried   = get_option( $this->theme_slug . '_license_key_tries', 0 );
        $license = trim( get_option( $this->theme_slug . '_license_key' ) );

        if ( $tried > 3 || $license ) {
            return;
        }

        add_action( 'admin_footer', function () {
            $url = add_query_arg( 'action', 'extendthemes_edd_maybe_retrieve_license',
                admin_url( 'admin-ajax.php' ) );
            ?>
            <script>
                jQuery.get("<?php echo $url; ?>");
            </script>
            <?php
        } );
    }

    function add_check_license_action() {
        if ( ! get_transient( $this->theme_slug . '_license_message' ) ) {
            add_action( 'admin_footer', function () {
                $url = add_query_arg( 'action', 'extendthemes_edd_handle_check_lincese_action',
                    admin_url( 'admin-ajax.php' ) );
                ?>
                <script>
                    jQuery.get("<?php echo $url; ?>");
                </script>
                <?php
            } );
        }
    }

    function handle_check_license_action() {
        if ( ! get_transient( $this->theme_slug . '_license_message' ) ) {
            set_transient( $this->theme_slug . '_license_message', $this->check_license(), DAY_IN_SECONDS );
        }
    }

    /**
     * Checks if license is valid and gets expire date.
     *
     * @return string $message License status message.
     * @since 1.0.0
     *
     */
    function check_license() {

        $license = trim( get_option( $this->theme_slug . '_license_key' ) );
        $strings = $this->strings;

        $api_params = array(
            'edd_action' => 'check_license',
            'license'    => $license,
            'item_name'  => urlencode( $this->item_name ),
            'url'        => home_url()
        );

        $response = $this->get_api_response( $api_params );

        // make sure the response came back okay
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
            } else {
                $message = $strings['license-status-unknown'];
            }

        } else {

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            // If response doesn't include license data, return
            if ( ! isset( $license_data->license ) ) {
                $message = $strings['license-status-unknown'];

                return $message;
            }

            // We need to update the license status at the same time the message is updated
            if ( $license_data && isset( $license_data->license ) ) {
                update_option( $this->theme_slug . '_license_key_status', $license_data->license );
            }

            // Get expire date
            $expires = false;
            if ( isset( $license_data->expires ) && 'lifetime' != $license_data->expires ) {
                $expires    = date_i18n( get_option( 'date_format' ),
                    strtotime( $license_data->expires, current_time( 'timestamp' ) ) );
                $renew_link = '<a href="' . esc_url( $this->get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
            } elseif ( isset( $license_data->expires ) && 'lifetime' == $license_data->expires ) {
                $expires = 'lifetime';
            }

            // Get site counts
            $site_count    = property_exists( $license_data, 'site_count' ) ? $license_data->site_count : 0;
            $license_limit = property_exists( $license_data, 'license_limit' ) ? $license_data->license_limit : false;

            // If unlimited
            if ( 0 == $license_limit ) {
                $license_limit = $strings['unlimited'];
            }

            if ( 0 == $license_limit ) {
                $license_limit = '';
            }

            if ( $license_data->license == 'valid' ) {
                $message = $strings['license-key-is-active'] . ' ';
                if ( isset( $expires ) && 'lifetime' != $expires ) {
                    $message .= sprintf( $strings['expires%s'], $expires ) . ' ';
                }
                if ( isset( $expires ) && 'lifetime' == $expires ) {
                    $message .= $strings['expires-never'];
                }
            } elseif ( $license_data->license == 'expired' ) {
                if ( $expires ) {
                    $message = sprintf( $strings['license-key-expired-%s'], $expires );
                } else {
                    $message = $strings['license-key-expired'];
                }
                if ( $renew_link ) {
                    $message .= ' ' . $renew_link;
                }
            } elseif ( $license_data->license == 'invalid' ) {
                $message = $strings['license-keys-do-not-match'];
            } elseif ( $license_data->license == 'inactive' ) {
                $message = $strings['license-is-inactive'];
            } elseif ( $license_data->license == 'disabled' ) {
                $message = $strings['license-key-is-disabled'];
            } elseif ( $license_data->license == 'site_inactive' ) {
                // Site is inactive
                $message = $strings['site-is-inactive'];
            } else {
                $message = $strings['license-status-unknown'];
            }

        }

        return $message;
    }

    /**
     * Makes a call to the API.
     *
     * @param array $api_params to be used for wp_remote_get.
     *
     * @return array $response decoded JSON response.
     * @since 1.0.0
     *
     */
    function get_api_response( $api_params ) {

        $api_params = array_merge( $api_params, array(
            'slug' => $this->theme_slug,
        ) );


        // Call the custom API.
        $verify_ssl = (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true );
        $response   = wp_remote_post( $this->remote_api_url,
            array( 'timeout' => 15, 'sslverify' => $verify_ssl, 'body' => $api_params ) );

        return $response;
    }

    /**
     * Constructs a renewal link
     *
     * @since 1.0.0
     */
    function get_renewal_link() {

        // If a renewal link was passed in the config, use that
        if ( '' != $this->renew_url ) {
            return $this->renew_url;
        }

        // If download_id was passed in the config, a renewal link can be constructed
        $license_key = trim( get_option( $this->theme_slug . '_license_key', false ) );
        if ( '' != $this->download_id && $license_key ) {
            $url = esc_url( $this->remote_api_url );
            $url .= '/checkout/?edd_license_key=' . $license_key . '&download_id=' . $this->download_id;

            return $url;
        }

        // Otherwise return the remote_api_url
        return $this->remote_api_url;

    }

    function maybe_retrive_license_action() {
        $tried   = get_option( $this->theme_slug . '_license_key_tries', 0 );
        $license = trim( get_option( $this->theme_slug . '_license_key' ) );

        if ( $tried > 3 || $license ) {
            return;
        }

        $call_succeded = $this->maybe_retrieve_license();
        if ( $call_succeded ) {
            update_option( $this->theme_slug . '_license_key_tries', 4 );
        } else {
            update_option( $this->theme_slug . '_license_key_tries', $tried + 1 );
        }


    }

    function maybe_retrieve_license() {
        $api_params = array(
            'edd_action' => 'maybe_retrieve_license',
            'item_name'  => $this->item_name,
            'slug'       => $this->theme_slug,
            'url'        => home_url(),
        );

        $response = $this->get_api_response( $api_params );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return false;
        } else {

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            if ( $license_data && property_exists( $license_data, 'key' ) && $license_data->key ) {
                update_option( $this->theme_slug . '_license_key_status', $license_data->license );
                update_option( $this->theme_slug . '_license_key',
                    $license_data->key );

                set_transient( $this->theme_slug . '_license_message', $this->check_license( $license_data->key ),
                    DAY_IN_SECONDS );

                return $license_data->key;
            }

            return true;

        }
    }

    /**
     * Creates the updater class.
     *
     * since 1.0.0
     */
    function updater() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! class_exists( 'ExtendThemes_EDD_Theme_Updater' ) ) {
            // Load our custom theme updater
            include( dirname( __FILE__ ) . '/theme-updater-class.php' );
        }

        new ExtendThemes_EDD_Theme_Updater(
            array(
                'remote_api_url' => $this->remote_api_url,
                'version'        => $this->version,
                'license'        => trim( get_option( $this->theme_slug . '_license_key' ) ),
                'item_name'      => $this->item_name,
                'author'         => $this->author,
                'beta'           => $this->beta,
                'theme_slug'     => $this->theme_slug,
            ),
            $this->strings
        );
    }

    /**
     * Adds a menu item for the theme license under the appearance menu.
     *
     * since 1.0.0
     */
    function license_menu() {
        $strings = $this->strings;
        $self    = $this;
        add_filter( 'mesmerize_info_page_tabs', function ( $tabs ) use ( $self ) {

            $tabs['licensing'] = array(
                'title'    => __( 'Theme License', 'mesmerize-pro' ),
                'callback' => array( $self, 'license_page' )
            );

            return $tabs;
        } );

    }

    /**
     * Outputs the markup used on the theme license page.
     *
     * since 1.0.0
     */
    function license_page() {
        $strings = $this->strings;

        $license = trim( get_option( $this->theme_slug . '_license_key' ) );
        $status  = get_option( $this->theme_slug . '_license_key_status', false );
        $message = '';
        // Checks license status to display under license key
        if ( ! $license ) {
//            $message = $strings['enter-key'];
        } else {
            // delete_transient( $this->theme_slug . '_license_message' );
            if ( ! get_transient( $this->theme_slug . '_license_message' ) ) {
                set_transient( $this->theme_slug . '_license_message', $this->check_license(), DAY_IN_SECONDS );
            }
            $message = get_transient( $this->theme_slug . '_license_message' );
        }

        $message_class = "";
        if ( isset( $_GET['sl_theme_activation'] ) && 'false' === $_GET['sl_theme_activation'] ) {
            $message       = urldecode( $_GET['sl_message'] );
            $message_class = "error";
        }

        if ( $status !== 'valid' ) {
            $message_class = "error";
        }

        $purchase_link = apply_filters( 'extendthemes_renew_purchase_url',
            'https://extendthemes.com/go/mesmerize-purchase-renew' );

        ?>
        <div class="tab-cols">
            <style>
                .notice {
                    display: none !important;
                }

                .extendthemes-edd-license-container {
                    width: 100%;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                    -ms-flex-direction: column;
                    flex-direction: column;
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    align-items: center;
                    -webkit-box-pack: center;
                    -ms-flex-pack: center;
                    justify-content: center;
                    margin-top: 2em;

                }

                .extendthemes-edd-license-container-card {
                    background: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 0 2px rgba(0, 0, 0, 0.26);
                    width: calc(100% - 100px);

                }

                .extendthemes-edd-license-container-card-header {
                    padding: 10px 32px;
                    border-bottom: 1px solid #ebebeb;
                }

                .extendthemes-edd-license-container-card-header h1 {
                    font-size: 26px;
                    color: #333333;
                    font-weight: 500;
                    line-height: 150%;
                    margin: 0;
                }

                .extendthemes-edd-license-container-card-content {
                    padding: 10px 100px 32px 32px;
                }

                .extendthemes-edd-license-container-card-content label {
                    font-size: 16px;
                    font-weight: bold;
                    margin-bottom: 10px;
                    display: block;
                    cursor: default;
                }

                .extendthemes-edd-license-container-card-content input[type="text"] {
                    width: 100%;
                }

                .extendthemes-edd-license-container-card-content .license-row {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: horizontal;
                    -webkit-box-direction: normal;
                    -ms-flex-direction: row;
                    flex-direction: row;

                }


                .extendthemes-edd-license-container-card-content .license-row .input {
                    -webkit-box-flex: 1;
                    -ms-flex-positive: 1;
                    flex-grow: 1;
                    padding-right: 1em;
                }

                .extendthemes-edd-license-container-card-content .license-row .auto {
                    -webkit-box-flex: 0;
                    -ms-flex-positive: 0;
                    flex-grow: 0;
                }

                .full-width-layout {
                    width: 100% !important;
                    max-width: unset !important;
                    margin: unset !important;
                }

                .extendthemes-edd-license-container-card-content .opex-license-subtitle {
                    font-size: 18px;
                    font-weight: normal;
                    margin-bottom: 30px;
                }

                .extendthemes-edd-license-container-card-content .description {
                    font-size: 13px;
                }

                .extendthemes-edd-license-container-card-content .info {
                    margin-top: 20px;
                    font-size: 13px;
                }

                .extendthemes-edd-license-container-card-content .description.error {
                    color: red;
                }

                .renew-area {
                    margin-top: 16px;
                }

                .renew-area label {
                    margin-bottom: 0px;
                }

                .renew-area p {
                    margin-bottom: 12px;
                }

            </style>
            <div class="wrap about-wrap full-width-layout">

                <div class="extendthemes-edd-license-container">
                    <div class="extendthemes-edd-license-container-card">
                        <div class="extendthemes-edd-license-container-card-header">
                            <h1><?php esc_html_e( 'Theme license', 'mesmerize-pro' ); ?></h1>
                        </div>
                        <div class="extendthemes-edd-license-container-card-content">
                            <p class="opex-license-subtitle">
                                <?php printf(
                                    __( 'Activate your %s theme to receive <strong>important  updates</strong> and <strong>priority support</strong>.',
                                        'mesmerize-pro' ),
                                    $this->item_name
                                ); ?>
                            </p>
                            <div style="max-width: 670px">
                                <div>
                                    <form method="post" action="options.php">
                                        <?php settings_fields( 'mesmerize-pro-licensing' ); ?>

                                        <input type="hidden" name="action" value="update"/>

                                        <div>
                                            <label> <?php echo $strings['license-key']; ?></label>
                                            <div class="license-row">
                                                <div class="input">

                                                    <input id="<?php echo $this->theme_slug; ?>_license_key"
                                                           name="<?php echo $this->theme_slug; ?>_license_key"
                                                           type="text"
                                                           class="regular-text"
                                                           placeholder="<?php echo esc_attr( 'Enter license key here',
                                                               'mesmerize-pro' ); ?>"
                                                           value="<?php echo esc_attr( $license ); ?>"/>

                                                </div>
                                                <div class="auto">
                                                    <?php
                                                    wp_nonce_field( $this->theme_slug . '_nonce',
                                                        $this->theme_slug . '_nonce' );
                                                    if ( 'valid' == $status ) { ?>
                                                        <input type="submit" class="button-primary"
                                                               name="<?php echo $this->theme_slug; ?>_license_deactivate"
                                                               value="<?php esc_attr_e( $strings['deactivate-license'] ); ?>"/>
                                                    <?php } else { ?>
                                                        <input type="submit" class="button-primary"
                                                               name="<?php echo $this->theme_slug; ?>_license_activate"
                                                               value="<?php esc_attr_e( $strings['activate-license'] ); ?>"/>
                                                    <?php }
                                                    ?>
                                                </div>

                                            </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div>
                            <?php if ( $message ): ?>
                                <p class="description <?php echo $message_class; ?>">
                                    <?php echo $message; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <?php if ( 'valid' !== $status ) : ?>
                            <div class="renew-area">
                                <label><?php esc_html_e( "Don't have an active license key?",
                                        'mesmerize-pro' ); ?></label>
                                <p class="description">
                                    <?php esc_html_e( sprintf( 'You will need a license key to keep your %s theme up to date (including security updates).',
                                        $this->item_name ) ); ?>
                                    <?php esc_html_e( " Buy a new license today with 20% discount.",
                                        'mesmerize-pro' ); ?></p>
                                <a href="<?php echo $purchase_link; ?>" class="button-primary"
                                   target="_blank"><?php esc_html_e( "Buy a license - 20% Off",
                                        'mesmerize-pro' ); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    function license_notice() {

        $first_appearance = get_option( 'extendthemes_license_notice_displayed', 0 );
        $notice_class     = "";

        if ( ! $first_appearance ) {
            $first_appearance = time();
            update_option( 'extendthemes_license_notice_displayed', $first_appearance );
        }

        if ( $first_appearance + WEEK_IN_SECONDS * 2 < time() ) {
            $notice_class = "extendthemes-license-warning";
        }
        if ( $first_appearance + WEEK_IN_SECONDS * 4 < time() ) {
            $notice_class = "extendthemes-license-error";
        }

        if ( isset( $_REQUEST['extendthemes_edd_notice_state_test'] ) && in_array( $_REQUEST['extendthemes_edd_notice_state_test'],
                array( 'warning', 'error' ) ) ) {
            $notice_class = "extendthemes-license-" . $_REQUEST['extendthemes_edd_notice_state_test'];
        }

        $tried         = get_option( $this->theme_slug . '_license_key_tries', 0 );
        $license       = trim( get_option( $this->theme_slug . '_license_key' ) );
        $status        = get_option( $this->theme_slug . '_license_key_status' );
        $message       = false;
        $button_link   = admin_url( 'themes.php?page=mesmerize-welcome&tab=licensing' );
        $purchase_link = apply_filters( 'extendthemes_renew_purchase_url',
            'https://extendthemes.com/go/mesmerize-purchase-renew' );
        $button_text   = __( 'Activate License', 'mesmerize-pro' );


        if ( isset( $_REQUEST['extendthemes_edd_notice_status_test'] ) ) {
            $license = "{$status}test";
        }


        if ( ! $license && $tried < 3 ) {
            return;
        }
        // Checks license status to display under license key
        if ( ! $license ) {
            $message = __( 'Please activate your %s license to receive continued updates and support',
                'mesmerize-pro' );

            if ( $first_appearance + WEEK_IN_SECONDS * 2 < time() || isset( $_REQUEST['extendthemes_edd_notice_state_test'] ) ) {
                $message = __( "Your %s license is not activated! You're currently not receiving important updates (including security updates).",
                    'mesmerize-pro' );
            }

            $message = sprintf( $message, $this->item_name );
        }

        if ( ! $message ) {
            if ( ! $status ) {
                set_transient( $this->theme_slug . '_license_message', $this->check_license(), DAY_IN_SECONDS );
            }

            $status = get_option( $this->theme_slug . '_license_key_status', false );

            if ( isset( $_REQUEST['extendthemes_edd_notice_status_test'] ) ) {
                $status = $_REQUEST['extendthemes_edd_notice_status_test'];
            }

            if ( $status === 'invalid' ) {
                $message = __( 'Your %s license appears to be invalid. Please make sure you copied the entire license key. If the problem persists, please contact support.',
                    'mesmerize-pro' );

                if ( $first_appearance + WEEK_IN_SECONDS * 2 < time() || isset( $_REQUEST['extendthemes_edd_notice_state_test'] ) ) {
                    $message = __( "Your %s license appears to be invalid. You're currently not receiving important updates (including security updates). Please contact support.",
                        'mesmerize-pro' );
                }

                $message = sprintf( $message, $this->item_name );
            }

            if ( $status === 'site_inactive' ) {
                $message = __( '%s theme license key is not active', 'mesmerize-pro' );
                $message = sprintf( $message, $this->item_name );
            }

            if ( $status === 'expired' ) {
                $message = "Your %s license is expired. Please renew your license to receive continued updates and support.";

                if ( $first_appearance + WEEK_IN_SECONDS * 2 < time() || isset( $_REQUEST['extendthemes_edd_notice_state_test'] ) ) {
                    $message = __( 'Your %s license is expired! You\'re currently not receiving important updates (including security updates).',
                        'mesmerize-pro' );
                }

                $message = sprintf( $message, $this->item_name );

                $new_version = get_option( 'extend_theme_update_failed_new_version', false );
                if ( $new_version ) {
                    $message .= '' .
                                '<br/><span style="font-size: 14px">' .
                                sprintf(
                                    __( 'There is a new version available for %s (%s). To access the new version you will need a valid theme license.',
                                        'mesmerize-pro' ),
                                    $this->item_name, $new_version
                                ) . '</span>';
                }

                $button_link   = $this->renew_url;
                $button_target = '_blank';
                $button_text   = __( 'Renew License', 'mesmerize-pro' );
            }
        }


        if ( $message ): ?>
            <?php $message = sprintf( $message, mesmerize_get_theme_name() ); ?>
            <style>
                .notice.extendthemes-license-warning {
                    background-color: #FFBB33;
                }

                .notice.extendthemes-license-error {
                    background: #FF4444;
                }

                .notice.extendthemes-license-error *:not([class*=button]) {
                    color: #fff;
                }
            </style>
            <div class="notice notice-error notice-large <?php echo $notice_class; ?>">
                <div style="display: flex;align-items: center;">
                    <div style="flex-grow: 0">
                        <img style="width: 60px;margin-right: 10px;"
                             src="<?php echo mesmerize_pro_uri( "/assets/images/extendthemes.png" ); ?>"/>
                    </div>
                    <div style="flex-grow: 1">
                        <h2 style="margin:0"><?php echo $message; ?></h2>
                    </div>
                    <div style="flex-grow: 0;display: flex;align-items: center;">
                        <a class="button button-primary button-large"
                           href="<?php echo $button_link; ?>"><?php echo $button_text; ?></a>
                        <span style="padding: 0px 4px;"><?php echo esc_html_e( 'or', 'mesmerize-pro' ); ?></span>
                        <a class="button button-large"
                           target="_blank"
                           href="<?php echo $purchase_link; ?>"><?php esc_html_e( 'Get a License - 20% Off',
                                'mesmerize-pro' ); ?></a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php $this->theme_update_error_notice(); ?>
        <?php endif;

    }

    function theme_update_error_notice() {
        $message     = get_option( 'extend_theme_update_failed_message', false );
        $button_link = admin_url( 'themes.php?page=mesmerize-welcome&tab=licensing' );
        $button_text = __( 'Check License', 'mesmerize-pro' );

        if ( ! $message ) {
            return;
        }

        ?>
        <div class="notice notice-error notice-large">
            <div style="display: flex;align-items: center;">
                <div style="flex-grow: 0">
                    <img style="width: 60px;margin-right: 10px;"
                         src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/extendthemes.png"/>
                </div>
                <div style="flex-grow: 1">
                    <h2 style="margin:0"><?php echo $message; ?></h2>
                </div>
                <div style="flex-grow: 0">
                    <a class="button button-primary button-large"
                       href="<?php echo $button_link; ?>"><?php echo $button_text; ?></a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Registers the option used to store the license key in the options table.
     *
     * since 1.0.0
     */
    function register_option() {
        register_setting(
            'mesmerize-welcome&tab=licensing',
            $this->theme_slug . '_license_key',
            array( $this, 'sanitize_license' )
        );
    }

    /**
     * Sanitizes the license key.
     *
     * since 1.0.0
     *
     * @param string $new License key that was submitted.
     *
     * @return string $new Sanitized license key.
     */
    function sanitize_license( $new ) {

        $old = get_option( $this->theme_slug . '_license_key' );

        if ( $old && $old != $new ) {
            // New license has been entered, so must reactivate
            delete_option( $this->theme_slug . '_license_key_status' );
            delete_transient( $this->theme_slug . '_license_message' );
        }

        return $new;
    }

    /**
     * Checks if a license action was submitted.
     *
     * @since 1.0.0
     */
    function license_action() {

        if ( isset( $_POST[ $this->theme_slug . '_license_activate' ] ) ) {
            if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
                $this->activate_license();
            }
        }

        if ( isset( $_POST[ $this->theme_slug . '_license_deactivate' ] ) ) {
            if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
                $this->deactivate_license();
            }
        }

    }

    /**
     * Activates the license key.
     *
     * @since 1.0.0
     */
    function activate_license() {

        if ( array_key_exists( $this->theme_slug . '_license_key', $_POST ) ) {
            update_option( $this->theme_slug . '_license_key', trim( $_POST[ $this->theme_slug . '_license_key' ] ) );
        }

        $license = trim( get_option( $this->theme_slug . '_license_key' ) );

        // Data to send in our API request.
        $api_params = array(
            'edd_action' => 'activate_license',
            'license'    => $license,
            'slug'       => $this->theme_slug,
            'item_name'  => $this->item_name,
            'url'        => home_url()
        );

        $response = $this->get_api_response( $api_params );

        // make sure the response came back okay
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
            } else {
                $message = __( 'An error occurred, please try again.' );
            }

            $base_url = admin_url( 'themes.php?page=mesmerize-welcome&tab=licensing' );
            $redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'sl_message' => urlencode( $message ) ),
                $base_url );

            wp_redirect( $redirect );
            exit();

        } else {

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            if ( false === $license_data->success ) {

                switch ( $license_data->error ) {

                    case 'expired' :

                        $message = sprintf(
                            __( 'Your license key expired on %s.' ),
                            date_i18n( get_option( 'date_format' ),
                                strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                        );
                        break;

                    case 'disabled':
                    case 'revoked' :

                        $message = __( 'Your license key has been disabled.' );
                        break;

                    case 'missing' :

                        $message = __( 'Invalid license.' );
                        break;

                    case 'invalid' :
                    case 'site_inactive' :

                        $message = __( 'Your license is not active for this URL.' );
                        break;

                    case 'item_name_mismatch' :

                        $message = sprintf( __( 'This appears to be an invalid license key for %s.' ),
                            $this->item_name );
                        break;

                    case 'no_activations_left':

                        $message = __( 'Your license key has reached its activation limit.' );
                        break;

                    default :

                        $message = __( 'An error occurred, please try again.' );
                        break;
                }

                if ( ! empty( $message ) ) {
                    $base_url = admin_url( 'themes.php?page=mesmerize-welcome&tab=licensing' );
                    $redirect = add_query_arg( array(
                        'sl_theme_activation' => 'false',
                        'sl_message'          => urlencode( $message )
                    ), $base_url );

                    wp_redirect( $redirect );
                    exit();
                }

            }

        }

        if ( $license_data && isset( $license_data->license ) ) {
            update_option( $this->theme_slug . '_license_key_status', $license_data->license );
            delete_transient( $this->theme_slug . '_license_message' );
            delete_transient( "extendthemes_license_notice_displayed" );
        }

        wp_redirect( admin_url( 'themes.php?page=mesmerize-welcome&tab=licensing' ) );
        exit();

    }

    /**
     * Deactivates the license key.
     *
     * @since 1.0.0
     */
    function deactivate_license() {

        // Retrieve the license from the database.
        $license = trim( get_option( $this->theme_slug . '_license_key' ) );

        // Data to send in our API request.
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license'    => $license,
            'item_name'  => $this->item_name,
            'slug'       => $this->theme_slug,
            'url'        => home_url()
        );

        $response = $this->get_api_response( $api_params );

        // make sure the response came back okay
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
            } else {
                $message = __( 'An error occurred, please try again.' );
            }

            $base_url = admin_url( 'themes.php?page=mesmerize-welcome&tab=licensing' );
            $redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'sl_message' => urlencode( $message ) ),
                $base_url );

            wp_redirect( $redirect );
            exit();

        } else {

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            // $license_data->license will be either "deactivated" or "failed"
            if ( $license_data && ( $license_data->license == 'deactivated' ) ) {
                delete_option( $this->theme_slug . '_license_key_status' );
                delete_option( $this->theme_slug . '_license_key' );
                delete_transient( $this->theme_slug . '_license_message' );
            }

        }

        if ( ! empty( $message ) ) {
            $base_url = admin_url( 'themes.php?page=mesmerize-welcome&tab=licensing' );
            $redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'sl_message' => urlencode( $message ) ),
                $base_url );

            wp_redirect( $redirect );
            exit();
        }

        wp_redirect( admin_url( 'themes.php?page=mesmerize-welcome&tab=licensing' ) );
        exit();

    }

    /**
     * Disable requests to wp.org repository for this theme.
     *
     * @since 1.0.0
     */
    function disable_wporg_request( $r, $url ) {

        // If it's not a theme update request, bail.
        if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
            return $r;
        }

        // Decode the JSON response
        $themes = json_decode( $r['body']['themes'] );

        // Remove the active parent and child themes from the check
        $parent = get_option( 'template' );
        $child  = get_option( 'stylesheet' );
        unset( $themes->themes->$parent );
        unset( $themes->themes->$child );

        // Encode the updated JSON response
        $r['body']['themes'] = json_encode( $themes );

        return $r;
    }

}

