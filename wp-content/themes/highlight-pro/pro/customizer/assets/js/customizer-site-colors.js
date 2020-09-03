(function (root, CP_Customizer, $) {


    var currentOpener = null;

    function onChange(color) {

        if (!color) {
            return;
        }

        var _color = color;
        if (typeof color === "string") {
            _color = new tinycolor(color);
        }
        _color = _color.toRgbString();

        if (currentOpener && currentOpener.length) {
            $(currentOpener).val(_color);
            $(currentOpener).trigger('change', [color]);
            $(currentOpener).parent().css({
                'background-color': _color,
                'color': _color
            });
        }

    }

    function getHolder(options) {

        var holder = $(".cp-color-picker-popup");

        if (!holder.length) {
            holder = $("<div class='cp-color-picker-popup'><input data-cp-spetrum-input='1'/></div>");
            holder.appendTo('body');

            holder.find('input').spectrum({
                flat: true,
                allowEmpty: true,
                instant: true,
                togglePaletteOnly: true,
                togglePaletteMoreText: window.CP_Customizer.translateCompanionString('add theme color'),
                togglePaletteLessText: window.CP_Customizer.translateCompanionString('use existing color'),
                preferredFormat: "rgb",
                showInput: true,
                change: function (color) {
                    onChange(color);
                    var spContainer = holder.find(".sp-container");
                    if (!spContainer.hasClass("sp-palette-only") && typeof holder.attr('data-hide') == 'undefined')
                    {
                        holder.attr('data-hide', true);
                        holder.hide();
                    } else {
                        holder.removeAttr('data-hide' );
                    }
                },
                hide: function (color) {
                    onChange(color);
                },
                move: function (color) {
                    onChange(color);
                    if (holder.find(".sp-container").hasClass("sp-palette-only"))
                    {
                        holder.hide();
                    }
                },
                showPaletteOnly: true,
                hideAfterPaletteSelect: true,
                palette: CP_Customizer.getPaletteColors(false, false, {
                    'color-white': '#ffffff',
                    'color-black': '#000000'
                })
            });

            holder.find('input').on('show.spectrum', function (e, tinycolor) {
                if (!colorpicker.spectrum("container").find("#goToThemeColors").length) {
                    colorpicker.spectrum("container").find(".sp-palette-button-container").append('&nbsp;&nbsp;' +
                        '<button type="button" id="goToThemeColors">' + window.CP_Customizer.translateCompanionString("edit theme colors") + '</button>');
                }

                holder.find('input').spectrum("container").find("#goToThemeColors").off("click").on("click", function () {
                    CP_Customizer.goToThemeColors(holder.find('input'));
                });

                holder.show();
            });
        }


        return holder;
    }

    function getSpectrum() {
        return getHolder().find('input[data-cp-spetrum-input]');
    }

    function hideSpectrumPopUp(event) {

        if ($(event.target).is('[data-cp-spectrum-popup-trigger]')) {
            return;
        }

        currentOpener = null;

        getHolder().hide();

        $(root.document).off("keydown.cp-color-picker-popup", hideSpectrumPopUp);
        $(root.document).off("click.cp-color-picker-popup", hideSpectrumPopUp);
        $('body').off("focus.cp-color-picker-popup", hideSpectrumPopUp);
        $(root).off("resize.cp-color-picker-popup", hideSpectrumPopUp);
    }

    function initSpectrumPopup($item, options) {
        $item.data('spectrumPopupOptions', options);
        $item.wrap('<div class="cp-spectrum-popup-trigger-popup-wrapper"  data-cp-spectrum-popup-trigger="1"/>');
    }


    $.fn.spectrumPopUp = function (opt, value) {
        if (typeof opt === "string") {
            switch (opt) {
                case "set":
                    this.each(function () {
                        $(this).val(value);

                        $(this).parent().css({
                            'background-color': value,
                            'color': value
                        });
                    });

                    getSpectrum().spectrum('set', value);

                    break;
            }
        } else {
            this.each(function () {
                initSpectrumPopup($(this), opt);
            });
        }
    }


    $('body').on('click.spectrum-popup', '[data-cp-spectrum-popup-trigger]', function () {

        currentOpener = $(this).find('input');
        var trigger = this,
            options = currentOpener.data('spectrumPopupOptions');

        getSpectrum().spectrum('option', 'palette', CP_Customizer.getPaletteColors(false, options.includeTransparent, {
            'color-white': '#ffffff',
            'color-black': '#000000'
        }));
        getSpectrum().spectrum('set', currentOpener.val() || null);

        getHolder().css({
            display: 'inline-block',
            top: trigger.getBoundingClientRect().top,
            left: trigger.getBoundingClientRect().left + trigger.getBoundingClientRect().width + 4
        });

        $(root.document).on("keydown.cp-color-picker-popup", hideSpectrumPopUp);
        $(root.document).on("click.cp-color-picker-popup", hideSpectrumPopUp);
        $('body').on("focus.cp-color-picker-popup", hideSpectrumPopUp);
        $(root).on("resize.cp-color-picker-popup", hideSpectrumPopUp);
    })

})(window, CP_Customizer, jQuery);


(function (root, CP_Customizer, $) {

    kirki.kirkiGetColorPalette = function () {
        return CP_Customizer.getPaletteColors(false, false, {
            'color-white': '#ffffff'
        })
    }
    CP_Customizer.jsTPL['colorselect'] = _.template('' +
        '<li class="customize-control customize-control-text">' +
        '    <label>' +
        '        <span class="customize-control-title"><%= label %></span>' +
        '        <input id="<%= id %>" value="<%= value %>" class="customize-control-title">' +
        '        <script>' +
        '                var sp = jQuery("#<%= id %>"); ' +
        '                CP_Customizer.initSpectrumButton(sp);  ' +
        '                sp.spectrum("set", "<%= value %>");  ' +
        '                CP_Customizer.addSpectrumButton(sp); ' +
        '        </script>' +
        '    </label>' +
        '</li>' +
        '');

    CP_Customizer.jsTPL['colorselect-transparent'] = _.template('' +
        '<li class="customize-control customize-control-text">' +
        '    <label>' +
        '        <span class="customize-control-title"><%= label %></span>' +
        '        <input id="<%= id %>" value="<%= value %>" class="customize-control-title">' +
        '        <script>' +
        '                var sp = jQuery("#<%= id %>"); ' +
        '                CP_Customizer.initSpectrumButton(sp);  ' +
        '                sp.spectrum("set", "<%= value %>");  ' +
        '                CP_Customizer.addSpectrumButton(sp); ' +
        '        </script>' +
        '    </label>' +
        '</li>' +
        '');

    CP_Customizer.initSpectrumButton = function (colorpicker, includeTransparent) {
        colorpicker.spectrum({
            allowEmpty: true,
            instant: true,
            togglePaletteOnly: true,
            togglePaletteMoreText: window.CP_Customizer.translateCompanionString('add theme color'),
            togglePaletteLessText: window.CP_Customizer.translateCompanionString('use existing color'),
            preferredFormat: includeTransparent ? "rgb" : "hex",
            showInput: true,
            showPaletteOnly: true,
            hideAfterPaletteSelect: true,
            palette: CP_Customizer.getPaletteColors(false, includeTransparent, {
                'color-white': '#ffffff',
                'color-black': '#000000'
            })
        });
    };

    CP_Customizer.initSpectrumPopup = function (colorpicker, options) {
        var opts = _.extend({
            showPaletteOnly: true,
            includeTransparent: false
        }, options || {});
        colorpicker.spectrumPopUp(opts);
    };

    CP_Customizer.initSpectrumButtonAdvanced = function (colorpicker, includeTransparent, showAlpha) {
        colorpicker.spectrum({
            instant: true,
            togglePaletteMoreText: window.CP_Customizer.translateCompanionString('add theme color'),
            togglePaletteLessText: window.CP_Customizer.translateCompanionString('use existing color'),
            allowEmpty: true,
            preferredFormat: includeTransparent ? "rgb" : "hex",
            showInput: true,
            showPalette: true,
            hideAfterPaletteSelect: true,
            showAlpha: !!showAlpha,
            palette: CP_Customizer.getPaletteColors(false, includeTransparent, {
                'color-white': '#ffffff',
                'color-black': '#000000'
            })
        });
    };

    CP_Customizer.addSpectrumButton = function (colorpicker) {

        colorpicker.on('show.spectrum', function (e, tinycolor) {
            if (!colorpicker.spectrum("container").find("#goToThemeColors").length) {
                colorpicker.spectrum("container").find(".sp-palette-button-container").append('&nbsp;&nbsp;' +
                    '<button type="button" id="goToThemeColors">' + window.CP_Customizer.translateCompanionString("edit theme colors") + '</button>');
            }

            colorpicker.spectrum("container").find("#goToThemeColors").off("click").on("click", function () {
                CP_Customizer.goToThemeColors(colorpicker);
            })
        });
    };

    CP_Customizer.addSpectrumTransparentButton = function (colorpicker) {

        colorpicker.on('show.spectrum', function (e, tinycolor) {
            if (!colorpicker.spectrum("container").find("#useTransparentColor").length) {
                colorpicker.spectrum("container").find(".sp-palette-button-container").append('&nbsp;&nbsp;' +
                    '<button type="button" id="useTransparentColor">' + window.CP_Customizer.translateCompanionString("Use Transparent Color") + '</button>');
            }

            colorpicker.spectrum("container").find("#useTransparentColor").off("click").on("click", function () {
                colorpicker.spectrum("set", "rgba(0,0,0,0)");
            })
        });
    };

    CP_Customizer.goToThemeColors = function ($sp) {
        wp.customize.control('color_palette').focus();
        $sp.spectrum("hide");
        tb_remove();
    };

    CP_Customizer.getThemeColor = function (value, clbk) {
        var name = CP_Customizer.getColorName(value);
        if (!name) {
            name = CP_Customizer.createColor(value, clbk);
        }
        return name;
    };

    CP_Customizer.getColorsObj = function (includeTransparent) {
        var colors = wp.customize.control('color_palette').getValue();
        var obj = {};
        for (var i = 0; i < colors.length; i++) {
            if (colors[i]) {
                obj[colors[i].name] = colors[i].value;
            }
        }

        if (includeTransparent) {
            obj['transparent'] = 'rgba(0,0,0,0)';
        }


        return obj;
    };

    CP_Customizer.getColorValue = function (name) {
        var colors = CP_Customizer.getColorsObj();

        if (name === "transparent") {
            return "rgba(0,0,0,0)";
        }

        if (name === "white") {
            return "#ffffff";
        }

        if (name === "black") {
            return "#000000";
        }


        if (name === "color-white") {
            return "#ffffff";
        }

        if (name === "color-black") {
            return "#000000";
        }


        if (name === "gray") {
            return "#bdbdbd";
        }

        return colors[name];
    };

    var defaultColors = {
        'ffffff': 'color-white',
        '000000': 'color-black'
    };

    CP_Customizer.createColor = function (color, clbk, forceCreate) {


        if (defaultColors[tinycolor(color).toHex()] && !forceCreate) {
            return defaultColors[tinycolor(color).toHex()]
        }

        var colors = CP_Customizer.getColorsObj();
        var max = 0;
        for (var c in colors) {
            var nu = parseInt(c.replace(/[a-z]+/, ''));
            if (nu != NaN) {
                max = Math.max(nu, max);
            }
        }
        var name = "color" + (++max);
        colors[name] = color;

        if (clbk) clbk(name);

        var control = wp.customize.control('color_palette');
        var theNewRow = control.addRow({
            name: name,
            label: name,
            value: color
        });
        theNewRow.toggleMinimize();
        control.initColorPicker();

        if (defaultColors[tinycolor(color).toHex()]) {
            return defaultColors[tinycolor(color).toHex()]
        }


        return name;
    };

    CP_Customizer.getColorName = function (color) {
        var colors = CP_Customizer.getColorsObj();
        var parsedColor = tinycolor(color);

        for (var c in colors) {
            var _temp = tinycolor(colors[c]);
            if (parsedColor.toHex() === _temp.toHex()) { // parsed colors by tinycolor will ensure the same Hex if the colors are equal
                return c;
            }
        }

        if (parsedColor.toHex() === tinycolor('#000000').toHex()) {
            return 'color-black';
        }

        if (parsedColor.toHex() === tinycolor('#ffffff').toHex()) {
            return 'color-white';
        }


        if (parsedColor.toHex() === tinycolor('#bdbdbd').toHex()) {
            return "gray";
        }

        if (tinycolor(color).getAlpha() === 0) {
            return "transparent";
        }

        return "";
    };

    CP_Customizer.getPaletteColors = function (json, includeTransparent, extras) {
        var colors = CP_Customizer.getColorsObj(includeTransparent);

        if (_.isObject(extras)) {
            colors = $.extend({}, colors, extras);
        }

        if (!json) return _.values(colors);
        return JSON.stringify(_.values(colors));
    };

    $(document).ready(function () {
        _.delay(function () {
            var control = wp.customize.control('color_palette');
            control.container.off('click', 'button.repeater-add');
            control.container.on('click', 'button.repeater-add', function (e) {
                e.preventDefault();
                CP_Customizer.createColor('#ffffff', undefined, true);
            });

            control.container.find('.repeater-add').html('Add theme color');

            control.container.find("[data-field=name][value=color1], [data-field=name][value=color2], [data-field=name][value=color3], [data-field=name][value=color4], [data-field=name][value=color5]").each(function () {
                $(this).parents(".repeater-row").find(".repeater-row-remove").hide();
            });
        }, 1000);
    })


    CP_Customizer.hooks.addFilter('spectrum_color_palette', function (colors) {
        var siteColors = jQuery.map(CP_Customizer.getColorsObj(), function (value, index) {
            return value;
        });

        return siteColors;

    });
})(window, CP_Customizer, jQuery);
