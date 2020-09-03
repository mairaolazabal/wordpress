<?php

function highlight_presets_update_values($preset)
{
    
    return
        array(
            "value"  => $preset,
            "fields" => array(
                'side_navigation_label_bg_color'        => highlight_side_navigation_defaults($preset, 'side_navigation_label_bg_color'),
                'side_navigation_item_color'            => highlight_side_navigation_defaults($preset, 'side_navigation_item_color'),
                'side_navigation_label_active_bg_color' => highlight_side_navigation_defaults($preset, 'side_navigation_label_active_bg_color'),
                'side_navigation_item_active_color'     => highlight_side_navigation_defaults($preset, 'side_navigation_item_active_color'),
                'side_navigation_label_border_color'    => highlight_side_navigation_defaults($preset, 'side_navigation_label_border_color'),
                'side_navigation_label_typography'      => highlight_side_navigation_defaults($preset, 'side_navigation_label_typography'),
            ),
        );
}

function highlight_pro_add_color_controls()
{
    
    $priority = 2;
    $section  = 'side_navigation';
    
    $default_preset = mesmerize_mod_default('side_navigation_design_preset');
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => 'side_navigation_bar_bg_color',
        'label'           => esc_attr__('Bullet bar background color', 'highlight'),
        'section'         => $section,
        'priority'        => $priority,
        'choices'         => array(
            'alpha' => true,
        ),
        'default'         => '#f1f1f1',
        'transport'       => 'postMessage',
        'js_vars'         => array(
            array(
                'element'  => '#side-navigation ul:before',
                'function' => 'style',
                'property' => 'background-color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                "setting"  => 'side_navigation_design_preset',
                "operator" => '==',
                "value"    => 'preset-1',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => 'side_navigation_label_bg_color',
        'label'           => esc_attr__('Normal color', 'highlight'),
        'section'         => $section,
        'priority'        => $priority,
        'choices'         => array(
            'alpha' => true,
        ),
        'default'         => highlight_side_navigation_defaults($default_preset, 'side_navigation_label_bg_color'),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => '#side-navigation ul[data-preset] > li > a',
                'property' => 'background-color',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => '#side-navigation ul[data-preset] > li > a',
                'function' => 'style',
                'property' => 'background-color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                "setting"  => 'side_navigation_design_preset',
                "operator" => 'in',
                "value"    => array('preset-2', 'preset-3', 'preset-4'),
            ),
            
            array(
                "setting"  => 'side_navigation_visible_labels',
                "operator" => 'in',
                "value"    => array('all'),
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => 'side_navigation_item_color',
        'label'           => esc_attr__('Normal text color', 'highlight'),
        'section'         => $section,
        'priority'        => $priority,
        'choices'         => array(
            'alpha' => false,
        ),
        'default'         => highlight_side_navigation_defaults($default_preset, 'side_navigation_item_color'),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => '#side-navigation ul[data-preset] > li > a',
                'property' => 'color',
            ),
        
        ),
        'js_vars'         => array(
            array(
                'element'  => '#side-navigation ul[data-preset] > li > a',
                'function' => 'style',
                'property' => 'color',
            ),
        
        ),
        'active_callback' => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
            
            array(
                "setting"  => 'side_navigation_visible_labels',
                "operator" => 'in',
                "value"    => array('all'),
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => 'side_navigation_label_active_bg_color',
        'label'           => esc_attr__('Active color', 'highlight'),
        'section'         => $section,
        'priority'        => $priority,
        'choices'         => array(
            'alpha' => true,
        ),
        'default'         => highlight_side_navigation_defaults($default_preset, 'side_navigation_label_active_bg_color'),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => '#side-navigation ul[data-preset] li.active a, #side-navigation ul[data-preset] li:hover a',
                'function' => 'style',
                'property' => 'background-color',
            ),
            array(
                'element'  => '#side-navigation ul[data-preset]:not([data-preset="preset-2"]) li.active a:after, ' .
                              '#side-navigation ul[data-preset]:not([data-preset="preset-2"]) li:hover a:after',
                'function' => 'style',
                'property' => 'background-color',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => '#side-navigation ul[data-preset] li.active a, #side-navigation ul[data-preset] li:hover a',
                'function' => 'style',
                'property' => 'background-color',
            ),
            array(
                'element'  => '#side-navigation ul[data-preset]:not([data-preset="preset-2"]) li.active a:after, ' .
                              '#side-navigation ul[data-preset]:not([data-preset="preset-2"]) li:hover a:after',
                'function' => 'style',
                'property' => 'background-color',
            ),
        
        
        ),
        'active_callback' => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                "setting"  => 'side_navigation_design_preset',
                "operator" => 'in',
                "value"    => array('preset-2', 'preset-3', 'preset-4'),
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => 'side_navigation_item_active_color',
        'label'           => esc_attr__('Active text color', 'highlight'),
        'section'         => $section,
        'priority'        => $priority,
        'choices'         => array(
            'alpha' => false,
        ),
        'default'         => highlight_side_navigation_defaults($default_preset, 'side_navigation_item_active_color'),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => '#side-navigation ul li.active a, #side-navigation ul li:hover a',
                'function' => 'style',
                'property' => 'color',
            ),
        
        ),
        'js_vars'         => array(
            array(
                'element'  => '#side-navigation ul li.active a, #side-navigation ul li:hover a',
                'function' => 'style',
                'property' => 'color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
        
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => 'side_navigation_label_border_color',
        'label'           => esc_attr__('Label border color', 'highlight'),
        'section'         => $section,
        'priority'        => $priority,
        'choices'         => array(
            'alpha' => true,
        ),
        'default'         => highlight_side_navigation_defaults($default_preset, 'side_navigation_label_border_color'),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => '#side-navigation ul[data-preset] > li > a',
                'function' => 'style',
                'property' => 'border-color',
            ),
            
            
            array(
                'element'  => '#side-navigation ul[data-preset=preset-4] > li > a',
                'property' => 'border-color',
                'suffix'   => '!important',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => '#side-navigation ul[data-preset] > li > a',
                'function' => 'style',
                'property' => 'border-color',
            ),
    
            array(
                'element'  => '#side-navigation ul[data-preset=preset-4] > li > a',
                'function' => 'style',
                'property' => 'border-color',
                'suffix'   => '!important',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                "setting"  => 'side_navigation_design_preset',
                "operator" => 'in',
                "value"    => array('preset-2', 'preset-3', 'preset-4'),
            ),
            
            array(
                "setting"  => 'side_navigation_visible_labels',
                "operator" => '!=',
                "value"    => 'none',
            ),
        ),
    ));
}


function highlight_pro_add_style_controls()
{
    $priority = 2;
    $section  = 'side_navigation';
    
    $default_preset = mesmerize_mod_default('side_navigation_design_preset');
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Design Options', 'highlight'),
        'section'         => $section,
        'settings'        => "side_navigation_design_options_separator",
        'priority'        => $priority,
        'active_callback' => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'              => 'select',
        'label'             => esc_html__('Navigation Design Preset', 'highlight'),
        'section'           => $section,
        'settings'          => 'side_navigation_design_preset',
        'choices'           => array(
            'preset-2' => __('Bullet inside label', 'highlight'),
            'preset-3' => __('Bullet outside label', 'highlight'),
            'preset-4' => __('Square bullets', 'highlight'),
        ),
        'default'           => $default_preset,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
        'priority'          => $priority,
        'update'            => array(
            highlight_presets_update_values($default_preset),
            highlight_presets_update_values('preset-2'),
            highlight_presets_update_values('preset-3'),
            highlight_presets_update_values('preset-4'),
        ),
        'active_callback'   => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
    highlight_pro_add_color_controls();
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings'        => 'side_navigation_label_typography_button',
        'label'           => esc_html__('Label Typography', 'highlight'),
        'section'         => $section,
        'priority'        => $priority,
        'active_callback' => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
    $group = "side_navigation_label_typography_button";
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Label Typography Options', 'highlight'),
        'section'         => $section,
        'priority'        => $priority,
        'settings'        => "side_navigation_label_typography_separator",
        'group'           => $group,
        'active_callback' => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'typography',
        'settings'        => 'side_navigation_label_typography',
        'label'           => __('Menu item label typography', 'highlight'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => highlight_side_navigation_defaults($default_preset, 'side_navigation_label_typography'),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element' => '#side-navigation ul[data-preset] > li > a',
            ),
        
        ),
        'js_vars'         => array(
            array(
                'element' => '#side-navigation ul[data-preset] > li > a',
            ),
        ),
        'group'           => $group,
        'active_callback' => array(
            array(
                'setting'  => 'enable_side_navigation',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
}

if (apply_filters('mesmerize_is_companion_installed', false)) {
    highlight_pro_add_style_controls();
}
