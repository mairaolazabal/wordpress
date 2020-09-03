<div <?php echo mesmerize_footer_container('footer-1') ?>>
    <div <?php echo mesmerize_footer_background('footer-content') ?>>
        <div class="gridContainer">
            <div class="row middle-xs">
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="footer-logo">
                        <h4><?php mesmerize_print_logo(true); ?></h4>
                    </div>
                    <div class="muted"><?php echo mesmerize_get_footer_copyright(); ?></div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 center-xs menu-column">
                    <?php mesmerize_footer_menu(); ?>
                </div>
                <?php mesmerize_print_area_social_icons('footer', 'content', 'end-sm col-sm-6 col-md-3 footer-social-icons', 5); ?>
            </div>
        </div>
    </div>
</div>
