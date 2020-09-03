<?php
    $slide = get_query_var('template_slide_data', array());
?>
<div class="row header-description-row">

    <div class="header-description-top media col-xs-12  text-center">
        <div class="header-media-container">
            <?php mesmerize_print_slide_media($slide); ?>
        </div>
    </div>
    <div class="header-description-bottom col-xs-12">
        <div class="header-content">
            <div class="<?php mesmerize_print_slide_content_holder_class($slide); ?>">
                <?php mesmerize_print_slide_content($slide); ?>
            </div>
        </div>
    </div>
</div>
