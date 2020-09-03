<?php

function mesmerize_get_slide_per_language_keys()
{
    $result = array(
        //
        // style
        //
        'slide_background_image',
        'slide_background_video',
        'slide_background_video_external',
        'slide_background_video_external',
        'slide_background_video_poster',
        
        //
        // content
        //
        
        //      texts
        'slide_title_options_title_text',
        'slide_title_options_text_animation_alternatives',
        'slide_subtitle_options_subtitle_text',
        'slide_subtitle2_options_subtitle2_text',
        'slide_buttons_options_normal_buttons',
        'slide_buttons_options_store_buttons',
        
        //      media
        'slide_media_box_settings_media_image',
        'slide_media_box_settings_content_video',
        'slide_media_box_settings_video_poster',
    
    );
    
    $result = apply_filters('mesmerize_slider_slide_per_language_options', $result);
    
    return $result;
}


function mesmerize_get_default_language_slider_settings($primary_language_value, $current_language_value)
{
    $translatableKeys   = mesmerize_get_slide_per_language_keys();
    $primary_slides_ids = array_keys($primary_language_value);
    $current_slides_ids = array_keys($current_language_value);
    
    
    // if the current language has more slides add it to the main language
    if (count($current_slides_ids) > count($primary_slides_ids)) {
        foreach ($current_slides_ids as $id) {
            if ( ! isset($primary_language_value[$id])) {
                $primary_language_value[$id] = $current_language_value[$id];
            }
        }
        
        $primary_slides_ids = $current_slides_ids;
    }
    
    
    // if the current language has less slides remove it from the main language
    if (count($current_slides_ids) < count($primary_slides_ids)) {
        foreach ($primary_slides_ids as $id) {
            if ( ! isset($current_language_value[$id])) {
                unset($primary_language_value[$id]);
            }
        }
        
        $primary_slides_ids = $current_slides_ids;
    }
    
    $keys_to_merge = null;
    
    foreach ($primary_slides_ids as $slide_id) {
        if ( ! $keys_to_merge) {
            $slide_keys    = array_keys($primary_language_value[$slide_id]);
            $keys_to_merge = array_diff($slide_keys, $translatableKeys);
        }
        
        foreach ($keys_to_merge as $key) {
            $primary_language_value[$slide_id][$key] = $current_language_value[$slide_id][$key];
        }
    }
    
    return $primary_language_value;
}
