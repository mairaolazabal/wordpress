<?php

mesmerize_pro_require("/inc/multilanguage/translatable-mods.php");


//  language functions
function mesmerize_get_default_language()
{
    global $pagenow;
    $lang = apply_filters("mesmerize_get_default_language", "");
    mesmerize_log2("mesmerize_get_default_language => $lang => $pagenow");
    
    return $lang;
}

function mesmerize_get_post_language($post_id)
{
    global $pagenow;
    $lang = apply_filters("mesmerize_get_post_language", "", $post_id);
    mesmerize_log2("mesmerize_get_post_language => $lang => $pagenow");
    
    return $lang;
}

function mesmerize_get_current_language()
{
    global $pagenow;
    $lang = apply_filters("mesmerize_get_current_language", "");
    
    if ( ! $lang || empty($lang)) {
        $lang = mesmerize_get_default_language();
    }
    
    mesmerize_log2("mesmerize_get_current_language => $lang => $pagenow");
    
    return $lang;
}

// get / set translated mods
function mesmerize_get_translated_mod($lang, $mod, $default)
{
    $translated           = get_option("mesmerize_translated_mods", array());
    $lang_translated_mods = isset($translated[$lang]) ? $translated[$lang] : array();
    
    if (isset($lang_translated_mods[$mod])) {
        return $lang_translated_mods[$mod];
    }
    
    return $default;
}

function mesmerize_set_translated_mod($lang, $mod, $value)
{
    $translated = get_option("mesmerize_translated_mods", array());
    
    if ( ! isset($translated[$lang])) {
        $translated[$lang] = array();
    }
    
    $translated[$lang][$mod] = $value;
    
    update_option("mesmerize_translated_mods", $translated, 'yes');
    
}


// handle mods in customizer

function mesmerize_customize_store_initial_mods()
{
    global $wp_customize;
    
    /** @var $wp_customize WP_Customize_Manager */
    if ($wp_customize) {
        $mods = get_theme_mods();
        mesmerize_set_in_memory("multilanguage_initial_mods", $mods);
    }
}

function mesmerize_customize_get_initial_mod($mod, $default = false)
{
    $mods = mesmerize_get_from_memory("multilanguage_initial_mods");
    if ( ! is_array($mods)) {
        $mods = array();
    }
    
    if (preg_match('/CP_AUTO_SETTING\[(.*)\]/s', $mod, $matches)) {
        $mod = $matches[1];
    }
    
    if (isset($mods[$mod])) {
        return $mods[$mod];
    }
    
    return $default;
}

function mesmerize_preview_get_translated_mods()
{
    if (mesmerize_has_in_memory("mesmerize_preview_translated_mods")) {
        return mesmerize_get_from_memory("mesmerize_preview_translated_mods");
    }
    
    $translated = get_transient("mesmerize_preview_translated_mods");
    mesmerize_set_in_memory("mesmerize_preview_translated_mods", $translated);
    
    return $translated;
}

function mesmerize_preview_get_translated_mod($changeset, $lang, $mod, $default)
{
    $translated           = mesmerize_preview_get_translated_mods();
    $lang_translated_mods = array();
    
    
    if (is_array($translated) && isset($translated[$changeset])) {
        $changeset_data = $translated[$changeset];
        if (isset($changeset_data[$lang])) {
            $lang_translated_mods = $changeset_data[$lang];
        }
    }
    
    if (isset($lang_translated_mods[$mod])) {
        return $lang_translated_mods[$mod];
    }
    
    return $default;
}

function mesmerize_preview_set_translated_mods($changeset, $lang, $values)
{
    $translated = get_transient("mesmerize_preview_translated_mods");
    if ( ! is_array($translated)) {
        $translated = array();
    }
    
    if ( ! isset($translated[$changeset])) {
        $translated[$changeset] = array();
    }
    
    $translated[$changeset][$lang] = $values;
    
    
    set_transient("mesmerize_preview_translated_mods", $translated);
    
}

function mesmerize_preview_clear_changeset_data($value)
{
    
    
    if ( ! isset($value['changeset_status']) || $value['changeset_status'] === "publish") {
        delete_transient("mesmerize_preview_translated_mods");
    }
    
    return $value;
}

function mesmerize_customize_changeset_save_data($response, $filter_context)
{
    
    $namespace_pattern = '/^(?P<stylesheet>.+?)::(?P<setting_id>.+)$/';
    $changed_mods      = array();
    
    $default_language = mesmerize_get_default_language();
    $current_language = mesmerize_get_current_language();
    
    
    if ($default_language !== $current_language) {
        $translatable_mods = mesmerize_get_translatable_mods();
        foreach ($response as $key => $data) {
            preg_match($namespace_pattern, $key, $matches);
            
            if (is_array($matches) && isset($matches['setting_id'])) {
                $setting = $matches['setting_id'];
                
                if (in_array($setting, $translatable_mods)) {
                    $changed_mods[$setting] = $data['value'];
                }
            } else {
                if (preg_match('/CP_AUTO_SETTING\[(.*)\]/s', $key, $matches)) {
                    $setting                = $matches[1];
                    $changed_mods[$setting] = $data['value'];
                }
            }
        }
        
        mesmerize_preview_set_translated_mods($filter_context['uuid'], $current_language, $changed_mods);
    }
    
    return $response;
}

function mesmerize_prepare_translation()
{
    mesmerize_customize_store_initial_mods();
    $translatable_mods = mesmerize_get_translatable_mods();
    
    foreach ($translatable_mods as $translatable_mod) {
        add_filter("theme_mod_{$translatable_mod}", function ($value) use ($translatable_mod) {
            $default_language = mesmerize_get_default_language();
            $current_language = mesmerize_get_current_language();
            
            if ($default_language !== $current_language) {
                global $wp_customize;
                $value = mesmerize_get_translated_mod($current_language, $translatable_mod, $value);
                
                /** @var WP_Customize_Manager $wp_customize */
                if ($wp_customize) {
                    $value = mesmerize_preview_get_translated_mod($wp_customize->changeset_uuid(), $current_language, $translatable_mod, $value);
                }
            }
            
            return $value;
            
        }, 0, 2);
        
        add_filter("pre_set_theme_mod_{$translatable_mod}", function ($value) use ($translatable_mod) {
            $default_language = mesmerize_get_default_language();
            $current_language = mesmerize_get_current_language();
            
            if ($default_language !== $current_language) {
                
                if (preg_match('/CP_AUTO_SETTING\[(.*)\]/s', $translatable_mod, $matches)) {
                    $translatable_mod = $matches[1];
                }
                
                mesmerize_set_translated_mod($current_language, $translatable_mod, $value);
                $value = mesmerize_customize_get_initial_mod($translatable_mod, $value);
            }
            
            return $value;
        }, PHP_INT_MAX);
    }
    
    add_filter('customize_changeset_save_data', 'mesmerize_customize_changeset_save_data', 10, 2);
    add_filter('customize_save_response', 'mesmerize_preview_clear_changeset_data', 10, 1);
    
    add_action('customize_register', function ($wp_customize) {
        $translatable_mods = mesmerize_get_translatable_mods();
        /** @var WP_Customize_Manager $wp_customize */
        foreach ($translatable_mods as $mod) {
            
            if ($wp_customize->get_setting($mod)) {
                continue;
            }
            
            
            $wp_customize->add_setting($mod, array(
                'type'      => 'theme_mod',
                'transport' => 'postMessage',
            ));
        }
    }, PHP_INT_MAX);
}

mesmerize_prepare_translation();
