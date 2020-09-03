<?php
    $slide = get_query_var('template_slide_data', array());
?>
<div class="row header-description-row  <?php echo esc_attr(apply_filters( 'mesmerize_hero_vertical_align', '' )); ?>">
    <div class="header-hero-content col-md col-xs-12">
        <div class="row header-hero-content-v-align  <?php echo esc_attr( apply_filters( 'mesmerize_slide_content_vertical_align-'.$slide['slide_id'], $slide['slide_text_box_settings_text_vertical_align'] ) ); ?>">
            <div class="header-content col-xs-12">
                <div class="<?php mesmerize_print_slide_content_holder_class($slide); ?>">
					<?php mesmerize_print_slide_content($slide); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="header-hero-media col-md col-xs-12">
        <div class="row header-hero-media-v-align <?php echo esc_attr( apply_filters( 'mesmerize_slide_media_vertical_align-'.$slide['slide_id'], $slide['slide_media_box_settings_media_vertical_align'] ) ); ?>">
            <div class="col-xs-12">
				<?php mesmerize_print_slide_media($slide); ?>
            </div>
        </div>
    </div>

</div>
