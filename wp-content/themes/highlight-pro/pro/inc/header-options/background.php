<?php

add_action("mesmerize_header_background_overlay_settings", function ($section, $prefix, $group, $inner, $priority) {
    
    $default = mesmerize_mod_default($prefix . '_overlay_gradient_colors');
    
    mesmerize_add_kirki_field(array(
        'type'     => 'gradient-control-pro',
        'label'    => esc_html__('Gradient', 'mesmerize'),
        'section'  => $section,
        'settings' => $prefix . '_overlay_gradient_colors',
        'default'  => is_string($default) ? json_decode($default, true) : $default,
        
        'choices' => array(
            'opacity' => 0.8,
        ),
        
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_overlay_type',
                'operator' => '==',
                'value'    => 'gradient',
            ),
            array(
                'setting'  => $prefix . '_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
        ),
        'priority'        => $priority,
        'transport'       => 'postMessage',
        'group'           => $group,
    ));
}, 2, 5);

add_filter("mesmerize_get_header_shapes_overlay_filter", function ($result) {
    $shapes = array(
        '10degree-stripes'          => array(
            'label' => __('10deg stripes', 'mesmerize'),
            'tile'  => false,
        ),
        'rounded-squares-blue'      => array(
            'label' => __('Rounded Squares Blue', 'mesmerize'),
            'tile'  => false,
        ),
        'many-rounded-squares-blue' => array(
            'label' => __('Many Rounded Squares Blue', 'mesmerize'),
            'tile'  => false,
        ),
        'two-circles'               => array(
            'label' => __('Two Circles', 'mesmerize'),
            'tile'  => false,
        ),
        'circles-2'                 => array(
            'label' => __('Circles 2', 'mesmerize'),
            'tile'  => false,
        ),
        'circles-3'                 => array(
            'label' => __('Circles 3', 'mesmerize'),
            'tile'  => false,
        ),
        'circles-gradient'          => array(
            'label' => __('Circles Gradient', 'mesmerize'),
            'tile'  => false,
        ),
        'circles-white-gradient'    => array(
            'label' => __('Circles White Gradient', 'mesmerize'),
            'tile'  => false,
        ),
        'waves'                     => array(
            'label' => __('Waves', 'mesmerize'),
            'tile'  => false,
        ),
        'waves-inverted'            => array(
            'label' => __('Waves Inverted', 'mesmerize'),
            'tile'  => false,
        ),
        'dots'                      => array(
            'label' => __('Dots', 'mesmerize'),
            'tile'  => true,
        ),
        'left-tilted-lines'         => array(
            'label' => __('Left tilted lines', 'mesmerize'),
            'tile'  => true,
        ),
        'right-tilted-lines'        => array(
            'label' => __('Right tilted lines', 'mesmerize'),
            'tile'  => true,
        ),
        'right-tilted-strips'       => array(
            'label' => __('Right tilted strips', 'mesmerize'),
            'tile'  => false,
        ),
    );
    
    $shapeURL = apply_filters('mesmerize_assets_url', mesmerize_pro_uri('/assets/shapes'), '/assets/shapes');
    foreach ($shapes as $shape => $data) {
        $shapes[$shape]['url'] = $shapeURL;
    }
    
    return array_merge($result, $shapes);
});
