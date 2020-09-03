<?php

mesmerize_pro_require("/inc/general-options/colors.php");
mesmerize_pro_require("/inc/general-options/typography.php");
mesmerize_pro_require("/inc/general-options/effects.php");
mesmerize_pro_require("/inc/general-options/custom-areas.php");


add_filter('cloudpress\companion\ajax_cp_data', function ($data, $companion, $filter) {
    
    
    if ($filter !== "sections") {
        return $data;
    }
    
    /** @var \Mesmerize\Companion $companion */
    $contentSections = $companion->loadPHPConfig(mesmerize_pro_dir("/sections/sections.php"));
    $contentSections = Mesmerize\Companion::filterDefault($contentSections);
    
    if (isset($data['sections']) && is_array($data['sections'])) {
        $data['sections'] = array_merge($data['sections'], $contentSections);
    }
    
    return $data;
}, 20, 3);


add_action('cloudpress\template\load_assets', function ($companion) {
    $ver = $companion->version;
    wp_enqueue_style('companion-pro-page-css', mesmerize_pro_uri('/sections/content.css'), array('companion-page-css'), $ver);
    
});

add_filter('cloudpress\customizer\control\content_sections\multiple', 'mesmerize_pro_content_add_insertion_type');

function mesmerize_pro_content_add_insertion_type($data)
{
    return 'multiple';
}

add_filter("mesmerize_get_footer_copyright", function ($copyright, $previewAtts) {
    $preview_atts = "";
    if (mesmerize_is_customize_preview()) {
        $preview_atts = "data-focus-control='footer_content_copyright_text'";
    }
    
    $defaultText   = __('&copy; {year} {blogname}. Built using WordPress and <a href="#">Mesmerize Theme</a>.', 'mesmerize');
    $defaultText   = apply_filters('mesmerize_footer_content_copyright_text_default', $defaultText);
    $copyrightText = get_theme_mod('footer_content_copyright_text', $defaultText);
    
    $copyrightText = str_replace("{year}", date_i18n(__('Y', 'mesmerize')), $copyrightText);
    $copyrightText = str_replace("{blogname}", esc_html(get_bloginfo('name')), $copyrightText);
    
    $allowed_html = array(
        'a'      => array(
            'href'  => array(),
            'title' => array(),
        ),
        'em'     => array(),
        'strong' => array(),
    );
    
    return '<p ' . $previewAtts . ' class="copyright" data-type="group" ' . $preview_atts . '>' . wp_kses_post($copyrightText) . '</p>';
}, 10, 2);

add_filter('cloudpress\customizer\global_data', function ($data) {
    
    $data['footerData'] = isset($data['footerData']) ? $data['footerData'] : array();
    
    $data['footerData']['year']     = date_i18n(__('Y', 'mesmerize'));
    $data['footerData']['blogname'] = esc_html(get_bloginfo('name'));
    
    return $data;
});


add_filter('mesmerize_override_with_thumbnail_image', function ($value) {
    
    if (mesmerize_is_woocommerce_page()) {
        $value = get_theme_mod('woocommerce_product_header_image', true);
        $value = (intval($value) === 1);
    }
    
    return $value;
});

add_filter('mesmerize_overriden_thumbnail_image', function ($url) {
    $blogPage = get_option('page_for_posts');
    
    $blogPageURL       = $url;
    $enabledOnPostPage = ! ! intval(apply_filters('mesmerize_override_with_thumbnail_image', false));
    
    if ($blogPage) {
        $blogPageURL = get_the_post_thumbnail_url($blogPage, 'mesmerize-full-hd');
    }
    
    if (mesmerize_is_blog()) {
        if (is_single() && $enabledOnPostPage) {
            $url = get_the_post_thumbnail_url(get_the_ID(), 'mesmerize-full-hd');
            
            if (empty($url)) {
                $url = $blogPageURL;
            }
            
        } else {
            $url = $blogPageURL;
        }
    }
    
    return $url;
});
