<?php

add_filter('mesmerize_notifications_template_slug', function () {
    return "highlight-pro";
},100);

add_filter('mesmerize_notifications_stylesheet_slug', function () {
    return "highlight-pro";
},100);

function highlight_pro_first_activation_stylesheet($stylesheet)
{
    $stylesheet = "highlight";
    
    return $stylesheet;
}

add_filter('mesmerize_pro_first_activation_stylesheet', 'highlight_pro_first_activation_stylesheet');

function highlight_side_navigation_defaults($preset, $setting)
{
    
    $defaults = array(
        'preset-1' => array(
            'side_navigation_label_bg_color'        => 'initial',
            'side_navigation_item_color'            => mesmerize_get_theme_colors('color1'),
            'side_navigation_label_active_bg_color' => 'initial',
            'side_navigation_item_active_color'     => mesmerize_get_theme_colors('color1'),
            'side_navigation_label_border_color'    => '#ffffff',
            'side_navigation_label_typography'      => array(
                'font-family'      => 'Open Sans',
                'font-size'        => '1em',
                'mobile-font-size' => '',
                'variant'          => '400',
                'line-height'      => '150%',
                'letter-spacing'   => '0px',
                'subsets'          => array(),
                'text-transform'   => 'capitalize',
                'addwebfont'       => true,
            ),
        ),
        'preset-2' => array(
            'side_navigation_label_bg_color'        => mesmerize_get_theme_colors('color1'),
            'side_navigation_item_color'            => '#ffffff',
            'side_navigation_label_active_bg_color' => mesmerize_get_theme_colors('color1'),
            'side_navigation_item_active_color'     => '#ffffff',
            'side_navigation_label_border_color'    => '#ffffff',
            'side_navigation_label_typography'      => array(
                'font-family'      => 'Open Sans',
                'font-size'        => '1em',
                'mobile-font-size' => '',
                'variant'          => '400',
                'line-height'      => '190%',
                'letter-spacing'   => '0px',
                'subsets'          => array(),
                'text-transform'   => 'capitalize',
                'addwebfont'       => true,
            ),
        ),
        'preset-3' => array(
            'side_navigation_label_bg_color'        => mesmerize_get_theme_colors('color1'),
            'side_navigation_item_color'            => '#ffffff',
            'side_navigation_label_active_bg_color' => mesmerize_get_theme_colors('color1'),
            'side_navigation_item_active_color'     => '#ffffff',
            'side_navigation_label_border_color'    => '#ffffff',
            'side_navigation_label_typography'      => array(
                'font-family'      => 'Open Sans',
                'font-size'        => '0.925em',
                'mobile-font-size' => '',
                'variant'          => '300',
                'line-height'      => '150%',
                'letter-spacing'   => '0px',
                'subsets'          => array(),
                'text-transform'   => 'capitalize',
                'addwebfont'       => true,
            ),
        ),
        'preset-4' => array(
            'side_navigation_label_bg_color'        => mesmerize_get_theme_colors('color1'),
            'side_navigation_item_color'            => '#ffffff',
            'side_navigation_label_active_bg_color' => mesmerize_get_theme_colors('color1'),
            'side_navigation_item_active_color'     => '#ffffff',
            'side_navigation_label_border_color'    => mesmerize_get_theme_colors('color1'),
            'side_navigation_label_typography'      => array(
                'font-family'      => 'Open Sans',
                'font-size'        => '0.925em',
                'mobile-font-size' => '',
                'variant'          => '600',
                'line-height'      => '190%',
                'letter-spacing'   => '0px',
                'subsets'          => array(),
                'text-transform'   => 'uppercase',
                'addwebfont'       => true,
            ),
        ),
    );
    
    return $defaults[$preset][$setting];
    
}

highlight_require("pro/customizer/customizer.php");

function highlight_side_navigation_filter($preset)
{
    $args = get_theme_mod('side_navigation_design_preset', $preset);
    
    return $args;
}

add_filter('side_navigation_design_preset', 'highlight_side_navigation_filter');


function highlight_footer_content_copyright_text_default()
{
    return __('&copy; {year} {blogname}. Built using WordPress and <a href="#">Highlight Theme</a>.', 'highlight');
}

add_filter('mesmerize_footer_content_copyright_text_default', 'highlight_footer_content_copyright_text_default');

function highlight_pro_update_check_params($args)
{
    
    $args = array_merge($args,
        array(
            'product_id'   => 'highlight-pro',
            'product_name' => 'Highlight PRO',
            'text_domain'  => 'highlight-pro',
        )
    );
    
    return $args;
}

add_filter('mesmerize_pro_update_check_params', 'highlight_pro_update_check_params');

add_filter( 'extendthemes_renew_purchase_url', function ( $url ) {
    $url = 'https://extendthemes.com/go/highlight-purchase-renew';
    return $url;
} );
