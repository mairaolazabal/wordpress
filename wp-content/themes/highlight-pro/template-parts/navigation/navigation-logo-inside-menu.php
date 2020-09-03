<div class="navigation-bar logo-inside-menu  <?php mesmerize_header_main_class() ?>" <?php mesmerize_navigation_sticky_attrs() ?>>
    <div class="navigation-wrapper <?php mesmerize_navigation_wrapper_class() ?>">
        <div class="row basis-auto">
	        <div class="logo_col col-xs col-sm-fit">
	            <?php mesmerize_print_logo(); ?>
	        </div>
	        <div class="main_menu_col col-xs-fit col-sm">
	            <?php mesmerize_print_primary_menu(new Mesmerize_Logo_Nav_Menu(), 'mesmerize_no_menu_logo_inside_cb'); ?>
	        </div>
	    </div>
    </div>
</div>
