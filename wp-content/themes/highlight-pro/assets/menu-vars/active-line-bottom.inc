/* active-line-bottom */

$dd_parent_selector ul.dropdown-menu.active-line-bottom > li > a,
$dd_parent_selector ul.dropdown-menu.active-line-bottom > li > a, ul.dropdown-menu.default > li > a,
$dd_parent_selector ul.dropdown-menu.default > li > a {
    border-bottom: 3px solid transparent;
}

$dd_parent_selector ul.dropdown-menu.active-line-bottom > .current_page_item > a,
$dd_parent_selector ul.dropdown-menu.active-line-bottom > .current-menu-item > a, ul.dropdown-menu.default > .current_page_item > a,
$dd_parent_selector ul.dropdown-menu.default > .current-menu-item > a {
  border-bottom-color: $dd_active_highlight_color;
}

$dd_parent_selector ul.dropdown-menu.active-line-bottom > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector ul.dropdown-menu.active-line-bottom > li:not(.current-menu-item):not(.current_page_item).hover > a, ul.dropdown-menu.default > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector ul.dropdown-menu.default > li:not(.current-menu-item):not(.current_page_item).hover > a {
  color: $dd_hover_color;
}

$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-bottom > .current_page_item > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-bottom > .current-menu-item > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.default > .current_page_item > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.default > .current-menu-item > a {
  border-bottom-color: $dd_fixed_active_highlight_color;
}

$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-bottom > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-bottom > li:not(.current-menu-item):not(.current_page_item).hover > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.default > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.default > li:not(.current-menu-item):not(.current_page_item).hover > a {
  color: $dd_fixed_hover_color;
}


$dd_parent_selector ul.dropdown-menu.active-line-bottom > .current_page_item > a,
$dd_parent_selector ul.dropdown-menu.active-line-bottom > .current-menu-item > a {
  color: $dd_active_color;
}

$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-bottom > .current_page_item > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-line-bottom > .current-menu-item > a {
  color: $dd_fixed_active_color;
}