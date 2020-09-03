<?php


function mesmerize_render_typography_control_slide($selector, $control_value)
{
    $result      = "";
    $mobile_only = "";
    if (isset($control_value['mobile-font-size'])) {
        $mobile_only = "@media (max-width:768px){" .
                       " {$selector} {" .
                       "     font-size:{$control_value['mobile-font-size']};" .
                       " }" .
                       "}";

        unset($control_value['mobile-font-size']);
    }

    $result .= "\n{$selector} {";

    $skip_props = array('addwebfont', 'subsets');

    if (isset($control_value['variant']) && isset($control_value['font-weight'])) {
        unset($control_value['font-weight']);
    }

    foreach ($control_value as $prop => $value) {
        if (in_array($prop, $skip_props)) {
            continue;
        }

        if ($prop === 'variant') {
            // Get the font weight and font style
            $font_weight = str_replace('italic', '', $value);
            $font_weight = (in_array($font_weight, array('', 'regular'))) ? '400' : $font_weight;
            $is_italic   = (false !== strpos($value, 'italic'));
            $font_style  = $is_italic ? "italic" : "normal";
            $result      .= "font-weight:{$font_weight};\n";
            $result      .= "font-style:{$font_style};\n";
        } else {
            $result .= "{$prop}:{$value};\n";
        }
    }

    $result .= "}";

    $result .= "\n" . $mobile_only;

    return $result;
}


function mesmerize_get_slide_style($slide)
{
    $selector                     = '#header-slides-container #header-slide-' . $slide['slide_id'];
    $slide_content_layout         = $slide['slide_content_layout'];
    $slide_content_spacing        = $slide['slide_content_spacing'];
    $slide_mobile_content_spacing = $slide['slide_mobile_content_spacing'];

    $slide_text_box_width                       = $slide['slide_text_box_settings_text_width'];
    $slide_text_box_background_color            = $slide['slide_text_box_settings_background_color'];
    $slide_text_box_background_spacing          = $slide['slide_text_box_settings_background_spacing'];
    $slide_text_box_background_border_radius    = $slide['slide_text_box_settings_background_border_radius'];
    $slide_text_box_background_border_color     = $slide['slide_text_box_settings_background_border_color'];
    $slide_text_box_background_border_thickness = $slide['slide_text_box_settings_background_border_thickness'];

    $slide_media_image_border_color     = $slide['slide_media_box_settings_image_border_color'];
    $slide_media_image_border_thickness = $slide['slide_media_box_settings_image_border_thickness'];

    $slide_hide_poster          = $slide['slide_media_box_settings_hide_video_poster'];
    $slide_has_shadow           = $slide['slide_media_box_settings_enable_media_shadow'];
    $slide_media_lr_image_width = $slide['slide_media_box_settings_media_left_right_width'];
    $slide_media_tb_image_width = $slide['slide_media_box_settings_media_top_bottom_width'];

    $slide_media_box_spacing = $slide['slide_media_box_settings_media_box_spacing'];

    $slide_media_box_video_icon_color           = $slide['slide_media_box_settings_video_icon_color'];
    $slide_media_box_video_icon_hover_color     = $slide['slide_media_box_settings_video_icon_hover_color'];
    $slide_media_box_video_poster_overlay_color = $slide['slide_media_box_settings_video_poster_overlay_color'];

    $slide_show_title                        = $slide['slide_show_title'];
    $slide_title_typography                  = $slide['slide_title_options_title_typography'];
    $slide_title_spacing                     = $slide['slide_title_options_title_spacing'];
    $slide_title_background_color            = $slide['slide_title_options_background_color'];
    $slide_title_background_spacing          = $slide['slide_title_options_background_spacing'];
    $slide_title_background_border_radius    = $slide['slide_title_options_background_border_radius'];
    $slide_title_background_border_color     = $slide['slide_title_options_background_border_color'];
    $slide_title_background_border_thickness = $slide['slide_title_options_background_border_thickness'];

    $slide_show_subtitle                        = $slide['slide_show_subtitle'];
    $slide_subtitle_typography                  = $slide['slide_subtitle_options_subtitle_typography'];
    $slide_subtitle_spacing                     = $slide['slide_subtitle_options_subtitle_spacing'];
    $slide_subtitle_background_color            = $slide['slide_subtitle_options_background_color'];
    $slide_subtitle_background_spacing          = $slide['slide_subtitle_options_background_spacing'];
    $slide_subtitle_background_border_radius    = $slide['slide_subtitle_options_background_border_radius'];
    $slide_subtitle_background_border_color     = $slide['slide_subtitle_options_background_border_color'];
    $slide_subtitle_background_border_thickness = $slide['slide_subtitle_options_background_border_thickness'];

    $slide_show_subtitle2                        = $slide['slide_show_subtitle2'];
    $slide_subtitle2_typography                  = $slide['slide_subtitle2_options_subtitle2_typography'];
    $slide_subtitle2_spacing                     = $slide['slide_subtitle2_options_subtitle2_spacing'];
    $slide_subtitle2_background_color            = $slide['slide_subtitle2_options_background_color'];
    $slide_subtitle2_background_spacing          = $slide['slide_subtitle2_options_background_spacing'];
    $slide_subtitle2_background_border_radius    = $slide['slide_subtitle2_options_background_border_radius'];
    $slide_subtitle2_background_border_color     = $slide['slide_subtitle2_options_background_border_color'];
    $slide_subtitle2_background_border_thickness = $slide['slide_subtitle2_options_background_border_thickness'];

    $slide_show_buttons                        = $slide['slide_show_buttons'];
    $slide_buttons_background_color            = $slide['slide_buttons_options_background_color'];
    $slide_buttons_background_spacing          = $slide['slide_buttons_options_background_spacing'];
    $slide_buttons_background_border_radius    = $slide['slide_buttons_options_background_border_radius'];
    $slide_buttons_background_border_color     = $slide['slide_buttons_options_background_border_color'];
    $slide_buttons_background_border_thickness = $slide['slide_buttons_options_background_border_thickness'];

    $styleArray = array(
        array(
            "media"     => "all",
            "selectors" => array(".header-description-row"),
            "rules"     => array(
                "padding" => "{$slide_content_spacing['top']} 0 {$slide_content_spacing['bottom']} 0",
            ),
        ),

        array(
            "media"     => "max-width: 767px",
            "selectors" => array(".header-description-row"),
            "rules"     => array(
                "padding" => "{$slide_mobile_content_spacing['top']} 0 {$slide_mobile_content_spacing['bottom']} 0",
            ),
        ),

        array(
            "media"     => "min-width: 768px",
            "selectors" => array(".header-content .align-holder"),
            "rules"     => array(
                "width" => "{$slide_text_box_width}% !important",
            ),
        ),

        array(
            "media"     => "all",
            "selectors" => array(".header-content .align-holder"),
            "rules"     => array(
                "background"    => "{$slide_text_box_background_color}",
                "padding"       => "{$slide_text_box_background_spacing['top']} {$slide_text_box_background_spacing['right']} {$slide_text_box_background_spacing['bottom']} {$slide_text_box_background_spacing['left']}",
                "border-style"  => "solid",
                "border-radius" => "{$slide_text_box_background_border_radius}px",
                "border-color"  => $slide_text_box_background_border_color,
                "border-width"  => "{$slide_text_box_background_border_thickness['top']} {$slide_text_box_background_border_thickness['right']} {$slide_text_box_background_border_thickness['bottom']} {$slide_text_box_background_border_thickness['left']}",
            ),
        ),
        array(
            "media"     => "all",
            "selectors" => array(".homepage-header-image"),
            "rules"     => array(
                "border-color" => $slide_media_image_border_color,
                "border-width" => "{$slide_media_image_border_thickness}px",
            ),
        ),

        array(
            "active"    => $slide_has_shadow,
            "media"     => "all",
            "selectors" => array(".header-description-row img.homepage-header-image", ".header-description-row .video-popup-button img", ".header-description-row iframe.header-hero-video"),
            "rules"     => array(
                "-moz-box-shadow"    => " 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23)",
                "-webkit-box-shadow" => " 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23)",
                "box-shadow"         => " 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23)",
            ),
        ),
        array(
            "active"    => $slide_has_shadow,
            "media"     => "all",
            "selectors" => array(".header-description-row .video-popup-button-link i"),
            "rules"     => array(
                "-webkit-filter" => "drop-shadow(10px 12px 8px rgba(0, 0, 0, 0.8))",
                "filter"         => "drop-shadow(10px 12px 8px rgba(0, 0, 0, 0.8))",
            ),
        ),

        array(
            "active"    => $slide_hide_poster,
            "media"     => "all",
            "selectors" => array(".video-popup-button.with-image:before"),
            "rules"     => array(
                "background-color" => "{$slide_media_box_video_poster_overlay_color}",
            ),
        ),
        array(
            "active"    => ($slide_content_layout == 'media-on-left' || $slide_content_layout == 'media-on-right'),
            "media"     => "min-width: 768px",
            "selectors" => array(".header-hero-content"),
            "rules"     => array(
                "-webkit-flex-basis"      => (100 - $slide_media_lr_image_width) . "%",
                "-moz-flex-basis"         => (100 - $slide_media_lr_image_width) . "%",
                "-ms-flex-preferred-size" => (100 - $slide_media_lr_image_width) . "%",
                "flex-basis"              => (100 - $slide_media_lr_image_width) . "%",
                "width"                   => (100 - $slide_media_lr_image_width) . "%",
                "max-width"               => (100 - $slide_media_lr_image_width) . "%",
            ),
        ),

        array(
            "active"    => ($slide_content_layout == 'media-on-left' || $slide_content_layout == 'media-on-right'),
            "media"     => "min-width: 768px",
            "selectors" => array(".header-hero-media"),
            "rules"     => array(
                "-webkit-flex-basis"      => "{$slide_media_lr_image_width}%",
                "-moz-flex-basis"         => "{$slide_media_lr_image_width}%",
                "-ms-flex-preferred-size" => "{$slide_media_lr_image_width}%",
                "flex-basis"              => "{$slide_media_lr_image_width}%",
                "width"                   => "{$slide_media_lr_image_width}%",
                "max-width"               => "{$slide_media_lr_image_width}%",
            ),
        ),

        array(
            "active"    => ($slide_content_layout == 'media-on-top' || $slide_content_layout == 'media-on-bottom'),
            "media"     => "min-width: 768px",
            "selectors" => array(".media-on-bottom .header-media-container", ".media-on-top .header-media-container"),
            "rules"     => array(
                "width" => "{$slide_media_tb_image_width}",
            ),
        ),

        array(
            "active"    => ($slide_content_layout == 'media-on-top' || $slide_content_layout == 'media-on-bottom'),
            "media"     => "min-width: 768px",
            "selectors" => array(".header-description-bottom.media", ".header-description-top.media"),
            "rules"     => array(
                "margin" => "{$slide_media_box_spacing['top']} 0 {$slide_media_box_spacing['bottom']} 0",
            ),
        ),

        array(
            "media"     => "all",
            "selectors" => array("a.video-popup-button-link"),
            "rules"     => array(
                "color" => "{$slide_media_box_video_icon_color}",
            ),
        ),

        array(
            "media"     => "all",
            "selectors" => array("a.video-popup-button-link:hover"),
            "rules"     => array(
                "color" => "{$slide_media_box_video_icon_hover_color}",
            ),
        ),

        // slide title
        array(
            "active"    => $slide_show_title,
            "media"     => "all",
            "selectors" => array("h1.slide-title"),
            "rules"     => $slide_title_typography,
            "renderer"  => "mesmerize_render_typography_control_slide",
        ),

        array(
            "active"    => $slide_show_title,
            "media"     => "all",
            "selectors" => array("h1.slide-title"),
            "rules"     => array(
                "margin-top"    => "{$slide_title_spacing['top']}",
                "margin-bottom" => "{$slide_title_spacing['bottom']}",
                "background"    => "{$slide_title_background_color}",
                "padding"       => "{$slide_title_background_spacing['top']} {$slide_title_background_spacing['right']} {$slide_title_background_spacing['bottom']} {$slide_title_background_spacing['left']}",
                "border-style"  => "solid",
                "border-radius" => "{$slide_title_background_border_radius}px",
                "border-color"  => "{$slide_title_background_border_color}",
                "border-width"  => "{$slide_title_background_border_thickness['top']} {$slide_title_background_border_thickness['right']} {$slide_title_background_border_thickness['bottom']} {$slide_title_background_border_thickness['left']}",
            ),
        ),
        // slide subtitle
        array(
            "active"    => $slide_show_subtitle,
            "media"     => "all",
            "selectors" => array(".slide-subtitle"),
            "rules"     => $slide_subtitle_typography,
            "renderer"  => "mesmerize_render_typography_control_slide",
        ),

        array(
            "active"    => $slide_show_subtitle,
            "media"     => "all",
            "selectors" => array(".slide-subtitle"),
            "rules"     => array(
                "margin-top"    => "{$slide_subtitle_spacing['top']}",
                "margin-bottom" => "{$slide_subtitle_spacing['bottom']}",
                "background"    => "{$slide_subtitle_background_color}",
                "padding"       => "{$slide_subtitle_background_spacing['top']} {$slide_subtitle_background_spacing['right']} {$slide_subtitle_background_spacing['bottom']} {$slide_subtitle_background_spacing['left']}",
                "border-style"  => "solid",
                "border-radius" => "{$slide_subtitle_background_border_radius}px",
                "border-color"  => "{$slide_subtitle_background_border_color}",
                "border-width"  => "{$slide_subtitle_background_border_thickness['top']} {$slide_subtitle_background_border_thickness['right']} {$slide_subtitle_background_border_thickness['bottom']} {$slide_subtitle_background_border_thickness['left']}",
            ),
        ),

        // slide Motto
        array(
            "active"    => $slide_show_subtitle2,
            "media"     => "all",
            "selectors" => array(".slide-subtitle2"),
            "rules"     => $slide_subtitle2_typography,
            "renderer"  => "mesmerize_render_typography_control_slide",
        ),

        array(
            "active"    => $slide_show_subtitle,
            "media"     => "all",
            "selectors" => array(".slide-subtitle2"),
            "rules"     => array(
                "margin-top"    => "{$slide_subtitle2_spacing['top']}",
                "margin-bottom" => "{$slide_subtitle2_spacing['bottom']}",
                "background"    => "{$slide_subtitle2_background_color}",
                "padding"       => "{$slide_subtitle2_background_spacing['top']} {$slide_subtitle2_background_spacing['right']} {$slide_subtitle2_background_spacing['bottom']} {$slide_subtitle2_background_spacing['left']}",
                "border-style"  => "solid",
                "border-radius" => "{$slide_subtitle2_background_border_radius}px",
                "border-color"  => "{$slide_subtitle2_background_border_color}",
                "border-width"  => "{$slide_subtitle2_background_border_thickness['top']} {$slide_subtitle2_background_border_thickness['right']} {$slide_subtitle2_background_border_thickness['bottom']} {$slide_subtitle2_background_border_thickness['left']}",
            ),
        ),

        // buttons wrapepr

        array(
            "active"    => $slide_show_buttons,
            "media"     => "all",
            "selectors" => array(".header-buttons-wrapper"),
            "rules"     => array(
                "background"    => "{$slide_buttons_background_color}",
                "padding"       => "{$slide_buttons_background_spacing['top']} {$slide_buttons_background_spacing['right']} {$slide_buttons_background_spacing['bottom']} {$slide_buttons_background_spacing['left']}",
                "border-style"  => "solid",
                "border-radius" => "{$slide_buttons_background_border_radius}px",
                "border-color"  => "{$slide_buttons_background_border_color}",
                "border-width"  => "{$slide_buttons_background_border_thickness['top']} {$slide_buttons_background_border_thickness['right']} {$slide_buttons_background_border_thickness['bottom']} {$slide_buttons_background_border_thickness['left']}",
            ),
        ),
    );

    $cssoutput = "";

    foreach ($styleArray as $styleItem) {
        if (isset($styleItem['active']) && ! $styleItem['active']) {
            continue;
        }
        $rulesContent = "";

        $composedSelectors = array();

        foreach ($styleItem['selectors'] as $s) {
            $composedSelectors[] = "{$selector} {$s}";
        }

        $sel = implode(",", $composedSelectors);

        if (isset($styleItem['renderer'])) {
            $rulesContent = call_user_func($styleItem['renderer'], $sel, $styleItem['rules']);

        } else {

            $rulesContent = "$sel {";

            foreach ($styleItem['rules'] as $prop => $value) {


                $rulesContent .= "\n{$prop}:{$value};";
            }

            $rulesContent .= "\n}";
        }

        if (isset($styleItem['media']) && $styleItem['media'] !== 'all') {
            $rulesContent = "@media ({$styleItem['media']}) {\n{$rulesContent}\n}";
        }

        $cssoutput .= "\n\n{$rulesContent}";
    }

    return $cssoutput;
}

// print slide content options
add_action('wp_head', function () {

    if (get_theme_mod('header_type', 'simple') !== 'slider') {
        return;
    }

    $slides = mesmerize_get_slider_elements();

    $slides_style = "";
    foreach ($slides as $slide) {

        $slides_style .= "\n/*** {slide - {$slide['slide_id']}} ***/\n" . mesmerize_get_slide_style($slide);

    }

    ?>
    <style data-name="header-slider-content">
        <?php echo $slides_style; ?>

        /*FINISHED*/
    </style>
    <?php

});
