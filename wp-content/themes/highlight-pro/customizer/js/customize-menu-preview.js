(function ($) {

    function bindData(inner) {
        var prefix = inner ? "inner_header" : "header";
        var parentSelector = inner ? '.mesmerize-inner-page' : '.mesmerize-front-page';

        var options = [
            {
                "setting": "_nav_menu_color",
                "var": "color"
            }, {
                "setting": "_nav_menu_hover_color",
                "var": "hover_color"
            }, {
                "setting": "_nav_menu_active_color",
                "var": "active_color"
            }, {
                "setting": "_nav_menu_active_highlight_color",
                "var": "active_highlight_color"
            }, {
                "setting": "_nav_fixed_menu_color",
                "var": "fixed_color"
            }, {
                "setting": "_nav_fixed_menu_hover_color",
                "var": "fixed_hover_color"
            }, {
                "setting": "_nav_fixed_menu_active_color",
                "var": "fixed_active_color"
            }, {
                "setting": "_nav_fixed_menu_active_highlight_color",
                "var": "fixed_active_highlight_color"
            }, {
                "setting": "_nav_submenu_background_color",
                "var": "submenu_bg"
            }, {
                "setting": "_nav_submenu_text_color",
                "var": "submenu_color"
            }, {
                "setting": "_nav_submenu_hover_background_color",
                "var": "submenu_hover_bg"
            }, {
                "setting": "_nav_submenu_hover_text_color",
                "var": "submenu_hover_color"
            }
        ];

        options.forEach(function (option) {
            liveUpdate(prefix + option.setting, updateStyle)
        });

        function updateStyle() {
            var $styleHolder = $('[data-name="menu-variant-style"][data-prefix=' + prefix + ']');

            if (!$styleHolder.length) {
                return;
            }

            var navStyle = wp.customize(prefix + '_nav_style').get();
            var style = window.__menu_preview_data.base;
            style += "\n\n" + window.__menu_preview_data.menu_vars[navStyle];
            style += "\n\n" + window.__menu_preview_data.submenu;

            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                var placeholder = "\$dd_" + option.var;
                var currentPrefix = (option.var.indexOf('submenu') !== -1) ? "header" : prefix;
                var value = wp.customize(currentPrefix + option.setting).get();
                style = style.split(placeholder).join(value);
            }

            style = style.split('$dd_parent_selector').join(parentSelector);

            $styleHolder.html(style);

        }

        liveUpdate(prefix + '_nav_style', function (value) {
            var $menu = jQuery(parentSelector + ' .main-menu');
            if ($menu.length) {
                for (var menu_var in window.__menu_preview_data.menu_vars) {
                    $menu.removeClass(menu_var);
                }
                $menu.addClass(value);
                updateStyle();
            }
        });
        liveUpdate(prefix + '_nav_menu_items_align', function (value) {
            var $navbar = jQuery(parentSelector + ' .navigation-bar');
            $navbar.find('.main-menu, .main_menu_col').css("justify-content", value);

        });
    }

    bindData(false);
    bindData(true);

})(jQuery);
