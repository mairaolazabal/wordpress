<?php

add_action('after_setup_theme', function () {
    
    highlight_require("pro/inc/side-navigation.php");
    
    add_action('cloudpress\customizer\preview_scripts', function ($customizer) {
        
        $url_js = highlight_get_stylesheet_directory_uri() . "/pro/customizer/assets/js/preview.js";
        wp_enqueue_script('highlight-pro-customizer-js', $url_js, array('customize-base'), false, true);
        
    }, 20, 1);
    
}, 20);

add_filter('highlight_demos_import_query', function ($query) {
    $query = array_merge($query,
        array(
            'theme' => implode(',', array('mesmerize-pro', 'mesmerize-full-screen-pro')),
        )
    );
    
    return $query;
});

add_filter('extendthemes_ocdi_customizer_templates', function ($templates) {
    $templates[] = 'mesmerize-pro';
    
    return $templates;
});


add_filter('cloudpress\companion\ajax_cp_data', function ($data, $companion, $filter) {
    
    
    if ($filter !== "sections") {
        return $data;
    }
    
    /** @var \Mesmerize\Companion $companion */
    $contentSectionsChild = $companion->loadPHPConfig(highlight_get_stylesheet_directory() . "/pro/sections/sections.php");
    $contentSectionsChild = Mesmerize\Companion::filterDefault($contentSectionsChild);
    
    if (isset($data['sections']) && is_array($data['sections'])) {
        $data['sections'] = array_merge($data['sections'], $contentSectionsChild);
    }
    
    foreach ($data['sections'] as $si => $section) {
        $data['sections'][$si]['content'] = highlight_replace_theme_tag($section['content']);
    }
    
    return $data;
}, 21, 3);
