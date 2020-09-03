<?php

add_filter("mesmerize_get_theme_colors", function ($colors, $color) {
    $colors = get_theme_mod('color_palette', $colors);

    return $colors;
}, 1, 2);

function mesmerize_theme_colors_options()
{
    $default = mesmerize_get_default_colors();

    mesmerize_add_kirki_field(array(
        'type'     => 'repeater',
        'settings' => 'color_palette',
        'label'    => esc_html__('Site Colors', 'mesmerize'),
        'section'  => "colors",
        "priority" => 0,

        'row_label' => array(
            'type'  => 'field',
            'field' => 'label',
        ),

        "fields" => array(
            "label" => array(
                'type'    => 'hidden',
                'label'   => esc_attr__('Label', 'mesmerize'),
                'default' => 'color',
            ),

            "name" => array(
                'type'    => 'hidden',
                'label'   => esc_attr__('Name', 'mesmerize'),
                'default' => 'color',
            ),

            "value" => array(
                'type'    => 'color',
                'label'   => esc_attr__('Value', 'mesmerize'),
                'default' => '#000',
            ),
        ),

        "default" => $default,
    ));

}

mesmerize_theme_colors_options();

add_action('wp_head', 'mesmerize_print_theme_colors_style', PHP_INT_MAX);

function mesmerize_print_theme_colors_style()
{

    if (mesmerize_can_show_cached_value('mesmerize_colors_cached_style')) {
        if ($style = mesmerize_get_cached_value('mesmerize_colors_cached_style')) {
            ?>
            <style data-name="site-colors">
                /** cached colors style */
                <?php echo $style; ?>
                /** cached colors style */
            </style>
            <?php
            return;

        }
    }

    $textElements = array('p', 'span');
    $headers      = range(1, 6);
    foreach ($headers as $header) {
        $textElements[] = "h{$header}";
    }
    ?>
    <style data-name="site-colors">
        <?php

        ob_start();
        $colors = mesmerize_get_changed_theme_colors();

        $colors = array_merge($colors,
        array(
                array(
                        "name" => "color-white", "value" => "#ffffff",
                ),array(
                        "name" => "color-black", "value" => "#000000",
                ),
        ));

		 foreach ( $colors as $colorData ) {
				$color       = $colorData['value'];
				$hoverColor  = Kirki_Color::adjust_brightness( $color, 20 );
				$colorClass  = "." . $colorData['name'];
				$colorName   = $colorData['name'];

				if(WP_DEBUG){
				    echo "\n/* STYLE FOR {$colorName} : {$colorClass} : {$color} : {$hoverColor}*/\n";
                }
				switch ($colorData['name']){
				    case 'color1':
				        include mesmerize_pro_dir( "/inc/general-options/print-primary-color-style.php") ;
						break;
					case 'color2':
					    include mesmerize_pro_dir( "/inc/general-options/print-secondary-color-style.php");
						break;
				}

				include mesmerize_pro_dir(  "/inc/general-options/print-color-style.php");

				do_action('mesmerize_print_theme_colors_style',$color,$hoverColor,$colorClass,$colorName);

		   };
		 $content = ob_get_clean();

		 if(!is_admin() && !mesmerize_is_customize_preview() && !WP_DEBUG){
		     $content = str_replace("\n"," ",$content);
		     $content = preg_replace("#\s+#"," ",$content);
		     $content = str_replace("/* */","",$content);
		     mesmerize_cache_value('mesmerize_colors_cached_style',$content);
		 }

		 echo $content;
	   ?>
    </style>
    <?php
}
