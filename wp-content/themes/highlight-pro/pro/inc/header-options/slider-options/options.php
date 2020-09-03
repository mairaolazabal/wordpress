<?php

//SWITCHER
add_action('mesmerize_header_background_settings', function ($section, $prefix, $group, $inner, $priority) {
    if ( ! $inner) {
        
        $heroTypes = array(
            'simple'               => esc_attr__('Simple', 'mesmerize'),
            'slider-not-supported' => esc_attr__('Slider', 'mesmerize'),
            'third_party_slider'   => esc_attr__('Custom Slider', 'mesmerize'),
        );
        
        if (apply_filters('mesmerize_supports-header-slider', false)) {
            $heroTypes = array(
                'simple'             => esc_attr__('Simple', 'mesmerize'),
                'slider'             => esc_attr__('Slider', 'mesmerize'),
                'third_party_slider' => esc_attr__('Custom Slider', 'mesmerize'),
            );
        }
        
        mesmerize_add_kirki_field(array(
            'type'              => 'radio-buttonset',
            'settings'          => 'header_type',
            'label'             => esc_html__('Hero Type', 'mesmerize'),
            'section'           => $section,
            'choices'           => $heroTypes,
            'default'           => 'simple',
            'sanitize_callback' => 'sanitize_text_field',
            'priority'          => 0,
        ));
        
    }
}, 10, 5);


function mesmerize_slider_default_content()
{
    $slide_default = require mesmerize_pro_dir("/inc/header-options/slider-options/default-slide.php");
    
    $slideOptions = array(
        '0' => array(
            'slide_background_image'         => apply_filters('mesmerize_assets_url', get_template_directory_uri(), '/') . '/assets/images/home_page_header-2.jpg',
            'slide_title_options_title_text' => 'First Slide.<br/>You can edit this text in customizer',
        ),
        '1' => array(
            'slide_background_image'         => apply_filters('mesmerize_assets_url', get_template_directory_uri(), '/') . '/assets/images/home_page_header.jpg',
            'slide_title_options_title_text' => 'Second Slide.<br/>You can edit this text in customizer',
            'slide_overlay_type'             => 'gradient',
            'slide_overlay_gradient'         => '{"angle":"142","colors":[{"color":"rgba(244,59,71, 0.8)","position":"0%"},{"color":"rgba(69,58,148, 0.8)","position":"100%"}]}',
        ),
    );
    
    $result = array();
    
    for ($i = 0; $i < 2; $i++) {
        $slide             = $slide_default;
        $slide['slide_id'] = (string)$i;
        
        foreach ($slideOptions[(string)$i] as $key => $value) {
            $slide[$key] = $value;
        }
        $result[(string)$i] = $slide;
    }
    
    return $result;
}

function mesmerize_slider_settings()
{
    
    $section  = 'header_background_chooser';
    $priority = 7;
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Slider Content', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'slider_content_separator',
        'priority'        => $priority,
        'active_callback' => array(
            array(
                'setting'  => 'header_type',
                'operator' => '==',
                'value'    => 'slider',
            ),
        ),
    ));
    
    
    $slide_default = require mesmerize_pro_dir("/inc/header-options/slider-options/default-slide.php");
    
    if (apply_filters('mesmerize_supports-header-slider', false)) {
        mesmerize_add_kirki_field(array(
            'type'            => 'header-slider',
            'label'           => esc_html__('Slider Elements', 'mesmerize'),
            'section'         => $section,
            'settings'        => 'slider_elements',
            'priority'        => $priority,
            'default'         => mesmerize_slider_default_content(),
            'choices'         => array(
                'max_elements'  => 7,
                'default_slide' => $slide_default,
            ),
            'transport'       => 'postMessage',
            'row_label'       => esc_attr__('slide', 'mesmerize'),
            'active_callback' => array(
                array(
                    'setting'  => 'header_type',
                    'operator' => '==',
                    'value'    => 'slider',
                ),
            ),
        ));
    } else {
        mesmerize_add_kirki_field(array(
            'type'            => 'ope-info',
            'label'           => esc_html__('Seems that you have an older version of companion. In order to use the mesmerize slider please update the companion first', 'mesmerize'),
            'section'         => $section,
            'settings'        => "slider_elements",
            'priority'        => '0',
            'active_callback' => array(
                array(
                    'setting'  => 'header_type',
                    'operator' => '==',
                    'value'    => 'slider-not-supported',
                ),
            ),
        ));
    }
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Slider Inner Elements', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'slider_inner_elements_separator',
        'priority'        => $priority,
        'active_callback' => array(
            array(
                'setting'  => 'header_type',
                'operator' => '==',
                'value'    => 'slider',
            ),
        ),
    ));
    
    
    mesmerize_pro_require("/inc/header-options/slider-options/inner-elements/bottom-separator.php");
    mesmerize_pro_require("/inc/header-options/slider-options/inner-elements/split-background.php");
    mesmerize_pro_require("/inc/header-options/slider-options/inner-elements/bottom-arrow.php");
    
    
    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'label'           => esc_html__('Full Height Background', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'slider_full_height_background_enabled',
        'priority'        => $priority,
        'default'         => false,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => 'header_type',
                'operator' => '==',
                'value'    => 'slider',
            ),
        ),
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'label'           => esc_html__('Allow content to overlap header', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'slider_overlap_header',
        'priority'        => $priority,
        'default'         => true,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => 'header_type',
                'operator' => '==',
                'value'    => 'slider',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'dimension',
        'label'           => esc_html__('Overlap with', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'slider_overlap_header_with',
        'priority'        => $priority,
        'default'         => '95px',
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => 'header_type',
                'operator' => '==',
                'value'    => 'slider',
            ),
            array(
                "setting"  => 'slider_overlap_header',
                "operator" => '==',
                "value"    => true,
            ),
        ),
    ));
    
    
    mesmerize_pro_require("/inc/header-options/slider-options/transitions.php");
    
    
    // slider navigation options
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Slider Navigation Options', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'slider_navigation_separator',
        'priority'        => $priority,
        'active_callback' => array(
            array(
                'setting'  => 'header_type',
                'operator' => '==',
                'value'    => 'slider',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'label'           => esc_html__('Show Navigation', 'mesmerize'),
        'description'     => esc_html__('Slider navigation is visible only if you have more than one slide added.', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'slider_enable_navigation',
        'priority'        => $priority,
        'default'         => true,
        'active_callback' => array(
            array(
                'setting'  => 'header_type',
                'operator' => '==',
                'value'    => 'slider',
            ),
        ),
    ));
    
    mesmerize_pro_require("/inc/header-options/slider-options/navigation/prev-next-buttons.php");
    mesmerize_pro_require("/inc/header-options/slider-options/navigation/play-pause-buttons.php");
    mesmerize_pro_require("/inc/header-options/slider-options/navigation/pagination.php");
    
    mesmerize_add_kirki_field(array(
        'type'            => 'text',
        'label'           => esc_html__('Slider Shortcode ', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'third_party_slider_shortcode',
        'priority'        => $priority,
        'default'         => "",
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => 'header_type',
                'operator' => '==',
                'value'    => 'third_party_slider',
            ),
        ),
    ));
}


add_action('mesmerize_customize_register_options', function () {
    mesmerize_slider_settings();
});

//mesmerize_pro_require("/inc/header-options/slider-options/multilanguage-filters.php");
