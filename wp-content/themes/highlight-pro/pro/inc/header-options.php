<?php

add_filter('mesmerize_header_presets', function ($presets) {
    $result       = array();
    $presets_file = mesmerize_pro_dir('/customizer/header-presets.php');
    if (file_exists($presets_file)) {
        $result = require $presets_file;
    }

//    $presets = array_merge($presets, $result);
    $presets = $result;

    return $presets;
});

mesmerize_pro_require("/inc/header-options/navigation.php");
mesmerize_pro_require("/inc/header-options/split-header.php");
mesmerize_pro_require("/inc/header-options/background.php");
mesmerize_pro_require("/inc/header-options/content.php");

// slider
mesmerize_pro_require("inc/header-options/slider.php");
