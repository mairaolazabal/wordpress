<?php


if ( ! defined('ABSPATH')) {
    die('Silence is golden');
}

add_filter('mesmerize_integration_modules', function ($integrations) {

// not enabled for now
//    $integrationBasePath = dirname(__FILE__);
//
//    $integrations = array_merge($integrations, array(
//        "{$integrationBasePath}/manual-theme-update",
//    ));
    
    return $integrations;
});
