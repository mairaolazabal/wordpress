/* simple-menu-items */



$dd_parent_selector ul.dropdown-menu.simple-menu-items > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector ul.dropdown-menu.simple-menu-items > li:not(.current-menu-item):not(.current_page_item).hover > a, ul.dropdown-menu.default > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector ul.dropdown-menu.default > li:not(.current-menu-item):not(.current_page_item).hover > a {
    color: $dd_hover_color;
}


$dd_parent_selector .fixto-fixed ul.dropdown-menu.simple-menu-items > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.simple-menu-items > li:not(.current-menu-item):not(.current_page_item).hover > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.default > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.default > li:not(.current-menu-item):not(.current_page_item).hover > a {
    color: $dd_fixed_hover_color;
}


$dd_parent_selector ul.dropdown-menu.simple-menu-items > .current_page_item > a,
$dd_parent_selector ul.dropdown-menu.simple-menu-items > .current-menu-item > a {
    color: $dd_active_color;
}

$dd_parent_selector .fixto-fixed ul.dropdown-menu.simple-menu-items > .current_page_item > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.simple-menu-items > .current-menu-item > a {
    color: $dd_fixed_active_color;
}