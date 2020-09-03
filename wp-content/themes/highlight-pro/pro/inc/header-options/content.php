<?php

function mesmerize_get_header_snippets()
{
    if (mesmerize_has_in_memory('header_snippets')) {
        return mesmerize_get_from_memory('header_snippets');
    }

    $content = array();
    if (class_exists('\Mesmerize\Companion')) {
        $companion = \Mesmerize\Companion::instance();

        if ($companion && method_exists($companion,'loadPHPConfig')) {
            $content = $companion->loadPHPConfig(mesmerize_pro_dir("/sections/header-snippets.php"));
            mesmerize_set_in_memory('header_snippets', $content);
        }
    }

    return $content;
}

add_filter("mesmerize_media_type_choices", function ($values) {
    $new = array(
        "video"       => __("Video", "mesmerize"),
        "video_popup" => __("Video Popup Button", "mesmerize"),
    );


    $sections = mesmerize_get_header_snippets();

    foreach ((array)$sections as $section) {
        if ($section['category'] === "header-contents") {
            $choice       = "header_contents|" . $section['id'];
            $new[$choice] = $section['name'];
        }
    }


    return array_merge($values, $new);
});


add_filter("mesmerize_media_type_custom_handled", function ($value, $mediaType) {
    if (strpos($mediaType, 'header_contents|') === 0) {
        $value = true;
    }

    return $value;
}, 10, 2);

add_filter('cloudpress\customizer\control\content_sections\data', 'mesmerize_ignore_header_content_sections', PHP_INT_MAX);


function mesmerize_ignore_header_content_sections($data)
{
    unset($data['header-contents']);

    return $data;
}

mesmerize_pro_require("/inc/header-options/content-options/content-types/video.php");

mesmerize_pro_require("/inc/header-options/content-options/inner-pages.php");
mesmerize_pro_require("/inc/header-options/content-options/subtitle2.php");
mesmerize_pro_require("/inc/header-options/content-options/title.php");
mesmerize_pro_require("/inc/header-options/content-options/subtitle.php");
mesmerize_pro_require("/inc/header-options/content-options/buttons.php");


add_action("mesmerize_print_header_media", function ($mediaType) {
    if ($mediaType == "video") {
        mesmerize_print_header_video();
    }
});

add_action("mesmerize_print_header_media", function ($mediaType) {
    if ($mediaType == "video_popup") {
        mesmerize_header_video_popup();
    }
});


add_action("mesmerize_print_header_media", function ($mediaType) {
    if (strpos($mediaType, 'header_contents|') === 0) {

        $mod_part = str_replace("header_contents|", "", $mediaType);
        mesmerize_print_header_section_content($mod_part);
    }
});

function mesmerize_oembed_autoplay_loop_args($provider, $url, $args)
{

    $provider = remove_query_arg('width', $provider);
    $provider = remove_query_arg('height', $provider);
    $provider = remove_query_arg('maxwidth', $provider);
    $provider = remove_query_arg('maxheight', $provider);

    return $provider;
}


function mesmerize_add_autoplay_loop_to_oembed($html, $url, $args)
{
    $autoplay = (isset($args['autoplay']) && intval($args['autoplay']));
    $loop     = (isset($args['loop']) && intval($args['loop']));

    $result = array();
    preg_match('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $html, $result);

    if (count($result)) {
        $newUrl = $result[0];

        if (intval($autoplay)) {
            $newUrl = add_query_arg('autoplay', 1, $newUrl);

        }

        if (intval($loop)) {
            $newUrl = add_query_arg('loop', 1, $newUrl);

        }

        if (preg_match("#https?://((m|www)\.)?youtube\.com/watch.*#i", $url)) {
            $videoId = explode('/', $newUrl);
            $videoId = array_pop($videoId);
            $videoId = explode('?', $videoId);
            $videoId = $videoId[0];

            $newUrl = add_query_arg('playlist', $videoId, $newUrl);
        }

        $html = str_replace($result, $newUrl, $html);

    }

    return $html;
}


function mesmerize_print_header_video()
{

    add_filter('oembed_fetch_url', 'mesmerize_oembed_autoplay_loop_args', 10, 3);
    add_filter('oembed_result', 'mesmerize_add_autoplay_loop_to_oembed', 10, 3);

    $video = get_theme_mod('header_content_video', 'https://www.youtube.com/watch?v=3iXYciBTQ0c');
    $embed = new WP_Embed();

    $autoplay = get_theme_mod('header_content_video_autoplay', '0');

    if (mesmerize_is_customize_preview()) {
        $autoplay = false;
    }

    $content = $embed->shortcode(array(
        'src'      => $video,
        'autoplay' => $autoplay,
        'loop'     => get_theme_mod('header_content_video_loop', '0'),
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

function mesmerize_header_video_popup()
{

    $url   = get_theme_mod('header_content_video', 'https://www.youtube.com/watch?v=3iXYciBTQ0c');
    $style = "";

    $image    = get_theme_mod('header_video_popup_image', apply_filters('mesmerize_assets_url', get_template_directory_uri() , '/') . "/assets/images/video-poster.jpg");
    $disabled = get_theme_mod('header_video_popup_image_disabled', false);

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


add_filter("mesmerize_header_content_partial", function ($values) {
    $new = array(
        "media-on-top"    => __("Text with media above", "mesmerize"),
        "media-on-bottom" => __("Text with media below", "mesmerize"),
    );

    return array_merge($values, $new);
});

add_filter("mesmerize_header_content_partial_update", function ($values) {
    $new = array(
        array(
            "value"  => "media-on-top",
            "fields" => array(
                'header_text_box_text_align' => 'center',
                'header_spacing'             => apply_filters(
                    'mesmerize_header_content_spacing_partial_update',
                    array(
                        'top'    => '5%',
                        'bottom' => '5%',
                    ),
                    2
                ),
            ),
        ),

        array(
            "value"  => "media-on-bottom",
            "fields" => array(
                'header_text_box_text_align' => 'center',
                'header_spacing'             => apply_filters(
                    'mesmerize_header_content_spacing_partial_update',
                    array(
                        'top'    => '5%',
                        'bottom' => '5%',
                    ),
                    2
                ),
            ),
        ),
    );

    return array_merge($values, $new);
});

add_action('wp_head', 'mesmerize_print_header_content_video_img_shadow');


function mesmerize_print_header_content_video_img_shadow()
{

    $hasShadow = get_theme_mod('header_content_video_img_shadow', false);

    if ($hasShadow) {
        ?>
        <style data-name="header_content_video_img_shadow">
            .header-description-row img.homepage-header-image,
            .header-description-row .video-popup-button img,
            .header-description-row iframe.header-hero-video {
                -moz-box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
                -webkit-box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
            }
        </style>
        <?php
    }

}

function mesmerize_print_header_section_content($mod_part)
{
    $mod     = "header_section_content_{$mod_part}";
    $content = get_theme_mod($mod, false);

    if ($content === false || ! trim($content)) {
        $sections  = mesmerize_get_header_snippets();


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
