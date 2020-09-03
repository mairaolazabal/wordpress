/* active-round-border-button */


$dd_parent_selector ul.dropdown-menu.active-round-border-button > .current_page_item > a,
$dd_parent_selector ul.dropdown-menu.active-round-border-button > .current-menu-item > a {
  border-color:$dd_active_highlight_color;
  color: $dd_active_highlight_color;
}

$dd_parent_selector ul.dropdown-menu.active-round-border-button > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector ul.dropdown-menu.active-round-border-button > li:not(.current-menu-item):not(.current_page_item).hover > a {
  border-color: $dd_hover_color;
  color: $dd_hover_color;
}


$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-round-border-button > .current_page_item > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-round-border-button > .current-menu-item > a {
  border-color: $dd_fixed_active_highlight_color;
  color: $dd_fixed_active_highlight_color;
}

$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-round-border-button > li:not(.current-menu-item):not(.current_page_item):hover > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-round-border-button > li:not(.current-menu-item):not(.current_page_item).hover > a {
  border-color: $dd_fixed_hover_color;
  color: $dd_fixed_hover_color;
}

$dd_parent_selector ul.dropdown-menu.active-round-border-button > .current_page_item > a,
$dd_parent_selector ul.dropdown-menu.active-round-border-button > .current-menu-item > a {
  background-color: $dd_active_highlight_color;
  color: $dd_active_color;
}

$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-round-border-button > .current_page_item > a,
$dd_parent_selector .fixto-fixed ul.dropdown-menu.active-round-border-button > .current-menu-item > a {
  background-color: $dd_fixed_active_highlight_color;
  color: $dd_fixed_active_color;
}