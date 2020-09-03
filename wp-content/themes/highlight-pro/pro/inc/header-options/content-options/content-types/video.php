<?php



add_action("mesmerize_front_page_header_media_box_options_after", "mesmerize_front_page_header_media_box_options_after", 0, 3);

function mesmerize_front_page_header_media_box_options_after($section, $prefix, $priority)
{

    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'settings'        => $prefix . '_content_image_rounded',
        'label'           => __('Make Image round', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => false,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => 'in',
                'value'    => array('image'),
            ),
        ),

    ));

    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => $prefix . '_content_image_border_color',
        'label'           => __('Image Border Color', 'mesmerize'),
        'section'         => $section,
        'default'         => '#ffffff',
        'priority'        => $priority,
        'choices'         => array(
            'alpha' => true,
        ),
        'output'          => array(
            array(
                'element'  => '.mesmerize-front-page .header-wrapper > div:not(.owl-carousel)  .homepage-header-image',
                'property' => 'border-color',
                'suffix'   => '!important',
            ),
        ),
        'transport'       => 'postMessage',
        'js_vars'         => array(
            array(
                'element'  => '.mesmerize-front-page .header-wrapper > div:not(.owl-carousel)  .homepage-header-image',
                'property' => 'border-color',
                'suffix'   => '!important',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => 'in',
                'value'    => array('image'),
            ),
            array(
                'setting'  => $prefix . '_content_image_rounded',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'            => 'slider',
        'settings'        => $prefix . '_content_image_border_thickness',
        'label'           => __('Image Border Thickness', 'mesmerize'),
        'section'         => $section,
        'default'         => 5,
        'priority'        => $priority,
         'choices' => array(
            'min'  => '0',
            'max'  => '20',
            'step' => '1',
        ),
        'output'          => array(
            array(
                'element'  => '.mesmerize-front-page .header-wrapper > div:not(.owl-carousel) .homepage-header-image',
                'property' => 'border-width',
                'suffix'   => 'px !important',
            ),
        ),
        'transport'       => 'postMessage',
        'js_vars'         => array(
            array(
                'element'  => '.mesmerize-front-page .header-wrapper > div:not(.owl-carousel) .homepage-header-image',
                'property' => 'border-width',
                'suffix'   => 'px !important',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => 'in',
                'value'    => array('image'),
            ),
            array(
                'setting'  => $prefix . '_content_image_rounded',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));


    // last
    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'settings'        => $prefix . '_content_video_img_shadow',
        'label'           => __('Enable media shadow', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => false,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => 'in',
                'value'    => array('image', 'video', 'video_popup'),
            ),
        ),
    ));
}

add_action("mesmerize_front_page_header_content_options_before", "mesmerize_front_page_header_content_options_video", 0, 3);

function mesmerize_front_page_header_content_options_video($section, $prefix, $priority)
{

    mesmerize_add_kirki_field(array(
        'type'            => 'text',
        'settings'        => $prefix . '_content_video',
        'label'           => __('Content Video', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => 'https://www.youtube.com/watch?v=3iXYciBTQ0c',
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => 'contains',
                'value'    => 'video',
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'settings'        => $prefix . '_content_video_autoplay',
        'label'           => __('Autoplay video', 'mesmerize'),
        'description'     => __('In customizer the video auto play is turned off for performance improvements', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => false,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => '==',
                'value'    => 'video',
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'settings'        => $prefix . '_content_video_loop',
        'label'           => __('Loop Video', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => false,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => '==',
                'value'    => 'video',
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => $prefix . '_color_video_popup_button',
        'label'           => __('Icon Color', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => '#ffffff',
        'choices'         => array(
            'alpha' => true,
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => 'a.video-popup-button-link',
                'property' => 'color',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => 'a.video-popup-button-link',
                'function' => 'css',
                'property' => 'color',
                'suffix'   => ' !important',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => 'in',
                'value'    => array('video_popup'),
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => $prefix . '_color_video_popup_button_hover',
        'label'           => __('Icon Hover Color', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => '#7AA7F5',
        'choices'         => array(
            'alpha' => true,
        ),
        'output'          => array(
            array(
                'element'  => 'a.video-popup-button-link:hover',
                'property' => 'color',
            ),
        ),
        'transport'       => 'postMessage',
        'js_vars'         => array(
            array(
                'element'  => 'a.video-popup-button-link:hover',
                'function' => 'css',
                'property' => 'color',
                'suffix'   => ' !important',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => 'in',
                'value'    => array('video_popup'),
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'settings'        => $prefix . '_video_popup_image_disabled',
        'label'           => __('Hide Video Poster', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => false,
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => 'in',
                'value'    => array('video_popup'),
            ),

        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'            => 'image',
        'settings'        => $prefix . '_video_popup_image',
        'label'           => __('Poster', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => apply_filters('mesmerize_assets_url', get_template_directory_uri() , '/') . '/assets/images/video-poster.jpg',
        'transport'       => 'postMessage',
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => 'in',
                'value'    => array('video_popup'),
            ),
            array(
                'setting'  => $prefix . '_video_popup_image_disabled',
                'operator' => '==',
                'value'    => false,
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'            => 'color',
        'settings'        => $prefix . '_video_popup_overlay_color',
        'label'           => __('Video Poster Overlay Color', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        'default'         => 'rgba(0, 0, 0, 0.5)',
        'choices'         => array(
            'alpha' => true,
        ),
        'transport'       => 'postMessage',
        'output'          => array(
            array(
                'element'  => '.video-popup-button.with-image:before',
                'property' => 'background-color',
            ),
        ),
        'js_vars'         => array(
            array(
                'element'  => '.video-popup-button.with-image:before',
                'function' => 'css',
                'property' => 'background-color',
                'suffix'   => ' !important',
            ),
        ),
        'active_callback' => array(
            array(
                'setting'  => $prefix . '_content_media',
                'operator' => 'in',
                'value'    => array('video_popup'),
            ),
            array(
                'setting'  => $prefix . '_video_popup_image_disabled',
                'operator' => '==',
                'value'    => false,
            ),
        ),
    ));
}


add_filter("header_media_box_settings_filter", function ($values) {
    $new = array(

        'header_content_video',
        'header_content_video_autoplay',
        'header_content_video_loop',

        'header_color_video_popup_button',
        'header_color_video_popup_button_hover',

        'header_video_popup_image_disabled',
        'header_video_popup_image',
        'header_video_popup_overlay_color'
    );


    $index = array_search("header_column_width", $values);

    array_splice($values, $index + 1, 0, array(
        'header_content_image_rounded',
        'header_content_image_border_color',
        'header_content_image_border_thickness',
        'header_content_video_img_shadow'
    ));

    return array_merge($values, $new);
});
