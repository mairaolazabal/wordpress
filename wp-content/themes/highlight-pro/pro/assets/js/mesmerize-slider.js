(function (window, settings) {

    window.wp = window.wp || {};

    // Fail gracefully in unsupported browsers.
    if (!('addEventListener' in window)) {
        return;
    }

    function toggleVideoElementState(iframe, state) {

        if (iframe.src.indexOf('vimeo.com') !== -1) {
            state = state.replace('Video', '').toLowerCase();
            iframe.contentWindow.postMessage('{"method":"' + state + '"}', '*')
        } else {
            iframe.contentWindow.postMessage('{"event":"command","func":"' + state + '","args":""}', '*')
        }


    }

    function prepareVideoInsideSlides(items) {

        if (wp && wp.customize) {
            return;
        }

        var videoElements = items.map(function (item, index) {
            return {
                slideNo: index,
                video: jQuery(item).find('.embed-container iframe').length ? jQuery(item).find('iframe')[0] : false
            }
        }).filter(function (item) {
            return item.video
        });

        videoElements.forEach(function (item) {
            var videoElement = item.video;
            var src = videoElement.getAttribute('src');
            if (src.indexOf('autoplay') !== -1) {

                if (item.slideNo !== 0) {
                    videoElement.setAttribute('src', src.replace('autoplay', '__CHANGED__') + '&enablejsapi=1&api=1');
                } else {
                    videoElement.setAttribute('src', src + '&enablejsapi=1&api=1');
                }
                jQuery(videoElement).attr('data-autoplay', 1);
                videoElement.playVideo = function () {
                    toggleVideoElementState(this, 'playVideo');
                };

                videoElement.pauseVideo = function () {
                    toggleVideoElementState(this, 'pauseVideo');
                };

            }


        });
    }

    function canUseMouseDrag() {

        if (wp && wp.customize) {
            return false;
        }

        return jQuery('html').hasClass('touch-enabled');
    }

    jQuery(document).ready(function () {

        var owl = jQuery('.owl-carousel');

        owl.owlCarousel({
            items: 1,
            loop: false,
            rewind: settings.slideRewind,
            autoplay: wp.customize ? false : settings.slideAutoplay,
            autoplayTimeout: settings.slideDuration,
            // autoplayHoverPause: true,
            // autoplaySpeed: settings.slideDuration,
            nav: settings.slideNavigation,
            navContainer: "#customNav",
            navElement: 'div  type="button" role="presentation"',
            navText: [
                "<i class='fa " + settings.slidePrevButtonIcon + " no-popup'></i>",
                "<i class='fa " + settings.slideNextButtonIcon + " no-popup'></i>"
            ],
            dots: true,
            dotsContainer: "#customDots",
            mouseDrag: canUseMouseDrag(),
            touchDrag: canUseMouseDrag(),
            animateOut: settings.slideAnimateOut,
            animateIn: settings.slideAnimateIn,

            autoHeight: true,
            onInitialize: function () {
                var duration = settings.slideDuration;
                jQuery(".slide-progress").css({
                    width: "100%",
                    transition: "width " + duration + "ms"
                });

            },
            onInitialized: function (event) {

                prepareVideoInsideSlides(this.items());

                jQuery('.owl-carousel .owl-stage').addClass('flexbox');

                if (settings.IEDetected) {
                    jQuery('.owl-carousel').addClass('ie-detected');
                }

                var upperHeight = jQuery('.header-top').outerHeight();
                var hasProgressBar = settings.slideProgressBar;
                var progressBarHeight = parseInt(settings.slideProgressBarHeight);
                var overlapWith = settings.slideOverlapWith;
                var sliderNavigation = jQuery('.header-slider-navigation');

                sliderNavigation.find('.owl-nav').append('<div class="owl-autoplay is-playing" data-play-icon="' + settings.slidePlayButtonIcon + '" data-pause-icon="' + settings.slidePauseButtonIcon + '"><i class="fa ' + settings.slidePauseButtonIcon + ' no-popup"></i></div>');
                sliderNavigation.find('.owl-nav .owl-autoplay').addClass(settings.slideAutoplayButtonStyle).hide();

                if (settings.slideNavigation) {
                    // if (!settings.slideGroupNavigation) {

                    var buttonProperties = {};
                    var screenSize = jQuery('.mesmerize-slider').width();
                    var buttonOffsetTop = settings.slideAutoplayButtonOffsetTop;
                    var buttonOffsetBottom = settings.slideAutoplayButtonOffsetBottom;
                    var autoplayButtonWidth = settings.slideAutoplayButtonSize;

                    switch (settings.slideAutoplayButtonPosition) {

                        case 'left top':
                            buttonProperties['top'] = hasProgressBar ? ((upperHeight + progressBarHeight) + 'px') : (upperHeight + 'px');
                            buttonProperties['left'] = '0px';
                            buttonProperties['margin-top'] = buttonOffsetTop + 'px';
                            break;

                        case 'center top':
                            buttonProperties['top'] = hasProgressBar ? ((upperHeight + progressBarHeight) + 'px') : (upperHeight + 'px');
                            buttonProperties['left'] = '50%';
                            buttonProperties['transform'] = 'translateX(-50%)';
                            buttonProperties['margin-top'] = buttonOffsetTop + 'px';
                            break;

                        case 'right top':
                            buttonProperties['top'] = hasProgressBar ? ((upperHeight + progressBarHeight) + 'px') : (upperHeight + 'px');
                            buttonProperties['right'] = '0px';
                            buttonProperties['margin-top'] = buttonOffsetTop + 'px';
                            break;

                        case 'left bottom':
                            buttonProperties['bottom'] = '0px';
                            buttonProperties['left'] = '0px';
                            buttonProperties['margin-bottom'] = buttonOffsetBottom + 'px';
                            break;

                        case 'center bottom':
                            buttonProperties['bottom'] = overlapWith + 'px';
                            buttonProperties['left'] = '50%';
                            buttonProperties['transform'] = 'translateX(-50%)';
                            buttonProperties['margin-left'] = '0px';
                            buttonProperties['margin-right'] = '0px';
                            buttonProperties['margin-bottom'] = buttonOffsetBottom + 'px';
                            break;

                        case 'right bottom':
                            buttonProperties['bottom'] = '0px';
                            buttonProperties['right'] = '0px';
                            buttonProperties['margin-bottom'] = buttonOffsetBottom + 'px';
                            break;
                    }
                    jQuery('.header-slider-navigation.separated .owl-nav .owl-autoplay').css(buttonProperties);
                    // }
                }

                if (settings.slideAutoplay) {

                    if (settings.sliderShowPlayPause) {
                        jQuery('.header-slider-navigation').find('.owl-nav > .owl-autoplay').show();
                    }


                }

                if (settings.slideNavigation) {
                    if (!settings.slidePrevNextButtons) {
                        jQuery('.header-slider-navigation').find('.owl-nav > .owl-prev').hide();
                        jQuery('.header-slider-navigation').find('.owl-nav > .owl-next').hide();
                    } else {
                        var buttonProperties = {};
                        buttonProperties['margin-top'] = settings.slidePrevNextButtonsOffsetCenter + 'px';

                        jQuery('.header-slider-navigation.separated .owl-nav .owl-prev,.header-slider-navigation.separated .owl-nav .owl-next').css(buttonProperties);
                    }

                    if (settings.slidePaginationPosition == 'top') {
                        paginationPosition = (hasProgressBar && settings.slideAutoplay) ? ((upperHeight + progressBarHeight) + 'px') : (upperHeight + 'px');
                        jQuery('.header-slider-navigation.separated .owl-dots').css('top', paginationPosition).css('bottom', 'unset');
                    }

                    if (!settings.slidePagination) {
                        jQuery('.header-slider-navigation').find('.owl-dots').hide();
                    }
                    jQuery('.header-slider-navigation.grouped').css('bottom', overlapWith + 'px');
                    jQuery('.header-slider-navigation .owl-dots').addClass(settings.slidePaginationShapesType);

                    jQuery('.header-slider-navigation .owl-nav .owl-prev').addClass(settings.slidePrevNextButtonsStyle);
                    jQuery('.header-slider-navigation .owl-nav .owl-next').addClass(settings.slidePrevNextButtonsStyle);

                    if (jQuery('.owl-carousel .owl-item').length <= 1) {
                        jQuery('.header-slider-navigation').hide();
                    }


                }

                if (jQuery('.slide-progress').css('top') == '0px') {
                    jQuery('.slide-progress').css('top', upperHeight + 'px');
                }
                if (!hasProgressBar || !settings.slideAutoplay) {
                    jQuery('.slide-progress').hide();
                }

                // homepage arrow recalculate offset
                if (settings.slideOverlappable) {
                    newArrowOffset = (parseInt(settings.slideBottomArrowOffset) + parseInt(overlapWith)) + 'px !important';
                    jQuery('.header-with-slider-wrapper .header-homepage-arrow').attr('style', 'bottom: ' + newArrowOffset);
                }


                jQuery('.header-slider-navigation').find('.owl-prev,.owl-next,.owl-dot').hover(
                    function () {
                      var owlInstance = owl.data('owl.carousel');
                      if (owlInstance && owlInstance.settings.autoplay) {
                          owlInstance._plugins.autoplay.pause();
                      }
                    },
                    function () {
                      var owlInstance = owl.data('owl.carousel');
                      if (owlInstance && owlInstance.settings.autoplay) {
                          owlInstance._plugins.autoplay.play();
                      }
                    }
                );

                jQuery('.header-slider-navigation').find('.owl-prev,.owl-next').on('click', function () {
                    owl.trigger('mesmerize.slider-nav.arrowClicked');
                });

                jQuery('.header-slider-navigation').find('.owl-dot').on('click', function () {
                    var jump = jQuery('.owl-dot').index(jQuery(this));
                    owl.trigger('mesmerize.slider-nav.dotClicked', jump);
                });

                jQuery(document).trigger('mesmerize-slide-focused');
            },
            onTranslate: function () {
                jQuery(".slide-progress").css({
                    width: 0,
                    transition: "width 0s"
                });
            },
            onTranslated: function () {
                var duration = settings.slideDuration - settings.slideAnimationDuration;
                jQuery(".slide-progress").css({
                    width: "100%",
                    transition: "width " + duration + "ms"
                });

                var autoplayVideoEl = this.items(this.current()).find('iframe[data-autoplay=1]')[0];

                if (autoplayVideoEl) {
                    autoplayVideoEl.playVideo();
                }
                jQuery(document).trigger('mesmerize-slide-focused');
            },

            onChange: function () {
                var autoplayVideoEl = this.items(this.current()).find('iframe[data-autoplay=1]')[0];

                if (autoplayVideoEl) {
                    autoplayVideoEl.pauseVideo();
                }
            }
        });

        jQuery('.owl-autoplay').on('click', function () {


            if (jQuery(this).hasClass('is-playing')) {


                jQuery('.owl-autoplay > i').removeClass(jQuery(this).attr('data-pause-icon')).addClass(jQuery(this).attr('data-play-icon'));
                jQuery('.owl-autoplay').removeClass('is-playing');

                if (wp && wp.customize) {
                    return;
                }

                owl.trigger('stop.owl.autoplay');
                owl.data('owl.carousel').settings.autoplay = false;

            }
            else {

                jQuery('.owl-autoplay > i').removeClass(jQuery(this).attr('data-play-icon')).addClass(jQuery(this).attr('data-pause-icon'));
                jQuery('.owl-autoplay').addClass('is-playing');

                if (wp && wp.customize) {
                    return;
                }
                owl.trigger('play.owl.autoplay', [settings.slideDuration]);
                owl.data('owl.carousel').settings.autoplay = true;

            }

        });

        jQuery(window).on('resize', function () {

            var upperHeight = jQuery('.header-top').outerHeight();
            if (settings.slideNavigation && settings.slideGroupNavigation) {
                if (jQuery('.header-slider-navigation.grouped').is('.nlt, .nct, .nrt')) {
                    jQuery('.header-slider-navigation.grouped').css('top', upperHeight + 'px');
                }
            }
            jQuery('.slide-progress').css('top', upperHeight + 'px');

        });

        setTimeout(function() {

            if(jQuery('#header-slides-container .slide-title span[data-text-effect]').length) {

                jQuery('#header-slides-container .slide-title span[data-text-effect]').each(function() {

                    var $container = jQuery(this).parent().parent();
                    var clone = jQuery(this).parent().clone();
                    var texts = clone.find('span[data-text-effect]').attr('data-text-effect').replace(/"/g, '').replace('[', '').replace(']', '').split(',');
                    var newHeight = 0;
                    var clone1 = clone.clone();
                    clone1.find('span[data-text-effect]').html(texts[0]);
                    var clone2 = clone.clone();
                    clone2.find('span[data-text-effect]').html(texts[1]);

                    $container.append(clone1).append(clone2);
                    newHeight = (clone1.height() > clone2.outerHeight()) ? clone1.height() : clone2.height();

                    clone.remove();
                    clone1.remove();
                    clone2.remove();

                    $container.find('.slide-title').css('height', newHeight + 'px');

                });

            }

        }, 50);


    });

})(window, window._sliderSettings || {});
