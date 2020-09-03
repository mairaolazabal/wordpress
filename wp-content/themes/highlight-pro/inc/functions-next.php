<?php


//More colors in pro

mesmerize_add_kirki_field(array(
    'type'     => 'ope-info-pro',
    'label'    => __('Customize all theme colors in PRO. @BTN@', 'mesmerize'),
    'section'  => 'colors',
    'settings' => "customize_colors_buttons_pro",
));


add_action("mesmerize_customize_register", function ($wp_customize) {
    if (apply_filters('mesmerize_show_inactive_plugin_infos', true)) {
        $wp_customize->add_setting('frontpage_header_presets_pro', array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control(new Mesmerize\Info_Control($wp_customize, 'frontpage_header_presets_pro',
            array(
                'label'     => __('10 more beautiful header designs are available in the PRO version. @BTN@', 'mesmerize'),
                'section'   => 'header_layout',
                'priority'  => 2,
                'transport' => 'postMessage',
            )
        ));
    }
});


add_action('after_setup_theme', function () {
    add_action('admin_menu', 'mesmerize_register_theme_page');
    
    include_once get_template_directory() . "/inc/Mesmerize_Logo_Nav_Menu.php";
    include_once get_template_directory() . "/inc/Mesmerize_Logo_Page_Menu.php";
    
});
