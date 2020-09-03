<?php

add_action('wp_head', 'mesmerize_print_woocommerce_theme_colors_style', PHP_INT_MAX);

mesmerize_add_kirki_field(array(
    'type'     => 'color',
    'settings' => "woocommerce_primary_color",
    'label'    => __('Primary Color', 'memserize'),
    'section'  => 'mesmerize_woocommerce_colors',
    'default'  => mesmerize_get_theme_colors('color1'),
));

mesmerize_add_kirki_field(array(
    'type'     => 'color',
    'settings' => "woocommerce_secondary_color",
    'label'    => __('Secondary Color', 'memserize'),
    'section'  => 'mesmerize_woocommerce_colors',
    'default'  => mesmerize_get_theme_colors('color2'),
));

mesmerize_add_kirki_field(array(
    'type'     => 'color',
    'settings' => "woocommerce_onsale_color",
    'label'    => __('"Sale" Badge Color', 'memserize'),
    'section'  => 'mesmerize_woocommerce_colors',
    'default'  => mesmerize_woocommerce_get_onsale_badge_default_color(),
));

mesmerize_add_kirki_field(array(
    'type'     => 'color',
    'settings' => "woocommerce_rating_stars_color",
    'label'    => __('Rating Stars Color', 'memserize'),
    'section'  => 'mesmerize_woocommerce_colors',
    'default'  => mesmerize_woocommerce_get_onsale_badge_default_color(),
));

function mesmerize_print_woocommerce_theme_colors_style()
{
    ?>
    <style data-name="woocommerce-colors">
        <?php

    // show on front page too for woocommerce sections //
    if (class_exists('WooCommerce')) {

        $vars = mesmerize_woocommerce_get_colors();

        foreach ($vars as $name => $var) {
            $$name = $var;
        }

        include mesmerize_pro_dir("/inc/woocommerce/print-colors.php");

    }
    ?>
    </style>
    <?php
}

function mesmerize_woocommerce_get_colors()
{
    $vars = array();

    $vars['color1']       = get_theme_mod('woocommerce_primary_color', mesmerize_get_theme_colors('color1'));
    $vars['color1_light'] = Kirki_Color::adjust_brightness($vars['color1'], 10);

    $vars['color2']       = get_theme_mod('woocommerce_secondary_color', mesmerize_get_theme_colors('color2'));
    $vars['color2_light'] = Kirki_Color::adjust_brightness($vars['color2'], 10);

    $vars['onsale_color']       = get_theme_mod('woocommerce_onsale_color', mesmerize_woocommerce_get_onsale_badge_default_color());
    $vars['rating_stars_color'] = get_theme_mod('woocommerce_rating_stars_color', mesmerize_woocommerce_get_onsale_badge_default_color());

    return apply_filters('mesmerize_woocommerce_get_colors', $vars);
}
