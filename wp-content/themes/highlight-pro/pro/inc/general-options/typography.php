<?php

add_action('mesmerize_customize_register', 'mesmerize_register_general_typography');

function mesmerize_register_general_typography($wp_customize)
{
    $wp_customize->add_section('general_site_style', array(
        'priority' => 2,
        'title'    => __('Typography', 'mesmerize'),
        'panel'    => 'general_settings',
    ));
}


function mesmerize_google_fonts_options()
{
    $priority = 1;
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Web Fonts in site', 'mesmerize'),
        'section'  => 'general_site_style',
        'settings' => "web_fonts_separator",
        'priority' => $priority,
    ));


    $defaultFonts = mesmerize_get_general_google_fonts();

    mesmerize_add_kirki_field(array(
        'type'     => 'web-fonts',
        'settings' => 'web_fonts',
        'label'    => '',
        'section'  => "general_site_style",
        'default'  => $defaultFonts,
        'priority' => $priority,
    ));

}

add_filter('mesmerize_google_fonts', function ($fonts) {
    $gFonts = get_theme_mod("web_fonts", "");


    if ($gFonts && is_string($gFonts)) {
        $gFonts = json_decode($gFonts, true);
    } else {
        $gFonts = array();
    }

    foreach ((array)$gFonts as $font) {
        $weights = $font['weights'];
        $fam     = $font['family'];
        if (isset($fonts[$fam])) {
            $weights = array_merge($weights, $fonts[$fam]['weights']);
        }
        $fonts[$fam] = array("weights" => $weights);
    }


    return $fonts;
});

function mesmerize_font_is_in_list($fonts, $font)
{
    $result = false;

    if (isset($fonts[$font])) {
        return true;
    }

    foreach ($fonts as $f) {
        if (isset($f['family']) && $f['family'] === $font) {
            $result = true;
            break;
        } else {

            if (isset($f['font']) && $f['font'] === $font) {
                $result = true;
                break;
            } else {
                if (isset($f['font-family']) && $f['font-family'] === $font) {
                    $result = true;
                    break;
                }
            }
        }
    }

    return $result;
}


function mesmerize_is_system_font($value)
{

    $value       = strtolower($value);
    $systemFonts = array(
        "Georgia",
        "Times",
        "Times New Roman",
        "Helvetica",
        "Arial",
        "Monaco",
        "Lucida Sans Typewriter",
        "Lucida Typewriter",
        "Courier New",
        "Courier",
        "monospace",
    );

    foreach ($systemFonts as $font) {

        if (strpos($value, strtolower($font)) !== false) {
            return true;
        }
    }

    return false;
}

function mesmerize_get_fonts_in_mods($fonts = array(), $numeric_keys = false)
{
    foreach (Kirki::$fields as $setting => $atts) {
        if (isset($atts['type']) && $atts['type'] === 'kirki-typography') {
            $data = get_theme_mod($setting, false);

            if ($data) {
                $font = isset($data['font-family']) ? $data['font-family'] : false;

                if ( ! $font || $font === "inherit") {
                    continue;
                }

                $variants = array('400');

                if (isset($data['font-family'])) {
                    $variants = (array)$data['font-family'];
                }
                if (isset($data['variant'])) {
                    $variants = (array)$data['variant'];

                    if ( ! in_array('400', $variants) && ! in_array('regular', $variants)) {
                        $variants[] = "400";
                    }
                }

                $font = trim($font);

                $fontData = array();

                if ( ! mesmerize_font_is_in_list($fonts, $font)) {
                    $fontData = array(
                        'font'        => $font,
                        'family'      => $font,
                        'font-family' => $font,
                        'weights'     => $variants,
                    );
                } else {
                    $existing = isset($fonts[$font]['weights']) ? $fonts[$font]['weights'] : array();
                    $variants = $existing + $variants;
                    $variants = array_unique($variants);
                    $fontData = array(
                        'font'        => $font,
                        'family'      => $font,
                        'font-family' => $font,
                        'weights'     => $variants,
                    );
                }

                $fonts[$font] = $fontData;
            }

        }
    }

    $result     = array();
    $kirkiFonts = Kirki_Fonts::get_google_fonts();

    if ($numeric_keys) {
        foreach ($fonts as $font) {
            $family = mesmerize_retrieve_font_family_from_data($font);
            if ( ! isset($kirkiFonts[$family])) {
                continue;
            }
            $result[] = $font;
        }
    } else {
        foreach ($fonts as $key => $font) {
            $family = mesmerize_retrieve_font_family_from_data($font);

            if (mesmerize_is_system_font($family)) {
                continue;
            }

            if ( ! $family && is_string($key)) {
                $family = $key;
            }

            if ($family) {
                $result[$family] = $font;
            }
        }
    }

    return $result;
}

add_filter('mesmerize_google_fonts', function ($fonts) {

    global $wp_customize;

    if ($wp_customize || is_customize_preview()) {
        $fonts = mesmerize_get_fonts_in_mods($fonts);
    }

    return $fonts;
});

function mesmerize_retrieve_font_family_from_data($font)
{
    $family = false;

    if (isset($font['family'])) {
        $family = $font['family'];
    } else {
        if (isset($font['font'])) {
            $family = $font['font'];
        } else {
            if (isset($font['font-family'])) {
                $family = $font['font-family'];
            }
        }
    }

    return $family;
}

function mesmerize_theme_mod_web_fonts_filter($fonts)
{
    $fonts  = mesmerize_get_fonts_in_mods((array)$fonts, true);
    $result = array();

    global $wp_customize;


    foreach ($fonts as $font) {

        $family = mesmerize_retrieve_font_family_from_data($font);


        if ( ! $family) {
            return;
        }

        if ( ! mesmerize_font_is_in_list($result, $family)) {
            $result[] = array(
                'family'  => $family,
                "weights" => isset($font['weights']) ? $font['weights'] : array('400'),
            );
        }
    }

    return $result;
}

add_filter("pre_update_option_theme_mods_" . mesmerize_get_text_domain(), function ($values) {
    $fonts = mesmerize_get_general_google_fonts();

    if (isset($values['web_fonts'])) {
        if (is_string($values['web_fonts'])) {
            try {
                $fonts = json_decode($values['web_fonts'], true);
            } catch (Exception $e) {
                $fonts = mesmerize_get_general_google_fonts();
            }
        } else {
            if (is_array($values['web_fonts'])) {
                $fonts = $values['web_fonts'];
            }
        }
    }

    $AllFonts = mesmerize_theme_mod_web_fonts_filter($fonts);

    $values['web_fonts'] = json_encode($AllFonts);

    return $values;
});


add_filter("cloudpress\customizer\preview_data", function ($data) {
    $fonts = get_theme_mod('web_fonts', mesmerize_get_general_google_fonts());

    if (is_string($fonts)) {
        try {
            $fonts = json_decode($fonts, true);
        } catch (Exception $e) {
            $fonts = mesmerize_get_general_google_fonts();
        }
    }


    $fonts = mesmerize_theme_mod_web_fonts_filter($fonts);

    $data['allFonts'] = $fonts;

    return $data;

});

mesmerize_google_fonts_options();

function mesmerize_general_typography_options()
{
    $priority = 2;
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Default Elements Typography', 'mesmerize'),
        'section'  => 'general_site_style',
        'settings' => "general_site_typography_separator",
        'priority' => $priority,
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'sidebar-button-group',
        'settings' => 'general_site_typography_group',
        'label'    => esc_attr__('General Typography', 'mesmerize'),
        'section'  => 'general_site_style',
        'priority' => $priority,
        "choices"  => array(
            'general_site_typography',
            'general_site_typography_size',
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'typography',
        'settings'  => 'general_site_typography',
        'label'     => esc_attr__('Site Typography', 'mesmerize'),
        'section'   => 'general_site_style',
        'priority'  => $priority,
        'default'   => array(
            'font-family' => 'Open Sans',
            'color'       => '#6B7C93',
        ),
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element' => 'body',
            ),
        ),
        'js_vars'   => array(
            array(
                'element' => "body",
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'slider',
        'settings'  => 'general_site_typography_size',
        'label'     => esc_attr__('Font Size', 'mesmerize'),
        'section'   => 'general_site_style',
        'default'   => 16,
        'priority'  => $priority,
        'choices'   => array(
            'min'  => '12',
            'max'  => '26',
            'step' => '1',
        ),
        'output'    => array(
            array(
                'element'       => 'body',
                'property'      => 'font-size',
                'value_pattern' => 'calc( $px * 0.875 )',

                'media_query' => '@media (max-width: 1023px)',
            ),
            array(
                'element'     => 'body',
                'property'    => 'font-size',
                'units'       => 'px',
                'media_query' => '@media (min-width: 1024px)',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'       => 'body',
                'property'      => 'font-size',
                'function'      => 'css',
                'value_pattern' => 'calc( $px * 0.875 )',
                'units'         => 'px',
                'media_query'   => '@media (max-width: 1023px)',
            ),
            array(
                'element'     => "body",
                'function'    => 'css',
                'property'    => 'font-size',
                'suffix'      => 'px!important',
                'media_query' => '@media (min-width: 1024px)',
            ),
        ),
    ));


}


mesmerize_general_typography_options();


function mesmerize_elements_typography_options()
{
    $priority = 3;

    $defaults = array(
        array(
            'font-size'      => "3rem",
            'line-height'    => '4rem',
            'letter-spacing' => 'normal',
            'color'          => '#3C424F',
        ),
        array(
            'font-size'      => "2.5rem",
            'text-transform' => 'none',
            'line-height'    => '3rem',
            'letter-spacing' => 'normal',
            'color'          => '#3C424F',
        ),
        array(
            'font-size'      => "1.5rem",
            'text-transform' => 'none',
            'line-height'    => '2.25rem',
            'letter-spacing' => 'normal',
            'color'          => '#3C424F',
        ),
        array(
            'font-size'      => "1.1rem",
            'text-transform' => 'none',
            'line-height'    => '1.75rem',
            'letter-spacing' => '0.0625rem',
            'color'          => '#3C424F',
        ),
        array(
            'font-size'      => "1rem",
            'text-transform' => 'none',
            'line-height'    => '1.5rem',
            'letter-spacing' => '2px',
            'color'          => '#3C424F',
            'subsets'        => array(),
            'font-weight'    => "800",
        ),
        array(
            'font-size'      => "0.875rem",
            'line-height'    => '1.375rem',
            'letter-spacing' => "0.1875rem",
            'color'          => '#3C424F',
            'subsets'        => array(),
            'font-weight'    => "800",

        ),
    );
    for ($i = 0; $i < 6; $i++) {
        $el = "h" . ($i + 1);

        mesmerize_add_kirki_field(array(
            'type'     => 'sidebar-button-group',
            'settings' => 'general_site_' . $el . '_typography_group',
            'label'    => sprintf(esc_attr__('%1s Typography', 'mesmerize'), strtoupper($el)),
            'section'  => 'general_site_style',
            'priority' => $priority,
            "choices"  => array(
                'general_site_' . $el . '_typography',
            ),
        ));

        $header_default = apply_filters(
            'mesmerize_default_headings_typography',
            array_merge(array(
                'font-weight' => "600",
                'font-family' => 'Muli',
            ), $defaults[$i]), $el
        );

        $mobile_font_size                   = 0.875 * floatval($header_default['font-size']);
        $header_default['mobile-font-size'] = number_format($mobile_font_size, 3) . "rem";

        mesmerize_add_kirki_field(array(
            'type'      => 'typography',
            'settings'  => 'general_site_' . $el . '_typography',
            'label'     => sprintf(esc_attr__('%1s Typography', 'mesmerize'), strtoupper($el)),
            'section'   => 'general_site_style',
            'priority'  => $priority,
            'default'   => $header_default,
            'transport' => 'postMessage',
            'output'    => array(
                array(
                    'element' => 'body ' . $el,
                ),
            ),
            'js_vars'   => array(
                array(
                    'element' => "body " . $el,
                ),
            ),
        ));
    }
}

mesmerize_elements_typography_options();


add_filter('theme_mod_web_fonts', 'mesmerize_clean_system_fonts', 5, 1);


function mesmerize_clean_system_fonts($value)
{
    global $wp_customize;

    if ($wp_customize || is_customize_preview()) {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        foreach ((array)$value as $id => $fontData) {
            if (mesmerize_is_system_font($fontData['family'])) {
                unset($value[$id]);
            }
        }

        $value = json_encode($value);
    }


    return $value;
}
