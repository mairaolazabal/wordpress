<?php
    $slide = get_query_var('template_slide_data', array());
?>
<div class="row header-description-row">
    <div class="header-content header-content-left col-sm-12 col-xs-12">
        <div class="<?php mesmerize_print_slide_content_holder_class($slide); ?>">
            <?php mesmerize_print_slide_content($slide); ?>
        </div>
    </div>
</div>
