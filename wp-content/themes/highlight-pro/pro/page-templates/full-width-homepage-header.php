<?php
/*
 * Template Name: Full Width Page With Homepage Header
 */
add_filter('mesmerize_full_width_page', '__return_true');
add_filter('mesmerize_is_front_page', '__return_true');

mesmerize_get_header('homepage');
?>

<div class="page-content">
    <div class="<?php mesmerize_page_content_wrapper_class(); ?>">
        <?php
        while (have_posts()) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</div>

<?php get_footer(); ?>
