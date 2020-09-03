<?php


mesmerize_pro_require("/inc/header-options/slider-options/options.php");
mesmerize_pro_require("/inc/header-options/slider-options/slider-print-style.php");


// header content filter

add_filter('mesmerize_hero_content', function ($value) {

    $header_type = get_theme_mod('header_type', 'simple');
    if ($header_type === "slider" || $header_type === "third_party_slider") {
        return $header_type;
    }

    return $value;
});


// header type active callback filter
add_filter('mesmerize_header_active_callback_filter', function ($conditions, $inner) {

    if ( ! $inner) {
        $value = "simple";
        if (empty($conditions)) {
            $conditions = array(
                array(
                    'setting'  => 'header_type',
                    'operator' => '==',
                    'value'    => $value,
                ),
            );
        } else {
            $conditions[] = array(
                'setting'  => 'header_type',
                'operator' => '==',
                'value'    => $value,
            );
        }

    }

    return $conditions;
}, 1, 3);

function mesmerize_get_slider_elements()
{
    $slides = get_theme_mod('slider_elements', mesmerize_slider_default_content());

    return apply_filters('mesmerize_slider_elements', $slides);
}

add_action('mesmerize_print_hero_content_slider', function () {
    mesmerize_pro_require("/inc/header-options/slider-options/template-functions.php");
    ?>
    <div class="header-wrapper header-with-slider-wrapper">
        <div id="header-slides-container" class="owl-carousel">
            <?php
            $slides = mesmerize_get_slider_elements();

            foreach ((array)$slides as $sid => $slide) {
                ?>

                <div id="header-slide-<?php echo($sid); ?>" <?php echo mesmerize_header_slide_background_atts($slide); ?>>
                    <?php do_action('mesmerize_before_header_slide_background', $slide); ?>
                    <?php mesmerize_print_slide_video_container($slide); ?>
                    <?php mesmerize_print_header_slide_content($slide); ?>
                </div>

                <?php
            }
            ?>
        </div>
        <?php do_action('mesmerize_after_header_slider_content'); ?>
        <?php mesmerize_print_header_slider_progress_bar(); ?>
        <?php mesmerize_print_header_slider_separator(); ?>
        <?php mesmerize_print_header_slider_navigation(); ?>
    </div>
    <?php
});

add_action('mesmerize_print_hero_content_third_party_slider', function () {

    $shortcode = get_theme_mod('third_party_slider_shortcode', false);

    if ( ! $shortcode) {
        return;
    }

    $shortcode = trim($shortcode);
    $shortcode = html_entity_decode($shortcode);

    ?>
    <div class="header-wrapper header-with-slider-wrapper">
        <?php echo do_shortcode($shortcode); ?>
    </div>
    <?php
});


// slide background attributes filters
add_filter('mesmerize_header_slide_background_atts', function ($attrs, $bg_type, $slide) {

    $show_overlay = $slide['slide_show_overlay'];
    if ($show_overlay) {
        $attrs['class'] .= " color-overlay ";
    }

    if ($bg_type === 'color') {
        $color = $slide['slide_background_color'];
        if ( ! isset($attrs['style'])) {
            $attrs['style'] = "";
        }
        $attrs['style'] .= "; background:" . esc_attr($color);
    }

    return $attrs;
}, 1, 3);

add_filter('mesmerize_header_slide_background_atts', function ($attrs, $bg_type, $slide) {

    if ($bg_type == 'image') {
        $bgImage         = $slide['slide_background_image'];
        $bgImageMobile   = get_theme_mod('header_front_page_image_mobile', false);
        $bgImageSize     = $slide['slide_background_image_size'];
        $bgImagePosition = $slide['slide_background_image_position'];

        $attrs['style'] .= '; background-image:url("' . esc_url($bgImage) . '")';
        $attrs['style'] .= '; background-size:' . $bgImageSize;
        $attrs['style'] .= '; background-position:' . $bgImagePosition;

        if ($bgImageMobile) {
            $attrs['class'] = isset($attrs['class']) ? $attrs['class'] . " custom-mobile-image " : "custom-mobile-image ";
        }

    }

    return $attrs;
}, 1, 3);

add_filter('mesmerize_header_slide_background_atts', function ($attrs, $bg_type, $slide) {

    if ($bg_type == 'video') {
        $attrs['class'] .= " cp-video-bg";
    }

    return $attrs;
}, 1, 3);

add_filter('mesmerize_header_slide_background_atts', function ($attrs, $bg_type, $slide) {

    if ($bg_type == 'gradient') {
        $bgGradient     = $slide['slide_background_gradient'];
        $attrs['class'] .= " " . esc_attr($bgGradient);
    }

    return $attrs;
}, 1, 3);

add_action('mesmerize_header_slide_background', function ($bg_type, $slide) {

    $slides_settings   = array();
    $slider_mod        = mesmerize_get_slider_elements();
    $slider_has_videos = false;

    foreach ($slider_mod as $sm => $slidem) {

        if ($slidem['slide_background_type'] == 'video') {

            $slider_has_videos = true;
            $internalVideo     = $slidem['slide_background_video'];
            $video_url         = $slidem['slide_background_video_external'];
            $videoPoster       = $slidem['slide_background_video_poster'];

            if ($internalVideo) {
                $video_url = wp_get_attachment_url($internalVideo);
                // apply core filter
                $video_url = apply_filters('get_header_video_url', $video_url);
            }

            $video_type = wp_check_filetype(esc_url_raw($video_url), wp_get_mime_types());
            $header     = get_custom_header();
            $settings   = array(
                'mimeType'  => '',
                'videoUrl'  => esc_url_raw($video_url),
                'posterUrl' => esc_url_raw($videoPoster),
                'width'     => absint($header->width),
                'height'    => absint($header->height),
                'minWidth'  => 768,
                'minHeight' => 300,
                'l10n'      => array(
                    'pause'      => esc_html__('Pause', 'mesmerize'),
                    'play'       => esc_html__('Play', 'mesmerize'),
                    'pauseSpeak' => esc_html__('Video is paused.', 'mesmerize'),
                    'playSpeak'  => esc_html__('Video is playing.', 'mesmerize'),
                ),
                'slide_id'  => $sm,
                'container' => "header-slide-" . $sm,
            );

            if (preg_match('#^https?://(?:www\.)?(?:youtube\.com/watch|youtu\.be/)#', $video_url)) {
                $settings['mimeType'] = 'video/x-youtube';
            } elseif ( ! empty($video_type['type'])) {
                $settings['mimeType'] = $video_type['type'];
            }

            // apply core filter
            $settings          = apply_filters('header_video_settings', $settings);
            $slides_settings[] = $settings;
        }
    }

    if ($slider_has_videos) {
        // enqueue core script for video feature //
        wp_enqueue_script('mesmerize-custom-slider-video', get_template_directory_uri() . '/pro/assets/js/custom-slider-video.js');
        wp_localize_script('mesmerize-custom-slider-video', '_sliderVideoSettings', $slides_settings);
        wp_enqueue_script('mesmerize-video-bg', get_template_directory_uri() . '/pro/assets/js/video-slider.js', array('wp-custom-header'));
    }

}, 1, 2);

// slide background overlay actions
add_action('mesmerize_before_header_slide_background', function ($slide) {

    $type            = $slide['slide_overlay_type'];
    $overlay_enabled = $slide['slide_show_overlay'];
    if ($type == 'gradient' && $overlay_enabled) {
        echo '<div class="background-overlay"></div>';
    }

}, 1, 1);


// header slider content actions

add_action("mesmerize_print_slide_content", function ($slide) {

    $title = $slide['slide_title_options_title_text'];

    $show = $slide['slide_show_title'];

    if (mesmerize_can_show_demo_content()) {
        if ($title == "") {
            $title = esc_html__('You can set this title from the customizer.', 'mesmerize');
        }
    }

    $title = mesmerize_wp_kses_post($title);

    if (isset($slide['slide_title_options_enable_text_animation']) && intval($slide['slide_title_options_enable_text_animation'])) {

        $title = mesmerize_apply_header_text_effects($title, $slide['slide_title_options_text_animation_alternatives']);
        mesmerize_enqueue_morph_animation();
    } else {
        $title = apply_filters("mesmerize_header_title", $title);
    }

    $extraAtts = "";

    if (mesmerize_is_customize_preview()) {
        $extraAtts = 'data-theme="slider_elements|' . $slide['slide_id'] . '|slide_title_options_title_text" data-dynamic-mod="true" ';
    }

    if ($show) {
        printf('<h1 class="slide-title"  ' . $extraAtts . ' >%1$s</h1>', $title);
    }
}, 2, 1);

add_action("mesmerize_print_slide_content", function ($slide) {

    $subtitle = $slide['slide_subtitle_options_subtitle_text'];
    $show     = $slide['slide_show_subtitle'];

    if (mesmerize_can_show_demo_content()) {
        if ($subtitle == "") {
            $subtitle = esc_html__('You can set this subtitle from the customizer.', 'mesmerize');
        }
    }
    if ($show) {
        printf('<p class="slide-subtitle" data-theme="slider_elements|' . $slide['slide_id'] . '|slide_subtitle_options_subtitle_text" data-dynamic-mod="true">%1$s</p>', mesmerize_wp_kses_post($subtitle));
    }

}, 3, 1);

add_action("mesmerize_print_slide_content", function ($slide) {

    $subtitle = $slide['slide_subtitle2_options_subtitle2_text'];
    $show     = $slide['slide_show_subtitle2'];

    if (mesmerize_can_show_demo_content()) {
        if ($subtitle == "") {
            $subtitle = __('You can set this secondary subtitle from the customizer.', 'mesmerize');
        }
    }
    if ($show) {
        printf('<p class="slide-subtitle2" data-theme="slider_elements|' . $slide['slide_id'] . '|slide_subtitle2_options_subtitle2_text" data-dynamic-mod="true">%1$s</p>', mesmerize_wp_kses_post($subtitle));
    }

}, 1, 1);

add_action("mesmerize_print_slide_content", function ($slide) {

    $content = "";
    $enabled = $slide['slide_show_buttons'];

    if ($enabled) {

        $buttons_type = $slide['slide_buttons_options_buttons_type'];

        ob_start();

        if ($buttons_type === 'store') {

            $stores = $slide['slide_buttons_options_store_buttons'];

            $locale  = get_locale();
            $locale  = explode('_', $locale);
            $locale  = $locale[0];
            $locale  = strtolower($locale);
            $imgRoot = mesmerize_pro_dir() . "/assets/store-badges";

            foreach ((array)$stores as $storeData) {

                $store   = $storeData['store'];
                $link    = $storeData['link'];
                $imgPath = "{$imgRoot}/{$store}";

                if ($store === "apple-store") {
                    $img = $imgPath . "/download_on_the_app_store_badge_{$locale}_135x40.svg";

                    if ( ! file_exists($img)) {
                        $img = $imgPath . "/download_on_the_app_store_badge_en_135x40.svg";
                    }

                    $imgPath = $img;
                }

                if ($store === "google-store") {
                    $img = $imgPath . "/{$locale}_badge_web_generic.svg";

                    if ( ! file_exists($img)) {
                        $img = $imgPath . "/en_badge_web_generic.svg";
                    }

                    $imgPath = $img;
                }

                $imgData = file_get_contents($imgPath);

                if ($store === "google-store") {
                    $imgData = str_replace('viewBox="0 0 155 60"', 'viewBox="10 10 135 40"', $imgData);
                }

                $imgData = preg_replace('/width="\d+px"/', '', $imgData);
                $imgData = preg_replace('/height="\d+px"/', '', $imgData);

                $previewData = '';
                if (mesmerize_is_customize_preview()) {
                    $previewData = 'data-focus-control="slide_buttons_options_group_button" data-dynamic-mod="true" data-type="group" data-slide="' . $slide['slide_id'] . '"';
                }

                printf('<a ' . $previewData . ' class="badge-button button %3$s" target="_blank" href="%1$s">%2$s</a>', esc_url($link), $imgData, $store);

            }

        } else {

            $default = array();
            if (mesmerize_can_show_demo_content()) {
                $default = mesmerize_header_buttons_defaults();
            }

            $buttons = $slide['slide_buttons_options_normal_buttons'];
            if (is_string($buttons)) {
                try {
                    $buttons = json_decode(urldecode($buttons), true);
                } catch (Exception $e) {

                }
            }

            foreach ($buttons as $index => $button) {
//                $button = apply_filters('mesmerize_print_buttons_list_button', $button, 'header_content_buttons', $index);
//                if ( ! $button) {
//                    continue;
//                }
                $title  = $button['label'];
                $url    = $button['url'];
                $target = $button['target'];
                $class  = $button['class'];

//                var_dump($button);

                if (empty($title)) {
                    $title = __('Action button', 'mesmerize');
                }

                if (is_customize_preview()) {
                    $identifier = 'slider_elements|' . $slide['slide_id'] . '|slide_buttons_options_normal_buttons|' . $index;

                    $btn_string = '<a class="%4$s"' .
                                  ' target="%3$s" href="%1$s" data-theme="' . esc_attr($identifier . '|label') . '"' .
                                  ' data-theme-href="' . esc_attr($identifier . '|url') . '"' .
                                  ' data-theme-target="' . esc_attr($identifier . '|target') . '"' .
                                  ' data-theme-class="' . esc_attr($identifier . '|class') . '"' .
                                  ' data-dynamic-mod="true">' .
                                  '%2$s' .
                                  '</a>';

                    printf($btn_string, esc_url($url), wp_kses_post($title), esc_attr($target), esc_attr($class));
                } else {
                    printf('<a class="%4$s" target="%3$s" href="%1$s">%2$s</a>', esc_url($url), wp_kses_post($title), esc_attr($target), esc_attr($class));
                }
            }

        }

        $content = ob_get_clean();
        $content = "<div data-dynamic-mod-container class=\"header-buttons-wrapper\">{$content}</div>";

    }

    echo $content;

}, 4, 1);

// header slider media actions

add_action("mesmerize_print_slide_media", function ($mediaType, $slide) {

    if ($mediaType == "image") {
        $roundImage   = $slide['slide_media_box_settings_make_image_round'];
        $extraClasses = "";
        if (intval($roundImage)) {
            $extraClasses .= " round";
        }

        $image          = $slide['slide_media_box_settings_media_image'];
        $customizerLink = "";

        if (mesmerize_is_customize_preview()) {
            $customizerLink = "data-type='group' data-focus-control='slide_media_box_settings_media_image_{$slide['slide_id']}' data-slide='{$slide['slide_id']}'";
        }

        if (is_numeric($image)) {
            $image = wp_get_attachment_image_src(absint($image), 'full', false);
            if ($image) {
                list($src, $width, $height) = $image;
                $image = $src;
            } else {
                $image = "#";
            }
        }

        if ( ! empty($image)) {
            $image = sprintf('<img class="homepage-header-image %2$s" %3$s src="%1$s"/>', esc_url($image), esc_attr($extraClasses), $customizerLink);
            mesmerize_print_slide_media_frame($image, $slide);
        }
    }

}, 1, 2);

add_action("mesmerize_print_slide_media", function ($mediaType, $slide) {
    if ($mediaType == "video") {
        mesmerize_print_slide_video($slide);
    }
}, 1, 2);

add_action("mesmerize_print_slide_media", function ($mediaType, $slide) {
    if ($mediaType == "video_popup") {
        mesmerize_slide_video_popup($slide);
    }
}, 1, 2);

add_action("mesmerize_print_slide_media", function ($mediaType, $slide) {
    if (strpos($mediaType, 'header_contents|') === 0) {
        $mod_part = str_replace("header_contents|", "", $mediaType);
        mesmerize_print_slide_section_content($mod_part, $slide);
    }
}, 2, 2);


if ( ! function_exists('mesmerize_print_slide_content_holder_class')) {
    function mesmerize_print_slide_content_holder_class($slide)
    {
        $align = $slide['slide_text_box_settings_text_align'];
        echo "align-holder " . esc_attr($align);
    }
}

if ( ! function_exists("mesmerize_print_slide_content")) {
    function mesmerize_print_slide_content($slide)
    {
        do_action("mesmerize_print_slide_content", $slide);
    }
}

if ( ! function_exists('mesmerize_print_slide_media')) {
    function mesmerize_print_slide_media($slide)
    {
        $headerContent = mesmerize_get_header_slide_media_and_partial($slide);
        $mediaType     = $headerContent['media'];

        do_action('mesmerize_print_slide_media', $mediaType, $slide);
    }
}

function mesmerize_get_header_slide_media_and_partial($slide)
{

    $partial   = $slide['slide_content_layout'];
    $mediaType = $slide['slide_content_media_type'];

    if ( ! array_key_exists($partial, mesmerize_get_partial_types())) {
        $partial = mesmerize_mod_default('header_content_partial');
    }

    if ( ! array_key_exists($mediaType, mesmerize_get_media_types())) {
        $mediaType = 'image';
    }

    return array(
        'partial' => $partial,
        'media'   => $mediaType,
    );

}

function mesmerize_print_slide_media_frame($media, $slide)
{

    $frame_type = $slide['slide_media_box_settings_frame_type'];
    if ($frame_type === "none") {
        echo $media;

        return;
    }

    $frame_width       = $slide['slide_media_box_settings_frame_width'];
    $frame_height      = $slide['slide_media_box_settings_frame_height'];
    $frame_offset_left = $slide['slide_media_box_settings_frame_offset_left'];
    $frame_offset_top  = $slide['slide_media_box_settings_frame_offset_top'];
    $frame_over_image  = $slide['slide_media_box_settings_frame_show_over_image'];
    $frame_color       = $slide['slide_media_box_settings_frame_color'];
    $frame_thickness   = $slide['slide_media_box_settings_frame_thickness'];
    $frame_shadow      = $slide['slide_media_box_settings_frame_show_shadow'];
    $frame_hide        = $slide['slide_media_box_settings_frame_hide_on_mobile'];

    $z_index = $frame_over_image ? 1 : -1;

    $style = "transform:translate($frame_offset_left%, $frame_offset_top%);";
    $style .= "width:{$frame_width}%;height:{$frame_height}%;";
    $style .= "{$frame_type}-color:{$frame_color};";
    $style .= "z-index:$z_index;";

    if ($frame_type == "border") {
        $style .= "border-width:{$frame_thickness}px;";
    }

    $classes = "overlay-box-offset  offset-" . $frame_type . " ";

    if ($frame_shadow) {
        $classes .= "shadow-medium ";
    }

    if ($frame_hide) {
        $classes .= "hide-xs ";
    }

    $headerContent = mesmerize_get_header_slide_media_and_partial($slide);
    $partial       = $headerContent['partial'];

    $align = "";
    if (in_array($partial, array("media-on-right", "media-on-left"))) {
        $align = "end-sm";
    }
    ?>
    <div class="flexbox center-xs <?php echo $align; ?> middle-xs">
        <div class="overlay-box">
            <div class="<?php echo esc_attr($classes); ?>" style="<?php echo esc_attr($style); ?>"></div>
            <?php echo $media; ?>
        </div>
    </div>
    <?php
}

function mesmerize_print_slide_video($slide)
{

    add_filter('oembed_fetch_url', 'mesmerize_oembed_autoplay_loop_args', 10, 3);
    add_filter('oembed_result', 'mesmerize_add_autoplay_loop_to_oembed', 10, 3);

    $video = $slide['slide_media_box_settings_content_video'];
    $embed = new WP_Embed();

    $autoplay = $slide['slide_media_box_settings_autoplay_video'];

    if (mesmerize_is_customize_preview()) {
        $autoplay = false;
    }

    $content = $embed->shortcode(array(
        'src'      => $video,
        'autoplay' => $autoplay,
        'loop'     => $slide['slide_media_box_settings_loop_video'],
    ));

    $content = preg_replace('/width="\d+"/', "", $content);
    $content = preg_replace('/height="\d+"/', 'class="header-hero-video"', $content);

    $class = "";

    if (strpos($content, '<iframe') !== false) {
        $class = "iframe-holder ";
    }

    remove_filter('oembed_fetch_url', 'mesmerize_oembed_autoplay_loop_args');
    remove_filter('oembed_result', 'mesmerize_add_autoplay_loop_to_oembed');


    echo '<div class="content-video-container ' . $class . '">' . $content . '</div>';
}

function mesmerize_slide_video_popup($slide)
{

    $url   = $slide['slide_media_box_settings_content_video'];
    $style = "";

    $image    = $slide['slide_media_box_settings_video_poster'];
    $disabled = $slide['slide_media_box_settings_hide_video_poster'];

    if (intval($disabled)) {
        $image = false;
    }

    ob_start();
    ?>
    <div class="video-popup-button <?php echo ($image) ? 'with-image' : '' ?>">
        <?php if ($image): ?>
            <img class="poster" src="<?php echo $image ?>"/>
        <?php endif; ?>
        <a class="video-popup-button-link" data-fancybox data-video-lightbox="true" href="<?php echo $url ?>">
            <i class="fa fa-play-circle-o"></i>
        </a>
    </div>
    <?php
    echo ob_get_clean();
}

function mesmerize_print_slide_section_content($mod_part)
{
    $mod     = "header_section_content_{$mod_part}";
    $content = get_theme_mod($mod, false);

    if ($content === false || ! trim($content)) {
        $companion = \Mesmerize\Companion::instance();
        $sections  = $companion->getCustomizerData("data:sections");


        foreach ($sections as $section) {
            if ($section['id'] === $mod_part) {
                $content = $section['content'];
                $content = \Mesmerize\Companion::filterDefault($content);
                break;
            }
        }
    }

    ?>
    <div class="header-section-content" data-theme="<?php echo $mod; ?>">
        <?php echo $content; ?>
    </div>
    <?php
}

add_action('mesmerize_after_header_slide_content', function () {
    echo "<div class='split-header' data-html2canvas-ignore='true'></div>";
}, 2);

// print slider full height option
add_action('wp_head', function () {

    if (get_theme_mod('header_type') !== 'slider') {
        return;
    }

    $full_height_header = get_theme_mod('slider_full_height_background_enabled', false);
    if ($full_height_header) {
        ?>
        <style>
            .owl-item > .header-slide {
                height: 100vh;
            }
        </style>
        <?php
    }

});

// print slide overlay options
add_action('wp_head', function () {

    if (get_theme_mod('header_type') !== 'slider') {
        return;
    }


    $slides = mesmerize_get_slider_elements();

    foreach ((array)$slides as $slide) {

        $show_overlay = isset($slide['slide_show_overlay']) ? $slide['slide_show_overlay'] : false;
        if ($show_overlay) {
            $bg_type    = $slide['slide_overlay_type'];
            $shape_type = $slide['slide_overlay_shape'];
            $selector   = '#header-slides-container #header-slide-' . $slide['slide_id'];

            if ($bg_type === 'color') {

                ?>
                <style data-name="header-slide-<?php echo esc_attr($slide['slide_id']); ?>-overlay">
                    <?php echo esc_attr($selector); ?>.color-overlay:before {
                        background: <?php echo esc_attr($slide['slide_overlay_color']); ?>;
                        opacity: <?php echo esc_attr($slide['slide_overlay_opacity']); ?>;
                    }
                </style>
                <?php

            }
            if ($bg_type === 'gradient') {

                $colors = $slide['slide_overlay_gradient'];
                if ($colors == "") {
                    $colors = mesmerize_mod_default('header_overlay_gradient_colors');
                } else {
                    $colors = json_decode($colors, true);
                }
                $gradient = mesmerize_get_gradient_value($colors['colors'], $colors['angle']);

                ?>
                <style data-name="header-slide-<?php echo esc_attr($slide['slide_id']); ?>-overlay">
                    <?php echo esc_attr($selector); ?>
                    .background-overlay {
                        background: <?php echo esc_attr($gradient); ?>;
                    }
                </style>
                <?php

            }
            if ($shape_type != "none") {

                $value = mesmerize_get_header_shape_overlay_value($shape_type);
                ?>
                <style data-name="header-slide-<?php echo esc_attr($slide['slide_id']); ?>-overlay">
                    <?php echo esc_attr($selector); ?>.color-overlay:after {
                        background: <?php echo esc_attr($value); ?>;
                        filter: invert(<?php echo esc_attr($slide['slide_overlay_shape_light']); ?>%);
                    }
                </style>
                <?php

            }

        } else {
            continue;
        }


    }

});


// print split header option
add_action('wp_head', function () {

    if (get_theme_mod('header_type') !== 'slider') {
        return;
    }

    $defaultColor   = '#000000';
    $enabled        = get_theme_mod('slider_use_split_background', false);
    $headerSelector = '.header-with-slider-wrapper .header-homepage';

    if ( ! intval($enabled)) {
        ?>
        <style>
            <?php echo $headerSelector; ?>
            .split-header {
                display: none;
            }
        </style>
        <?php

        return;
    }

    $color    = get_theme_mod('slider_split_background_color', $defaultColor);
    $angle    = get_theme_mod('slider_split_background_angle', 0);
    $fade     = get_theme_mod('slider_split_background_fade', 0);
    $size     = get_theme_mod('slider_split_background_size', 50);
    $gradient = mesmerize_get_split_header_gradient_value($color, $angle, $size, $fade);

    $angle = get_theme_mod('slider_split_background_angle_mobile', 90);
    $size  = get_theme_mod('slider_split_background_size_mobile', 50);

    $mobileGradient = mesmerize_get_split_header_gradient_value($color, $angle, $size, $fade);

    ?>
    <style>
        <?php echo $headerSelector; ?>
        .split-header {
            width: 100%;
            height: 100%;
            top: 0px;
            left: 0px;
            position: absolute;
            z-index: -1;
            display: inline-block;
            content: "";
        }
    </style>
    <style data-name="header-slide-split-style">
        <?php echo $headerSelector; ?>
        .split-header {
            background: linear-gradient(<?php echo $mobileGradient; ?>);
            background: -webkit-linear-gradient(<?php echo $mobileGradient; ?>);
            background: linear-gradient(<?php echo $mobileGradient; ?>);
        }

        @media screen and (min-width: 767px) {
        <?php echo $headerSelector; ?> .split-header {
            background: linear-gradient(<?php echo $gradient; ?>);
            background: -webkit-linear-gradient(<?php echo $gradient; ?>);
            background: linear-gradient(<?php echo $gradient; ?>);
        }
        }

    </style>

    <?php
});

// print content overlap
add_action('wp_head', function () {

    if (get_theme_mod('header_type') !== 'slider') {
        return;
    }

    $margin   = get_theme_mod('slider_overlap_header_with', '95px');
    $selector = '.mesmerize-front-page-with-slider.overlap-first-section';

    // we always print this style, so we can use no refresh
    ?>
    <style data-name="slider-overlap">
        @media only screen and (min-width: 768px) {
            <?php echo esc_attr($selector); ?> .header-with-slider-wrapper .header-homepage {
                padding-bottom: <?php echo  esc_attr($margin); ?>;
            }

            <?php echo esc_attr($selector); ?> .content {
                position: relative;
                z-index: 10;
            }

            <?php echo esc_attr($selector); ?> .page-content div[data-overlap]:first-of-type > div:not([class*="section-separator"]) {
                margin-top: -<?php echo  esc_attr($margin); ?>;
            }

            <?php echo esc_attr($selector); ?> [data-overlap]:first-of-type {
                padding-top: 0px;
            }

            <?php echo esc_attr($selector); ?> #customDots {
                bottom: <?php echo  esc_attr($margin); ?>;
            }
        }
    </style>
    <?php
});

// print slider navigation buttons option
add_action('wp_head', function () {

    if (get_theme_mod('header_type') !== 'slider') {
        return;
    }

    $enabled = get_theme_mod('slider_enable_navigation', true);

    if ( ! intval($enabled)) {
        return;
    }

    $navigationSelector = '.header-slider-navigation';

    $active_play_color  = get_theme_mod('slider_play_button_icon_color', '#ffffff');
    $active_pause_color = get_theme_mod('slider_pause_button_icon_color', 'rgba(255,255,255,0.8)');

    ?>

    <style>
        <?php echo $navigationSelector; ?>
        .owl-nav .owl-autoplay i {
            color: <?php echo $active_play_color; ?>;
        }

        <?php echo $navigationSelector; ?>
        .owl-nav .owl-autoplay.is-playing i {
            color: <?php echo $active_pause_color; ?>;
        }
    </style>

    <?php
});


add_action('mesmerize_after_header_slider_content', function () {

    $show   = get_theme_mod('slider_show_bottom_arrow', false);
    $bounce = get_theme_mod('slider_bottom_arrow_bounce', true);

    $class = "header-homepage-arrow ";
    $hidden_attr = "";

    if (!$show) {
        $hidden_attr = "data-reiki-hidden='true'";
    }

    if ($bounce) {
        $class .= "move-down-bounce";
    }

    if(mesmerize_is_customize_preview() || (!mesmerize_is_customize_preview() && $show)) {
        $icon = get_theme_mod('slider_bottom_arrow_icon', "fa-angle-down");
        ?>
        <div class="header-homepage-arrow-c" <?php echo $hidden_attr; ?>>
            <span class="<?php echo esc_attr($class); ?>"> <i class="fa arrow <?php echo esc_attr($icon); ?>" aria-hidden="true"></i>
            </span>
        </div>
        <?php
    }

}, 3, 1);


add_action('wp_enqueue_scripts', function () {

    $slider_settings = array();

    if (get_theme_mod('header_type') !== "slider") {
        return;
    }

    $slider_settings['slideRewind']            = get_theme_mod('slider_enable_rewind', true);
    $slider_settings['slideAutoplay']          = ! ! intval(get_theme_mod('slider_enable_autoplay', true));
    $slider_settings['sliderShowPlayPause']    = get_theme_mod('slider_enable_play_pause_button', false);
    $slider_settings['slideDuration']          = intval(get_theme_mod('slider_transitions_duration', 7000));
    $slider_settings['slideProgressBar']       = ! ! intval(get_theme_mod('slider_show_progress_bar', true));
    $slider_settings['slideProgressBarHeight'] = get_theme_mod('slider_progress_bar_height', 5);
    $slider_settings['slideAnimationDuration'] = intval(get_theme_mod('slider_animations_duration', 1000));

    if ( ! mesmerize_ie_detected()) {
        if (get_theme_mod('slider_animation_effect', 'horizontal') == 'custom') {
            $slider_settings['slideAnimateOut'] = get_theme_mod('slider_transitions_out_effect', 'slideOutDown');
            $slider_settings['slideAnimateIn']  = get_theme_mod('slider_transitions_in_effect', 'slideInDown');
        } else {
            $slider_settings['slideAnimateOut'] = mesmerize_get_current_transition_effect(get_theme_mod('slider_animation_effect', 'horizontal'), 'out');
            $slider_settings['slideAnimateIn']  = mesmerize_get_current_transition_effect(get_theme_mod('slider_animation_effect', 'horizontal'), 'in');
        }
    } else {
        $slider_settings['slideAnimateOut'] = 'fadeOut';
        $slider_settings['slideAnimateIn']  = 'fadeIn';
    }

    $slider_settings['slideNavigation']      = ! ! intval(get_theme_mod('slider_enable_navigation', true));
    $slider_settings['slideGroupNavigation'] = ! ! intval(get_theme_mod('slider_group_navigation', false));
//    $slider_settings['slideMouseDrag'] = get_theme_mod('slider_enable_mousedrag', true);
//    $slider_settings['slideTouchDrag'] = get_theme_mod('slider_enable_touchdrag', true);

    $slider_settings['slidePrevNextButtons']             = ! ! intval(get_theme_mod('slider_enable_prev_next_buttons', true));
    $slider_settings['slidePrevNextButtonsPosition']     = get_theme_mod('slider_prev_next_buttons_position', 'center');
    $slider_settings['slidePrevNextButtonsOffsetTop']    = intval(get_theme_mod('slider_prev_next_buttons_top_offset', 0));
    $slider_settings['slidePrevNextButtonsOffsetCenter'] = intval(get_theme_mod('slider_prev_next_buttons_center_offset', 0));
    $slider_settings['slidePrevNextButtonsOffsetBottom'] = get_theme_mod('slider_prev_next_buttons_bottom_offset', 0);
    $slider_settings['slidePrevNextButtonsStyle']        = get_theme_mod('slider_prev_next_buttons_style', 'medium-slider-button');
    $slider_settings['slidePrevNextButtonsSize']         = get_theme_mod('slider_prev_next_buttons_size', 80);
    $slider_settings['slidePrevButtonIcon']              = get_theme_mod('slider_prev_next_button_icon', 'fa-angle') . "-left";
    $slider_settings['slideNextButtonIcon']              = get_theme_mod('slider_prev_next_button_icon', 'fa-angle') . "-right";;

    $slider_settings['slideAutoplayButtonPosition']     = get_theme_mod('slider_play_pause_button_position', 'right bottom');
    $slider_settings['slideAutoplayButtonOffsetTop']    = get_theme_mod('slider_play_pause_button_top_offset', 0);
    $slider_settings['slideAutoplayButtonOffsetBottom'] = get_theme_mod('slider_play_pause_button_bottom_offset', 0);
    $slider_settings['slideAutoplayButtonStyle']        = get_theme_mod('slider_play_pause_button_style', 'square');
    $slider_settings['slideAutoplayButtonSize']         = get_theme_mod('slider_play_pause_button_size', 42);
    $slider_settings['slidePauseButtonIcon']            = get_theme_mod('slider_pause_action_icon', 'fa-pause');
    $slider_settings['slidePlayButtonIcon']             = get_theme_mod('slider_play_action_icon', 'fa-play');

    $slider_settings['slidePagination']         = ! ! intval(get_theme_mod('slider_enable_pagination', true));
    $slider_settings['slidePaginationPosition'] = get_theme_mod('slider_pagination_position', 'bottom');
//    $slider_settings['slidePaginationType'] = get_theme_mod('slider_pagination_type', 'shapes');
    $slider_settings['slidePaginationShapesType'] = get_theme_mod('slider_pagination_shapes_type', 'medium-circles');
//    $slider_settings['slidePaginationThumbnailsSize'] = get_theme_mod('slider_pagination_thumbnails_size', 140);

//    $slider_settings['slideVideoThumbnailPoster'] = apply_filters('mesmerize_assets_url', get_template_directory_uri() , '/') . '/assets/images/video-poster.jpg';

    $slider_settings['slideOverlappable'] = ! ! intval(get_theme_mod('slider_overlap_header', true));
    $slider_settings['slideOverlapWith']  = ( ! ! intval(get_theme_mod('slider_overlap_header', true))) ? intval(get_theme_mod('slider_overlap_header_with', '95px')) : 0;

    $slider_settings['slideBottomArrowOffset'] = get_theme_mod('slider_bottom_arrow_icon_bottom_offset', 5);

    $slider_settings['IEDetected'] = mesmerize_ie_detected();


    wp_enqueue_style('owl-carousel-min-css', get_template_directory_uri() . '/pro/assets/css/owlcarousel/owl.carousel.min.css');
    wp_enqueue_style('owl-carousel-theme-default-min-css', get_template_directory_uri() . '/pro/assets/css/owlcarousel/mesmerize-owl-theme.min.css');
    wp_enqueue_script('owl-carousel-min-js', get_template_directory_uri() . '/pro/assets/js/owl.carousel.min.js', array('jquery'));
    wp_enqueue_script('mesmerize-slider-custom-js', get_template_directory_uri() . '/pro/assets/js/mesmerize-slider.js', array('owl-carousel-min-js'));
    mesmerize_add_script_data('mesmerize-slider-custom-js', '_sliderSettings', $slider_settings);


}, 5);

function mesmerize_get_transition_effect_list($type)
{

    $in = array(
        'pulse'             => esc_html__('pulse', 'mesmerize'),
        'rubberBand'        => esc_html__('rubberBand', 'mesmerize'),
        'shake'             => esc_html__('shake', 'mesmerize'),
        'swing'             => esc_html__('swing', 'mesmerize'),
        'tada'              => esc_html__('tada', 'mesmerize'),
        'wobble'            => esc_html__('wobble', 'mesmerize'),
        'jello'             => esc_html__('jello', 'mesmerize'),
        'bounceIn'          => esc_html__('bounceIn', 'mesmerize'),
        'bounceInDown'      => esc_html__('bounceInDown', 'mesmerize'),
        'bounceInLeft'      => esc_html__('bounceInLeft', 'mesmerize'),
        'bounceInRight'     => esc_html__('bounceInRight', 'mesmerize'),
        'bounceInUp'        => esc_html__('bounceInUp', 'mesmerize'),
        'fadeIn'            => esc_html__('fadeIn', 'mesmerize'),
        'fadeInDown'        => esc_html__('fadeInDown', 'mesmerize'),
        'fadeInDownBig'     => esc_html__('fadeInDownBig', 'mesmerize'),
        'fadeInLeft'        => esc_html__('fadeInLeft', 'mesmerize'),
        'fadeInLeftBig'     => esc_html__('fadeInLeftBig', 'mesmerize'),
        'fadeInRight'       => esc_html__('fadeInRight', 'mesmerize'),
        'fadeInRightBig'    => esc_html__('fadeInRightBig', 'mesmerize'),
        'fadeInUp'          => esc_html__('fadeInUp', 'mesmerize'),
        'fadeInUpBig'       => esc_html__('fadeInUpBig', 'mesmerize'),
        'flip'              => esc_html__('flip', 'mesmerize'),
        'flipInX'           => esc_html__('flipInX', 'mesmerize'),
        'flipInY'           => esc_html__('flipInY', 'mesmerize'),
        'lightSpeedIn'      => esc_html__('lightSpeedIn', 'mesmerize'),
        'rotateIn'          => esc_html__('rotateIn', 'mesmerize'),
        'rotateInDownLeft'  => esc_html__('rotateInDownLeft', 'mesmerize'),
        'rotateInDownRight' => esc_html__('rotateInDownRight', 'mesmerize'),
        'rotateInUpLeft'    => esc_html__('rotateInUpLeft', 'mesmerize'),
        'rotateInUpRight'   => esc_html__('rotateInUpRight', 'mesmerize'),
        'slideInUp'         => esc_html__('slideInUp', 'mesmerize'),
        'slideInDown'       => esc_html__('slideInDown', 'mesmerize'),
        'slideInLeft'       => esc_html__('slideInLeft', 'mesmerize'),
        'slideInRight'      => esc_html__('slideInRight', 'mesmerize'),
        'zoomIn'            => esc_html__('zoomIn', 'mesmerize'),
        'zoomInDown'        => esc_html__('zoomInDown', 'mesmerize'),
        'zoomInLeft'        => esc_html__('zoomInLeft', 'mesmerize'),
        'zoomInRight'       => esc_html__('zoomInRight', 'mesmerize'),
        'zoomInUp'          => esc_html__('zoomInUp', 'mesmerize'),
        'rollIn'            => esc_html__('rollIn', 'mesmerize'),
    );

    $out = array(
        'pulse'              => esc_html__('pulse', 'mesmerize'),
        'rubberBand'         => esc_html__('rubberBand', 'mesmerize'),
        'shake'              => esc_html__('shake', 'mesmerize'),
        'swing'              => esc_html__('swing', 'mesmerize'),
        'tada'               => esc_html__('tada', 'mesmerize'),
        'wobble'             => esc_html__('wobble', 'mesmerize'),
        'jello'              => esc_html__('jello', 'mesmerize'),
        'bounceOut'          => esc_html__('bounceOut', 'mesmerize'),
        'bounceOutDown'      => esc_html__('bounceOutDown', 'mesmerize'),
        'bounceOutLeft'      => esc_html__('bounceOutLeft', 'mesmerize'),
        'bounceOutRight'     => esc_html__('bounceOutRight', 'mesmerize'),
        'bounceOutUp'        => esc_html__('bounceOutUp', 'mesmerize'),
        'fadeOut'            => esc_html__('fadeOut', 'mesmerize'),
        'fadeOutDown'        => esc_html__('fadeOutDown', 'mesmerize'),
        'fadeOutDownBig'     => esc_html__('fadeOutDownBig', 'mesmerize'),
        'fadeOutLeft'        => esc_html__('fadeOutLeft', 'mesmerize'),
        'fadeOutLeftBig'     => esc_html__('fadeOutLeftBig', 'mesmerize'),
        'fadeOutRight'       => esc_html__('fadeOutRight', 'mesmerize'),
        'fadeOutRightBig'    => esc_html__('fadeOutRightBig', 'mesmerize'),
        'fadeOutUp'          => esc_html__('fadeOutUp', 'mesmerize'),
        'fadeOutUpBig'       => esc_html__('fadeOutUpBig', 'mesmerize'),
        'flip'               => esc_html__('flip', 'mesmerize'),
        'flipOutX'           => esc_html__('flipOutX', 'mesmerize'),
        'flipOutY'           => esc_html__('flipOutY', 'mesmerize'),
        'lightSpeedOut'      => esc_html__('lightSpeedOut', 'mesmerize'),
        'rotateOut'          => esc_html__('rotateOut', 'mesmerize'),
        'rotateOutDownLeft'  => esc_html__('rotateOutDownLeft', 'mesmerize'),
        'rotateOutDownRight' => esc_html__('rotateOutDownRight', 'mesmerize'),
        'rotateOutUpLeft'    => esc_html__('rotateOutUpLeft', 'mesmerize'),
        'rotateOutUpRight'   => esc_html__('rotateOutUpRight', 'mesmerize'),
        'slideOutUp'         => esc_html__('slideOutUp', 'mesmerize'),
        'slideOutDown'       => esc_html__('slideOutDown', 'mesmerize'),
        'slideOutLeft'       => esc_html__('slideOutLeft', 'mesmerize'),
        'slideOutRight'      => esc_html__('slideOutRight', 'mesmerize'),
        'zoomOut'            => esc_html__('zoomOut', 'mesmerize'),
        'zoomOutDown'        => esc_html__('zoomOutDown', 'mesmerize'),
        'zoomOutLeft'        => esc_html__('zoomOutLeft', 'mesmerize'),
        'zoomOutRight'       => esc_html__('zoomOutRight', 'mesmerize'),
        'zoomOutUp'          => esc_html__('zoomOutUp', 'mesmerize'),
        'hinge'              => esc_html__('hinge', 'mesmerize'),
        'rollOut'            => esc_html__('rollOut', 'mesmerize'),
    );

    if ($type == 'in') {
        return $in;
    }
    if ($type == 'out') {
        return $out;
    }

}

function mesmerize_get_current_transition_effect($effect, $type)
{

    $transition = '';

    switch ($effect) {
        case 'horizontal':
            if ($type == 'in') {
                $transition = 'slideInRight';
            } else {
                $transition = 'slideOutLeft';
            }
            break;
        case 'vertical-down':
            if ($type == 'in') {
                $transition = 'slideInDown';
            } else {
                $transition = 'slideOutDown';
            }
            break;
        case 'vertical-up':
            if ($type == 'in') {
                $transition = 'slideInUp';
            } else {
                $transition = 'slideOutUp';
            }
            break;
        case 'fade':
            if ($type == 'in') {
                $transition = 'fadeIn';
            } else {
                $transition = 'fadeOut';
            }
            break;

        case 'zoom':
            if ($type == 'in') {
                $transition = 'fadeIn';
            } else {
                $transition = 'zoomOut';
            }
            break;

        case 'flip':
            if ($type == 'in') {
                $transition = 'fadeIn';
            } else {
                $transition = 'flipOutX';
            }
            break;
    }

    return $transition;

}

function mesmerize_ie_detected()
{

    $found = false;

    preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);

    if (count($matches) < 2) {
        preg_match('/Trident\/\d{1,2}.\d{1,2};(.*)rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
    }

    if (count($matches) > 1) {
        $version = ($matches[2] == 11) ? $matches[2] : $matches[1];
        $found   = true;
    }

    return $found;

}
