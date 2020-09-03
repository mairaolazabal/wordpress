<?php

add_filter('mesmerize_header_buttons_group', 'mesmerize_header_app_buttons_controls');

function mesmerize_header_app_buttons_controls($controls)
{
    array_unshift($controls, 'header_buttons_type');
    
    $controls[] = 'header_store_badges';
    
    $controls[] = 'header_content_buttons_background_options_separator';
    $controls[] = 'header_content_buttons_background_color';
    $controls[] = 'header_content_buttons_background_spacing';
    $controls[] = 'header_content_buttons_background_border_radius';
    
    return $controls;
}


add_action('mesmerize_front_page_header_buttons_options_before', 'mesmerize_front_page_header_appstore_buttons_options', 10, 3);


function mesmerize_header_normal_buttons_active_store_badge($active_callbacks)
{
    
    $active_callbacks[] = array(
        'setting'  => 'header_buttons_type',
        'operator' => '==',
        'value'    => 'normal',
    );
    
    return $active_callbacks;
}

add_filter('mesmerize_header_normal_buttons_active', 'mesmerize_header_normal_buttons_active_store_badge');

function mesmerize_front_page_header_appstore_buttons_options($section, $prefix, $priority)
{
    mesmerize_add_kirki_field(
        array(
            'title'    => __('Buttons Type', 'mesmerize'),
            'type'     => 'select',
            'settings' => "{$prefix}_buttons_type",
            'section'  => $section,
            'priority' => $priority - 1,
            'default'  => 'normal',
            'choices'  => array(
                'normal' => __('Normal Buttons', 'mesmerize'),
                'store'  => __('App Store buttons', 'mesmerize'),
            ),
        )
    );
    
    mesmerize_add_kirki_field(array(
        'type'            => 'repeater',
        'settings'        => "{$prefix}_store_badges",
        'label'           => esc_html__('Store Badges', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        "default"         => array(
            array(
                'store' => 'google-store',
                'link'  => '#',
            ),
            array(
                'store' => 'apple-store',
                'link'  => '#',
            ),
        ),
        'row_label'       => array(
            'type'  => 'field',
            'field' => 'store',
            'value' => esc_attr__('Store Badge', 'mesmerize'),
        ),
        "fields"          => array(
            "store" => array(
                "type"    => "select",
                'label'   => esc_attr__('Badge Type', 'mesmerize'),
                "choices" => array(
                    "google-store" => "Google Play Badge",
                    "apple-store"  => "App Store Badge",
                ),
                "default" => "google-store",
            ),
            'link'  => array(
                'type'    => 'text',
                'label'   => esc_attr__('Link', 'mesmerize'),
                'default' => '#',
            ),
        ),
        'choices'         => array(
            'limit' => 2,
        ),
        'active_callback' => array(
            array(
                'setting'  => "{$prefix}_buttons_type",
                'operator' => '==',
                'value'    => 'store',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => esc_html__('Background Options', 'mesmerize'),
        'section'  => $section,
        'settings' => "header_content_buttons_background_options_separator",
        'priority' => $priority,
    ));
    
    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'label'     => esc_html__('Background Color', 'mesmerize'),
        'section'   => $section,
        'settings'  => 'header_content_buttons_background_color',
        'default'   => 'rgba(0,0,0,0)',
        'transport' => 'postMessage',
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => true,
        ),
        "output"    => array(
            array(
                'element'  => '.header-buttons-wrapper',
                'property' => 'background',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-buttons-wrapper",
                'function' => 'css',
                'property' => 'background',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'      => 'spacing',
        'settings'  => 'header_content_buttons_background_spacing',
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
                'element'  => '.header-buttons-wrapper',
                'property' => 'padding',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-buttons-wrapper",
                'function' => 'style',
                'property' => 'padding',
            ),
        ),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'      => 'dimension',
        'settings'  => 'header_content_buttons_background_border_radius',
        'label'     => esc_html__('Border Radius', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => '0px',
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'  => '.header-buttons-wrapper',
                'property' => 'border-radius',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-buttons-wrapper",
                'function' => 'style',
                'property' => 'border-radius',
            ),
        ),
    ));
    
}


add_filter('mesmerize_header_buttons_content', 'mesmerize_header_appstore_buttons_content', 10);

function mesmerize_header_appstore_buttons_content($content)
{
    
    $buttons_type = get_theme_mod('header_buttons_type', 'normal');
    
    if ($buttons_type === 'store') {
        ob_start();
        
        mesmerize_print_stores_badges();
        
        $content = ob_get_clean();
    }
    
    return $content;
    
}


function mesmerize_print_stores_badges()
{
    $stores = get_theme_mod('header_store_badges', array(
        array(
            'store' => 'google-store',
            'link'  => '#',
        ),
        array(
            'store' => 'apple-store',
            'link'  => '#',
        ),
    ));
    
    $locale = get_locale();
    $locale = explode('_', $locale);
    $locale = $locale[0];
    $locale = strtolower($locale);
    
    
    $imgRoot = mesmerize_pro_dir() . "/assets/store-badges";
    
    
    foreach ((array)$stores as $storeData) {
        
        $store = $storeData['store'];
        $link  = $storeData['link'];
        
        $imgPath = "{$imgRoot}/{$store}";
        
        if ($store === "apple-store") {
            
            $img = $imgPath . "/download_on_the_app_store_badge_{$locale}_135x40.svg";
            
            if ( ! file_exists($img)) {
                $img = $imgPath . "/download_on_the_app_store_badge_en_135x40.svg";
            }
            
            $imgPath = $img;
        }
        
        if ($store === "google-store") {
            $img = $imgPath . "/{$locale}_badge_web_generic.svg";
            
            if ( ! file_exists($img)) {
                $img = $imgPath . "/en_badge_web_generic.svg";
            }
            
            $imgPath = $img;
        }
        
        
        $imgData = file_get_contents($imgPath);
        
        if ($store === "google-store") {
            $imgData = str_replace('viewBox="0 0 155 60"', 'viewBox="10 10 135 40"', $imgData);
        }
        
        $imgData = preg_replace('/width="\d+px"/', '', $imgData);
        $imgData = preg_replace('/height="\d+px"/', '', $imgData);
        
        $previewData = '';
        if (mesmerize_is_customize_preview()) {
            $previewData = 'data-focus-control="header_store_badges" data-dynamic-mod="true" data-type="group"';
        }
        
        printf('<a ' . $previewData . ' class="badge-button button %3$s" target="_blank" href="%1$s">%2$s</a>', esc_url($link), $imgData, $store);
        
    }
    
}
