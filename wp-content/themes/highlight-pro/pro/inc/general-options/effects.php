<?php
function mesmerize_page_options()
{
    $section = "page_settings";

    mesmerize_add_kirki_field(array(
        'type'     => 'checkbox',
        'settings' => 'enable_content_reveal_effect',
        'label'    => __('Enable content reveal effect', 'mesmerize'),
        'section'  => $section,
        'default'  => false,
        'transport' => 'postMessage',
    ));
}

//mesmerize_page_options();


add_action('wp_head', 'mesmerize_add_content_effects');

add_filter("mesmerize_theme_pro_settings", function($settings){
    $enable_effects = get_theme_mod("enable_content_reveal_effect", false) && !mesmerize_is_customize_preview();
    $settings['reveal-effect'] = array(
        "enabled" => $enable_effects
    );
    return $settings;
});

function mesmerize_add_content_effects()
{
    $enable_effects = get_theme_mod("enable_content_reveal_effect", false) && !mesmerize_is_customize_preview();
    if ($enable_effects) {
    	?>
    	<style data-name="site-effects">
    		.content .row > * {
    			visibility: hidden;
    		}
    	</style>
    	<?php
    }
}
