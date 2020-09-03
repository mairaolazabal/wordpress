<?php

add_action('customize_register', 'mesmerize_add_multilanguage_customizer_section');

function mesmerize_add_multilanguage_customizer_section($wp_customize)
{
    $wp_customize->add_section('mesmerize_multilanguage_settings', array(
        'title'      => __('Multilanguage Options', 'mesmerize-pro'),
        'panel'      => 'general_settings',
        'capability' => 'edit_theme_options',
    ));

}

// Controls
function add_mesmerize_multilanguage_controls()
{
    $section       = 'mesmerize_multilanguage_settings';
    $settingPrefix = "mesmerize_multilanguage_";
    
    Kirki::add_field('mesmerize', array(
        'type'     => 'checkbox',
        'settings' => 'mesmerize_show_language_switcher',
        'label'    => __('Show side language switcher', 'mesmerize-pro'),
        'section'  => $section,
        'default'  => true,
    ));
    
    if (function_exists('pll_current_language')) {
        Kirki::add_field('mesmerize', array(
            'type'     => 'checkbox',
            'settings' => 'mesmerize_polylang_display_as_dropdown',
            'label'    => __('Display as dropdown', 'mesmerize-pro'),
            'section'  => $section,
            'default'  => false,
        
        ));
    }
    
    
    Kirki::add_field('mesmerize', array(
        'type'            => 'color',
        'settings'        => "{$settingPrefix}background_color",
        'label'           => __('Switcher background color', 'mesmerize-pro'),
        'section'         => $section,
        'default'         => "#ffffff",
        'choices'         => array(
            'alpha' => true,
        ),
        "output"          => array(
            array(
                'element'  => '.mesmerize-language-switcher',
                'property' => 'background-color',
                'suffix'   => ' !important',
            ),
        ),
        'transport'       => 'postMessage',
        'js_vars'         => array(
            array(
                'element'  => '.mesmerize-language-switcher',
                'function' => 'css',
                'property' => 'background-color',
                'suffix'   => ' !important',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'mesmerize_show_language_switcher',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
    
    Kirki::add_field('mesmerize', array(
        'type'            => 'dimension',
        'settings'        => "{$settingPrefix}position",
        'label'           => __('Switcher top offset', 'mesmerize-pro'),
        'section'         => $section,
        'default'         => "80px",
        "output"          => array(
            array(
                'element'     => '.mesmerize-language-switcher',
                'property'    => 'top',
                'suffix'      => ' !important',
                "media_query" => "@media only screen and (min-width: 768px)",
            ),
        ),
        'transport'       => 'postMessage',
        'js_vars'         => array(
            array(
                'element'     => '.mesmerize-language-switcher',
                'function'    => 'css',
                'property'    => 'top',
                'suffix'      => ' !important',
                "media_query" => "@media only screen and (min-width: 768px)",
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'mesmerize_show_language_switcher',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
}

add_action('init', 'mesmerize_multilanguage_settings');
function mesmerize_multilanguage_settings()
{
    
    if ( ! class_exists("\Kirki")) {
        return;
    }
    
    add_mesmerize_multilanguage_controls();
}

