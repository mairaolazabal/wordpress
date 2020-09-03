/* active-line-top-bottom */

$dd_parent_selector ul.dropdown-menu.active-line-top-bottom > .current_page_item > a,
$dd_parent_selector ul.dropdown-menu.active-line-top-bottom > .current-menu-item > a {
  border-top-color: $dd_active_highlight_color;
  border-bottom-color: $dd_active_highlight_color;
}

$dd_parent_selector ul.dropdown-menu.active-line-top-bottom > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector ul.dropdown-menu.active-line-top-bottom > li:not(.current-menu-item):not(.current_page_item).hover > a {
  color: $dd_hover_color;
}


$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-top-bottom > .current_page_item > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-top-bottom > .current-menu-item > a {
  border-top-color:  $dd_fixed_active_highlight_color;
  border-bottom-color: $dd_fixed_active_highlight_color;
}

$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-top-bottom > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-top-bottom > li:not(.current-menu-item):not(.current_page_item).hover > a {
  color: $dd_fixed_hover_color;
}


$dd_parent_selector ul.dropdown-menu.active-line-top-bottom > .current_page_item > a,
$dd_parent_selector ul.dropdown-menu.active-line-top-bottom > .current-menu-item > a {
  color: $dd_active_color;
}

$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-top-bottom > .current_page_item > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-top-bottom > .current-menu-item > a {
  color: $dd_fixed_active_color;
}