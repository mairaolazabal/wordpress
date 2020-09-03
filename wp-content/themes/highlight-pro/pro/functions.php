<?php


function mesmerize_free_options_exists( $stylesheet ) {
	$default = '___MESMERIZE_FREE_MODS_NOT_AVAILABLE___';
	$options = get_option( 'theme_mods_' . $stylesheet, $default );

	return ( $options !== $default );
}

if ( is_admin() && ! is_customize_preview() ) {

	function mesmerize_theme_updater() {
		require( mesmerize_pro_dir( '/updater/theme-updater.php' ) );
	}

	add_action( 'after_setup_theme', 'mesmerize_theme_updater' );

}


add_action( 'after_switch_theme', 'mesmerize_pro_first_activation' );
add_action( 'mesmerize_show_main_info_pro_messages', '__return_false' ); // section info pro button

function mesmerize_pro_first_activation() {
	$freeStylesheet     = apply_filters( 'mesmerize_pro_first_activation_stylesheet', 'mesmerize' );
	$firstActivationKey = 'mesmerize_pro_first_activation_processed';

	if ( get_stylesheet() === $freeStylesheet || ! mesmerize_free_options_exists( $freeStylesheet ) ) {
		return;
	}

	$was_processed = get_option( $firstActivationKey, false );
	if ( $was_processed === false ) {
		$freeOptions = get_option( 'theme_mods_' . $freeStylesheet, array() );

		update_option( 'theme_mods_' . get_stylesheet(), $freeOptions );
		update_option( $firstActivationKey, true );
	}

}

add_filter( 'cloudpress\customizer\supports', "__return_true" );
add_filter( 'mesmerize_show_info_pro_messages', '__return_false' );
add_filter( 'kirki_skip_fonts_enqueue', '__return_true' );
add_filter( 'cloudpresss\companion\can_edit_in_customizer', '__return_true' );
add_filter( 'header_content_buttons_limit', "__return_false" );


function mesmerize_pro_require( $path ) {
	$path = trim( $path, "\\/" );
	require_once get_template_directory() . "/pro/{$path}";
}

function mesmerize_pro_dir( $path = "" ) {
	return get_template_directory() . "/" . mesmerize_pro_relative_dir( $path );
}

function mesmerize_pro_relative_dir( $path ) {
	$path = trim( $path, "\\/" );

	return "pro/{$path}";
}

function mesmerize_pro_uri( $path = "" ) {
	$path = trim( $path, "\\/" );

	if ( strlen( $path ) ) {
		$path = "/" . $path;
	}

	return get_template_directory_uri() . "/pro{$path}";
}


function mesmerize_no_footer_menu_cb() {
	return wp_page_menu( array(
		"menu_class" => 'fm2_horizontal_footer_menu',
		"menu_id"    => 'horizontal_main_footer_container',
		'before'     => '<ul id="horizontal_footer_menu" class="fm2_horizontal_footer_menu">',
	) );
}

function mesmerize_placeholder_p( $text, $echo = false ) {
	$content = "";

	if ( mesmerize_is_customize_preview() ) {
		$content = '<p class="content-placeholder-p">' . $text . '</p>';
	}

	if ( $echo ) {
		echo $content;
	} else {
		return $content;
	}
}

function mesmerize_get_pro_version() {
	$theme = wp_get_theme();
	$ver   = $theme->get( 'Version' );
	$ver   = apply_filters( 'mesmerize_get_pro_version', $ver );

	return $ver;
}


if ( ! defined( 'MESMERIZE_PRO_CUSTOMIZER_DIR' ) ) {
	define( 'MESMERIZE_PRO_CUSTOMIZER_DIR', mesmerize_pro_dir( "/customizer" ) );
}


if ( ! defined( 'MESMERIZE_PRO_CUSTOMIZER_URI' ) ) {
	define( 'MESMERIZE_PRO_CUSTOMIZER_URI', mesmerize_pro_uri( "/customizer" ) );
}


function mesmerize_print_contextual_jQuery() {
	$isShortcodeRefresh = apply_filters( 'mesmerize_is_shortcode_refresh', false );
	echo $isShortcodeRefresh ? "parent.CP_Customizer.preview.jQuery()" : "jQuery";
}

function mesmerize_print_contextual_window() {
	$isShortcodeRefresh = apply_filters( 'mesmerize_is_shortcode_refresh', false );
	echo $isShortcodeRefresh ? "parent.CP_Customizer.preview.frame()" : "window";
}

mesmerize_pro_require( "/inc/multilanguage.php" );

mesmerize_pro_require( "/inc/header-options.php" );
mesmerize_pro_require( "/inc/footer-options.php" );
mesmerize_pro_require( "/inc/general-options.php" );

mesmerize_pro_require( "/customizer/customizer.php" );
mesmerize_pro_require( "/inc/shortcodes.php" );
mesmerize_pro_require( "/inc/templates-functions.php" );
mesmerize_pro_require( "/inc/integrations/index.php" );

if ( class_exists( 'WooCommerce' ) ) {
	mesmerize_pro_require( "/inc/woocommerce.php" );
}

add_action( 'wp_enqueue_scripts', function () {

	$localized_handle = "theme-pro";
	if ( apply_filters( 'mesmerize_load_bundled_version', true ) ) {
		$textDomain       = mesmerize_get_text_domain();
		$localized_handle = "{$textDomain}-theme";
		wp_dequeue_script( "{$textDomain}-theme" );
		wp_deregister_script( "{$textDomain}-theme" );

		mesmerize_enqueue_script( "{$textDomain}-theme", array(
			'src'  => mesmerize_pro_uri( '/assets/js/theme.bundle.min.js' ),
			'deps' => array( 'jquery', 'masonry' ),
		) );

	} else {

		mesmerize_enqueue_script( 'jquery-fancybox', array(
			'src'  => mesmerize_pro_uri( 'assets/js/jquery.fancybox.min.js' ),
			'deps' => array( 'jquery' ),
			'ver'  => '3.0.47',
		) );


		mesmerize_enqueue_script( 'theme-pro', array(
			'src'  => mesmerize_pro_uri( 'assets/js/theme.js' ),
			'deps' => array( 'jquery' ),
		) );

	}

	$mesmerize_theme_pro_settings = apply_filters( 'mesmerize_theme_pro_settings', array() );
	wp_localize_script( $localized_handle, 'mesmerize_theme_pro_settings', $mesmerize_theme_pro_settings );

}, 50 );


add_action( 'wp_enqueue_scripts', function () {

	if ( apply_filters( 'mesmerize_load_bundled_version', true ) ) {

		$textDomain = mesmerize_get_text_domain();
		wp_dequeue_style( $textDomain . '-style-bundle' );
		wp_deregister_style( $textDomain . '-style-bundle' );

		mesmerize_enqueue_style( $textDomain . '-style-bundle', array(
			'src' => mesmerize_pro_uri( '/assets/css/theme.bundle.min.css' ),
		) );

	} else {
		mesmerize_enqueue_style( 'jquery-fancybox', array(
			'src'  => mesmerize_pro_uri( 'assets/css/jquery.fancybox.min.css' ),
			'deps' => array(),
			'ver'  => '3.0.47',
		) );
	}
}, 50 );

function mesmerize_widgets_init_pro() {
	register_sidebar( array(
		'name'          => __( "Footer Newsletter Subscriber", 'mesmerize' ),
		'id'            => "newsletter_subscriber_widgets",
		'title'         => "Widget Area",
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widgettitle">',
		'after_title'   => '</h5>',
	) );
}

add_action( 'widgets_init', 'mesmerize_widgets_init_pro' );
function mesmerize_setup_pro() {
	register_nav_menus( array(
		'footer_menu'        => __( 'Footer Menu', 'mesmerize' ),
		'top_bar_area-left'  => __( 'Top Bar Left Menu', 'mesmerize' ),
		'top_bar_area-right' => __( 'Top Bar Right Menu', 'mesmerize' ),
	) );
}


add_filter( 'cloudpress\customizer\global_data', function ( $data ) {
	$data['PRO_URL'] = mesmerize_pro_uri();

	return $data;
} );


function mesmerize_footer_no_menu_cb() {
	return wp_page_menu( array(
		'menu_id'    => 'horizontal_main_footer_container',
		'menu_class' => 'horizontal_footer_menu',
		'depth'      => 1,
	) );
}

function mesmerize_footer_menu() {
	wp_nav_menu( array(
		'theme_location'  => 'footer_menu',
		'menu_id'         => 'footer_menu',
		'menu_class'      => 'footer-nav',
		'container_class' => 'horizontal_footer_menu',
		'fallback_cb'     => 'mesmerize_footer_no_menu_cb',
		'depth'           => 1,
	) );
}


function mesmerize_no_menu_logo_inside_cb() {
	mesmerize_nomenu_fallback( new Mesmerize_Logo_Page_Menu() );
}


add_action( 'after_setup_theme', 'mesmerize_setup_pro' );

function mesmerize_tgma_pro_suggest_plugins( $plugins ) {
	$plugins[] = array(
		'name'     => 'MailChimp for WordPress',
		'slug'     => 'mailchimp-for-wp',
		'required' => false,
	);

	return $plugins;
}

function mesmerize_theme_pro_info_plugins( $plugins ) {
	$plugins = array_merge( $plugins,
		array(
			'mailchimp-for-wp' => array(
				'title'       => __( 'MailChimp for WordPress', 'mesmerize' ),
				'description' => __( 'The MailChimp for WordPress plugin is recommended for the One Page Express subscribe sections.',
					'mesmerize' ),
				'activate'    => array(
					'label' => __( 'Activate', 'mesmerize' ),
				),
				'install'     => array(
					'label' => __( 'Install', 'mesmerize' ),
				),
			),
		)
	);

	return $plugins;
}

add_filter( 'mesmerize_tgmpa_plugins', 'mesmerize_tgma_pro_suggest_plugins' );
add_filter( 'mesmerize_theme_info_plugins', 'mesmerize_theme_pro_info_plugins' );


// MODS DEFAULT EXPORTERS
//add_action('customize_controls_print_footer_scripts', function () {
//    global $wp_customize;
//    $data = array();
//    $sets = $wp_customize->settings();
//    foreach ($sets as $id => $setting) {
//
//        if (
//            strpos($id, 'inner_header') === false &&
//            strpos($id, 'header') !== false &&
//            strpos($id, 'CP_AUTO_SETTING') === false
//        ) {
//
//            $control = $wp_customize->get_control($id);
//            if ($control) {
//                if ($control->type !== 'sectionseparator') {
//                    $data[$id] = $setting->default;
//                }
//                continue;
//            } else {
//                $data[$id] = $setting->default;
//            }
//        }
//
//    }
//
//    $data = var_export($data, true);
//    $data = str_replace(site_url(), "", $data);
//    file_put_contents(ABSPATH . '/wp-content/plugins/mods-exporter/preset.php', "<?php\n\n return " . $data . ";\n");
//
//});
