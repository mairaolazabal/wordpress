<?php
    $slide = get_query_var('template_slide_data', array());
?>
<div class="row header-description-row <?php echo esc_attr(apply_filters('mesmerize_hero_vertical_align','middle-sm')); ?>">
    <div class="header-content header-content-centered col-md col-xs-12">
        <div class="<?php mesmerize_print_slide_content_holder_class($slide); ?>">
            <?php mesmerize_print_slide_content($slide); ?>
        </div>
    </div>
</div>
