<div <?php echo mesmerize_footer_container('footer-7') ?>>
    <div <?php echo mesmerize_footer_background('footer-content small') ?>>
        <div class="gridContainer">
            <div class="row">
                <div class="col-sm-5 last-xs first-sm">
                    <h4>
                        <?php
                        mesmerize_print_logo(true);
                        ?>
                    </h4>
                    <p class="footer-description" <?php mesmerize_customizer_focus_control_attr('footer_content_box_text'); ?>  ><?php mesmerize_print_footer_box_description() ?></p>
                    <?php echo mesmerize_get_footer_copyright(); ?>
                </div>
                <div class="col-sm-6 three-widgets-area">
                    <div class="row">
                        <div class="col-sm-4">
                             <?php
                               mesmerize_print_widget('first_box_widgets');
                            ?>
                        </div>
                        <div class="col-sm-4">
                            <?php
                               mesmerize_print_widget('second_box_widgets');
                            ?>
                        </div>
                        <div class="col-sm-4">
                            <?php
                                mesmerize_print_widget('third_box_widgets');
                            ?>
                        </div>
                    </div>
                </div>
                <?php mesmerize_print_area_social_icons('footer', 'content', 'col-sm-1 footer-social-icons flexbox center-xs start-sm bottom-sm', 5); ?>
            </div>
        </div>
    </div>
</div>
