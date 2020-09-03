<div <?php echo mesmerize_footer_container('footer-4') ?>>
    <div <?php echo mesmerize_footer_background('footer-content') ?>>
        <div class="gridContainer">
            <div class="row">
                <div class="col-sm-9 col-xs-12">
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

                <div class="col-sm-3 col-xs-12">
                    <?php
                        mesmerize_print_widget('newsletter_subscriber_widgets');
                        mesmerize_print_area_social_icons('footer', 'content', 'footer-social-icons', 5);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-bar footer-bg-accent">
        <div class="gridContainer">
        <div class="row middle-xs center-xs v-spacing">
            <div>
               <h4><?php mesmerize_print_logo(true); ?></h4>
                <?php echo mesmerize_get_footer_copyright(); ?>
            </div>
        </div>
    </div>
</div>
</div>
