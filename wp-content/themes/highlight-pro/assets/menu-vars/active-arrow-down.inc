/* active arrow down button */

$dd_parent_selector ul.dropdown-menu.active-arrow-down > .current_page_item:after,
$dd_parent_selector ul.dropdown-menu.active-arrow-down > .current-menu-item:after {
  border-color: transparent transparent $dd_active_highlight_color transparent;
}

$dd_parent_selector ul.dropdown-menu.active-arrow-down > li:not(.current-menu-item):not(.current_page_item):hover:after,
$dd_parent_selector ul.dropdown-menu.active-arrow-down > li:not(.current-menu-item):not(.current_page_item).hover:after {
  border-color: transparent transparent $dd_hover_color transparent;
  color: $dd_hover_color;
}


$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-arrow-down > .current_page_item:after,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-arrow-down > .current-menu-item:after {
  border-color: transparent transparent $dd_fixed_active_highlight_color transparent;
}

$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-arrow-down > li:not(.current-menu-item):not(.current_page_item):hover:after,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-arrow-down > li:not(.current-menu-item):not(.current_page_item).hover:after {
  border-color: transparent transparent $dd_hover_color transparent;
}

$dd_parent_selector ul.dropdown-menu.active-arrow-down > .current_page_item > a,
$dd_parent_selector ul.dropdown-menu.active-arrow-down > .current-menu-item > a {
  color: $dd_active_color;
}

$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-arrow-down > .current_page_item > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-arrow-down > .current-menu-item > a {
  color: $dd_fixed_active_color;
}