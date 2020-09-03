<?php

add_action("mesmerize_customize_register_options", function () {
    mesmerize_offscreen_menu_settings_pro();
});

function mesmerize_offscreen_menu_social_icons($section, $prefix, $priority)
{

    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Offscreen Menu Settings', 'mesmerize'),
        'settings' => "{$prefix}_offscreen_nav_social_separator",
        'section'  => $section,
        'priority' => $priority,
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'checkbox',
        'settings' => "{$prefix}_show_social",
        'label'    => __('Offscreen nav bar social icons', 'mesmerize'),
        'section'  => $section,
        'default'  => true,
        'priority' => $priority,

    ));

    $group_choices = array();

    $default_icons = array(
        array(
            "icon" => "fa-facebook-official",
            "link" => "https://facebook.com",
        ),
        array(
            "icon" => "fa-twitter-square",
            "link" => "https://twitter.com",
        ),
        array(
            "icon" => "fa-instagram",
            "link" => "https://instagram.com",
        ),
        array(
            "icon" => "fa-google-plus-square",
            "link" => "https://plus.google.com/",
        ),
    );

    $icon_setting_prefix = "{$prefix}_offscreen_nav";

    for ($i = 0; $i < 4; $i++) {
        mesmerize_add_kirki_field(array(
            'type'      => 'checkbox',
            'label'     => sprintf(__('Show Icon %d', 'mesmerize'), ($i + 1)),
            'section'   => $section,
            'priority'  => $priority,
            'settings'  => "{$icon_setting_prefix}_social_icon_{$i}_enabled",
            'default'   => true,
            'transport' => 'postMessage',
        ));

        $group_choices[] = "{$icon_setting_prefix}_social_icon_{$i}_enabled";

        mesmerize_add_kirki_field(array(
            'type'      => 'font-awesome-icon-control',
            'settings'  => "{$icon_setting_prefix}_social_icon_{$i}_icon",
            'label'     => sprintf(__('Icon %d icon', 'mesmerize'), ($i + 1)),
            'section'   => $section,
            'priority'  => $priority,
            'default'   => $default_icons[$i]['icon'],
            'transport' => 'postMessage',
            'active_callback' => array(
                array(
                    'setting'  => "{$icon_setting_prefix}_social_icon_{$i}_enabled",
                    'operator' => '==',
                    'value'    => true,
                ),
            ),
        ));

        $group_choices[] = "{$icon_setting_prefix}_social_icon_{$i}_icon";

        mesmerize_add_kirki_field(array(
            'type'      => 'text',
            'settings'  => "{$icon_setting_prefix}_social_icon_{$i}_link",
            'label'     => sprintf(__('Field %d link', 'mesmerize'), ($i + 1)),
            'transport' => 'postMessage',
            'section'   => $section,
            'priority'  => $priority,
            'default'   => $default_icons[$i]['link'],
            'active_callback' => array(
                array(
                    'setting'  => "{$icon_setting_prefix}_social_icon_{$i}_enabled",
                    'operator' => '==',
                    'value'    => true,
                ),
            ),
        ));

        $group_choices[] = "{$icon_setting_prefix}_social_icon_{$i}_link";
    }

    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings'        => "{$prefix}_offscreen_nav_icons_group",
        'label'           => esc_html__('Offscreen Nav Social Icons', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        "choices"         => $group_choices,
        'active_callback' => array(
            array(
                'setting'  => "{$prefix}_show_social",
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
}

function mesmerize_offscreen_menu_settings_pro()
{
    $prefix   = "header_offscreen_nav";
    $section  = "navigation_offscreen";
    $priority = 1;

    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Offscreen Menu Button Settings', 'mesmerize'),
        'settings' => "{$prefix}_nav_bar_offscreen_menu_button_settings_separator",
        'section'  => $section,
        'priority' => $priority,
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_nav_bar_offscreen_hamburger_color",
        'label'    => __('Button Normal Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => true,
        ),
        'default' => mesmerize_get_var("dd_color"),
        'output'  => array(
            array(
                'element'  => "[data-component=\"offcanvas\"] i.fa",
                'property' => 'color',
                'suffix'   => '!important',
            ),
            array(
                'element'  => "[data-component=\"offcanvas\"] .bubble",
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => "[data-component=\"offcanvas\"] i.fa",
                'property' => 'color',
                'suffix'   => '!important',
            ),
            array(
                'element'  => "[data-component=\"offcanvas\"] .bubble",
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_nav_bar_offscreen_hamburger_color_sticky",
        'label'    => __('Button Sticky Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => true,
        ),
        'default' => "#000000",
        'output'  => array(
            array(
                'element'  => ".fixto-fixed [data-component=\"offcanvas\"] i.fa",
                'property' => 'color',
                'suffix'   => '!important',
            ),
            array(
                'element'  => ".fixto-fixed [data-component=\"offcanvas\"] .bubble",
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => ".fixto-fixed [data-component=\"offcanvas\"] i.fa",
                'property' => 'color',
                'suffix'   => '!important',
            ),
            array(
                'element'  => ".fixto-fixed [data-component=\"offcanvas\"] .bubble",
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Offscreen Nav Bar Settings', 'mesmerize'),
        'settings' => "{$prefix}_nav_bar_settings_separator",
        'section'  => $section,
        'priority' => $priority,
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_nav_bar_offscreen_color",
        'label'    => __('Nav Bar Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => true,
        ),
        'default' => "#222B34",
        'output'  => array(
            array(
                'element'  => "#offcanvas-wrapper",
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => "#offcanvas-wrapper",
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_nav_bar_offscreen_overlay_color",
        'label'    => __('Nav Bar Overlay Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => true,
        ),
        'default' => "rgba(34, 43, 52, 0.7)",
        'output'  => array(
            array(
                'element'  => "html.has-offscreen body:after",
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => "html.has-offscreen body:after",
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_nav_bar_offscreen_text_color",
        'label'    => __('Nav Texts Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => false,
        ),
        'default' => "#ffffff",
        'output'  => array(
            array(
                'element'  => "#offcanvas-wrapper *:not(.arrow)",
                'property' => 'color',
                'suffix'   => '!important',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => "#offcanvas-wrapper *:not(.arrow)",
                'property' => 'color',
                'function' => 'css',
                'suffix'   => '!important',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_nav_bar_offscreen_highlight_color",
        'label'    => __('Highlight Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => false,
        ),
        'default' => "#FFFFFF",
        'output'  => array(
            array(
                'element'  => "#offcanvas_menu li.open, #offcanvas_menu li.current-menu-item, #offcanvas_menu li.current-menu-item > a, #offcanvas_menu li.current_page_item, #offcanvas_menu li.current_page_item > a",
                'property' => 'background-color',
            ),

        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  =>"#offcanvas_menu li.open, #offcanvas_menu li.current-menu-item, #offcanvas_menu li.current-menu-item > a, #offcanvas_menu li.current_page_item, #offcanvas_menu li.current_page_item > a",
                'property' => 'background-color',
                'function' => 'style',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_nav_bar_offscreen_highlight_text_color",
        'label'    => __('Highlight Text Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => false,
        ),
        'default' => "#2395F6",
        'output'  => array(
            array(
                'element'  => "#offcanvas_menu li.open > a, #offcanvas_menu li.open > a > i, #offcanvas_menu li.current-menu-item > a, #offcanvas_menu li.current_page_item > a",
                'property' => 'color',
                'suffix'   => '!important',
            ),
            array(
                'element'  => "#offcanvas_menu li.open > a, #offcanvas_menu li.current-menu-item > a, #offcanvas_menu li.current_page_item > a",
                'property' => 'border-left-color',
                'suffix'   => '!important',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => "#offcanvas_menu li.open > a, #offcanvas_menu li.open > a > i, #offcanvas_menu li.current-menu-item > a, #offcanvas_menu li.current_page_item > a",
                'property' => 'color',
                'suffix'   => '!important',
                'function' => 'style',
            ),

            array(
                'element'  => "#offcanvas_menu li.open > a, #offcanvas_menu li.current-menu-item > a, #offcanvas_menu li.current_page_item > a",
                'property' => 'border-left-color',
                'suffix'   => '!important',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_nav_bar_offscreen_submenu_color",
        'label'    => __('Submenu Background Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => true,
        ),

        'default' => "#686B77",
        'output'  => array(
            array(
                'element'  => "#offcanvas_menu li > ul",
                'property' => 'background-color',
            ),

        ),

        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => "#offcanvas_menu li > ul",
                'property' => 'background-color',
                'function' => 'style',
            ),

        ),


    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'sidebar-button-group',
        'settings' => "{$prefix}_item_offscreen_typography_group",
        'label'    => __('Offscreen Menu Typography', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        "choices"  => array(
            "{$prefix}_item_offscreen_typography",
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'typography',
        'settings' => "{$prefix}_item_offscreen_typography",
        'label'    => __('Offscreen Menu Typography', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'default'  => array(
            'font-family'    => 'Open Sans',
            'font-size'      => '0.875rem',
            'font-weight'    => '400',
            'line-height'    => '100%',
            'letter-spacing' => '0px',
            'subsets'        => array(),
            'text-transform' => 'none',
            'mobile-font-size' => '',
            'addwebfont'     => true,
        ),
        'output'   => array(
            array(
                'element' => '#offcanvas_menu li > a',
            ),

        ),

        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element' => '#offcanvas_menu li > a',
            ),
        ),
    ));
    mesmerize_offscreen_menu_social_icons($section, $prefix, $priority + 1);
}
