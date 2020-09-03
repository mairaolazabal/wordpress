(function ($) {

  function toggleOffscreenMenuIcon(index) {
      return function (value) {
          value.bind(function (newval) {
              if (newval) {
                  $('#offcanvas-wrapper .social-icons .social-icon').eq(index).removeAttr('data-reiki-hidden');
              } else {
                  $('#offcanvas-wrapper .social-icons .social-icon').eq(index).attr('data-reiki-hidden', 'true');
              }
          });
      }
  }

  function changeOffscreenMenuIcon(index) {
      return function (value) {
          value.bind(function (newval, oldval) {
              $('#offcanvas-wrapper .social-icons .social-icon').eq(index).find('i').removeClass(oldval).addClass(newval);
          });
      }
  }

  function toggleNavigationCustomAreaIcon(navigation, index) {
      return function (value) {
          value.bind(function (newval) {
              if (newval) {
                  navigation.find('.navigation-custom-area .social-icons .social-icon').eq(index).removeAttr('data-reiki-hidden');
              }
              else {
                  navigation.find('.navigation-custom-area .social-icons .social-icon').eq(index).attr('data-reiki-hidden', 'true');
              }
          });
      }
  }

  function changeNavigationCustomAreaIcon(navigation, index) {
      return function (value) {
          value.bind(function (newval, oldval) {
              navigation.find('.navigation-custom-area .social-icons .social-icon').eq(index).find('i').removeClass(oldval).addClass(newval);
          });
      }
  }

  /* START TOP BAR */

  wp.customize('top_bar_background_type', function (value) {
      value.bind(function (newval) {
          if(newval == 'color') {
              $('.header-top-bar').css('background', wp.customize('top_bar_background_color').get());
              $('.header-top-bar').removeClass(wp.customize('top_bar_background_gradient').get());
          }
          if(newval == 'gradient') {
              $('.header-top-bar').addClass(wp.customize('top_bar_background_gradient').get());
              $('.header-top-bar').removeAttr('style');
          }
      });
  });

  wp.customize('top_bar_background_color', function (value) {
      value.bind(function (newval) {
          $('.header-top-bar').css('background', newval);
      });
  });

  wp.customize('top_bar_background_gradient', function (value) {
      value.bind(function (newval, oldval) {
          $('.header-top-bar').removeClass(oldval);
          $('.header-top-bar').addClass(newval);
      });
  });

  wp.customize('top_bar_enable_bottom_border', function (value) {
      value.bind(function (newval) {
          $('.header-top-bar').css({
              'border-bottom-color' : (newval ? wp.customize('top_bar_bottom_border_color').get() : ''),
              'border-bottom-width' : (newval ? wp.customize('top_bar_bottom_border_thickness').get() + 'px' : '0px'),
              'border-bottom-style' : (newval ? 'solid' : '')
          });
      });
  });

  wp.customize('top_bar_bottom_border_color', function (value) {
      value.bind(function (newval) {
          $('.header-top-bar').css('border-bottom-color', newval);
      });
  });

  wp.customize('top_bar_bottom_border_thickness', function (value) {
      value.bind(function (newval) {
          $('.header-top-bar').css({
              'border-bottom-width' : newval + 'px',
              'border-bottom-style' : 'solid'
          });
      });
  });

  /* END TOP BAR */

  /* START NAVIGATION CUSTOM AREA */

  var headerPanels = ['header', 'inner_header'];

  var navigationSelectors = {
      'header' : '.mesmerize-front-page',
      'inner_header' : '.mesmerize-inner-page'
  };

  $.each(headerPanels, function(index, panel) {

      var $navBar = $([navigationSelectors[panel], ".navigation-bar"].join(' '));
      for (var i=0; i < 5; i++) {
          wp.customize(panel + '_nav_custom_area_social_icon_' + i + '_enabled', toggleNavigationCustomAreaIcon($navBar, i));
          wp.customize(panel + '_nav_custom_area_social_icon_' + i + '_icon', changeNavigationCustomAreaIcon($navBar, i));
      }

  });

  /* END NAVIGATION CUSTOM AREA */

  /* START OFFSCREEN MENU */

  for (var i=0; i < 4; i++) {
      wp.customize('header_offscreen_nav_offscreen_nav_social_icon_' + i + '_enabled', toggleOffscreenMenuIcon(i));
      wp.customize('header_offscreen_nav_offscreen_nav_social_icon_' + i + '_icon', changeOffscreenMenuIcon(i));
  }

  /* END OFFSCREEN MENU */

  /* START HEADER CONTENT */

  wp.customize('header_content_video_img_shadow', function (value) {
      value.bind(function (newval) {
          var styleHolder = jQuery('[data-name="header_content_video_img_shadow"]');

          if(newval) {
              if (styleHolder.length == 0) {
                  styleHolder = jQuery('<style data-name="header_content_video_img_shadow"></style>');
                  styleHolder.appendTo('head');
              }
              styleHolder.text("" +
                  "   .header-description-row img.homepage-header-image,\n" +
                  "   .header-description-row .video-popup-button img,\n" +
                  "   .header-description-row iframe.header-hero-video {\n" +
                  "       -moz-box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);\n" +
                  "       -webkit-box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);\n" +
                  "       box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);\n" +
                  "   }");
          }
          else {
              styleHolder.remove();
          }
      });
  });

  wp.customize('header_video_popup_image_disabled', function (value) {
      value.bind(function (newval) {
          if(newval) {
              $('.video-popup-button').removeClass('with-image').find('.poster').remove();
          }
          else {
              var image = wp.customize('header_video_popup_image').get();
              imageElement = $('<img class="poster" src="' + image + '"/>');
              $('.video-popup-button').addClass('with-image').prepend(imageElement);
          }
      });
  });

  wp.customize('header_video_popup_image', function (value) {
      value.bind(function (newval) {
          $('.video-popup-button.with-image').find('.poster').attr('src', newval);
      });
  });

  /* END HEADER CONTENT */

})(jQuery);

(function ($) {
    liveUpdateAutoSetting('pro_background_overlay', function (value) {
        var style = jQuery.map(value, function (color, id) {
            return '[data-ovid="' + id + '"]:before { background-color: ' + color + ' !important; }'
        }).join("\n");
        jQuery('style[data-for=pro_background_overlay]').html(style);
    });
    liveUpdate('header_nav_title_typography', function (value) {
        jQuery('#main_menu>li:hover>a, #main_menu>li.hover>a, #main_menu>li.current_page_item>a').css({
            'text-shadow': "0px 0px 0px " + value.color
        });
        jQuery('#main_menu>li:hover>a, #main_menu>li.hover>a, #main_menu>li.current_page_item>a').css({
            'border-bottom-color': value.color
        });
        jQuery('.header-top.homepage.bordered').css({
            'border-bottom-color': value.color
        });
    });
    liveUpdate('header_content_vertical_align', function (value) {
        var header = jQuery('.header-homepage');
        header.removeClass('v-align-top');
        header.removeClass('v-align-middle');
        header.removeClass('v-align-bottom');
        header.addClass(value);
    });
    liveUpdate('header_text_align', function (value) {
        var header = jQuery('.header-content:not(.header-content-centered, .header-content-left, .header-content-right)');
        if (header.length) {
            header.removeClass('container-align-center');
            header.removeClass('container-align-left');
            header.removeClass('container-align-right');
            header.addClass('container-align-' + value);
        }
    });
    liveUpdate('full_height', function (value) {
        var contentVerticalControl = parent.wp.customize.control('header_content_vertical_align');
        contentVerticalControl.active(value);
    });
    liveUpdate('header_content_image_rounded', function (value) {
        if (value) {
            $('.homepage-header-image').addClass('round');
        } else {
            $('.homepage-header-image').removeClass('round');
        }
    });
})(jQuery);
(function ($) {

    var prefix = $('body').hasClass('mesmerize-front-page') ? 'header' : 'inner_header';

    function getHeaderSplitGradientValue(color, angle, size, fade) {
        angle = -90 + parseInt(angle);
        fade = parseInt(fade) / 2;
        transparentMax = (100 - size) - fade;
        colorMin = (100 - size) + fade;
        var gradient = angle + "deg, " + "transparent 0%, transparent " + transparentMax + "%, " + color + " " + colorMin + "%, " + color + " 100%";
        // return gradient;
        var result = 'background: linear-gradient(' + gradient + ');' + 'background: -webkit-linear-gradient(' + gradient + ');' + 'background: linear-gradient(' + gradient + ');';
        return result;
    }

    function recalculateHeaderSplitGradient() {
        var color = wp.customize(prefix + '_split_header_color').get();
        var angle = wp.customize(prefix + '_split_header_angle').get();
        var fade = wp.customize(prefix + '_split_header_fade') ? wp.customize('split_header_fade').get() : 0;
        var size = wp.customize(prefix + '_split_header_size').get();
        var gradient = getHeaderSplitGradientValue(color, angle, size, fade);
        var angle = wp.customize(prefix + '_split_header_angle_mobile').get();
        var size = wp.customize(prefix + '_split_header_size_mobile').get();
        var mobileGradient = getHeaderSplitGradientValue(color, angle, size, fade);
        var style = '';
        if (prefix === 'header') {
            style += '.header-homepage  .split-header {' + mobileGradient + '}' + "\n\n" + '@media screen and (min-width: 767px) { .header-homepage  .split-header {' + gradient + '} }';
        } else {
            style += '.mesmerize-inner-page  .split-header {' + mobileGradient + '}' + "\n\n" + '@media screen and (min-width: 767px) { .mesmerize-inner-page .split-header {' + gradient + '} }';
        }
        jQuery('style[data-name="header-split-style"]').html(style);
    }

    liveUpdate(prefix + '_split_header_fade', recalculateHeaderSplitGradient);
    liveUpdate(prefix + '_split_header_color', recalculateHeaderSplitGradient);
    liveUpdate(prefix + '_split_header_angle', recalculateHeaderSplitGradient);
    liveUpdate(prefix + '_split_header_size', recalculateHeaderSplitGradient);
    liveUpdate(prefix + '_split_header_angle_mobile', recalculateHeaderSplitGradient);
    liveUpdate(prefix + '_split_header_size_mobile', recalculateHeaderSplitGradient);

    function recalculateSliderSplitGradient() {
        var color = wp.customize('slider_split_background_color').get();
        var angle = wp.customize('slider_split_background_angle').get();
        var fade = wp.customize('slider_split_background_fade') ? wp.customize('slider_split_background_fade').get() : 0;
        var size = wp.customize('slider_split_background_size').get();
        var gradient = getHeaderSplitGradientValue(color, angle, size, fade);
        var angle = wp.customize('slider_split_background_angle_mobile').get();
        var size = wp.customize('slider_split_background_size_mobile').get();
        var mobileGradient = getHeaderSplitGradientValue(color, angle, size, fade);
        var style = '';
        style += '.header-with-slider-wrapper .header-homepage .split-header {' + mobileGradient + '}' + "\n\n" + '@media screen and (min-width: 767px) { .header-with-slider-wrapper .header-homepage .split-header {' + gradient + '} }';

        jQuery('style[data-name="header-slide-split-style"]').html(style);
    }

    liveUpdate('slider_split_background_fade', recalculateSliderSplitGradient);
    liveUpdate('slider_split_background_color', recalculateSliderSplitGradient);
    liveUpdate('slider_split_background_angle', recalculateSliderSplitGradient);
    liveUpdate('slider_split_background_size', recalculateSliderSplitGradient);
    liveUpdate('slider_split_background_angle_mobile', recalculateSliderSplitGradient);
    liveUpdate('slider_split_background_size_mobile', recalculateSliderSplitGradient);

})(jQuery);
(function ($) {

    function getGradientValue(setting) {
        var control = parent.wp.customize.control(setting);
        var gradient = parent.CP_Customizer.utils.getValue(control);
        var colors = gradient.colors;
        var angle = gradient.angle;
        angle = parseFloat(angle);
        return parent.Mesmerize.Utils.getGradientString(colors, angle);
    }


    function recalculateFooterOverlayGradient() {
        var gradient = getGradientValue('footer_overlay_gradient_colors');
        $("<style>.footer .footer-content.color-overlay::before { background: " + gradient + "}</style>").appendTo("head");
    }

    /* START FOOTER */

    wp.customize('footer_bg_image', function (value) {
        value.bind(function (newval) {
            $('.footer .footer-content').css('background-image', 'url(' + newval + ')');
        });
    });

    wp.customize('footer_show_overlay', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.footer .footer-content').addClass('color-overlay');
            } else {
                $('.footer .footer-content').removeClass('color-overlay');
            }
        });
    });

    liveUpdate('footer_content_copyright_text', function (value) {
        var footerOptions = parent.CP_Customizer.options('footerData', {
            year: (new Date()).getFullYear(),
            blogname: 'BlogName'
        });

        value = value.replace('{year}', footerOptions.year);
        value = value.replace('{blogname}', footerOptions.blogname);


        jQuery('[data-footer-copyright="true"]').html(value);
    });

    liveUpdate('footer_content_box_text', function (value) {
        jQuery('[data-focus-control="footer_content_box_text"]').html(value);
    });

    liveUpdate('footer_overlay_gradient_colors', recalculateFooterOverlayGradient);

    wp.customize('footer_enable_top_border', function (value) {
        value.bind(function (newval) {
            $('.footer .footer-content').css({
                'border-top-color' : (newval ? wp.customize('footer_top_border_color').get() : ''),
                'border-top-width' : (newval ? wp.customize('footer_top_border_thickness').get() + 'px' : '0px'),
                'border-top-style' : (newval ? 'solid' : '')
            });
        });
    });

    wp.customize('footer_top_border_color', function (value) {
        value.bind(function (newval) {
            $('.footer .footer-content').css('border-top-color', newval);
        });
    });

    wp.customize('footer_top_border_thickness', function (value) {
        value.bind(function (newval, oldval) {
            $('.footer .footer-content').css({
                'border-top-width' : newval + 'px',
                'border-top-style' : 'solid'
            });

            var footer = $('.footer.paralax');
            if(footer.length) {
                footer.prev().css("margin-bottom", (footer.outerHeight() - 1));
            }
        });
    });

    wp.customize('footer_spacing', function (value) {
        value.bind(function (newval, oldval) {
            var footer = $('.footer.paralax');
            if(footer.length) {
                var spacingDiff = parseInt(oldval.top) + parseInt(oldval.bottom) - parseInt(newval.top) - parseInt(newval.bottom);
                footer.prev().css("margin-bottom", (footer.outerHeight() - 1 - spacingDiff));
            }
        });
    });

    /* END FOOTER */

    // dark logo //

    wp.customize('inner_header_nav_use_dark_logo', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.navigation-bar:not(.homepage) .navigation-wrapper').addClass('dark-logo');
                $('.navigation-bar:not(.homepage) .navigation-wrapper').removeClass('white-logo');
            } else {
                $('.navigation-bar:not(.homepage) .navigation-wrapper').addClass('white-logo');
                $('.navigation-bar:not(.homepage) .navigation-wrapper').removeClass('dark-logo');
            }
        });
    });

    wp.customize('header_nav_use_dark_logo', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.navigation-bar.homepage .navigation-wrapper').addClass('dark-logo');
                $('.navigation-bar.homepage .navigation-wrapper').removeClass('white-logo');
            } else {
                $('.navigation-bar.homepage .navigation-wrapper').addClass('white-logo');
                $('.navigation-bar.homepage .navigation-wrapper').removeClass('dark-logo');
            }
        });
    });


    wp.customize('inner_header_nav_fixed_use_dark_logo', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.navigation-bar:not(.homepage) .navigation-wrapper').addClass('fixed-dark-logo');
                $('.navigation-bar:not(.homepage) .navigation-wrapper').removeClass('fixed-white-logo');
            } else {
                $('.navigation-bar:not(.homepage) .navigation-wrapper').addClass('fixed-white-logo');
                $('.navigation-bar:not(.homepage) .navigation-wrapper').removeClass('fixed-dark-logo');
            }
        });
    });

    wp.customize('header_nav_fixed_use_dark_logo', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.navigation-bar.homepage .navigation-wrapper').addClass('fixed-dark-logo');
                $('.navigation-bar.homepage .navigation-wrapper').removeClass('fixed-white-logo');
            } else {
                $('.navigation-bar.homepage .navigation-wrapper').addClass('fixed-white-logo');
                $('.navigation-bar.homepage .navigation-wrapper').removeClass('fixed-dark-logo');
            }
        });
    });

    var customSliderActionTime = 0;

    wp.customize('third_party_slider_shortcode', function (value) {
        value.bind(function (newval) {

            customSliderActionTime = Math.round(+new Date()/1000);

            _.delay(function() {
                if((Math.round(+new Date()/1000)) >= (customSliderActionTime + 3)) {
                    parent.CP_Customizer.preview.refresh();
                }
            }, 3000);

        });
    });

})(jQuery);
(function ($) {

    function getGroupedNavigationClass(position) {

        var navClass = 'nct';

        switch (position) {
            case "left top":
                navClass = 'nlt';
                break;
            case "left bottom":
                navClass = 'nlb';
                break;
            case "center top":
                navClass = 'nct';
                break;
            case "center bottom":
                navClass = 'ncb';
                break;
            case "right top":
                navClass = 'nrt';
                break;
            case "right bottom":
                navClass = 'nrb';
                break;
        }

        return navClass;
    }

    function setPrevNextButtonsPositionAndMargin(position, buttons) {

        var buttonProperties = {};
        var overlapWith = 0;

        if (wp.customize('slider_overlap_header').get()) {
            var overlapWith = parseInt(wp.customize('slider_overlap_header_with').get());
        }

        // slider_enable_prev_next_buttons
        switch (position) {
            case 'top':
                buttonProperties['top'] = (wp.customize('slider_show_progress_bar').get() && wp.customize('slider_enable_autoplay').get()) ?
                    (($('.header-top').outerHeight() + parseInt(wp.customize('slider_progress_bar_height').get())) + 'px') :
                    ($('.header-top').outerHeight() + 'px');
                buttonProperties['margin-top'] = wp.customize('slider_prev_next_buttons_top_offset').get() + 'px';
                break;

            case 'center':
                buttonProperties['top'] = (($('.owl-carousel').outerHeight() + $('.header-top').outerHeight()) / 2) - (wp.customize('slider_prev_next_buttons_size').get() / 2) + 'px';
                buttonProperties['margin-top'] = wp.customize('slider_prev_next_buttons_center_offset').get() + 'px';
                break;

            case 'bottom':
                buttonProperties['bottom'] = overlapWith + 'px';
                buttonProperties['margin-bottom'] = wp.customize('slider_prev_next_buttons_bottom_offset').get() + 'px';
                break;
        }

        jQuery.each(buttons, function (index, button) {
            $(button).removeAttr('style');
            $(button).css(buttonProperties);
        });


        if (!wp.customize('slider_enable_prev_next_buttons').get()) {
            $(buttons.join(',')).css({
                display: 'none'
            });
        }
    }

    function setPlayPauseButtonPositionAndMargin(position, button) {

        var buttonProperties = {};
        var hasProgressBar = wp.customize('slider_show_progress_bar').get();
        var autoplayEnabled = wp.customize('slider_enable_autoplay').get();
        var upperHeight = $('.header-top').outerHeight();
        var progressBarHeight = parseInt(wp.customize('slider_progress_bar_height').get());
        var screenSize = $('.mesmerize-slider').width();
        var autoplayButtonWidth = wp.customize('slider_play_pause_button_size').get();
        var lrOffset = wp.customize('slider_play_pause_button_lr_spacing').get();
        var topOffset = wp.customize('slider_play_pause_button_top_offset').get();
        var bottomOffset = wp.customize('slider_play_pause_button_bottom_offset').get();
        var overlapWith = 0;
        if (wp.customize('slider_overlap_header').get()) var overlapWith = parseInt(wp.customize('slider_overlap_header_with').get());

        switch (position) {
            case 'left top':
                buttonProperties['top'] = (hasProgressBar && autoplayEnabled) ? ((upperHeight + progressBarHeight) + 'px') : (upperHeight + 'px');
                buttonProperties['left'] = '0px';
                buttonProperties['margin-left'] = lrOffset + 'px';
                buttonProperties['margin-right'] = lrOffset + 'px';
                buttonProperties['margin-top'] = topOffset + 'px';
                break;

            case 'center top':
                buttonProperties['top'] = (hasProgressBar && autoplayEnabled) ? ((upperHeight + progressBarHeight) + 'px') : (upperHeight + 'px');
                buttonProperties['left'] = '50%';
                buttonProperties['margin-left'] = '0px';
                buttonProperties['margin-right'] = '0px';
                buttonProperties['margin-top'] = topOffset + 'px';
                buttonProperties['transform'] = 'translateX(-50%)';
                break;

            case 'right top':
                buttonProperties['top'] = (hasProgressBar && autoplayEnabled) ? ((upperHeight + progressBarHeight) + 'px') : (upperHeight + 'px');
                buttonProperties['right'] = '0px';
                buttonProperties['margin-left'] = lrOffset + 'px';
                buttonProperties['margin-right'] = lrOffset + 'px';
                buttonProperties['margin-top'] = topOffset + 'px';
                break;

            case 'left bottom':
                buttonProperties['bottom'] = '0px';
                buttonProperties['left'] = '0px';
                buttonProperties['margin-left'] = lrOffset + 'px';
                buttonProperties['margin-right'] = lrOffset + 'px';
                buttonProperties['margin-bottom'] = bottomOffset + 'px';
                break;

            case 'center bottom':
                buttonProperties['bottom'] = overlapWith + 'px';
                buttonProperties['left'] = '50%';

                buttonProperties['margin-bottom'] = bottomOffset + 'px';
                buttonProperties['transform'] = 'translateX(-50%)';
                buttonProperties['margin-left'] = '0px';
                buttonProperties['margin-right'] = '0px';
                break;

            case 'right bottom':
                buttonProperties['bottom'] = '0px';
                buttonProperties['right'] = '0px';
                buttonProperties['margin-left'] = lrOffset + 'px';
                buttonProperties['margin-right'] = lrOffset + 'px';
                buttonProperties['margin-bottom'] = bottomOffset + 'px';
                break;
        }

        $(button).removeAttr('style').css(buttonProperties);

        if (!wp.customize('slider_enable_play_pause_button').get() || !autoplayEnabled) {
            $(button).css({
                'display': 'none'
            })
        }

    }

    function setPaginationPositionAndMargin(position) {

        var hasProgressBar = wp.customize('slider_show_progress_bar').get();
        var autoplayEnabled = wp.customize('slider_enable_autoplay').get();
        var upperHeight = $('.header-top').outerHeight();
        var progressBarHeight = parseInt(wp.customize('slider_progress_bar_height').get());
        var overlapWith = 0;
        if (wp.customize('slider_overlap_header').get()) {
            overlapWith = parseInt(wp.customize('slider_overlap_header_with').get());
        }

        if (position == 'top') {
            topPosition = (hasProgressBar && autoplayEnabled) ? ((upperHeight + parseInt(progressBarHeight)) + 'px') : (upperHeight + 'px');
            bottomPosition = 'unset';
        }
        if (position == 'bottom') {
            topPosition = 'unset';
            bottomPosition = overlapWith + 'px';
        }
        $('.header-slider-navigation.separated .owl-dots').css('top', topPosition).css('bottom', bottomPosition);

    }

    wp.customize('slider_full_height_background_enabled', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.owl-item > .header-slide').removeClass('nh-slide').addClass('fh-slide');
            }
            else {
                $('.owl-item > .header-slide').removeClass('fh-slide').addClass('nh-slide');
            }

            $('#header-slides-container').data('owl.carousel').refresh();
        });
    });

    wp.customize('slider_enable_autoplay', function (value) {
        value.bind(function (newval) {

            if (newval) {
                // first create the buttons if they don't exist
                if (!$('.header-slider-navigation').find('.owl-nav > .owl-pause').length || !$('.header-slider-navigation').find('.owl-nav > .owl-play').length) {
                    $('.header-slider-navigation').find('.owl-nav').append('<div class="owl-pause"><i class="fa fa-pause no-popup"></i></div>');
                    $('.header-slider-navigation').find('.owl-nav').append('<div class="owl-play"><i class="fa fa-play no-popup"></i></div>');
                }

                if (wp.customize('slider_show_progress_bar').get()) {
                    $('.header-progress-bar').show();
                }
            }
            else {
                // $('#header-slides-container').trigger('stop.owl.autoplay');
                $('.header-progress-bar').hide();
            }

            setPrevNextButtonsPositionAndMargin('center', ['.header-slider-navigation.separated .owl-nav .owl-prev', '.header-slider-navigation.separated .owl-nav .owl-next']);
            setPlayPauseButtonPositionAndMargin(wp.customize('slider_play_pause_button_position').get(), '.header-slider-navigation.separated .owl-nav .owl-autoplay');
            setPaginationPositionAndMargin(wp.customize('slider_pagination_position').get());

        });
    });

    wp.customize('slider_enable_rewind', function (value) {
        value.bind(function (newval) {
            var owl = $('#header-slides-container');
            var carousel = owl.data('owl.carousel');
            carousel.settings.rewind = newval;
        });
    });

    wp.customize('slider_transitions_duration', function (value) {
        value.bind(function (newval) {
            var owl = $('#header-slides-container');
            var carousel = owl.data('owl.carousel');
            carousel.settings.autoplayTimeout = newval;
            carousel.options.autoplayTimeout = newval;

            carousel.settings.onTranslated = function () {
                $(".slide-progress").css({
                    width: "100%",
                    transition: "width " + (newval - wp.customize('slider_animations_duration').get()) + "ms"
                });
            };
            carousel.options.onTranslated = function () {
                $(".slide-progress").css({
                    width: "100%",
                    transition: "width " + (newval - wp.customize('slider_animations_duration').get()) + "ms"
                });
            };

            owl.trigger('refresh.owl.carousel');
        });
    });
    wp.customize('slider_show_progress_bar', function (value) {
        value.bind(function (newval) {

            setPrevNextButtonsPositionAndMargin('center', ['.header-slider-navigation.separated .owl-nav .owl-prev', '.header-slider-navigation.separated .owl-nav .owl-next']);
            setPlayPauseButtonPositionAndMargin(wp.customize('slider_play_pause_button_position').get(), '.header-slider-navigation.separated .owl-nav .owl-autoplay');
            setPaginationPositionAndMargin(wp.customize('slider_pagination_position').get());

            if (newval) {
                $('.header-progress-bar').show();
            }
            else {
                $('.header-progress-bar').hide();
            }
        });
    });
    wp.customize('slider_animations_duration', function (value) {
        value.bind(function (newval) {
            var owl = $('#header-slides-container');
            var carousel = owl.data('owl.carousel');

            carousel.settings.onTranslated = function () {
                $(".slide-progress").css({
                    width: "100%",
                    transition: "width " + (wp.customize('slider_transitions_duration').get() - newval) + "ms"
                });
            };
            carousel.options.onTranslated = function () {
                $(".slide-progress").css({
                    width: "100%",
                    transition: "width " + (wp.customize('slider_transitions_duration').get() - newval) + "ms"
                });
            };

            owl.trigger('refresh.owl.carousel');
        });
    });
    wp.customize('slider_animation_effect', function (value) {
        value.bind(function (newval) {

            if ($('.owl-carousel').hasClass('ie-detected')) return;

            var transitionIn = '';
            var transitionOut = '';

            switch (newval) {
                case 'horizontal':
                    transitionIn = 'slideInRight';
                    transitionOut = 'slideOutLeft';
                    break;
                case 'vertical-down':
                    transitionIn = 'slideInDown';
                    transitionOut = 'slideOutDown';
                    break;
                case 'vertical-up':
                    transitionIn = 'slideInUp';
                    transitionOut = 'slideOutUp';
                    break;
                case 'fade':
                    transitionIn = 'fadeIn';
                    transitionOut = 'fadeOut';
                    break;
                case 'zoom':
                    transitionIn = 'fadeIn';
                    transitionOut = 'zoomOut';
                    break;
                case 'flip':
                    transitionIn = 'fadeIn';
                    transitionOut = 'flipOutX';
                    break;
                case 'custom':
                    transitionIn = wp.customize('slider_transitions_in_effect').get();
                    transitionOut = wp.customize('slider_transitions_out_effect').get();
                    break;
            }

            var owl = $('#header-slides-container');
            var carousel = owl.data('owl.carousel');
            carousel.options.animateIn = transitionIn;
            carousel.options.animateOut = transitionOut;
            owl.trigger('refresh.owl.carousel');
        });
    });
    wp.customize('slider_transitions_out_effect', function (value) {
        value.bind(function (newval) {

            if ($('.owl-carousel').hasClass('ie-detected')) return;

            var owl = $('#header-slides-container');
            var carousel = owl.data('owl.carousel');
            carousel.settings.animateOut = newval;
            carousel.options.animateOut = newval;
            owl.trigger('refresh.owl.carousel');
        });
    });
    wp.customize('slider_transitions_in_effect', function (value) {
        value.bind(function (newval) {

            if ($('.owl-carousel').hasClass('ie-detected')) return;

            var owl = $('#header-slides-container');
            var carousel = owl.data('owl.carousel');
            carousel.settings.animateIn = newval;
            carousel.options.animateIn = newval;
            owl.trigger('refresh.owl.carousel');
        });
    });
    /*
    wp.customize('slider_enable_touchdrag', function (value) {
        value.bind(function (newval) {
            var owl = $('#header-slides-container');
            var carousel = owl.data('owl.carousel');
            carousel.settings.touchDrag = newval;
            carousel.options.touchDrag = newval;
            owl.trigger('refresh.owl.carousel');
        });
    });
    */
    wp.customize('slider_group_navigation', function (value) {
        value.bind(function (newval) {

            var upperHeight = $('.header-top').outerHeight();
            if (newval) {
                var navClass = getGroupedNavigationClass(wp.customize('slider_grouped_navigation_position').get());
                $('.header-slider-navigation').removeClass().addClass('header-slider-navigation mesmerize-slider grouped ' + navClass);
                if ($('.header-slider-navigation.grouped').is('.nlt, .nct, .nrt')) {
                    $('.header-slider-navigation.grouped').css('top', upperHeight + 'px');
                }
                else {
                    if (wp.customize('slider_overlap_header').get()) {
                        $('.header-slider-navigation.grouped').css('bottom', parseInt(wp.customize('slider_overlap_header_with').get()) + 'px');
                    }
                }
                $('.header-slider-navigation.grouped .owl-nav [class*="owl-"]').removeAttr('style');

//                parent.wp.customize.control('slider_pagination_type').setting.set('shapes');
            }
            else {
                $('.header-slider-navigation').removeClass().addClass('header-slider-navigation mesmerize-slider separated');
                $('.header-slider-navigation.separated').removeAttr('style');

                if (wp.customize('slider_enable_navigation').get()) {
                    setPrevNextButtonsPositionAndMargin('center', ['.header-slider-navigation.separated .owl-nav .owl-prev', '.header-slider-navigation.separated .owl-nav .owl-next']);
                    setPlayPauseButtonPositionAndMargin(wp.customize('slider_play_pause_button_position').get(), '.header-slider-navigation.separated .owl-nav .owl-autoplay');
                    setPaginationPositionAndMargin(wp.customize('slider_pagination_position').get());
                }
            }

        });
    });
    wp.customize('slider_grouped_navigation_position', function (value) {
        value.bind(function (newval) {
            // if (wp.customize('slider_group_navigation').get()) {
            //     var navClass = getGroupedNavigationClass(newval);
            //     var upperHeight = $('.header-top-bar').outerHeight() + $('.navigation-bar').outerHeight();
            //     $('.header-slider-navigation.grouped').removeClass().addClass('header-slider-navigation mesmerize-slider grouped ' + navClass);
            //     if ($('.header-slider-navigation.grouped').is('.nlt, .nct, .nrt')) {
            //         $('.header-slider-navigation.grouped').css('top', upperHeight + 'px');
            //     }
            //     else {
            $('.header-slider-navigation.grouped').removeAttr('style');
            //     }
            // }
        });
    });
    wp.customize('slider_enable_prev_next_buttons', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.header-slider-navigation').find('.owl-nav > .owl-prev').show();
                $('.header-slider-navigation').find('.owl-nav > .owl-next').show();
            }
            else {
                $('.header-slider-navigation').find('.owl-nav > .owl-prev').hide();
                $('.header-slider-navigation').find('.owl-nav > .owl-next').hide();
            }
        });
    });
    wp.customize('slider_prev_next_buttons_position', function (value) {
        value.bind(function (newval) {
            setPrevNextButtonsPositionAndMargin(newval, ['.header-slider-navigation.separated .owl-nav .owl-prev', '.header-slider-navigation.separated .owl-nav .owl-next']);
        });
    });
    wp.customize('slider_prev_next_buttons_top_offset', function (value) {
        value.bind(function (newval) {
            $('.header-slider-navigation.separated .owl-nav .owl-prev').css('margin-top', newval + 'px');
            $('.header-slider-navigation.separated .owl-nav .owl-next').css('margin-top', newval + 'px');
        });
    });
    wp.customize('slider_prev_next_buttons_center_offset', function (value) {
        value.bind(function (newval) {
            $('.header-slider-navigation.separated .owl-nav .owl-prev').css('margin-top', newval + 'px');
            $('.header-slider-navigation.separated .owl-nav .owl-next').css('margin-top', newval + 'px');
        });
    });
    wp.customize('slider_prev_next_buttons_bottom_offset', function (value) {
        value.bind(function (newval) {
            $('.header-slider-navigation.separated .owl-nav .owl-prev').css('margin-bottom', newval + 'px');
            $('.header-slider-navigation.separated .owl-nav .owl-next').css('margin-bottom', newval + 'px');
        });
    });
    wp.customize('slider_prev_next_buttons_style', function (value) {
        value.bind(function (newval) {
            $('.header-slider-navigation').find('.owl-nav > .owl-prev').removeClass().addClass('owl-prev').addClass(newval);
            $('.header-slider-navigation').find('.owl-nav > .owl-next').removeClass().addClass('owl-next').addClass(newval);
        });
    });
    wp.customize('slider_prev_next_buttons_size', function (value) {
        value.bind(function (newval) {
            setPrevNextButtonsPositionAndMargin('center', ['.header-slider-navigation.separated .owl-nav .owl-prev', '.header-slider-navigation.separated .owl-nav .owl-next']);
        });
    });

    wp.customize('slider_prev_next_button_icon', function (value) {
        value.bind(function (newval) {
            var $navigation = $('.header-slider-navigation').find('.owl-nav');
            $navigation.find('.owl-prev > i').removeClass().addClass('fa no-popup ' + newval + '-left');
            $navigation.find('.owl-next > i').removeClass().addClass('fa no-popup ' + newval + '-right');
        });
    });

    wp.customize('slider_enable_play_pause_button', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.header-slider-navigation').find('.owl-nav > .owl-autoplay').css('display', 'inline-block');
            }
            else {
                $('.header-slider-navigation').find('.owl-nav > .owl-autoplay').hide();
            }
        });
    });
    wp.customize('slider_play_pause_button_position', function (value) {
        value.bind(function (newval) {
            setPlayPauseButtonPositionAndMargin(wp.customize('slider_play_pause_button_position').get(), '.header-slider-navigation.separated .owl-nav .owl-autoplay');
        });
    });
    wp.customize('slider_play_pause_button_lr_spacing', function (value) {
        value.bind(function (newval) {
            if (wp.customize('slider_play_pause_button_position').get() !== 'center top' && wp.customize('slider_play_pause_button_position').get() !== 'center bottom') {
                $('.header-slider-navigation.separated .owl-nav .owl-autoplay').css({'margin-left': newval + 'px', 'margin-right': newval + 'px'});
            }
        });
    });
    wp.customize('slider_play_pause_button_top_offset', function (value) {
        value.bind(function (newval) {
            $('.header-slider-navigation.separated .owl-nav .owl-autoplay').css('margin-top', newval + 'px');
        });
    });
    wp.customize('slider_play_pause_button_bottom_offset', function (value) {
        value.bind(function (newval) {
            $('.header-slider-navigation.separated .owl-nav .owl-autoplay').css('margin-bottom', newval + 'px');
        });
    });
    wp.customize('slider_play_pause_button_style', function (value) {
        value.bind(function (newval) {
            var keepIsPlaying = keepOnPause = false;
            if ($('.header-slider-navigation .owl-nav > .owl-autoplay').hasClass('is-playing')) keepIsPlaying = true;

            $('.header-slider-navigation').find('.owl-nav > .owl-autoplay').removeClass().addClass('owl-autoplay').addClass(keepIsPlaying ? 'is-playing' : '').addClass(newval);
            setPlayPauseButtonPositionAndMargin(wp.customize('slider_play_pause_button_position').get(), '.header-slider-navigation.separated .owl-nav .owl-autoplay');
        });
    });
    wp.customize('slider_play_pause_button_size', function (value) {
        value.bind(function (newval) {
            setPlayPauseButtonPositionAndMargin(wp.customize('slider_play_pause_button_position').get(), '.header-slider-navigation.separated .owl-nav .owl-autoplay');
        });
    });
    wp.customize('slider_pause_action_icon', function (value) {
        value.bind(function (newval) {
            $('.header-slider-navigation').find('.owl-nav > .owl-autoplay').attr('data-pause-icon', newval);
            if ($('.header-slider-navigation').find('.owl-nav > .owl-autoplay').hasClass('is-playing')) {
                $('.header-slider-navigation').find('.owl-nav > .owl-autoplay > i').removeClass().addClass('fa ' + newval + ' no-popup');
            }
        });
    });
    wp.customize('slider_play_action_icon', function (value) {
        value.bind(function (newval) {
            $('.header-slider-navigation').find('.owl-nav > .owl-autoplay').attr('data-play-icon', newval);
            if (!$('.header-slider-navigation').find('.owl-nav > .owl-autoplay').hasClass('is-playing')) {
                $('.header-slider-navigation').find('.owl-nav > .owl-autoplay > i').removeClass().addClass('fa ' + newval + ' no-popup');
            }
        });
    });
    wp.customize('slider_enable_pagination', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.header-slider-navigation').find('.owl-dots').show();
            }
            else {
                $('.header-slider-navigation').find('.owl-dots').hide();
            }
        });
    });
    wp.customize('slider_pagination_position', function (value) {
        value.bind(function (newval) {
            setPaginationPositionAndMargin(newval);
        });
    });
    wp.customize('slider_pagination_shapes_type', function (value) {
        value.bind(function (newval) {
            $('.header-slider-navigation').find('.owl-dots').removeClass().addClass('owl-dots ' + newval);
        });
    });
    /*
    wp.customize('slider_pagination_thumbnails_size', function (value) {
        value.bind(function (newval) {
            $('.header-slider-navigation .owl-dots.thumbnails .owl-dot > *').attr('style', 'width: ' + newval+'px !important; height: ' + newval*0.5625+'px !important');
            $('.header-slider-navigation .owl-dots.thumbnails .owl-dot .video-thumbnail-icon').attr('style', 'font-size: ' + newval*0.5625/1.8 + 'px;');
        });
    });
    */


    /* START SLIDER BOTTOM ARROW */

    wp.customize('slider_show_bottom_arrow', function (value) {
        value.bind(function (newval) {
            if (newval) {
                var fontSize = wp.customize('slider_bottom_arrow_icon_size').get();

                if (wp.customize('slider_overlap_header').get()) {
                    var newOffset = parseInt(wp.customize('slider_overlap_header_with').get()) + parseInt(newval);
                }
                else {
                    var newOffset = parseInt(newval);
                }

                $('.header-with-slider-wrapper .header-homepage-arrow-c').removeAttr('data-reiki-hidden');
                $('.header-with-slider-wrapper .header-homepage-arrow').css('font-size', fontSize + 'px');
                $('.header-with-slider-wrapper .header-homepage-arrow').css('bottom', newOffset + 'px');
                $('.header-with-slider-wrapper .header-homepage-arrow').css('background', wp.customize('slider_bottom_arrow_icon_background_color').get());
                $('.header-with-slider-wrapper .header-homepage-arrow > i').css('width', fontSize + 'px');
                $('.header-with-slider-wrapper .header-homepage-arrow > i').css('height', fontSize + 'px');
                $('.header-with-slider-wrapper .header-homepage-arrow > i').css('color', wp.customize('slider_bottom_arrow_icon_color').get());
            } else {
                $('.header-with-slider-wrapper .header-homepage-arrow-c').attr('data-reiki-hidden', 'true');
            }

        });
    });

    wp.customize('slider_bottom_arrow_bounce', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.header-with-slider-wrapper .header-homepage-arrow').addClass('move-down-bounce');
            } else {
                $('.header-with-slider-wrapper .header-homepage-arrow').removeClass('move-down-bounce');
            }
        });
    });

    wp.customize('slider_bottom_arrow_icon', function (value) {
        value.bind(function (newval, oldval) {
            $('.header-with-slider-wrapper .header-homepage-arrow .fa').removeClass(oldval).addClass(newval);
        });
    });

    wp.customize('slider_bottom_arrow_icon_size', function (value) {
        value.bind(function (newval) {
            $('.header-with-slider-wrapper .header-homepage-arrow').css('font-size', newval + 'px');
            $('.header-with-slider-wrapper .header-homepage-arrow > i').css('width', newval + 'px');
            $('.header-with-slider-wrapper .header-homepage-arrow > i').css('height', newval + 'px');
        });
    });

    wp.customize('slider_bottom_arrow_icon_bottom_offset', function (value) {
        value.bind(function (newval) {
            if (wp.customize('slider_overlap_header').get()) {
                var newOffset = parseInt(wp.customize('slider_overlap_header_with').get()) + parseInt(newval);
            }
            else {
                var newOffset = parseInt(newval);
            }
            $('.header-with-slider-wrapper .header-homepage-arrow').css('bottom', newOffset + 'px');
        });
    });

    wp.customize('slider_bottom_arrow_icon_color', function (value) {
        value.bind(function (newval) {
            $('.header-with-slider-wrapper .header-homepage-arrow > i').css('color', newval);
        });
    });

    wp.customize('slider_bottom_arrow_icon_background_color', function (value) {
        value.bind(function (newval) {
            $('.header-with-slider-wrapper .header-homepage-arrow').css('background', newval);
        });
    });

    /* END BOTTOM ARROW */

    /* START SLIDER OVERLAP */

    wp.customize('slider_overlap_header', function (value) {
        value.bind(function (newval) {

            setPaginationPositionAndMargin(wp.customize('slider_pagination_position').get());

            if (newval) {
                var newOffset = parseInt(wp.customize('slider_bottom_arrow_icon_bottom_offset').get()) + parseInt(wp.customize('slider_overlap_header_with').get());
            }
            $('.header-with-slider-wrapper .header-homepage-arrow').attr('style', 'bottom: ' + newOffset + 'px !important');

            if (newval) {
                $('body').addClass('overlap-first-section');
            }
            else {
                $('body').removeClass('overlap-first-section');
            }

            var currentOwlHeight = $('#header-slides-container .header-slide').eq(0).outerHeight();
            $('#header-slides-container .owl-stage-outer').css('height', currentOwlHeight);

        });
    });

    wp.customize('slider_overlap_header_with', function (value) {
        value.bind(function (newval) {

            var styleHolder = jQuery('[data-name="slider-overlap"]');

            styleHolder.text('' +
                '@media only screen and (min-width: 768px) {\n' +
                    '.mesmerize-front-page-with-slider.overlap-first-section .header-with-slider-wrapper .header-homepage {\n' +
                        'padding-bottom: ' + newval + ';\n' +
                    '}\n' +
                    '.mesmerize-front-page-with-slider.overlap-first-section .content {\n' +
                        'position: relative;\n' +
                        'z-index: 10;\n' +
                    '}\n' +
                    '.mesmerize-front-page-with-slider.overlap-first-section .page-content div[data-overlap]:first-of-type > div:not([class*="section-separator"]) {\n' +
                        'margin-top: -' + newval + ' !important;\n' +
                    '}\n' +
                    '.mesmerize-front-page-with-slider.overlap-first-section [data-overlap="true"]:first-of-type {\n' +
                        'padding-top: 0px;\n' +
                    '}\n' +
                    '.mesmerize-front-page-with-slider.overlap-first-section #customDots {\n' +
                        'bottom: + newval +;\n' +
                    '}\n' +
                '}');


            setPaginationPositionAndMargin(wp.customize('slider_pagination_position').get());

            if (wp.customize('slider_overlap_header').get()) {
                var newOffset = parseInt(wp.customize('slider_bottom_arrow_icon_bottom_offset').get()) + parseInt(newval);
            }
            $('.header-with-slider-wrapper .header-homepage-arrow').attr('style', 'bottom: ' + newOffset + 'px !important');

            var currentOwlHeight = $('#header-slides-container .header-slide').eq(0).outerHeight();
            $('#header-slides-container .owl-stage-outer').css('height', currentOwlHeight);

        });
    });

    /* END SLIDER OVERLAP */

})(jQuery);
