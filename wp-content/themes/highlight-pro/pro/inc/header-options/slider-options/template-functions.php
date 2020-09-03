<?php


function mesmerize_header_slide_background_atts($slide)
{
    
    $attrs = array(
        'class' => "header-homepage header-slide ",
        'style' => "",
    );
    
    $bgType = $slide['slide_background_type'];
    
    do_action('mesmerize_header_slide_background', $bgType, $slide);
    
    $attrs = apply_filters('mesmerize_header_slide_background_atts', $attrs, $bgType, $slide);
    
    $result = "";
    foreach ($attrs as $key => $value) {
        $value  = trim(esc_attr($value));
        $result .= " {$key}='" . esc_attr($value) . "'";
    }
    
    return $result;
}


function mesmerize_print_slide_video_container($slide)
{
    
    $bgType = $slide['slide_background_type'];
    $poster = $slide['slide_background_video_poster'];
    
    if ($bgType === 'video'):
        ?>
        <script>
            // resize the poster image as fast as possible to a 16:9 visible ratio
            var mesmerize_video_background_<?php echo($slide['slide_id']); ?> = {
                getVideoRect: function () {
                    var header = document.querySelector("#header-slides-container #header-slide-<?php echo($slide['slide_id']); ?>");
                    var headerWidth = header.getBoundingClientRect().width,
                        videoWidth = headerWidth,
                        videoHeight = header.getBoundingClientRect().height;

                    videoWidth = Math.max(videoWidth, videoHeight);

                    if (videoWidth < videoHeight * 16 / 9) {
                        videoWidth = 16 / 9 * videoHeight;
                    } else {
                        videoHeight = videoWidth * 9 / 16;
                    }

                    videoWidth *= 1.2;
                    videoHeight *= 1.2;

                    var marginLeft = -0.5 * (videoWidth - headerWidth);

                    return {
                        width: Math.round(videoWidth),
                        height: Math.round(videoHeight),
                        left: Math.round(marginLeft)
                    }
                },

                resizePoster: function () {

                    var posterHolder = document.querySelector('#header-slides-container #header-slide-<?php echo($slide['slide_id']); ?> .wp-custom-header');

                    if (!jQuery(posterHolder).data('mesmerize_video_background')) {
                        jQuery(posterHolder).data('mesmerize_video_background', mesmerize_video_background_<?php echo($slide['slide_id']); ?>);
                    }

                    var size = mesmerize_video_background_<?php echo($slide['slide_id']); ?>.getVideoRect();
                    posterHolder.style.backgroundSize = size.width + 'px auto';
                    posterHolder.style.backgroundPositionX = size.left + 'px';
                    posterHolder.style.minHeight = size.height + 'px';
                }

            };

        </script>
        <?php $containerID = 'wp-custom-header-' . $slide['slide_id']; ?>
        <div id="<?php echo($containerID); ?>" class="wp-custom-header cp-video-bg"></div>
        <style type="text/css">
            .header-wrapper {
                background: transparent;
            }

            body #<?php echo $containerID; ?> {
                background-image: url('<?php echo esc_url($poster); ?>');
                background-color: #000000;
                background-position: center top;
                background-size: cover;
                position: absolute;
                z-index: -3;
                height: 100%;
                width: 100%;
                margin-top: 0;
                top: 0px;
                -webkit-transform: translate3d(0, 0, -2px);
            }

            body #<?php echo $containerID; ?>:before {
                position: absolute;
                width: 100%;
                height: 100%;
                z-index: 1;
            }

            .header-homepage.cp-video-bg {
                background-color: transparent !important;
                overflow: hidden;
            }

            body <?php echo('#'.$containerID); ?> > iframe, <?php echo('#'.$containerID); ?> > video {
                object-fit: cover;
                position: absolute;
                opacity: 0;
                width: 100%;
                transition: opacity 0.4s cubic-bezier(0.44, 0.94, 0.25, 0.34);
            }

            body <?php echo('#'.$containerID); ?> > button.wp-custom-header-video-button {
                display: none;
            }
        </style>
        <script type="text/javascript">
            setTimeout(mesmerize_video_background_<?php echo($slide['slide_id']); ?>.resizePoster, 0);

            jQuery(document).on('mesmerize-slide-focused', function () {
                mesmerize_video_background_<?php echo($slide['slide_id']); ?>.resizePoster();
            });

        </script>
    <?php
    endif;
}

function mesmerize_print_header_slide_content($slide)
{
    
    $partial   = $slide['slide_content_layout'];
    $mediaType = $slide['slide_content_media_type'];
    
    if ( ! array_key_exists($partial, mesmerize_get_partial_types())) {
        $partial = mesmerize_mod_default('header_content_partial');
    }
    
    if ( ! array_key_exists($mediaType, mesmerize_get_media_types())) {
        $mediaType = 'image';
    }
    
    $classes = apply_filters('mesmerize_header_description_classes', $partial);
    
    do_action('mesmerize_before_header_slide_content');
    
    add_filter('mesmerize_slide_content_vertical_align-' . $slide['slide_id'], function ($align) {
        return $align;
    });
    add_filter('mesmerize_slide_media_vertical_align-' . $slide['slide_id'], function ($align) {
        return $align;
    });
    
    set_query_var('template_slide_data', $slide);
    
    ?>

    <div class="header-description gridContainer <?php echo esc_attr($classes); ?>" data-html2canvas-ignore="true">
        <?php mesmerize_get_template_part('template-parts/slider/slider', $partial); ?>
    </div>
    
    <?php
    
    do_action('mesmerize_after_header_slide_content');
}

function mesmerize_print_header_slider_progress_bar()
{
    
    echo '<div class="header-progress-bar slide-progress"></div>';
    
}


function mesmerize_print_header_slider_separator()
{
    
    $show = get_theme_mod('slider_show_separator', false);
    
    if ($show) {
        
        $separator = get_theme_mod('slider_bottom_separator_type', 'mesmerize/1.wave-and-line');
        $reverse   = "";
        
        if (strpos($separator, "mesmerize/") !== false) {
            $reverse = strpos($separator, "-negative") === false ? "" : "header-separator-reverse";
        } else {
            $reverse = strpos($separator, "-negative") === false ? "header-separator-reverse" : "";
        }
        
        echo '<div class="header-separator header-separator-bottom ' . esc_attr($reverse) . '">';
        ob_start();
        
        // local svg as template ( ensure it will work with filters in child theme )
        locate_template('/assets/separators/' . $separator . '.svg', true, true);
        
        $content = ob_get_clean();
        echo $content;
        echo '</div>';
        
    }
}


function mesmerize_print_header_slider_navigation()
{
    
    $show          = get_theme_mod('slider_enable_navigation', true);
    $type          = get_theme_mod('slider_group_navigation', false);
    $position      = get_theme_mod('slider_grouped_navigation_position', 'center top');
    $positionClass = '';
    
    if ($type) {
        switch ($position) {
            case "left top":
                $positionClass = 'nlt';
                break;
            case "left bottom":
                $positionClass = 'nlb';
                break;
            case "center top":
                $positionClass = 'nct';
                break;
            case "center bottom":
                $positionClass = 'ncb';
                break;
            case "right top":
                $positionClass = 'nrt';
                break;
            case "right bottom":
                $positionClass = 'nrb';
                break;
        }
    }
    
    if ($show) {
        
        ?>
        <div class="header-slider-navigation mesmerize-slider <?php echo($type ? 'grouped' : 'separated'); ?> <?php echo($positionClass); ?>">
            <div class="owl-controls">
                <div id="customNav" class="owl-nav"></div>
                <div id="customDots" class="owl-dots"></div>
            </div>
        </div>
        <?php
        
    }
    
}
