<?php


add_shortcode('mesmerize_display_widgets_area', 'mesmerize_display_widgets_area');


function mesmerize_display_widgets_area($atts)
{
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);

    $content = '';

    if (empty($atts['id'])) {
        $content = mesmerize_placeholder_p(__('This is a placeholder','mesmerize') . '<br/>' . __('Configure this to display a "widgets area"' , 'mesmerize'));
    } else {
        $sidebars_widgets = wp_get_sidebars_widgets();
        if (empty($sidebars_widgets[$atts['id']])) {
            $widgets_areas_mod = get_theme_mod('mesmerize_users_custom_widgets_areas', array());
            $index             = str_replace('mesmerize_users_custom_widgets_areas_', '', $atts['id']);
            $name              = 'Widgets Area';
            if (isset($widgets_areas_mod[$index])) {
                $name = $widgets_areas_mod[$index]['name'];
                $name = "\"{$name}\" Widgets Area";
            }

            $content = mesmerize_placeholder_p($name .
                ' ' .
                __("is empty" , 'mesmerize') .
                '<br/>' .
                __('Configure it from WP Admin' , 'mesmerize')
                );
        }

        ob_start();
        dynamic_sidebar($atts['id']);
        $content .= ob_get_clean();

    }


    $content = '<div data-name="mesmerize-widgets-area">' . $content . '</div>';

    return $content;
}
