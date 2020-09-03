<?php

function mesmerize_footer_background_type()
{
    
    $section = 'footer_settings';
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => esc_html__('Footer Background', 'mesmerize'),
        'section'  => $section,
        'priority' => 3,
        'settings' => 'footer_background_type_separator',
    ));
    
    
    $footer_content_copyright_text = __('&copy; {year} {blogname}. Built using WordPress and <a href="#">Mesmerize Theme</a>.', 'mesmerize');
    
    mesmerize_add_kirki_field(array(
        'type'      => 'textarea',
        'settings'  => 'footer_content_copyright_text',
        'label'     => __('Copyright Text', 'mesmerize'),
        'section'   => $section,
        'priority'  => 2,
        'default'   => apply_filters('mesmerize_footer_content_copyright_text_default', $footer_content_copyright_text),
        'transport' => 'postMessage',
    ));
    
    mesmerize_add_kirki_field(array(
        'type'              => 'select',
        'settings'          => 'footer_background_type',
        'label'             => esc_html__('Background Type', 'mesmerize'),
        'section'           => $section,
        'choices'           => apply_filters('mesmerize_footer_background_types', array(
            'color'    => __('Color', 'mesmerize'),
            'image'    => __('Image', 'mesmerize'),
            'gradient' => __('Gradient', 'mesmerize'),
        )),
        'default'           => 'color',
        'sanitize_callback' => 'sanitize_text_field',
        'priority'          => 3,
    ));
    
    // Image background settings
    
    $group = "footer_bg_options_group_button";
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Background Image Options', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_bg_image_separator',
        'priority'        => 3,
        'group'           => $group,
        'active_callback' => array(
            array(
                'setting'  => 'footer_background_type',
                'operator' => '==',
                'value'    => 'image',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'              => 'image',
        'settings'          => 'footer_bg_image',
        'label'             => esc_html__('Footer Image', 'mesmerize'),
        'section'           => $section,
        'sanitize_callback' => 'esc_url_raw',
        'default'           => apply_filters('mesmerize_assets_url', get_template_directory_uri(), '/') . '/assets/images/home_page_header.jpg',
        'priority'          => 3,
        'group'             => $group,
        'transport'         => 'postMessage',
        'active_callback'   => array(
            array(
                'setting'  => 'footer_background_type',
                'operator' => '==',
                'value'    => 'image',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'select',
        'settings'        => 'footer_bg_image_size',
        'label'           => esc_html__('Background Image Size', 'mesmerize'),
        'section'         => $section,
        'priority'        => 3,
        'group'           => $group,
        'default'         => 'cover',
        'choices'         => array(
            'auto'    => __('Auto', 'mesmerize'),
            'contain' => __('Contain', 'mesmerize'),
            'cover'   => __('Cover', 'mesmerize'),
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => array('.footer-content'),
                'property' => 'background-size',
            ),
        
        ),
        'js_vars'         => array(
            array(
                'element'  => array('.footer-content'),
                'function' => 'css',
                'property' => 'background-size',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_background_type',
                'operator' => '==',
                'value'    => 'image',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'select',
        'settings'        => 'footer_bg_image_position',
        'label'           => esc_html__('Background Image Position', 'mesmerize'),
        'section'         => $section,
        'priority'        => 3,
        'group'           => $group,
        'default'         => 'center center',
        'choices'         => array(
            'left top'    => __('Left Top', 'mesmerize'),
            'left center' => __('Left Center', 'mesmerize'),
            'left bottom' => __('Left Bottom', 'mesmerize'),
            
            'center top'    => __('Center Top', 'mesmerize'),
            'center center' => __('Center Center', 'mesmerize'),
            'center bottom' => __('Center Bottom', 'mesmerize'),
            
            'right top'    => __('Right Top', 'mesmerize'),
            'right center' => __('Right Center', 'mesmerize'),
            'right bottom' => __('Right Bottom', 'mesmerize'),
        
        ),
        'output'          => array(
            array(
                'element'  => array('.footer-content'),
                'property' => 'background-position',
            ),
        
        ),
        'transport'       => 'postMessage',
        'js_vars'         => array(
            array(
                'element'  => array('.footer-content'),
                'function' => 'css',
                'property' => 'background-position',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_background_type',
                'operator' => '==',
                'value'    => 'image',
            ),
        ),
    ));
    
    // Gradient background settings
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Background Gradient Options', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_bg_gradient_separator',
        'priority'        => 3,
        'group'           => $group,
        'active_callback' => array(
            array(
                'setting'  => 'footer_background_type',
                'operator' => '==',
                'value'    => 'gradient',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'web-gradients',
        'settings'        => 'footer_bg_gradient',
        'label'           => esc_html__('Footer Gradient', 'mesmerize'),
        'section'         => $section,
        'default'         => 'plum_plate',
        'priority'        => 3,
        'group'           => $group,
        'active_callback' => array(
            array(
                'setting'  => 'footer_background_type',
                'operator' => '==',
                'value'    => 'gradient',
            ),
        ),
    ));
    
    // color background settings
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Background Color Options', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_bg_color_separator',
        'priority'        => 3,
        'group'           => $group,
        'active_callback' => array(
            array(
                'setting'  => 'footer_background_type',
                'operator' => '==',
                'value'    => 'color',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'label'           => esc_html__('Footer Color', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_background_color',
        'default'         => mesmerize_footer_default('footer_background_color'),
        'priority'        => 3,
        'group'           => $group,
        'choices'         => array(
            'alpha' => false,
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => array('.footer .footer-content'),
                'property' => 'background-color',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => array('.footer .footer-content'),
                'function' => 'css',
                'property' => 'background-color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_background_type',
                'operator' => '==',
                'value'    => 'color',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings'        => 'footer_bg_options_group_button',
        'label'           => esc_html__('Options', 'mesmerize'),
        'section'         => $section,
        'priority'        => 3,
        'description'     => esc_html__('Options', 'mesmerize'),
        'active_callback' => array(
            array(
                'setting'  => 'footer_background_type',
                'operator' => 'in',
                'value'    => array('color', 'image', 'gradient'),
            ),
        ),
        'in_row_with'     => array('footer_background_type'),
    ));
    
}

mesmerize_footer_background_type();


function mesmerize_footer_overlay_options()
{
    
    $section = 'footer_settings';
    
    mesmerize_add_kirki_field(array(
        'type'     => 'checkbox',
        'settings' => 'footer_show_overlay',
        'label'    => esc_html__('Show overlay', 'mesmerize'),
        'section'  => $section,
        'default'  => false,
        'priority' => 4,
    ));
    
    
    // overlay options settings
    
    $group = "footer_overlay_options_group_button";
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Overlay Options', 'mesmerize'),
        'section'         => $section,
        'priority'        => 4,
        'group'           => $group,
        'settings'        => 'footer_overlay_options_separator',
        'active_callback' => array(
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'            => 'select',
        'settings'        => 'footer_overlay_type',
        'label'           => esc_html__('Overlay Type', 'mesmerize'),
        'section'         => $section,
        'choices'         => apply_filters('mesmerize_overlay_types', array(
            'none'     => __('Shape Only', 'mesmerize'),
            'color'    => __('Color', 'mesmerize'),
            'gradient' => __('Gradient', 'mesmerize'),
        )),
        'default'         => 'color',
        'priority'        => 4,
        'group'           => $group,
        'active_callback' => array(
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Overlay Color Options', 'mesmerize'),
        'section'         => $section,
        'priority'        => 4,
        'group'           => $group,
        'settings'        => 'footer_overlay_color_options_separator',
        'active_callback' => array(
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                'setting'  => 'footer_overlay_type',
                'operator' => '==',
                'value'    => 'color',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'label'           => esc_html__('Overlay Color', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_overlay_color',
        'default'         => '#ffffff',
        'priority'        => 4,
        'group'           => $group,
        'choices'         => array(
            'alpha' => false,
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => array('.footer-content.color-overlay::before'),
                'property' => 'background',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => array('.footer-content.color-overlay::before'),
                'function' => 'css',
                'property' => 'background',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                'setting'  => 'footer_overlay_type',
                'operator' => '==',
                'value'    => 'color',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Overlay Gradient Options', 'mesmerize'),
        'section'         => $section,
        'priority'        => 4,
        'group'           => $group,
        'settings'        => 'footer_overlay_gradient_options_separator',
        'active_callback' => array(
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                'setting'  => 'footer_overlay_type',
                'operator' => '==',
                'value'    => 'gradient',
            ),
        ),
    ));
    
    $gradients = mesmerize_get_parsed_gradients();
    
    mesmerize_add_kirki_field(array(
        'type'            => 'gradient-control-pro',
        'label'           => esc_html__('Gradient Colors', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_overlay_gradient_colors',
        'priority'        => 4,
        'group'           => $group,
        'default'         => json_encode($gradients['red_salvation']),
        'active_callback' => array(
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                'setting'  => 'footer_overlay_type',
                'operator' => '==',
                'value'    => 'gradient',
            ),
        ),
        'transport'       => 'postMessage',
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'slider',
        'label'           => esc_html__('Overlay Opacity', 'mesmerize'),
        'section'         => $section,
        'priority'        => 4,
        'group'           => $group,
        'settings'        => 'footer_overlay_opacity',
        'default'         => 0.5,
        'transport'       => 'postMessage',
        'choices'         => array(
            'min'  => '0',
            'max'  => '1',
            'step' => '0.01',
        ),
        'output'          => array(
            array(
                'element'  => array('.footer-content.color-overlay::before'),
                'property' => 'opacity',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => array('.footer-content.color-overlay::before'),
                'function' => 'css',
                'property' => 'opacity',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
            array(
                'setting'  => 'footer_overlay_type',
                'operator' => 'in',
                'value'    => array('color'),
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Overlay Shapes Options', 'mesmerize'),
        'section'         => $section,
        'priority'        => 4,
        'group'           => $group,
        'settings'        => 'footer_overlay_shapes_options_separator',
        'active_callback' => array(
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'select',
        'label'           => esc_html__('Overlay Shapes', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_overlay_shape',
        'default'         => 'none',
        'priority'        => 4,
        'choices'         => mesmerize_get_header_shapes_overlay(),
        'active_callback' => array(
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
        ),
        'group'           => $group,
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'slider',
        'label'           => esc_html__('Shape Light', 'mesmerize'),
        'section'         => $section,
        'priority'        => 4,
        'group'           => $group,
        'settings'        => 'footer_overlay_shape_light',
        'default'         => 0,
        'choices'         => array(
            'min'  => '0',
            'max'  => '100',
            'step' => '1',
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'       => array('.footer-content::after'),
                'property'      => 'filter',
                'value_pattern' => 'invert($%) ',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'       => array('.footer-content::after'),
                'function'      => 'css',
                'property'      => 'filter',
                'value_pattern' => 'invert($%) ',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_overlay_shape',
                'operator' => '!=',
                'value'    => 'none',
            ),
            
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings'        => 'footer_overlay_options_group_button',
        'label'           => esc_html__('Options', 'mesmerize'),
        'section'         => $section,
        'priority'        => 4,
        'active_callback' => array(
            array(
                'setting'  => 'footer_show_overlay',
                'operator' => '==',
                'value'    => true,
            ),
        ),
        'in_row_with'     => array('footer_show_overlay'),
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'      => 'spacing',
        'settings'  => 'footer_spacing',
        'label'     => esc_html__('Footer Spacing', 'mesmerize'),
        'section'   => $section,
        'priority'  => 4,
        'default'   => mesmerize_footer_default('footer_spacing'),
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'     => '.footer .footer-content',
                'property'    => 'padding',
                'media_query' => '@media (min-width: 767px)',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'     => '.footer .footer-content',
                'function'    => 'style',
                'property'    => 'padding',
                'media_query' => '@media (min-width: 767px)',
            ),
        ),
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'      => 'checkbox',
        'label'     => __('Show Footer Border', 'mesmerize'),
        'section'   => $section,
        'priority'  => 4,
        'settings'  => 'footer_enable_top_border',
        'default'   => false,
        'transport' => 'postMessage',
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'label'           => __('Footer Border Color', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_top_border_color',
        'priority'        => 4,
        'choices'         => array(
            'alpha' => true,
        ),
        'default'         => '#e8e8e8',
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => '.footer .footer-content',
                'property' => 'border-top-color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_enable_top_border',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    mesmerize_add_kirki_field(array(
        'type'            => 'number',
        'label'           => __('Footer Border Thickness', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_top_border_thickness',
        'choices'         => array(
            'min'  => 1,
            'max'  => 50,
            'step' => 1,
        ),
        'default'         => '1',
        'priority'        => 4,
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => '.footer .footer-content',
                'property' => 'border-top-width',
                'suffix'   => 'px',
            ),
            array(
                'element'       => '.footer .footer-content',
                'property'      => 'border-top-style',
                'value_pattern' => 'solid',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_enable_top_border',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    
    
}

mesmerize_footer_overlay_options();

function mesmerize_footer_default($setting)
{
    $values          = mesmerize_footer_templates_update_pro(array());
    $footer_template = get_theme_mod("footer_template", "simple");
    
    $defaults = false;
    foreach ($values as $key => $value) {
        if ($value['value'] == $footer_template) {
            $defaults = $value['fields'];
            break;
        }
    }
    
    return $defaults[$setting];
}

function mesmerize_footer_fonts_color()
{
    
    $section = 'footer_settings';
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => esc_html__('Footer Colors', 'mesmerize'),
        'section'  => $section,
        'priority' => 5,
        'settings' => 'footer_font_colors_separator',
    ));
    
    // font colors options and section
    
    $group = "footer_font_colors_group_button";
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => esc_html__('Footer Colors Options', 'mesmerize'),
        'section'  => $section,
        'priority' => 5,
        'group'    => $group,
        'settings' => 'footer_font_colors_options_separator',
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'label'           => esc_html__('Title Color', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_font_title_color',
        'default'         => mesmerize_footer_default('footer_font_title_color'),
        'priority'        => 5,
        'group'           => $group,
        'choices'         => array(
            'alpha' => false,
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => array('.footer h1, .footer h2, .footer h3, .footer h4, .footer h5, .footer h6'),
                'property' => 'color',
                'suffix'   => '!important',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => array('.footer h1, .footer h2, .footer h3, .footer h4, .footer h5, .footer h6'),
                'function' => 'css',
                'property' => 'color',
                'suffix'   => '!important',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_template',
                'operator' => 'in',
                'value'    => array('1', '4', '7', 'content-lists'),
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'label'           => esc_html__('Paragraph Color', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_font_paragraph_color',
        'default'         => mesmerize_footer_default('footer_font_paragraph_color'),
        'priority'        => 5,
        'group'           => $group,
        'choices'         => array(
            'alpha' => false,
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => array('.footer p, .footer'),
                'property' => 'color',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => array('.footer p, .footer'),
                'function' => 'css',
                'property' => 'color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_template',
                'operator' => 'in',
                'value'    => array('1', '4', '7', 'simple', 'contact-boxes', 'content-lists'),
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'label'           => esc_html__('Anchor Color', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_font_anchor_color',
        'default'         => mesmerize_footer_default('footer_font_anchor_color'),
        'priority'        => 5,
        'group'           => $group,
        'choices'         => array(
            'alpha' => false,
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => array('.footer a'),
                'property' => 'color',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => array('.footer a'),
                'function' => 'css',
                'property' => 'color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_template',
                'operator' => 'in',
                'value'    => array('1', '4', '7', 'content-lists'),
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'label'           => esc_html__('Anchor Color on Hover', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_font_anchor_hover_color',
        'default'         => mesmerize_footer_default('footer_font_anchor_hover_color'),
        'priority'        => 5,
        'group'           => $group,
        'choices'         => array(
            'alpha' => false,
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => array('.footer a:hover'),
                'property' => 'color',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => array('.footer a:hover'),
                'function' => 'css',
                'property' => 'color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_template',
                'operator' => 'in',
                'value'    => array('1', '4', '7', 'content-lists'),
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'label'           => esc_html__('Icon Color', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_font_icon_color',
        'default'         => mesmerize_footer_default('footer_font_icon_color'),
        'priority'        => 5,
        'group'           => $group,
        'choices'         => array(
            'alpha' => false,
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => array('.footer a .fa, .footer .fa'),
                'property' => 'color',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => array('.footer a .fa, .footer .fa'),
                'function' => 'css',
                'property' => 'color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_template',
                'operator' => 'in',
                'value'    => array('1', '4', '7', 'contact-boxes', 'content-lists'),
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'label'           => esc_html__('Icon Color on Hover', 'mesmerize'),
        'section'         => $section,
        'settings'        => 'footer_font_icon_hover_color',
        'default'         => mesmerize_footer_default('footer_font_icon_hover_color'),
        'priority'        => 5,
        'group'           => $group,
        'choices'         => array(
            'alpha' => false,
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => '.footer a:hover .fa',
                'property' => 'color',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => '.footer a:hover .fa',
                'function' => 'css',
                'property' => 'color',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_template',
                'operator' => 'in',
                'value'    => array('1', '4', '7', 'contact-boxes', 'content-lists'),
            ),
        ),
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'priority'        => 5,
        'settings'        => 'footer_accent_color',
        'label'           => __('Accent Bg. Color', 'mesmerize'),
        'section'         => $section,
        'default'         => mesmerize_footer_default('footer_accent_color'),
        'transport'       => 'postMessage',
        'choices'         => array(
            'alpha' => true,
        ),
        'group'           => $group,
        'output'          => array(
            array(
                'element'  => '.footer-border-accent',
                'property' => 'border-color',
                'suffix'   => ' !important',
            ),
            array(
                'element'  => '.footer-bg-accent',
                'property' => 'background-color',
                'suffix'   => ' !important',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => '.footer-border-accent',
                'function' => 'css',
                'property' => 'border-color',
                'suffix'   => ' !important',
            ),
            array(
                'element'  => '.footer-bg-accent',
                'function' => 'css',
                'property' => 'background-color',
                'suffix'   => ' !important',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => 'footer_template',
                'operator' => 'in',
                'value'    => array('4', 'contact-boxes', 'content-lists'),
            ),
        ),
    ));
    
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sidebar-button-group',
        'settings' => 'footer_font_colors_group_button',
        'label'    => esc_html__('Footer Colors Options', 'mesmerize'),
        'section'  => $section,
        'priority' => 5,
    ));
    
}

mesmerize_footer_fonts_color();


// print gradient overlay option
add_action('wp_head', function () {
    $type = get_theme_mod('footer_overlay_type', "color");
    if ($type != "gradient") {
        return;
    }
    
    $colors = get_theme_mod('footer_overlay_gradient_colors', "");
    $colors = json_decode($colors, true);
    
    $gradient = mesmerize_get_gradient_value($colors['colors'], $colors['angle']);
    
    ?>
    <style data-name="footer-gradient-overlay">
        .footer-content.color-overlay::before {
            background: <?php echo $gradient; ?>;
        }
    </style>
    <?php
});

add_action('wp_head', 'mesmerize_print_footer_shape', PHP_INT_MAX);
function mesmerize_print_footer_shape()
{
    
    $value           = get_theme_mod('footer_overlay_shape', "none");
    $overlay_enabled = get_theme_mod('footer_show_overlay', true);
    
    if ($value != "none" && $overlay_enabled) {
        $selector = '.footer-content::after';
        $value    = mesmerize_get_header_shape_overlay_value($value);
        ?>
        <style data-name="footer-shapes">
            <?php echo esc_html($selector)." {background:$value}"; ?>
        </style>
        <?php
    }
}

function mesmerize_update_footer_settings($dark_bg, $spacing = array(), $accent_color = false)
{
    $settings = array(
        "footer_font_title_color",
        "footer_font_paragraph_color",
        "footer_font_anchor_color",
        "footer_font_anchor_hover_color",
        "footer_font_icon_color",
        "footer_font_icon_hover_color",
        "footer_accent_color",
        "footer_background_color",
    );
    
    $defaults = array(
        "footer_background_type" => "color",
    );
    
    foreach ($settings as $index => $setting) {
        $defaults[$setting] = mesmerize_get_var($setting . "_" . ($dark_bg ? "dark" : "light"));
    }
    
    if ($dark_bg) {
        $defaults['footer_enable_top_border'] = false;
    } else {
        $defaults['footer_enable_top_border'] = true;
    }
    
    
    if (count($spacing)) {
        $defaults = array_merge(
            $defaults,
            array("footer_spacing" => $spacing)
        );
    }
    
    if ($accent_color) {
        $defaults['footer_accent_color'] = $accent_color;
    }
    
    return $defaults;
}


add_filter("mesmerize_footer_templates_update", "mesmerize_footer_templates_update_pro");

function mesmerize_footer_templates_update_pro($values)
{
    $values = array_merge($values, array(
        array(
            'value'  => 'contact-boxes',
            'fields' => mesmerize_update_footer_settings(true, array(
                'top'    => '0px',
                'bottom' => '0px',
            )),
        ),
        array(
            'value'  => 'content-lists',
            'fields' => mesmerize_update_footer_settings(true, array(
                'top'    => '0px',
                'bottom' => '0px',
            ), '#000000'),
        ),
        array(
            'value'  => 'simple',
            'fields' => mesmerize_update_footer_settings(false, array(
                'top'    => '40px',
                'bottom' => '40px',
            )),
        ),
        array(
            'value'  => '1',
            'fields' => mesmerize_update_footer_settings(false, array(
                'top'    => '30px',
                'bottom' => '30px',
            )),
        ),
        
        array(
            'value'  => '4',
            'fields' => mesmerize_update_footer_settings(false, array(
                'top'    => '15px',
                'bottom' => '15px',
            )),
        ),
        
        array(
            'value'  => '7',
            'fields' => mesmerize_update_footer_settings(false, array(
                'top'    => '20px',
                'bottom' => '20px',
            )),
        ),
    ));
    
    return $values;
}

function mesmerize_footer_templates_pro($values)
{
    $new = array(
        "1" => __("Copyright / Menu / Social", 'mesmerize'),
        "4" => __("Widgets / Copyright", 'mesmerize'),
        "7" => __("Copyright box / Widgets / Social", 'mesmerize'),
    );
    
    $result = $values + $new;
    
    return $result;
}

add_filter('mesmerize_footer_templates', 'mesmerize_footer_templates_pro');


function mesmerize_footer_templates_with_social_pro($values)
{
    $new = array(
        "1",
        "4",
        "7",
    );
    
    return array_merge($values, $new);
}

add_filter('mesmerize_footer_templates_with_social', 'mesmerize_footer_templates_with_social_pro');


add_action("mesmerize_customize_register_options", function () {
    mesmerize_footer_settings_pro();
});


function mesmerize_footer_settings_pro()
{
    
    $section = 'footer_settings';
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sectionseparator',
        'label'           => esc_html__('Description Content', 'mesmerize'),
        'section'         => $section,
        'priority'        => 1,
        'group'           => 'footer_content_7_box_group_button',
        'settings'        => 'footer_content_box_separator',
        'active_callback' => array(
            array(
                'setting'  => 'footer_template',
                'operator' => '==',
                'value'    => '7',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'              => 'textarea',
        'settings'          => 'footer_content_box_text',
        'label'             => esc_html__('Text', 'mesmerize'),
        'section'           => $section,
        'priority'          => 1,
        'group'             => 'footer_content_7_box_group_button',
        'default'           => __("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'mesmerize'),
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_kses_post',
        'active_callback'   => array(
            array(
                'setting'  => 'footer_template',
                'operator' => '==',
                'value'    => '7',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings'        => 'footer_content_7_box_group_button',
        'label'           => esc_html__('Description Options', 'mesmerize'),
        'section'         => $section,
        'priority'        => 1,
        'active_callback' => array(
            array(
                'setting'  => 'footer_template',
                'operator' => '==',
                'value'    => '7',
            ),
        ),
    ));
    
}

function mesmerize_print_footer_box_description()
{
    
    $text = get_theme_mod('footer_content_box_text', __("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'mesmerize'));
    
    echo mesmerize_wp_kses_post($text);
}


add_filter("mesmerize_footer_background_atts", function ($attrs) {
    if ( ! isset($attrs['style'])) {
        $attrs['style'] = "";
    } else {
        $attrs['style'] .= ";";
    }
    
    $bgType = get_theme_mod('footer_background_type', 'color');
    $theme  = wp_get_theme();
    
    $show_overlay = get_theme_mod("footer_show_overlay", false);
    if ($show_overlay) {
        $attrs['class'] .= " color-overlay ";
    }
    
    switch ($bgType) {
        case 'image':
            $bgImage        = get_theme_mod('footer_bg_image', apply_filters('mesmerize_assets_url', get_template_directory_uri() , '/') . "/assets/images/home_page_header.jpg");
            $attrs['style'] = 'background-image:url("' . esc_url($bgImage) . '")';
            break;
        
        case 'gradient':
            $bgGradient     = get_theme_mod("footer_bg_gradient", "plum_plate");
            $attrs['class'] .= $bgGradient;
            break;
    }
    
    return $attrs;
});
