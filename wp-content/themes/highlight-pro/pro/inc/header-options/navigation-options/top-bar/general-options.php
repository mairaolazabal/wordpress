<?php

add_filter("mesmerize_header_top_bar_class", function($header_top_bar_class) {
    if (get_theme_mod('top_bar_background_type', 'color') == 'gradient') {
        $header_top_bar_class = get_theme_mod("top_bar_background_gradient", "plum_plate");
    }
    return $header_top_bar_class;
});

add_action("mesmerize_top_bar_options_before", 'mesmerize_add_top_bar_options_pro', 1);

function mesmerize_add_top_bar_options_pro($section)
{

    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Top Bar General Options', 'mesmerize'),
        'section'  => $section,
        'settings' => 'top_bar_general_options_sep',
        'priority' => 1,
        'active_callback' => array(
            array(
                'setting'  => 'enable_top_bar',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'            => 'select',
        'settings'        => 'top_bar_background_type',
        'label'           => __('Background Type', 'mesmerize'),
        'section'         => $section,
        'choices'         => apply_filters('mesmerize_footer_background', array(
            'color'    => __('Color', 'mesmerize'),
            'gradient' => __('Gradient', 'mesmerize'),
        )),
        'default'         => 'color',
        'sanitize_callback' => 'sanitize_text_field',
        'priority'        => 1,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => 'enable_top_bar',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'    => 'color',
        'label'   => __('Background Color', 'mesmerize'),
        'section' => $section,
        'settings'  => 'top_bar_background_color',
        'default'   => '#222',
        'priority'  => 1,
        'choices'   => array(
            'alpha' => true,
        ),
        'transport' => 'postMessage',
        'output' => array(
            array(
                'element'  => '.header-top-bar',
                'property' => 'background-color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'enable_top_bar',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                'setting'  => 'top_bar_background_type',
                'operator' => '==',
                'value'    => 'color',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'web-gradients',
        'label'     => esc_html__('Background Gradient', 'mesmerize'),
        'section'   => $section,
        'settings'  => 'top_bar_background_gradient',
        'default'   => 'plum_plate',
        'priority'  => 1,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => 'enable_top_bar',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                'setting'  => 'top_bar_background_type',
                'operator' => '==',
                'value'    => 'gradient',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'            => 'number',
        'label'           => __('Background Height', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'top_bar_height',
        'choices'         => array(
            'min'         => 5,
            'max'         => 200,
            'step'        => 1,
        ),
        'default'         => '40',
        'priority'        => 1,
        'transport' => 'postMessage',
        'output' => array(
            array(
                'element'  => '.header-top-bar-inner',
                'property' => 'height',
                'suffix'   => 'px',
            ),
        ),
        'js_vars' => array(
            array(
                'element'  => '.header-top-bar-inner',
                'property' => 'height',
                'function' => 'css',
                'suffix'   => 'px',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'enable_top_bar',
                'operator' => '==',
                'value'    => true,
            ),
        ),

    ));

    mesmerize_add_kirki_field(array(
        'type'     		  => 'checkbox',
        'label'    		  => __('Show Bottom Border', 'mesmerize'),
        'section'  		  => $section,
        'priority' 		  => 1,
        'settings' 		  => 'top_bar_enable_bottom_border',
        'default'  		  => false,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => 'enable_top_bar',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'    => 'color',
        'label'   => __('Bottom Border Color', 'mesmerize'),
        'section' => $section,
        'settings'  => 'top_bar_bottom_border_color',
        'priority'  => 1,
        'choices'   => array(
            'alpha' => true,
        ),
        'default' => 'rgba(39, 124, 234, 1)',
        'transport' => 'postMessage',
        'output' => array(
            array(
                'element'  => '.header-top-bar',
                'property' => 'border-bottom-color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'top_bar_enable_bottom_border',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                'setting'  => 'enable_top_bar',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    mesmerize_add_kirki_field(array(
        'type'            => 'number',
        'label'           => __('Bottom Border Thickness', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'top_bar_bottom_border_thickness',
        'choices'         => array(
            'min'         => 1,
            'max'         => 50,
            'step'        => 1,
        ),
        'default'         => '5',
        'priority'        => 1,
        'transport' => 'postMessage',
        'output' => array(
            array(
                'element'  => '.header-top-bar',
                'property' => 'border-bottom-width',
                'suffix'   => 'px'
            ),
            array(
                'element'  => '.header-top-bar',
                'property' => 'border-bottom-style',
                'value_pattern' => 'solid'
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'top_bar_enable_bottom_border',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                'setting'  => 'enable_top_bar',
                'operator' => '==',
                'value'    => true,
            ),
        ),

    ));

}
