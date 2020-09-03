<?php
/*
 * Template Name: Full Width Template
 */

add_filter('mesmerize_full_width_page', '__return_true');

mesmerize_get_header();
?>
    <div class="page-content <?php mesmerize_page_content_class(); ?>">
        <div class="<?php mesmerize_page_content_wrapper_class(); ?>">
            <?php
            while (have_posts()) : the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </div>

<?php get_footer(); ?>
