<?php

add_action('mesmerize_add_sections', function ($wp_customize) {
    $sections = array(
        'navigation_logo'      => __('Site logo', 'mesmerize'),
    );

    foreach ($sections as $id => $title) {
        $wp_customize->add_section($id, array(
            'title'    => $title,
            'panel'    => 'navigation_panel',
            'priority' => 3,
        ));
    }
});

mesmerize_pro_require("/inc/header-options/navigation-options/nav-bar.php");
mesmerize_pro_require("/inc/header-options/navigation-options/offscreen.php");
mesmerize_pro_require("/inc/header-options/navigation-options/top-bar.php");
mesmerize_pro_require("/inc/header-options/navigation-options/logo.php");


function mesmerize_get_nav_selector($inner = false)
{
    $selector = array('.mesmerize-front-page .navigation-bar.coloured-nav:not(.fixto-fixed)');

    if ($inner) {
        $selector = array(".mesmerize-inner-page .navigation-bar.coloured-nav:not(.fixto-fixed)");
    }

    return implode(',', $selector);
}


function mesmerize_get_sticky_nav_selector($inner = false)
{
    $selector = array('.mesmerize-front-page .navigation-bar.fixto-fixed');

    if ($inner) {
        $selector = array(".mesmerize-inner-page .navigation-bar.fixto-fixed");
    }

    return implode(',', $selector);
}

function mesmerize_get_nav_text_logo_selector($inner = false, $prefix)
{

    $transparent_nav = get_theme_mod($prefix . '_nav_transparent', mesmerize_mod_default("{$prefix}_nav_transparent"));

    if ($transparent_nav) {
        $selectorsStart = array('.navigation-bar.homepage:not(.coloured-nav)');
    } else {
        $selectorsStart = array('.navigation-bar.homepage.coloured-nav');
    }

    if ($inner) {
        $selectorsStart = array(".navigation-bar:not(.homepage)", ".navigation-bar:not(.homepage)");
    }

    $logoSelector = array();
    foreach ($selectorsStart as $selector) {
        $logoSelector[] = $selector . " a.text-logo";
        $logoSelector[] = $selector . " #main_menu li.logo > a.text-logo";
        $logoSelector[] = $selector . " #main_menu li.logo > a.text-logo:hover";
    }

    return implode(',', $logoSelector);
}

function mesmerize_get_sticky_nav_text_logo_selector($inner = false)
{
    $selectorsStart = array('.navigation-bar.homepage.fixto-fixed');

    if ($inner) {
        $selectorsStart = array(".navigation-bar.fixto-fixed:not(.homepage)", " .navigation-bar.alternate:not(.homepage)");
    }

    $logoSelector = array();
    foreach ($selectorsStart as $selector) {
        $logoSelector[] = $selector . " a.text-logo";
        $logoSelector[] = $selector . " .dark-logo a.text-logo";
    }

    return implode(',', $logoSelector);
}
