<?php


add_filter("mesmerize_navigation_wrapper_class", function ($classes, $inner) {
    $prefix = $inner ? "inner_header" : "header";
    
    if ( ! get_theme_mod("{$prefix}_nav_use_dark_logo", false)) {
        $classes[] = "white-logo";
    } else {
        $classes[] = "dark-logo";
    }
    
    if ( ! get_theme_mod("{$prefix}_nav_fixed_use_dark_logo", true)) {
        $classes[] = "fixed-white-logo";
    } else {
        $classes[] = "fixed-dark-logo";
    }
    
    return $classes;
}, 1, 2);

function mesmerize_offcanvas_primary_menu_footer()
{
    $prefix  = "header_offscreen_nav";
    $area    = "offscreen_nav";
    $enabled = get_theme_mod("{$prefix}_show_social", true);
    
    if ( ! intval($enabled)) {
        return;
    }
    
    mesmerize_print_area_social_icons($prefix, $area);
}

add_action("mesmerize_offcanvas_primary_menu_footer", "mesmerize_offcanvas_primary_menu_footer");


add_action("mesmerize_customize_register_options", function () {
    mesmerize_navigation_general_options_pro(true);
    mesmerize_navigation_general_options_pro(false);
    
    mesmerize_navigation_menu_settings(true);
    mesmerize_navigation_menu_settings(false);
    mesmerize_navigation_submenu_settings(false);
    
    mesmerize_navigation_custom_area_settings(false);
    mesmerize_navigation_custom_area_settings(true);
});

add_action('mesmerize_after_navigation_separator_option', 'mesmerize_use_front_page_nav_options', 10, 3);

function mesmerize_use_front_page_nav_options($inner, $section, $prefix)
{
    if ($inner) {
        mesmerize_add_kirki_field(array(
            'type'      => 'checkbox',
            'label'     => __('Use front page navigation style', 'mesmerize'),
            'section'   => $section,
            'priority'  => 1,
            'settings'  => "{$prefix}_nav_use_front_page",
            'default'   => false,
            'transport' => 'refresh',
        ));
    }
}

// filter mods when use same style on inner nav
add_filter('pre_update_option_' . get_stylesheet(), 'mesmerize_use_same_header_options_in_inner_page');
add_filter('option_theme_mods_' . get_stylesheet(), 'mesmerize_use_same_header_options_in_inner_page');

function mesmerize_use_same_header_options_in_inner_page($mods)
{
    
    if (isset($mods['inner_header_nav_use_front_page']) && intval($mods['inner_header_nav_use_front_page'])) {
        foreach ($mods as $key => $mod) {
            if (strpos($key, 'header_nav') === 0) {
                $inner_key        = "inner_{$key}";
                $mods[$inner_key] = $mod;
            }
        }
    }
    
    return $mods;
}

add_filter('customize_control_active', 'mesmerize_inner_header_nav_controls_active_callback_filter', 10, 2);

function mesmerize_inner_header_nav_controls_active_callback_filter($active, $control)
{
    
    if ($control->id === 'inner_header_nav_use_front_page' || $control->id === 'inner_header_nav_separator') {
        return true;
    }
    
    $useFrontPageStyle = get_theme_mod('inner_header_nav_use_front_page', false);
    $explicit          = array('inner_header_normal_menu_color_group', 'inner_header_fixed_menu_color_group');
    
    if (strpos($control->id, 'inner_header_nav') === 0) {
        if ($useFrontPageStyle) {
            $active = false;
        }
    }
    
    
    if ($useFrontPageStyle && in_array($control->id, $explicit)) {
        $active = false;
    }
    
    return $active;
}

add_filter('mesmerize_navigation_types', 'mesmerize_pro_navigations_types');
add_filter('mesmerize_nav_bar_menu_settings_partial_update', 'mesmerize_pro_nav_bar_menu_settings_partial_update', 10, 2);

function mesmerize_pro_navigations_types($types)
{
    $types = array_merge($types, array(
        'logo-inside-menu'     => __('Logo Inside Navigation', 'mesmerize'),
        'logo-menu-area'       => __('Logo, Navigation, Custom Area', 'mesmerize'),
        'menu-logo-area'       => __('Navigation, Logo, Custom Area', 'mesmerize'),
        'logo-area-menu-below' => __('Logo, Custom Area, Navigation Below', 'mesmerize'),
    ));
    
    return $types;
}

function mesmerize_pro_nav_bar_menu_settings_partial_update($partial_updates, $prefix)
{
    $partial_updates = array_merge($partial_updates, array(
        array(
            "value"  => "logo-inside-menu",
            "fields" => array(
                "{$prefix}_nav_menu_items_align"   => 'center',
                "{$prefix}_fixed_menu_items_align" => 'center',
            ),
        ),
        array(
            "value"  => "logo-menu-area",
            "fields" => array(
                "{$prefix}_nav_menu_items_align"   => 'flex-end',
                "{$prefix}_fixed_menu_items_align" => 'flex-end',
            ),
        ),
        
        array(
            "value"  => "menu-logo-area",
            "fields" => array(
                "{$prefix}_nav_menu_items_align"   => 'flex-start',
                "{$prefix}_fixed_menu_items_align" => 'flex-start',
            ),
        ),
        array(
            "value"  => "logo-area-menu-below",
            "fields" => array(
                "{$prefix}_nav_menu_items_align"   => 'center',
                "{$prefix}_fixed_menu_items_align" => 'flex-end',
            ),
        ),
    ));
    
    return $partial_updates;
}


add_filter('mesmerize_navigation_styles', 'mesmerize_pro_navigation_styles');

function mesmerize_pro_navigation_styles($styles)
{
    $styles = array_merge($styles, array(
        'active-line-top'            => __('Active item with top line', 'mesmerize'),
        'active-line-top-bottom'     => __('Active item with top and bottom line', 'mesmerize'),
        'active-round-button'        => __('Active item as round button ', 'mesmerize'),
        'active-round-border-button' => __('Active item as round bordered button ', 'mesmerize'),
    ));
    
    return $styles;
}

add_filter('mesmerize_navigation_menu_settings_partial_update', 'mesmerize_pro_navigation_menu_settings_partial_update', 10, 2);

function mesmerize_pro_navigation_menu_settings_partial_update($partials, $prefix)
{
    $partials = array_merge(
        $partials,
        array(
            array(
                "value"  => "active-line-top",
                "fields" => array(
                    "{$prefix}_nav_menu_active_color"       => mesmerize_get_var("dd_color"),
                    "{$prefix}_nav_fixed_menu_active_color" => mesmerize_get_var("dd_fixed_color"),
                ),
            ),
            array(
                "value"  => "active-line-top-bottom",
                "fields" => array(
                    "{$prefix}_nav_menu_active_color"       => mesmerize_get_var("dd_color"),
                    "{$prefix}_nav_fixed_menu_active_color" => mesmerize_get_var("dd_fixed_color"),
                ),
            ),
            array(
                "value"  => "active-round-button",
                "fields" => array(
                    "{$prefix}_nav_menu_active_color"       => mesmerize_get_var("dd_color"),
                    "{$prefix}_nav_fixed_menu_active_color" => mesmerize_get_var("dd_color"),
                ),
            ),
            array(
                "value"  => "active-round-border-button",
                "fields" => array(
                    "{$prefix}_nav_menu_active_color"       => mesmerize_get_var("dd_color"),
                    "{$prefix}_nav_fixed_menu_active_color" => mesmerize_get_var("dd_color"),
                ),
            ),
        )
    );
    
    return $partials;
}

function mesmerize_navigation_general_options_pro($inner = false)
{
    $priority = 1;
    $section  = $inner ? "inner_page_navigation" : "front_page_navigation";
    $prefix   = $inner ? "inner_header" : "header";
    
    
    do_action('mesmerize_customizer_navigation_options', $section, $prefix, $priority, $inner);
}


// NAVIGATION MENU SETTINGS - START

function mesmerize_normal_navigation_settings($inner, $prefix, $section, $priority)
{
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sidebar-button-group',
        'settings' => "{$prefix}_normal_menu_color_group",
        'label'    => __('Normal Menu Settings', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        "choices"  => array(
            "{$prefix}_normal_menu_color_separator",
            "{$prefix}_nav_menu_items_align",
            "{$prefix}_nav_bar_color",
            "{$prefix}_nav_menu_color",
            "{$prefix}_nav_menu_active_highlight_color",
            "{$prefix}_nav_menu_active_color",
            "{$prefix}_nav_menu_hover_color",
            "{$prefix}_nav_use_dark_logo",
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Normal Menu Settings', 'mesmerize'),
        'settings' => "{$prefix}_normal_menu_color_separator",
        'section'  => $section,
        'priority' => $priority,
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'label'           => esc_html__('Use dark logo image', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'settings'        => "{$prefix}_nav_use_dark_logo",
        'default'         => false,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => 'logo_dark',
                'operator' => '!=',
                'value'    => false,
            ),
            
            array(
                'setting'  => 'logo_dark',
                'operator' => '!=',
                'value'    => '',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_nav_bar_color",
        'label'    => __('Nav Bar Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => true,
        ),
        
        'default' => "rgba(255, 255, 255, 1)",
        'output'  => array(
            array(
                'element'  => mesmerize_get_nav_selector($inner),
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
        
        'transport'       => 'postMessage',
        'js_vars'         => array(
            array(
                'element'  => mesmerize_get_nav_selector($inner),
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => "{$prefix}_nav_transparent",
                'operator' => '=',
                'value'    => false,
            ),
        ),
    
    ));
    
    mesmerize_add_kirki_field(array(
        'type'      => 'select',
        'settings'  => "{$prefix}_nav_menu_items_align",
        'label'     => esc_attr__('Items align', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'choices'   => array(
            'flex-start' => 'Left',
            'center'     => 'Center',
            'flex-end'   => 'Right',
        ),
        'default'   => "flex-end",
        'transport' => 'postMessage',
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'settings'  => "{$prefix}_nav_menu_color",
        'label'     => esc_attr__('Items color', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => false,
        ),
        'default'   => mesmerize_get_var("dd_color"),
        'transport' => 'postMessage',
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => "{$prefix}_nav_menu_active_highlight_color",
        'label'           => esc_attr__('Highlight color', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'choices'         => array(
            'alpha' => false,
        ),
        'default'         => mesmerize_get_theme_colors('color1'),
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                "setting"  => "{$prefix}_nav_style",
                "operator" => "!=",
                "value"    => "simple-menu-items",
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'settings'  => "{$prefix}_nav_menu_active_color",
        'label'     => esc_attr__('Highlighted item text color', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => false,
        ),
        'default'   => mesmerize_get_var("dd_color"),
        'transport' => 'postMessage',
    
    ));
    
    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'settings'  => "{$prefix}_nav_menu_hover_color",
        'label'     => esc_attr__('Hovered item text color', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => false,
        ),
        'default'   => mesmerize_get_theme_colors('color1'),
        'transport' => 'postMessage',
    ));
}

function mesmerize_sticky_navigation_settings($inner, $prefix, $section, $priority)
{
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings'        => "{$prefix}_fixed_menu_color_group",
        'label'           => __('Sticky Menu Settings', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        "choices"         => array(
            "{$prefix}_fixed_menu_color_separator",
            "{$prefix}_fixed_menu_items_align",
            "{$prefix}_nav_fixed_bar_color",
            "{$prefix}_nav_fixed_menu_color",
            "{$prefix}_nav_fixed_menu_active_highlight_color",
            "{$prefix}_nav_fixed_menu_active_color",
            "{$prefix}_nav_fixed_menu_hover_color",
            "{$prefix}_nav_fixed_use_dark_logo",
        ),
        "active_callback" => array(
            array(
                'setting'  => "{$prefix}_nav_sticked",
                'operator' => '=',
                'value'    => true,
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Sticky Menu Colors', 'mesmerize'),
        'settings' => "{$prefix}_fixed_menu_color_separator",
        'section'  => $section,
        'priority' => $priority,
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'label'           => esc_html__('Use dark logo image', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'settings'        => "{$prefix}_nav_fixed_use_dark_logo",
        'default'         => true,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => 'logo_dark',
                'operator' => '!=',
                'value'    => false,
            ),
            
            array(
                'setting'  => 'logo_dark',
                'operator' => '!=',
                'value'    => '',
            ),
        ),
    ));
    
    $parent_selector = $inner ? '.mesmerize-inner-page' : '.mesmerize-front-page';
    mesmerize_add_kirki_field(array(
        'type'      => 'select',
        'settings'  => "{$prefix}_fixed_menu_items_align",
        'label'     => esc_attr__('Items align', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'choices'   => array(
            'flex-start' => 'Left',
            'center'     => 'Center',
            'flex-end'   => 'Right',
        ),
        'default'   => "flex-end",
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'  => "$parent_selector .fixto-fixed .main_menu_col, $parent_selector .fixto-fixed .main-menu",
                'property' => 'justify-content',
                'suffix'   => '!important',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => "$parent_selector .fixto-fixed .main_menu_col, $parent_selector .fixto-fixed .main-menu",
                'property' => 'justify-content',
                'suffix'   => '!important',
            ),
        ),
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_nav_fixed_bar_color",
        'label'    => __('Nav Bar Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => true,
        ),
        
        'default' => "rgba(255, 255, 255, 1)",
        'output'  => array(
            array(
                'element'  => mesmerize_get_sticky_nav_selector($inner),
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
        
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => mesmerize_get_sticky_nav_selector($inner),
                'property' => 'background-color',
                'suffix'   => '!important',
            ),
        ),
    
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'settings'  => "{$prefix}_nav_fixed_menu_color",
        'label'     => esc_attr__('Items color', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => false,
        ),
        'default'   => mesmerize_get_var("dd_fixed_color"),
        'transport' => 'postMessage',
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => "{$prefix}_nav_fixed_menu_active_highlight_color",
        'label'           => esc_attr__('Highlight color', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'choices'         => array(
            'alpha' => false,
        ),
        'default'         => mesmerize_get_theme_colors('color1'),
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                "setting"  => "{$prefix}_nav_style",
                "operator" => "!=",
                "value"    => "simple-menu-items",
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'settings'  => "{$prefix}_nav_fixed_menu_active_color",
        'label'     => esc_attr__('Highlighted item text color', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => false,
        ),
        'default'   => mesmerize_get_var("dd_fixed_color"),
        'transport' => 'postMessage',
    
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'settings'  => "{$prefix}_nav_fixed_menu_hover_color",
        'label'     => esc_attr__('Hovered item text color', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => false,
        ),
        'default'   => mesmerize_get_theme_colors('color1'),
        'transport' => 'postMessage',
    ));
}

function mesmerize_navigation_typography($inner, $prefix, $section, $priority)
{
    mesmerize_add_kirki_field(array(
        'type'     => 'sidebar-button-group',
        'settings' => "{$prefix}_nav_typography_group",
        'label'    => __('Navigation Typography', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        "choices"  => array(
            "{$prefix}_nav_typography",
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'     => 'typography',
        'settings' => "{$prefix}_nav_typography",
        'label'    => __('Navigation Typography', 'mesmerize'),
        'section'  => $section,
        'default'  => array(
            'font-family'    => 'Open Sans',
            'font-size'      => '14px',
            'variant'        => '600',
            'line-height'    => '160%',
            'letter-spacing' => '1px',
            'subsets'        => array(),
            'text-transform' => 'uppercase',
            'mobile-font-size' => '',
            'addwebfont'     => true,
        ),
        'output'   => array(
            array(
                'element' => $inner ? '.mesmerize-inner-page #main_menu > li > a' : '.mesmerize-front-page #main_menu > li > a',
            ),
        
        ),
        
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element' => $inner ? '.mesmerize-inner-page #main_menu > li > a' : '.mesmerize-front-page #main_menu > li > a',
            ),
        ),
    ));
}

function mesmerize_navigation_menu_settings($inner = false)
{
    $priority = 2;
    $section  = $inner ? "inner_page_navigation" : "front_page_navigation";
    $prefix   = $inner ? "inner_header" : "header";
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Navigation Menu Settings', 'mesmerize'),
        'settings' => "{$prefix}_nav_typo_separator",
        'section'  => $section,
        'priority' => $priority,
    ));
    
    mesmerize_normal_navigation_settings($inner, $prefix, $section, $priority);
    mesmerize_sticky_navigation_settings($inner, $prefix, $section, $priority);
    mesmerize_navigation_typography($inner, $prefix, $section, $priority);
}

// NAVIGATION MENU SETTINGS - END


// NAVIGATION SUBMENU SETTINGS - START

function mesmerize_navigation_submenu_settings($inner)
{
    $priority = 3;
    $section  = $inner ? "inner_page_navigation" : "front_page_navigation";
    $prefix   = $inner ? "inner_header" : "header";
    
    // sub menu settings
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Navigation submenu settings', 'mesmerize'),
        'settings' => "{$prefix}_nav_submenu_separator",
        'section'  => $section,
        'priority' => $priority,
    
    ));
    
    if ( ! $inner) {
        mesmerize_add_kirki_field(array(
            'type'     => 'sidebar-button-group',
            'settings' => "{$prefix}_submenus_color_group",
            'label'    => __('Submenu Colors', 'mesmerize'),
            'section'  => $section,
            'priority' => $priority,
            "choices"  => array(
                "{$prefix}_nav_submenu_background_color",
                "{$prefix}_nav_submenu_text_color",
                "{$prefix}_nav_submenu_hover_background_color",
                "{$prefix}_nav_submenu_hover_text_color",
            ),
        ));
        
        
        mesmerize_add_kirki_field(array(
            'type'      => 'color',
            'settings'  => "{$prefix}_nav_submenu_background_color",
            'label'     => esc_attr__('Background Color', 'mesmerize'),
            'section'   => $section,
            'choices'   => array(
                'alpha' => true,
            ),
            'default'   => mesmerize_get_var("dd_submenu_bg"),
            'priority'  => $priority,
            'transport' => 'postMessage',
        ));
        
        
        mesmerize_add_kirki_field(array(
            'type'      => 'color',
            'settings'  => "{$prefix}_nav_submenu_text_color",
            'label'     => esc_attr__('Text Color', 'mesmerize'),
            'section'   => $section,
            'choices'   => array(
                'alpha' => true,
            ),
            'default'   => mesmerize_get_var("dd_submenu_color"),
            'priority'  => $priority,
            'transport' => 'postMessage',
        ));
        
        
        mesmerize_add_kirki_field(array(
            'type'      => 'color',
            'settings'  => "{$prefix}_nav_submenu_hover_background_color",
            'label'     => esc_attr__('Hover Background Color', 'mesmerize'),
            'section'   => $section,
            'priority'  => $priority,
            'choices'   => array(
                'alpha' => true,
            ),
            'default'   => mesmerize_get_var("dd_submenu_hover_bg"),
            'transport' => 'postMessage',
        ));
        
        mesmerize_add_kirki_field(array(
            'type'      => 'color',
            'settings'  => "{$prefix}_nav_submenu_hover_text_color",
            'label'     => esc_attr__('Hover Text Color', 'mesmerize'),
            'section'   => $section,
            'priority'  => $priority,
            'choices'   => array(
                'alpha' => true,
            ),
            'default'   => mesmerize_get_var("dd_submenu_hover_color"),
            'transport' => 'postMessage',
        ));
        
        
        mesmerize_add_kirki_field(array(
            'type'     => 'sidebar-button-group',
            'settings' => "{$prefix}_nav_submenu_item_typography_group",
            'label'    => __('Item Typography', 'mesmerize'),
            'section'  => $section,
            'priority' => $priority,
            "choices"  => array(
                "{$prefix}_item_typography",
            ),
        
        ));
    }
    mesmerize_add_kirki_field(array(
        'type'     => 'typography',
        'settings' => "{$prefix}_item_typography",
        'label'    => __('Item Typography', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'default'  => array(
            'font-family'    => 'Open Sans',
            'font-size'      => '0.875rem',
            'variant'        => '600',
            'line-height'    => '120%',
            'letter-spacing' => '0px',
            'subsets'        => array(),
            'text-transform' => 'none',
            'addwebfont'     => true,
            'mobile-font-size' => '',
        ),
        'output'   => array(
            array(
                'element' => $inner ? '.mesmerize-inner-page #main_menu > li li > a ' : '.mesmerize-front-page #main_menu > li li > a',
            ),
        
        ),
        
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element' => $inner ? '.mesmerize-inner-page #main_menu > li li > a ' : '.mesmerize-front-page #main_menu > li li > a',
            ),
        ),
    ));
}

// NAVIGATION SUBMENU SETTINGS - END


function mesmerize_navigation_custom_area_buttons_setting($prefix, $section, $priority)
{
    
    $companion = apply_filters('mesmerize_is_companion_installed', false);
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings'        => "{$prefix}_navigation_custom_area_buttons_group",
        'label'           => __('Buttons Settings', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        "choices"         => array(
            "{$prefix}_navigation_custom_area_buttons",
        ),
        'active_callback' => array(
            array(
                'setting'  => "{$prefix}_navigation_custom_area_type",
                'operator' => '==',
                'value'    => 'buttons',
            ),
            array(
                'setting'  => "{$prefix}_nav_bar_type",
                'operator' => 'contains',
                'value'    => 'area',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(
        array(
            'type'     => 'repeater',
            'settings' => "{$prefix}_navigation_custom_area_buttons",
            'label'    => esc_html__('Buttons', 'mesmerize'),
            'section'  => $section,
            "priority" => $priority,
            "default"  => array(
                array(
                    'label'  => __('Get Started', 'mesmerize'),
                    'url'    => '#',
                    'target' => '_self',
                    'class'  => 'button',
                ),
            ),
            
            'row_label' => array(
                'type'  => 'text',
                'value' => esc_attr__('Button', 'mesmerize'),
            ),
            "fields"    => apply_filters('mesmerize_navigation_custom_area_buttons_fields', array(
                "label" => array(
                    'type'    => $companion ? 'hidden' : 'text',
                    'label'   => esc_attr__('Label', 'mesmerize'),
                    'default' => 'Action Button',
                ),
                "url"   => array(
                    'type'    => $companion ? 'hidden' : 'text',
                    'label'   => esc_attr__('Link', 'mesmerize'),
                    'default' => '#',
                ),
                
                "target" => array(
                    'type'    => 'hidden',
                    'label'   => esc_attr__('Target', 'mesmerize'),
                    'default' => '_self',
                ),
                
                "class" => array(
                    'type'    => 'hidden',
                    'label'   => esc_attr__('Class', 'mesmerize'),
                    'default' => 'button',
                ),
            )),
        )
    );
}

function mesmerize_nav_bar_default_icons()
{
    $default_icons                                       = mesmerize_default_icons();
    $default_icons[count($default_icons) - 1]['enabled'] = false;
    
    return $default_icons;
}


function mesmerize_navigation_custom_area_social_icons($prefix, $section, $priority, $inner)
{
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Social Icons Colors', 'mesmerize'),
        'settings' => "{$prefix}_navigation_custom_area_social_color_sep",
        'section'  => $section,
        'priority' => $priority,
    ));
    
    $styleSelector       = $inner ? ".mesmerize-inner-page .inner_header-nav-area .social-icons a" : ".mesmerize-front-page .header-nav-area .social-icons a";
    $styleStickySelector = $inner ? ".mesmerize-inner-page .fixto-fixed .inner_header-nav-area .social-icons a" : ".mesmerize-front-page .fixto-fixed .header-nav-area .social-icons a";
    
    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_navigation_custom_area_social_color",
        'label'    => __('Normal Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => true,
        ),
        
        'default' => "#FFFFFF",
        'output'  => array(
            array(
                'element'  => $styleSelector,
                'property' => 'color',
                'suffix'   => '!important',
            ),
        ),
        
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => $styleSelector,
                'property' => 'color',
                'suffix'   => '!important',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_navigation_custom_area_social_color_sticky",
        'label'    => __('Sticky Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => true,
        ),
        
        'default' => "#000000",
        'output'  => array(
            array(
                'element'  => $styleStickySelector,
                'property' => 'color',
                'suffix'   => '!important',
            ),
        ),
        
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => $styleStickySelector,
                'property' => 'color',
                'suffix'   => '!important',
            ),
        ),
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Social Icons', 'mesmerize'),
        'settings' => "{$prefix}_navigation_custom_area_social_sep",
        'section'  => $section,
        'priority' => $priority,
    ));
    
    $group_choices = array(
        "{$prefix}_navigation_custom_area_social_color_sep",
        "{$prefix}_navigation_custom_area_social_color",
        "{$prefix}_navigation_custom_area_social_color_sticky",
        "{$prefix}_navigation_custom_area_social_sep",
    );
    
    
    $icon_setting_prefix = "{$prefix}_nav_custom_area";
    $default_icons       = mesmerize_nav_bar_default_icons();
    
    for ($i = 0; $i < count($default_icons); $i++) {
        mesmerize_add_kirki_field(array(
            'type'      => 'checkbox',
            'label'     => sprintf(esc_html__('Show Icon %d', 'mesmerize'), ($i + 1)),
            'section'   => $section,
            'priority'  => $priority,
            'settings'  => "{$icon_setting_prefix}_social_icon_{$i}_enabled",
            'default'   => $default_icons[$i]['enabled'],
            'transport' => 'postMessage',
        ));
        
        $group_choices[] = "{$icon_setting_prefix}_social_icon_{$i}_enabled";
        
        mesmerize_add_kirki_field(array(
            'type'      => 'font-awesome-icon-control',
            'settings'  => "{$icon_setting_prefix}_social_icon_{$i}_icon",
            'label'     => sprintf(esc_html__('Icon %d icon', 'mesmerize'), ($i + 1)),
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
            'transport' => 'postMessage',
            'label'     => sprintf(__('Field %d link', 'mesmerize'), ($i + 1)),
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
        'settings'        => "{$prefix}_navigation_custom_area_social_group",
        'label'           => __('Social Icons Settings', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        "choices"         => $group_choices,
        'active_callback' => array(
            array(
                'setting'  => "{$prefix}_navigation_custom_area_type",
                'operator' => '==',
                'value'    => 'social',
            ),
            array(
                'setting'  => "{$prefix}_nav_bar_type",
                'operator' => 'contains',
                'value'    => 'area',
            ),
        ),
    ));
}

function mesmerize_navigation_custom_area_search($prefix, $section, $priority, $inner)
{
    $styleSelectorStart       = $inner ? ".mesmerize-inner-page .nav-search.widget_search" : ".mesmerize-front-page  .nav-search.widget_search";
    $styleStickySelectorStart = $inner ? ".mesmerize-inner-page .fixto-fixed .nav-search.widget_search" : ".mesmerize-front-page .fixto-fixed  .nav-search.widget_search";
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Search Bar Colors', 'mesmerize'),
        'settings' => "{$prefix}_navigation_custom_area_search_color_sep",
        'section'  => $section,
        'priority' => $priority,
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'settings'  => "{$prefix}_navigation_custom_area_search_color",
        'label'     => __('Normal Color', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => true,
        ),
        'default'   => "#FFFFFF",
        'output'    => array(
            array(
                'element'  => "{$styleSelectorStart} *",
                'property' => 'color',
            ),
            
            array(
                'element'  => "{$styleSelectorStart} input",
                'property' => 'border-color',
            ),
            
            array(
                'element'  => "{$styleSelectorStart} input::-webkit-input-placeholder",
                'property' => 'color',
            ),
            
            array(
                'element'  => "{$styleSelectorStart} input:-ms-input-placeholder",
                'property' => 'color',
            ),
            
            array(
                'element'  => "{$styleSelectorStart} input:-moz-placeholder",
                'property' => 'color',
            ),
        
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => "{$styleSelectorStart} *",
                'property' => 'color',
            ),
            array(
                'element'  => "{$styleSelectorStart} input",
                'property' => 'border-color',
            ),
            
            array(
                'element'  => "{$styleSelectorStart} input::-webkit-input-placeholder",
                'property' => 'color',
                'function' => 'style',
            ),
            
            array(
                'element'  => "{$styleSelectorStart} input:-ms-input-placeholder",
                'property' => 'color',
                'function' => 'style',
            ),
            
            array(
                'element'  => "{$styleSelectorStart} input:-moz-placeholder",
                'property' => 'color',
                'function' => 'style',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_navigation_custom_area_search_color_sticky",
        'label'    => __('Sticky Color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'choices'  => array(
            'alpha' => true,
        ),
        
        'default' => "#000000",
        'output'  => array(
            array(
                'element'  => "{$styleStickySelectorStart} *",
                'property' => 'color',
            ),
            
            array(
                'element'  => "{$styleStickySelectorStart} input",
                'property' => 'border-color',
            ),
            
            array(
                'element'  => "{$styleStickySelectorStart} input::-webkit-input-placeholder",
                'property' => 'color',
            ),
            
            array(
                'element'  => "{$styleStickySelectorStart} input:-ms-input-placeholder",
                'property' => 'color',
            ),
            
            array(
                'element'  => "{$styleStickySelectorStart} input:-moz-placeholder",
                'property' => 'color',
                'function' => 'style',
            ),
        ),
        
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => "{$styleStickySelectorStart} *",
                'property' => 'color',
            ),
            
            array(
                'element'  => "{$styleStickySelectorStart} input",
                'property' => 'border-color',
            ),
            
            array(
                'element'  => "{$styleStickySelectorStart} input::-webkit-input-placeholder",
                'property' => 'color',
                'function' => 'style',
            ),
            
            array(
                'element'  => "{$styleStickySelectorStart} input:-ms-input-placeholder",
                'property' => 'color',
                'function' => 'style',
            ),
            
            array(
                'element'  => "{$styleStickySelectorStart} input:-moz-placeholder",
                'property' => 'color',
                'function' => 'style',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings'        => "{$prefix}_navigation_custom_area_search_box_group",
        'label'           => __('Search Box Settings', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        "choices"         => array(
            "{$prefix}_navigation_custom_area_search_color_sep",
            "{$prefix}_navigation_custom_area_search_color",
            "{$prefix}_navigation_custom_area_search_color_sticky",
        ),
        'active_callback' => array(
            array(
                'setting'  => "{$prefix}_navigation_custom_area_type",
                'operator' => '==',
                'value'    => 'search',
            ),
            array(
                'setting'  => "{$prefix}_nav_bar_type",
                'operator' => 'contains',
                'value'    => 'area',
            ),
        ),
    ));
}


function mesmerize_navigation_custom_area_settings($inner)
{
    $priority = 3;
    $section  = $inner ? "inner_page_navigation" : "front_page_navigation";
    $prefix   = $inner ? "inner_header" : "header";
    
    $nav_bar_type_active_cb = array(
        array(
            'setting'  => "{$prefix}_nav_bar_type",
            'operator' => 'contains',
            'value'    => 'area',
        ),
    );
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => __('Navigation custom area settings', 'mesmerize'),
        'settings'        => "{$prefix}_nav_custom_area_separator",
        'section'         => $section,
        'priority'        => $priority,
        'active_callback' => $nav_bar_type_active_cb,
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'select',
        'settings'        => "{$prefix}_navigation_custom_area_type",
        'label'           => esc_html__('Custom area content', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => 'buttons',
        'choices'         => array(
            'buttons' => __('Buttons List', 'mesmerize'),
            'social'  => __('Social Icons', 'mesmerize'),
            'search'  => __('Search bar', 'mesmerize'),
        ),
        'active_callback' => $nav_bar_type_active_cb,
    ));
    
    mesmerize_navigation_custom_area_buttons_setting($prefix, $section, $priority);
    
    mesmerize_navigation_custom_area_social_icons($prefix, $section, $priority, $inner);
    
    mesmerize_navigation_custom_area_search($prefix, $section, $priority, $inner);
    
}


// NAVIGATION APPLY SETTINGS


add_action('wp_head', function () {
    $prefix = mesmerize_is_front_page(true) ? "header" : "inner_header";
    
    $content         = "";
    $parent_selector = mesmerize_is_front_page(true) ? ".mesmerize-front-page" : ".mesmerize-inner-page";
    
    if (mesmerize_can_show_cached_value("{$prefix}-menu-variant-style")) {
        $content = mesmerize_get_cached_value("{$prefix}-menu-variant-style");
        $content = "/** cached menu style */{$content}";
    } else {
        
        $variation = get_theme_mod("{$prefix}_nav_style", "active-line-bottom");
        
        $default_active_color = mesmerize_get_var("dd_color");
        
        $transparent_nav = get_theme_mod($prefix . '_nav_transparent', mesmerize_mod_default("{$prefix}_nav_transparent"));
        
        
        $default_color = mesmerize_get_var("dd_color");
        
        if ( ! $transparent_nav) {
            $default_color        = mesmerize_get_var("dd_fixed_color");
            $default_active_color = mesmerize_get_var("dd_fixed_color");
        }
        
        if ($variation == "simple-menu-items") {
            $default_active_color = "#03a9f4";
        }
        
        $content = "/* {$prefix} ### {$variation} */ \n\n\n";
        $content .= file_get_contents(get_template_directory() . "/assets/menu-vars/base.inc") . "\n\n";
        $content .= file_get_contents(get_template_directory() . "/assets/menu-vars/{$variation}.inc") . "\n\n";
        $content .= file_get_contents(get_template_directory() . "/assets/menu-vars/submenus.inc") . "\n\n";
        
        
        $vars = array(
            'parent_selector' => $parent_selector,
            
            'color'                  => get_theme_mod("{$prefix}_nav_menu_color", $default_color),
            'active_highlight_color' => get_theme_mod("{$prefix}_nav_menu_active_highlight_color", mesmerize_get_theme_colors('color1')),
            'active_color'           => get_theme_mod("{$prefix}_nav_menu_active_color", $default_active_color),
            'hover_color'            => get_theme_mod("{$prefix}_nav_menu_hover_color", mesmerize_get_theme_colors('color1')),
            
            'fixed_color'                  => get_theme_mod("{$prefix}_nav_fixed_menu_color", mesmerize_get_var("dd_fixed_color")),
            'fixed_active_highlight_color' => get_theme_mod("{$prefix}_nav_fixed_menu_active_highlight_color", mesmerize_get_theme_colors('color1')),
            'fixed_active_color'           => get_theme_mod("{$prefix}_nav_fixed_menu_active_color", mesmerize_get_var("dd_fixed_color")),
            'fixed_hover_color'            => get_theme_mod("{$prefix}_nav_fixed_menu_hover_color", mesmerize_get_theme_colors('color1')),
            
            'submenu_bg'          => get_theme_mod("header_nav_submenu_background_color", mesmerize_get_var("dd_submenu_bg")),
            'submenu_color'       => get_theme_mod("header_nav_submenu_text_color", mesmerize_get_var("dd_submenu_color")),
            'submenu_hover_bg'    => get_theme_mod("header_nav_submenu_hover_background_color", mesmerize_get_var("dd_submenu_hover_bg")),
            'submenu_hover_color' => get_theme_mod("header_nav_submenu_hover_text_color", mesmerize_get_var("dd_submenu_hover_color")),
        
        );
        
        foreach ($vars as $var => $value) {
            $content = str_replace("\$dd_{$var}", $value, $content);
        }
        
        // align menu
        
        $nav_type = get_theme_mod($prefix . '_nav_bar_type', 'default');
        
        $default_menu_align = "flex-end";
        if ($nav_type == 'logo-inside-menu' || $nav_type == 'logo-above-menu') {
            $default_menu_align = "center";
        }
        
        
        if ( ! is_admin() && ! mesmerize_is_customize_preview() && ! WP_DEBUG) {
            
            $content = preg_replace("#\/\*(.*)\*\/#", "", $content);
            $content = str_replace("\n", " ", $content);
            $content = preg_replace("#\s\s?#", " ", $content);
            
        }
        mesmerize_cache_value("{$prefix}-menu-variant-style", trim($content));
    }
    
    $menu_align = get_theme_mod($prefix . '_nav_menu_items_align', $default_menu_align);
    ?>
    <style data-prefix="<?php echo $prefix; ?>" data-name="menu-variant-style"><?php echo $content; ?></style>
    <style data-name="menu-align"><?php echo "$parent_selector .main-menu, $parent_selector .main_menu_col {justify-content:$menu_align;}"; ?></style>
    <?php
    
});

function mesmerize_menu_get_preview_data()
{
    $menu_vars = apply_filters('mesmerize_navigation_styles', array(
        'simple-menu-items'  => esc_html__('Simple text menu', 'mesmerize'),
        'active-line-bottom' => esc_html__('Underlined active item', 'mesmerize'),
    ));

//    $menu_vars = array_keys($menu_vars);
    
    foreach ($menu_vars as $var => $labels) {
        ob_start();
        locate_template("assets/menu-vars/{$var}.inc", true, true);
        $menu_vars[$var] = ob_get_clean();
    }
    
    
    $data = array(
        'menu_vars' => $menu_vars,
    );
    
    ob_start();
    locate_template("assets/menu-vars/submenus.inc", true, true);
    $data['submenu'] = ob_get_clean();
    
    ob_start();
    locate_template("assets/menu-vars/base.inc", true, true);
    $data['base'] = ob_get_clean();
    
    ob_start();
    
    ?>
    var __menu_preview_data = <?php echo json_encode($data); ?>
    <?php
    
    return ob_get_clean();
}

add_action('customize_preview_init', function () {
    mesmerize_enqueue_script('customize-menu-preview', array(
        'src'  => get_template_directory_uri() . "/customizer/js/customize-menu-preview.js",
        'deps' => array(mesmerize_get_text_domain() . "-customize-preview"),
    ));
    
    $data = mesmerize_menu_get_preview_data();
    
    wp_add_inline_script('customize-menu-preview', $data);
});


/*
    template functions
*/


add_filter("mesmerize_header_main_class", function ($classes, $prefix) {
    $nav_type   = get_theme_mod($prefix . '_nav_bar_type', 'default');
    $menu_align = get_theme_mod($prefix . '_nav_menu_items_align', 'flex-end');
    if ($nav_type == "logo-menu-area" && $menu_align == "center") {
        $classes[] = "centered-menu";
    }
    
    return $classes;
}, 1, 2);

function mesmerize_print_nav_custom_buttons()
{
    $prefix  = mesmerize_is_front_page(true) ? "header" : "inner_header";
    $setting = "{$prefix}_navigation_custom_area_buttons";
    $default = array(
        array(
            'label'  => __('Get Started', 'mesmerize'),
            'url'    => '#',
            'target' => '_self',
            'class'  => 'button',
        ),
    );
    
    mesmerize_print_buttons_list($setting, $default);
    
}

function mesmerize_print_nav_custom_search()
{
    echo mesmerize_instantiate_widget('WP_Widget_Search', array(
        'before_widget' => '<div class="widget nav-search %s">',
        'after_widget'  => "</div>",
    ));
}

function mesmerize_print_navigation_custom_area()
{
    $prefix   = mesmerize_is_front_page(true) ? "header" : "inner_header";
    $to_print = get_theme_mod("{$prefix}_navigation_custom_area_type", 'buttons');
    ?>
    <div data-dynamic-mod-container class="navigation-custom-area <?php echo "{$prefix}-nav-area" ?>">
        <?php
        switch ($to_print) {
            case 'buttons':
                mesmerize_print_nav_custom_buttons();
                break;
            
            case 'social':
                mesmerize_print_area_social_icons($prefix, "nav_custom_area", 'social-icons', 5);
                break;
            
            case 'search':
                mesmerize_print_nav_custom_search();
                break;
        }
        
        ?>
    </div>
    <?php
}
