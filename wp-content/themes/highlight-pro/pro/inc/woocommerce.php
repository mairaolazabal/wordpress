<?php

//ADD SECTIONS

add_action('mesmerize_customize_register_woocommerce_section', 'mesmerize_pro_customize_register_woocommerce_section', 10, 3);

function mesmerize_pro_customize_register_woocommerce_section($wp_customize, $panel, $priority)
{
    $wp_customize->add_section('mesmerize_woocommerce_colors', array(
        'title'    => __('Colors', 'memserize'),
        'priority' => $priority - 5,
        'panel'    => $panel,
    ));
}

require_once mesmerize_pro_dir("/inc/woocommerce/colors.php");

// ADD CONTROLS

add_action('mesmerize_customizer_prepend_woocommerce_list_options', 'mesmerize_pro_customizer_prepend_woocommerce_list_options', 10, 1);

function mesmerize_pro_customizer_prepend_woocommerce_list_options($section)
{
    mesmerize_add_kirki_field(array(
        'type'     => 'sortable',
        'settings' => 'woocommerce_card_item_get_print_order',
        'label'    => __('Product Fields Order', 'mesmerize'),
        'section'  => $section,
        'priority' => 11,
        'default'  => array('title', 'rating', 'price', 'categories'),
        'choices'  => apply_filters('mesmerize_woocommerce_list_product_options',
            array(
                'title'       => __('Product Name', 'mesmerize'),
                'rating'      => __('Rating Stars', 'mesmerize'),
                'price'       => __('Price', 'mesmerize'),
                'categories'  => __('Product Categories', 'mesmerize'),
                'description' => __('Product Description (excerpt) ', 'mesmerize'),
            )
        ),
    ));
}

// FUNTIONS

function mesmerize_woocommerce_get_onsale_badge_default_color()
{
    $primary_color = get_theme_mod('woocommerce_primary_color', false);
    if ( ! $primary_color) {
        $primary_color = mesmerize_get_theme_colors('color1');
    }

    return Kirki_Color::adjust_brightness($primary_color, 10);
}
