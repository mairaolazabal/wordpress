<?php


add_filter('mesmerize_show_info_pro_messages', "__return_false");
add_filter('mesmerize_enable_kirki_selective_refresh', "__return_false");

require_once MESMERIZE_PRO_CUSTOMIZER_DIR . "/webgradients-list.php";


add_action('cloudpress\companion\ready', function ($companion) {
    $customizer = $companion->customizer();
    if ($customizer) {
        $customizer->registerScripts('mesmerize_pro_customizer_scripts', 80);
        $customizer->previewInit('mesmerize_pro_customizer_preview_scripts');
    }

});

add_action('customize_register', 'mesmerize_pro_load_customizer_controls');

function mesmerize_pro_customizer_scripts()
{

    $ver        = mesmerize_get_pro_version();
    $textDomain = mesmerize_get_text_domain();

    wp_enqueue_style('ope-pro-customizer', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/css/customizer.css", $ver);

    if (apply_filters('mesmerize_load_bundled_version', true)) {
        wp_enqueue_script('customizer-pro', MESMERIZE_PRO_CUSTOMIZER_URI . "/pro-customizer.bundle.min.js", array('customizer-base', $textDomain . '-customize'), $ver, true);
//        pro-customizer.bundle.js
    } else {
        wp_enqueue_script('customizer-ope', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-custom-style-manager', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer-custom-style-manager.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-scss-settings-vars', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer-scss-settings-vars.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-sectionsetting-control', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/sectionsetting-control.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-section-separator', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/sectionseparator-control.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-pro-section-panel', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer-pro-section-panel.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-site-colors', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer-site-colors.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-button-style', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer-button-style.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-icon-style', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer-icon-style.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-galleries-settings', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer-galleries-settings.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-shortcodes-pro', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer-shortcodes.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-section-separator-settings', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer-section-separators.js", array('customizer-base'), $ver, true);
        wp_enqueue_script('customizer-custom-sections-settings', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/customizer-custom-sections-settings.js", array('customizer-base'), $ver, true);

    }

}

function mesmerize_pro_customizer_preview_scripts()
{
    wp_enqueue_script('mesmerize-pro-customize-preview', MESMERIZE_PRO_CUSTOMIZER_URI . "/assets/js/preview.js", array('mesmerize-customize-preview'), false, true);
}


function mesmerize_pro_load_customizer_controls($wp_customize)
{


//    require_once MESMERIZE_PRO_CUSTOMIZER_DIR . "/controls/SectionSettingControl.php";
    require_once MESMERIZE_PRO_CUSTOMIZER_DIR . "/controls/GradientControl.php";
    require_once MESMERIZE_PRO_CUSTOMIZER_DIR . "/controls/WebFontsControl.php";
    require_once MESMERIZE_PRO_CUSTOMIZER_DIR . "/controls/HeaderSliderControl.php";

//    $wp_customize->register_control_type("Mesmerize\\SectionSettingControl");
    $wp_customize->register_control_type("Mesmerize\\WebFontsControl");
    $wp_customize->register_control_type('Mesmerize\\GradientControlPro');
    $wp_customize->register_control_type('Mesmerize\\HeaderSliderControl');

    add_filter('kirki/control_types', function ($controls) {
//        $controls['sectionsetting']       = "Mesmerize\\SectionSettingControl";
        $controls['web-fonts']            = "Mesmerize\\WebFontsControl";
        $controls['gradient-control-pro'] = "Mesmerize\\GradientControlPro";
        $controls['header-slider']        = "\\Mesmerize\\HeaderSliderControl";

        return $controls;
    });
}


add_filter('cloudpress\customizer\global_data', 'mesmerize_pro_customizer_data');
function mesmerize_pro_customizer_data($data)
{


    $data['gradients']          = mesmerize_get_gradients_classes();
    $data['sectionsOverlays']   = get_theme_mod('pro_background_overlay', array());
    $data['section_separators'] = mesmerize_get_separators_list(true);
    $data['proStylesheet']      = mesmerize_pro_uri();

    return $data;
}
