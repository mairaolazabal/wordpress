<?php


add_filter("header_content_title_group_filter", function ($values) {
    $new = array(
        "header_content_title_typography",
        "header_content_title_spacing",

        "header_content_title_background_options_separator",
        "header_content_title_background_color",
        "header_content_title_background_spacing",
        "header_content_title_background_border_radius",

        "header_text_morph_separator",
        "header_show_text_morph_animation",
        "header_show_text_morph_animation_info",
        "header_text_morph_alternatives",
    );

    return array_merge($values, $new);
});

add_action("mesmerize_front_page_header_title_options_after", function ($section, $prefix, $priority) {

    mesmerize_add_kirki_field(array(
        'type'      => 'typography',
        'settings'  => 'header_content_title_typography',
        'label'     => esc_html__('Title Typography', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'font-family'      => apply_filters('mesmerize_default_headings_font_family', 'Muli'),
            'font-size'        => '3.5rem',
            'mobile-font-size' => '3.3em',
            'font-weight'      => '300',
            'line-height'      => '114%',
            'letter-spacing'   => '0.9px',
            'subsets'          => array(),
            'color'            => '#ffffff',
            'text-transform'   => 'none',
            'addwebfont'       => true,
        ),
        'output'    => array(
            array(
                'element' => '.header-homepage h1.hero-title',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element' => ".header-homepage h1.hero-title",
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'spacing',
        'settings'  => 'header_content_title_spacing',
        'label'     => esc_html__('Title Spacing', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'top'    => '0',
            'bottom' => '20px',
        ),
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'  => '.header-homepage .hero-title',
                'property' => 'margin',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage .hero-title",
                'function' => 'style',
                'property' => 'margin',
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => esc_html__('Background Options', 'mesmerize'),
        'section'  => $section,
        'settings' => "header_content_title_background_options_separator",
        'priority' => $priority,
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'label'     => esc_html__('Background Color', 'mesmerize'),
        'section'   => $section,
        'settings'  => 'header_content_title_background_color',
        'default'   => 'rgba(0,0,0,0)',
        'transport' => 'postMessage',
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => true,
        ),
        "output"    => array(
            array(
                'element'  => '.header-homepage .hero-title',
                'property' => 'background',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage .hero-title",
                'function' => 'css',
                'property' => 'background',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'spacing',
        'settings'  => 'header_content_title_background_spacing',
        'label'     => esc_html__('Background Spacing', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'top'    => '0px',
            'bottom' => '0px',
            'left'   => '0px',
            'right'  => '0px',
        ),
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'  => '.header-homepage .hero-title',
                'property' => 'padding',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage .hero-title",
                'function' => 'style',
                'property' => 'padding',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'dimension',
        'settings'  => 'header_content_title_background_border_radius',
        'label'     => esc_html__('Border Radius', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => '0px',
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'  => '.header-homepage .hero-title',
                'property' => 'border-radius',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage .hero-title",
                'function' => 'style',
                'property' => 'border-radius',
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => esc_html__('Text Animation', 'mesmerize'),
        'section'  => $section,
        'settings' => "header_text_morph_separator",
        'priority' => $priority,
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'checkbox',
        'settings' => 'header_show_text_morph_animation',
        'label'    => esc_html__('Enable text animation', 'mesmerize'),
        'section'  => $section,
        'default'  => false,
        'priority' => $priority,
    ));


    mesmerize_add_kirki_field(array(
        'type'            => 'ope-info',
        'label'           => esc_html__('The text between the curly braces will be replaced with the alternative texts in the following text area. Type one alternative text per line.', 'one-page-express'),
        'section'         => $section,
        'settings'        => "header_show_text_morph_animation_info",
        'active_callback' => array(
            array(
                'setting'  => 'header_show_text_morph_animation',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));
    mesmerize_add_kirki_field(array(
        'type'            => 'textarea',
        'settings'        => 'header_text_morph_alternatives',
        'label'           => esc_html__('Alternative text (one per row)', 'mesmerize'),
        'section'         => $section,
        'default'         => __("some text\nsome other text", 'mesmerize'),
        'transport'       => "postMessage",
        'priority'        => $priority,
        'active_callback' => array(
            array(
                'setting'  => 'header_show_text_morph_animation',
                'operator' => '==',
                'value'    => true,
            ),
        ),
    ));

}, 1, 3);

function mesmerize_apply_header_text_effects($text, $alternative_texts = false)
{
    if (is_customize_preview()) {
        return $text;
    }

    $matches = array();

    preg_match_all('/\{([^\}]+)\}/i', $text, $matches);

    if ( ! $alternative_texts) {
        $alternative_texts = get_theme_mod("header_text_morph_alternatives", __("some text\nsome other text", 'mesmerize'));
    }
    $alternative_texts = preg_split("/[\r\n]+/", $alternative_texts);

    for ($i = 0; $i < count($matches[1]); $i++) {
        $orig    = $matches[0][$i];
        $str     = $matches[1][$i];
        $strings = explode("|", $str);
        if (count($alternative_texts)) {
            $str = json_encode(array_merge($strings, $alternative_texts));
        }
        $text = str_replace($orig, '<span data-text-effect="' . esc_attr($str) . '">' . esc_html($strings[0]) . '</span>', $text);
    }

    return $text;
}


add_filter("mesmerize_header_title", function ($title) {
    $has_text_effect = get_theme_mod('header_show_text_morph_animation', true);
    if ($has_text_effect) {
        $title = mesmerize_apply_header_text_effects($title);
    }

    return $title;
});
add_filter("mesmerize_theme_deps", function ($deps) {
    $textDomain = mesmerize_get_text_domain();

    $useTextAnimation = get_theme_mod('header_show_text_morph_animation', false);

    if ($useTextAnimation) {
        array_push($deps, 'typedjs');
    }

    return $deps;
});


function mesmerize_enqueue_morph_animation()
{
    if (did_action('mesmerize_test_enqueue_morph_animation')) {
        return;
    }
    mesmerize_enqueue_script(
        'typedjs',
        array(
            'src'     => get_template_directory_uri() . '/assets/js/libs/typed.js',
            'deps'    => array('jquery'),
            'has_min' => true,
        )
    );

    $mesmerize_jssettings = array(
        'header_text_morph_speed' => intval(get_theme_mod('header_text_morph_speed', 200)),
        'header_text_morph'       => get_theme_mod('header_show_text_morph_animation', true),
    );

    wp_localize_script('typedjs', 'mesmerize_morph', $mesmerize_jssettings);
    do_action('mesmerize_test_enqueue_morph_animation');
}

// add text animation scripts
add_action('wp_enqueue_scripts', function () {
    $useTextAnimation = get_theme_mod('header_show_text_morph_animation', false);

    if (intval($useTextAnimation)) {
        mesmerize_enqueue_morph_animation();
    }

});
