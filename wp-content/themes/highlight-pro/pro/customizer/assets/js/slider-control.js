wp.customize.controlConstructor['header-slider'] = wp.customize.Control.extend({
    ready: function () {

        var control = this;
        var slideLabel = this.params.slide_label;
        var sliderID = this.params.id;
        var sliderContainer = jQuery('#customize-control-' + sliderID + '>.slider-fields');
        var defaultSlide = this.params.choices.default_slide;
        var slides = this.params.value;
        var limit = this.params.limit;
        var activeSlidesNumber = 0;
        var overlayShapes = this.params.overlay_shapes;
        var overlayShapesValues = this.params.overlay_shapes_values;

        //if (wp.customize('header_type').get() == 'slider') {


        function bindSlideChangeFromCanvas() {
            var owlWrapper = CP_Customizer.preview.find('#header-slides-container'),
                owlInstance = owlWrapper.data('owl.carousel');

            if (!owlInstance) {
                return;
            }

            owlWrapper.on('mesmerize.slider-nav.arrowClicked', function () {
                var owlInstance = CP_Customizer.preview.jQuery(this).data('owl.carousel');
                focusSlide(owlInstance.current(), owlInstance.settings.autoplayTimeout);
            });

            owlWrapper.on('mesmerize.slider-nav.dotClicked', function (fEvent, jump) {
               var owlInstance = CP_Customizer.preview.jQuery(this).data('owl.carousel');
               focusSlide(jump, owlInstance.settings.autoplayTimeout);
            });

        }

        function moveSliderTo(jumpTo, speed) {
            var owlInstance = CP_Customizer.preview.find('#header-slides-container').data('owl.carousel');


            if (!owlInstance) {
                return;
            }

            owlInstance.settings.autoplay = false;
            owlInstance.to(jumpTo, speed || 0);
        }

        function focusSlide(jumpTo, speed) {
            var slideControl = control.container.find('.slider-fields').children().eq(jumpTo);
            slideControl.removeClass('minimized');
            slideControl.find('.slide-content').show();
            slideControl.siblings().addClass('minimized');
            moveSliderTo(jumpTo, speed || 0);
        }

        jQuery(document).ready(function () {

            jQuery.each(slides, function (key, slide) {

                var slideContent = generateSlideContent(slideLabel, key, activeSlidesNumber);

                sliderContainer.append(slideContent);
                addSlideControls(slideContent.find('.slide-content'), slide, key, activeSlidesNumber, true);
                activeSlidesNumber++;

            });

            if (activeSlidesNumber == 0) {

                var newSlideID = (new Date()).getTime().toString();
                var newSlide = jQuery.extend({slide_id: newSlideID}, defaultSlide);
                var slideContent = generateSlideContent(slideLabel, newSlideID, activeSlidesNumber);

                sliderContainer.append(slideContent);
                addSlideControls(slideContent.find('.slide-content'), newSlide, newSlideID, activeSlidesNumber, false);
                activeSlidesNumber++;

                var newSlideID = (new Date()).getTime().toString();
                var newSlide = jQuery.extend({slide_id: newSlideID}, defaultSlide);
                var slideContent = generateSlideContent(slideLabel, newSlideID, activeSlidesNumber);

                sliderContainer.append(slideContent);
                addSlideControls(slideContent.find('.slide-content'), newSlide, newSlideID, activeSlidesNumber, false);
                activeSlidesNumber++;

            }

            if (activeSlidesNumber >= limit) {
                sliderContainer.parent().find('.button-secondary.slider-add').hide();
            }

            // go to opened slide after previewer refreshes
            CP_Customizer.bind(CP_Customizer.events.PREVIEW_LOADED, function () {
                var maximizedSlide = sliderContainer.find('.slider-row:not(.minimized)');

                if (maximizedSlide.length) {
                    var jumpTo = jQuery('.slider-row').index(maximizedSlide);
                    moveSliderTo(jumpTo);
                } else {
                    focusSlide(0);
                }

                bindSlideChangeFromCanvas();
            });

            // reorder slides
            sliderContainer.sortable({
                axis: "y",
                handle: ".reorder-handler",
                cancel: ".slider-row:not(.minimized)",
                update: function (event, ui) {

                    var aux = {};
                    var auxSlides = CP_Customizer.utils.deepClone(control.setting.get());

                    sliderContainer.children().each(function (index) {
                        var cid = jQuery(this).attr('data-id');
                        var nid = (Math.floor((new Date()).getTime() / 10) * 10 + index).toString();
                        aux[nid] = auxSlides[cid];
                        aux[nid].slide_id = nid;
                        jQuery(this).attr('data-id', nid);
                    });

                    control.setting.set(aux);
                    slides = aux;
                    CP_Customizer.preview.refresh();

                    //refresh slides ui and slides controls
                    sliderContainer.html('');
                    activeSlidesNumber = 0;

                    jQuery.each(slides, function (key, slide) {
                        var slideContent = generateSlideContent(slideLabel, key, activeSlidesNumber);
                        sliderContainer.append(slideContent);
                        addSlideControls(slideContent.find('.slide-content'), slide, key, activeSlidesNumber, true);
                        activeSlidesNumber++;
                    });

                }
            });

        });

        //}


        // toggle slide details
        sliderContainer.on('click', '.slider-row > .slider-row-header', function () {

            var maximize;

            if (jQuery(this).parent().hasClass('minimized')) {
                maximize = 1;

            } else {
                maximize = 0;
            }


            sliderContainer.find('.slider-row').addClass('minimized').find('.slider-row-header > .dashicons').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
            sliderContainer.find('.slide-content').hide();

            var jumpTo = jQuery('.slider-row > .slider-row-header').index(this);

            if (maximize === 1) {
                jQuery(this).parent().removeClass('minimized').find('.slider-row-header > .dashicons').addClass('dashicons-arrow-up').removeClass('dashicons-arrow-down');
                jQuery(this).parent().find('.slide-content').show();

                CP_Customizer.preview.blur(true);
                moveSliderTo(jumpTo);
                // if (wp.customize('slider_enable_autoplay').get()) {
                //     CP_Customizer.preview.find('#header-slides-container').trigger('stop.owl.autoplay');
                // }
            }
            else {
                // if (wp.customize('slider_enable_autoplay').get()) {
                //     CP_Customizer.preview.find('#header-slides-container').trigger('play.owl.autoplay');
                // }
            }

            jQuery(this).parent().parent().find('.customizer-right-section').removeClass('active');

        });

        // add new slide
        sliderContainer.parent().on('click', '.button-secondary.slider-add', function (e) {
            e.preventDefault();

            var newSlideID = (new Date()).getTime().toString();
            var newSlide = jQuery.extend({slide_id: newSlideID}, defaultSlide);
            var slideContent = generateSlideContent(slideLabel, newSlideID, activeSlidesNumber);

            sliderContainer.append(slideContent);
            addSlideControls(slideContent.find('.slide-content'), newSlide, newSlideID, activeSlidesNumber, false);
            slides[newSlideID] = newSlide;
            activeSlidesNumber++;

            if (activeSlidesNumber >= limit) {
                sliderContainer.parent().find('.button-secondary.slider-add').hide();
            }

            CP_Customizer.one(CP_Customizer.events.PREVIEW_LOADED, function () {
                focusSlide(activeSlidesNumber - 1);
            });

            return false;

        });

        // duplicate slide
        sliderContainer.parent().on('click', '.slider-row > .slider-row-actions > .slider-row-duplicate', function (e) {
            e.preventDefault();

            if (activeSlidesNumber < limit) {

                var slides = CP_Customizer.getMod('slider_elements');
                var slideContainer = jQuery(this).closest('[data-field="slider_elements"]');
                var id = slideContainer.attr('data-id');

                var newSlideID = (new Date()).getTime().toString();
                var newSlide = jQuery.extend({slide_id: newSlideID}, slides[id]);
                newSlide['slide_id'] = newSlideID;
                var slideContent = generateSlideContent(slideLabel, newSlideID, activeSlidesNumber);

                sliderContainer.append(slideContent);
                addSlideControls(slideContent.find('.slide-content'), newSlide, newSlideID, activeSlidesNumber, false);
                slides[newSlideID] = newSlide;
                activeSlidesNumber++;

                if (activeSlidesNumber >= limit) {
                    sliderContainer.parent().find('.button-secondary.slider-add').hide();
                }

                CP_Customizer.one(CP_Customizer.events.PREVIEW_LOADED, function () {
                    focusSlide(activeSlidesNumber - 1);
                });

            }
            else {

                jQuery(this).parent().parent().after('<p class="description slider-notification">' + CP_Customizer.translateCompanionString('You cannot duplicate the slide because you have reached your slides limit!') + '</p>');
                setTimeout(function () {
                    jQuery('.slider-notification').fadeOut(1000, function () {
                        jQuery(this).remove();
                    });
                }, 10000);

            }

            return false;

        });

        // delete slide
        sliderContainer.on('click', '.slider-row > .slider-row-actions > .slider-row-remove', function (e) {

            if (activeSlidesNumber > 1) {

                var slideContainer = jQuery(this).closest('[data-field="slider_elements"]');
                var id = slideContainer.attr('data-id');
                var aux = {};
                var currentSlides = CP_Customizer.utils.deepClone(control.setting.get());
                var slideIndex = 0;
                var found = false;

                jQuery.each(currentSlides, function (ckey, cslide) {
                    if (ckey != id) {
                        aux[ckey] = cslide;
                        if (!found) slideIndex++;
                    }
                    else {
                        found = true;
                    }
                });

                control.setting.set(aux);
                slides = aux;
                slideContainer.remove();
                activeSlidesNumber--;

                CP_Customizer.preview.find('#header-slides-container').trigger('remove.owl.carousel', slideIndex).trigger('refresh.owl.carousel');

                reorderSlidesNumbers();

                if (activeSlidesNumber < limit) {
                    sliderContainer.parent().find('.button-secondary.slider-add').show();
                }

                if (activeSlidesNumber <= 1) {
                    CP_Customizer.preview.find('.header-slider-navigation').hide();
                }

            }
            else {

                jQuery(this).parent().parent().after('<p class="description slider-notification">' + CP_Customizer.translateCompanionString('The slider must have at least one element!') + '</p>');
                setTimeout(function () {
                    jQuery('.slider-notification').fadeOut(1000, function () {
                        jQuery(this).remove();
                    });
                }, 7000);

            }

        });

        function addSlideControls($container, slide, slideID, index, edit) {

            if (!edit) {
                var currentSlides = CP_Customizer.utils.deepClone(CP_Customizer.getMod('slider_elements'));
                currentSlides[slideID] = CP_Customizer.utils.deepClone(slide);
                CP_Customizer.setMod('slider_elements', currentSlides, 'refresh');
            }

            var slideBackgroundSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_background_separator_' + slideID,
                $container,
                CP_Customizer.translateCompanionString('Slide') + ' ' + (index + 1) + ' ' + CP_Customizer.translateCompanionString('Background')
            );

            /*--------> START SLIDE BACKGROUND TYPE CONTROLS AND FUNCTIONS <--------------*/

            var slideBackgroundType = CP_Customizer.createControl.select(
                'slide_background_type_' + slideID,
                $container,
                {
                    value: slide.slide_background_type,
                    label: CP_Customizer.translateCompanionString('Background Type'),
                    choices: {
                        'color': CP_Customizer.translateCompanionString('Color'),
                        'image': CP_Customizer.translateCompanionString('Image'),
                        'video': CP_Customizer.translateCompanionString('Video'),
                        'gradient': CP_Customizer.translateCompanionString('Gradient')
                    }
                }
            );

            var slideBackgroundOptionsGroupButton = CP_Customizer.createControl.buttonGroup(
                'slide_background_options_group_button_' + slideID,
                $container,
                {
                    label: CP_Customizer.translateCompanionString('Options'),
                    description: 'Options',
                    in_row_with: ['slide_background_type_' + slideID],
                    choices: null
                }
            );

            var $slideBackgroundOptionsContainer = $container.find('div#slide_background_options_group_button_' + slideID + '-popup > .section-settings-container');

            // mimic in_row_with
            slideBackgroundOptionsGroupButton.control.container.css({
                "width": "40%",
                "clear": "right",
                "float": "right",
            });
            slideBackgroundType.control.container.css({
                "width": "auto",
                "max-width": "calc(60% - 12px)"
            });


            // Object for sidebar callback
            var backgroundOptionsSidebarDependencies = {
                color: [],
                image: [],
                video: [],
                gradient: []
            }


            // Color Background Controls

            var slideBackgroundColorOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_background_color_options_separator_' + slideID,
                $slideBackgroundOptionsContainer,
                CP_Customizer.translateCompanionString('Color Background Options')
            );
            backgroundOptionsSidebarDependencies.color.push('slideBackgroundColorOptionsSeparator');

            var slideBackgroundColor = CP_Customizer.createControl.color(
                'slide_background_color_' + slideID,
                $slideBackgroundOptionsContainer,
                {
                    value: slide.slide_background_color,
                    label: CP_Customizer.translateCompanionString('Background Color')
                }
            );
            backgroundOptionsSidebarDependencies.color.push('slideBackgroundColor');


            // Image Background Controls

            var slideBackgroundImageOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_background_image_options_separator_' + slideID,
                $slideBackgroundOptionsContainer,
                CP_Customizer.translateCompanionString('Image Background Options')
            );
            backgroundOptionsSidebarDependencies.image.push('slideBackgroundImageOptionsSeparator');

            var slideBackgroundImage = CP_Customizer.createControl.image(
                'slide_background_image_' + slideID,
                $slideBackgroundOptionsContainer,
                {
                    value: slide.slide_background_image,
                    label: CP_Customizer.translateCompanionString('Background Image'),
                    url: slide.slide_background_image,
                }
            );
            backgroundOptionsSidebarDependencies.image.push('slideBackgroundImage');

            var slideBackgroundImageSize = CP_Customizer.createControl.select(
                'slide_background_image_size_' + slideID,
                $slideBackgroundOptionsContainer,
                {
                    value: slide.slide_background_image_size,
                    label: CP_Customizer.translateCompanionString('Background Image Size'),
                    choices: {
                        'auto': CP_Customizer.translateCompanionString('Auto'),
                        'cover': CP_Customizer.translateCompanionString('Cover')
                    }
                }
            );
            backgroundOptionsSidebarDependencies.image.push('slideBackgroundImageSize');

            var slideBackgroundImagePosition = CP_Customizer.createControl.select(
                'slide_background_image_position_' + slideID,
                $slideBackgroundOptionsContainer,
                {
                    value: slide.slide_background_image_position,
                    label: CP_Customizer.translateCompanionString('Background Image Position'),
                    choices: {
                        'left top'     : CP_Customizer.translateCompanionString('left top'),
                        'left center'  : CP_Customizer.translateCompanionString('left center'),
                        'left bottom'  : CP_Customizer.translateCompanionString('left bottom'),
                        'center top'   : CP_Customizer.translateCompanionString('center top'),
                        'center center': CP_Customizer.translateCompanionString('center center'),
                        'center bottom': CP_Customizer.translateCompanionString('center bottom'),
                        'right top'    : CP_Customizer.translateCompanionString('right top'),
                        'right center' : CP_Customizer.translateCompanionString('right center'),
                        'right bottom' : CP_Customizer.translateCompanionString('right bottom'),
                    }
                }
            );
            backgroundOptionsSidebarDependencies.image.push('slideBackgroundImagePosition');


            // Video Background Controls

            var slideBackgroundVideoOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_background_video_options_separator_' + slideID,
                $slideBackgroundOptionsContainer,
                CP_Customizer.translateCompanionString('Video Background Options')
            );
            backgroundOptionsSidebarDependencies.video.push('slideBackgroundVideoOptionsSeparator');

            var slideBackgroundVideo = CP_Customizer.createControl.video(
                'slide_background_video_' + slideID,
                $slideBackgroundOptionsContainer,
                {
                    value: slide.slide_background_video,
                    label: CP_Customizer.translateCompanionString('Self hosted video (MP4)'),
                    mime_type: 'video',
                }
            );
            backgroundOptionsSidebarDependencies.video.push('slideBackgroundVideo');

            var slideBackgroundVideoExternal = CP_Customizer.createControl.generic(
                'slide_background_video_external_' + slideID,
                $slideBackgroundOptionsContainer,
                {
                    value: slide.slide_background_video_external,
                    label: CP_Customizer.translateCompanionString('External Video'),
                }
            );
            backgroundOptionsSidebarDependencies.video.push('slideBackgroundVideoExternal');

            var slideBackgroundVideoPoster = CP_Customizer.createControl.image(
                'slide_background_video_poster_' + slideID,
                $slideBackgroundOptionsContainer,
                {
                    value: slide.slide_background_video_poster,
                    label: CP_Customizer.translateCompanionString('Video Poster'),
                    url: slide.slide_background_video_poster,
                }
            );
            backgroundOptionsSidebarDependencies.video.push('slideBackgroundVideoPoster');


            // Gradient Background Controls

            var slideBackgroundGradientOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_background_gradient_options_separator_' + slideID,
                $slideBackgroundOptionsContainer,
                CP_Customizer.translateCompanionString('Gradient Background Options')
            );
            backgroundOptionsSidebarDependencies.gradient.push('slideBackgroundGradientOptionsSeparator');

            var slideBackgroundGradient = CP_Customizer.createControl.gradient(
                'slide_background_gradient_' + slideID,
                $slideBackgroundOptionsContainer,
                {
                    value: slide.slide_background_gradient,
                    label: CP_Customizer.translateCompanionString('Background Gradient')
                }
            );
            backgroundOptionsSidebarDependencies.gradient.push('slideBackgroundGradient');


            // Initially show only the controls for the selected background type

            $slideBackgroundOptionsContainer.children().each(function () {
                jQuery(this).hide();
            });
            jQuery.each(backgroundOptionsSidebarDependencies[slideBackgroundType.control.setting.get()], function (controlIndex, controlName) {
                eval(controlName).show();
            });

            // Background type setters

            slideBackgroundType.attachWithSetter(slide.slide_background_type, function (newValue, oldValue) {
                // hide sidebar former active fields and show only the fields attached to the current background type
                jQuery.each(backgroundOptionsSidebarDependencies[oldValue], function (controlIndex, controlName) {
                    eval(controlName).hide();
                });
                jQuery.each(backgroundOptionsSidebarDependencies[newValue], function (controlIndex, controlName) {
                    eval(controlName).show();
                });

                // slide.slide_background_type = newValue;
                setSlideValue(slideID, 'slide_background_type', newValue, true);
            });
            slideBackgroundColor.attachWithSetter(slide.slide_background_color, function (value) {
                // slide.slide_background_color = value;
                setSlideValue(slideID, 'slide_background_color', value);
                addSlideJSVars(slideID, '', 'background', 'all', value);
            });
            slideBackgroundImage.attachWithSetter(slide.slide_background_image, function (value) {
                // slide.slide_background_image = value;
                setSlideValue(slideID, 'slide_background_image', value);
                addSlideJSVars(slideID, '', 'background-image', 'all', 'url("' + value + '")!important');
            });
            slideBackgroundImageSize.attachWithSetter(slide.slide_background_image_size, function (value) {
                // slide.slide_background_image_size = value;
                setSlideValue(slideID, 'slide_background_image_size', value);
                addSlideJSVars(slideID, '', 'background-size', 'all', value);
            });
            slideBackgroundImagePosition.attachWithSetter(slide.slide_background_image_position, function (value) {
                // slide.slide_background_image_position = value;
                setSlideValue(slideID, 'slide_background_image_position', value);
                addSlideJSVars(slideID, '', 'background-position', 'all', value);
            });
            slideBackgroundVideo.attachWithSetter(slide.slide_background_video, function (value) {
                // slide.slide_background_video = value.id;
                setSlideValue(slideID, 'slide_background_video', value.id, true);
            });
            slideBackgroundVideoExternal.attachWithSetter(slide.slide_background_video_external, function (value) {
                // slide.slide_background_video_external = value;
                setSlideValue(slideID, 'slide_background_video_external', value, true);
            });
            slideBackgroundVideoPoster.attachWithSetter(slide.slide_background_video_poster, function (value) {
                // slide.slide_background_video_poster = value;
                setSlideValue(slideID, 'slide_background_video_poster', value);
                addSlideJSVars(slideID, ' .cp-video-bg', 'background-image', 'all', 'url("' + value + '")');
            });
            slideBackgroundGradient.attachWithSetter(slide.slide_background_gradient, function (newValue, oldValue) {
                // slide.slide_background_gradient = newValue;
                setSlideValue(slideID, 'slide_background_gradient', newValue);
                CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).removeClass(oldValue).addClass(newValue);
            });

            /*--------> END SLIDE BACKGROUND TYPE CONTROLS AND FUNCTIONS <----------------*/

            /*--------> START SLIDE OVERLAY CONTROLS AND FUNCTIONS <----------------------*/

            var slideShowOverlay = CP_Customizer.createControl.checkbox(
                'slide_show_overlay_' + slideID,
                $container,
                CP_Customizer.translateCompanionString('Show overlay')
            );

            var slideOverlayOptionsGroupButton = CP_Customizer.createControl.buttonGroup(
                'slide_overlay_options_group_button_' + slideID,
                $container,
                {
                    label: CP_Customizer.translateCompanionString('Options'),
                    in_row_with: ['slide_show_overlay_' + slideID],
                    choices: null
                }
            );
            var $slideOverlayOptionsContainer = $container.find('div#slide_overlay_options_group_button_' + slideID + '-popup > .section-settings-container');

            // mimic in_row_with for slideOverlayOptionsGroupButton
            slideOverlayOptionsGroupButton.control.container.css({
                "width": "40%",
                "clear": "right",
                "float": "right",
            });
            slideShowOverlay.control.container.css({
                "width": "auto",
                "max-width": "calc(60% - 12px)"
            });

            if (slide.slide_show_overlay === false) {
                slideOverlayOptionsGroupButton.hide();
            }

            var slideOverlayOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_overlay_options_separator_' + slideID,
                $slideOverlayOptionsContainer,
                CP_Customizer.translateCompanionString('Overlay Options')
            );

            var slideOverlayType = CP_Customizer.createControl.select(
                'slide_overlay_type_' + slideID,
                $slideOverlayOptionsContainer,
                {
                    value: slide.slide_overlay_type,
                    label: CP_Customizer.translateCompanionString('Overlay Type'),
                    choices: {
                        'none': CP_Customizer.translateCompanionString('Shape Only'),
                        'color': CP_Customizer.translateCompanionString('Color'),
                        'gradient': CP_Customizer.translateCompanionString('Gradient')
                    }
                }
            );


            // Object for sidebar callback
            var overlayOptionsSidebarDependencies = {
                none: ['slideOverlayOptionsSeparator', 'slideOverlayType'],
                color: ['slideOverlayOptionsSeparator', 'slideOverlayType'],
                gradient: ['slideOverlayOptionsSeparator', 'slideOverlayType']
            }


            // Color+Gradient Overlay Controls

            var slideOverlayColor = CP_Customizer.createControl.color(
                'slide_overlay_color_' + slideID,
                $slideOverlayOptionsContainer,
                {
                    value: slide.slide_overlay_color,
                    label: CP_Customizer.translateCompanionString('Overlay Color'),
                    alpha: false
                }
            );
            overlayOptionsSidebarDependencies.color.push('slideOverlayColor');

            var slideOverlayGradient = CP_Customizer.createControl.gradientPro(
                'slide_overlay_gradient_' + slideID,
                $slideOverlayOptionsContainer,
                {
                    value: slide.slide_overlay_gradient,
                    label: CP_Customizer.translateCompanionString('Overlay Gradient'),
                    choices: {
                        opacity: 0.8
                    },
                }
            );
            overlayOptionsSidebarDependencies.gradient.push('slideOverlayGradient');
            // to add gradient pro here and remove opacity from this

            var slideOverlayOpacity = CP_Customizer.createControl.slider(
                'slide_overlay_opacity_' + slideID,
                $slideOverlayOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Overlay Opacity'),
                    choices: {
                        min: 0,
                        max: 1,
                        step: 0.01
                    },
                    default: slide.slide_overlay_opacity,
                }
            );
            overlayOptionsSidebarDependencies.color.push('slideOverlayOpacity');


            // Shapes Overlay Controls

            var slideOverlayShape = CP_Customizer.createControl.select(
                'slide_overlay_shape_' + slideID,
                $slideOverlayOptionsContainer,
                {
                    value: slide.slide_overlay_shape,
                    label: CP_Customizer.translateCompanionString('Overlay Shapes'),
                    choices: overlayShapes
                }
            );
            overlayOptionsSidebarDependencies.none.push('slideOverlayShape');
            overlayOptionsSidebarDependencies.color.push('slideOverlayShape');
            overlayOptionsSidebarDependencies.gradient.push('slideOverlayShape');

            var slideOverlayShapeLight = CP_Customizer.createControl.slider(
                'slide_overlay_shape_light_' + slideID,
                $slideOverlayOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Shape Light'),
                    choices: {
                        min: 0,
                        max: 100,
                        step: 1
                    },
                    default: slide.slide_overlay_shape_light,
                }
            );
            overlayOptionsSidebarDependencies.none.push('slideOverlayShapeLight');
            overlayOptionsSidebarDependencies.color.push('slideOverlayShapeLight');
            overlayOptionsSidebarDependencies.gradient.push('slideOverlayShapeLight');


            // Initially show only the controls for the selected overlay type

            $slideOverlayOptionsContainer.children().each(function () {
                jQuery(this).hide();
            });
            jQuery.each(overlayOptionsSidebarDependencies[slideOverlayType.control.setting.get()], function (controlIndex, controlName) {
                eval(controlName).show();
            });

            // hide shape light if no shape is selected
            if (slideOverlayShape.control.setting.get() == 'none') slideOverlayShapeLight.hide();

            // Overlay setters

            slideShowOverlay.attachWithSetter(slide.slide_show_overlay, function (value) {
                if (value === true) slideOverlayOptionsGroupButton.show();
                else slideOverlayOptionsGroupButton.hide();

                // slide.slide_show_overlay = value;
                setSlideValue(slideID, 'slide_show_overlay', value, true);
            });
            slideOverlayType.attachWithSetter(slide.slide_overlay_type, function (newValue, oldValue) {
                // hide sidebar former active fields and show only the fields attached to the current background type
                jQuery.each(overlayOptionsSidebarDependencies[oldValue], function (controlIndex, controlName) {
                    eval(controlName).hide();
                });
                jQuery.each(overlayOptionsSidebarDependencies[newValue], function (controlIndex, controlName) {
                    eval(controlName).show();
                });

                if (slideOverlayShape.control.setting.get() == 'none') slideOverlayShapeLight.hide();

                // slide.slide_overlay_type = newValue;
                setSlideValue(slideID, 'slide_overlay_type', newValue, true);
            });
            slideOverlayColor.attachWithSetter(slide.slide_overlay_color, function (value) {
                // slide.slide_overlay_color = value;
                setSlideValue(slideID, 'slide_overlay_color', value);
                addSlideJSVars(slideID, '.color-overlay:before', 'background', 'all', value);
            });
            slideOverlayGradient.attachWithSetter(slide.slide_overlay_gradient, function (value) {
                CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID + ' .background-overlay').css("background-image", getSlideGradientValue(slideOverlayGradient.control.getValue(value)));

                // slide.slide_overlay_gradient = value;
                setSlideValue(slideID, 'slide_overlay_gradient', value);
            });
            slideOverlayOpacity.attachWithSetter(slide.slide_overlay_opacity, function (value) {
                // slide.slide_overlay_opacity = value;
                setSlideValue(slideID, 'slide_overlay_opacity', value);
                addSlideJSVars(slideID, '.color-overlay:before', 'opacity', 'all', value);
            });
            slideOverlayShape.attachWithSetter(slide.slide_overlay_shape, function (value) {
                if (value == 'none') slideOverlayShapeLight.hide();
                else slideOverlayShapeLight.show();

                // slide.slide_overlay_shape = value;
                setSlideValue(slideID, 'slide_overlay_shape', value);
                addSlideJSVars(slideID, '.color-overlay:after', 'background', 'all', overlayShapesValues[value] + ' !important');
            });
            slideOverlayShapeLight.attachWithSetter(slide.slide_overlay_shape_light, function (value) {
                // slide.slide_overlay_shape_light = value;
                setSlideValue(slideID, 'slide_overlay_shape_light', value);
                addSlideJSVars(slideID, '.color-overlay:after', 'filter', 'all', 'invert(' + value + '%)');
            });

            /*--------> END SLIDE OVERLAY CONTROLS AND FUNCTIONS <------------------------*/

            var slideContentSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_content_separator_' + slideID,
                $container,
                CP_Customizer.translateCompanionString('Slide') + ' ' + (index + 1) + ' ' + CP_Customizer.translateCompanionString('Content')
            );

            /*--------> START SLIDE CONTENT OPTIONS CONTROLS AND FUNCTIONS <---------------*/

            var slideContentLayout = CP_Customizer.createControl.select(
                'slide_content_layout_' + slideID,
                $container,
                {
                    value: slide.slide_content_layout,
                    label: CP_Customizer.translateCompanionString('Content Layout'),
                    choices: {
                        'content-on-center': CP_Customizer.translateCompanionString('Text on center'),
                        'content-on-right': CP_Customizer.translateCompanionString('Text on right'),
                        'content-on-left': CP_Customizer.translateCompanionString('Text on left'),
                        'media-on-left': CP_Customizer.translateCompanionString('Text with media on left'),
                        'media-on-right': CP_Customizer.translateCompanionString('Text with media on right'),
                        'media-on-top': CP_Customizer.translateCompanionString('Text with media above'),
                        'media-on-bottom': CP_Customizer.translateCompanionString('Text with media below')
                    }
                }
            );

            var slideContentMediaType = CP_Customizer.createControl.select(
                'slide_content_media_type_' + slideID,
                $container,
                {
                    value: slide.slide_content_media_type,
                    label: CP_Customizer.translateCompanionString('Media Type'),
                    choices: {
                        'image': CP_Customizer.translateCompanionString('Image'),
                        'video': CP_Customizer.translateCompanionString('Video'),
                        'video_popup': CP_Customizer.translateCompanionString('Video Popup Button')
                    }
                }
            );

            var slideContentSpacing = CP_Customizer.createControl.spacing(
                'slide_content_spacing_' + slideID,
                $container,
                {
                    label: CP_Customizer.translateCompanionString('Content Spacing'),
                    sides: ['top', 'bottom']
                }
            );

            var slideMobileContentSpacing = CP_Customizer.createControl.spacing(
                'slide_mobile_content_spacing_' + slideID,
                $container,
                {
                    label: CP_Customizer.translateCompanionString('Mobile Content Spacing'),
                    sides: ['top', 'bottom']
                }
            );

            // Text Box Settings Controls

            var slideTextBoxSettingsGroupButton = CP_Customizer.createControl.buttonGroup(
                'slide_text_box_settings_group_button_' + slideID,
                $container,
                {
                    label: CP_Customizer.translateCompanionString('Text box settings'),
                    choices: null
                }
            );
            var $slideTextBoxSettingsContainer = $container.find('div#slide_text_box_settings_group_button_' + slideID + '-popup > .section-settings-container');

            var slideTextBoxSettingsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_text_box_settings_separator_' + slideID,
                $slideTextBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Text Box Settings')
            );

            var slideTextBoxSettingsTextAlign = CP_Customizer.createControl.radioButtonset(
                'slide_text_box_settings_text_align_' + slideID,
                $slideTextBoxSettingsContainer,
                {
                    value: slide.slide_text_box_settings_text_align,
                    label: CP_Customizer.translateCompanionString('Text Align'),
                    choices: {
                        'left': CP_Customizer.translateCompanionString('Left'),
                        'center': CP_Customizer.translateCompanionString('Center'),
                        'right': CP_Customizer.translateCompanionString('Right')
                    }
                }
            );

            var slideTextBoxSettingsTextWidth = CP_Customizer.createControl.slider(
                'slide_text_box_settings_text_width_' + slideID,
                $slideTextBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Text Width'),
                    choices: {
                        min: 0,
                        max: 100,
                        step: 1
                    },
                    default: slide.slide_text_box_settings_text_width,
                }
            );

            var slideTextBoxSettingsTextVerticalAlign = CP_Customizer.createControl.select(
                'slide_text_box_settings_text_vertical_align_' + slideID,
                $slideTextBoxSettingsContainer,
                {
                    value: slide.slide_text_box_settings_text_vertical_align,
                    label: CP_Customizer.translateCompanionString('Text Vertical Align'),
                    choices: {
                        'top-sm': CP_Customizer.translateCompanionString('Top'),
                        'middle-sm': CP_Customizer.translateCompanionString('Middle'),
                        'bottom-sm': CP_Customizer.translateCompanionString('Bottom')
                    }
                }
            );

            var slideTextBoxSettingsBackgroundOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_text_box_settings_background_options_separator_' + slideID,
                $slideTextBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Text Box Background Options')
            );

            var slideTextBoxSettingsBackgroundColor = CP_Customizer.createControl.color(
                'slide_text_box_settings_background_color_' + slideID,
                $slideTextBoxSettingsContainer,
                {
                    value: slide.slide_text_box_settings_background_color,
                    label: CP_Customizer.translateCompanionString('Background Color')
                }
            );

            var slideTextBoxSettingsBackgroundSpacing = CP_Customizer.createControl.spacing(
                'slide_text_box_settings_background_spacing_' + slideID,
                $slideTextBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Background Spacing'),
                    sides: ['top', 'bottom', 'left', 'right']
                }
            );

            var slideTextBoxSettingsBackgroundBorderRadius = CP_Customizer.createControl.number(
                'slide_text_box_settings_background_border_radius_' + slideID,
                $slideTextBoxSettingsContainer,
                {
                    default: slide.slide_text_box_settings_background_border_radius,
                    label: CP_Customizer.translateCompanionString('Background Border Radius'),
                    choices: {
                        min: 0,
                        max: 500,
                        step: 1
                    }
                }
            );

            var slideTextBoxSettingsBackgroundBorderColor = CP_Customizer.createControl.color(
                'slide_text_box_settings_background_border_color_' + slideID,
                $slideTextBoxSettingsContainer,
                {
                    value: slide.slide_text_box_settings_background_border_color,
                    label: CP_Customizer.translateCompanionString('Background Border Color')
                }
            );

            var slideTextBoxSettingsBackgroundBorderThickness = CP_Customizer.createControl.spacing(
                'slide_text_box_settings_background_border_thickness_' + slideID,
                $slideTextBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Background Border Thickness'),
                    sides: ['top', 'bottom', 'left', 'right']
                }
            );

            // Media Box Settings Controls

            var slideMediaBoxSettingsGroupButton = CP_Customizer.createControl.buttonGroup(
                'slide_media_box_settings_group_button_' + slideID,
                $container,
                {
                    label: CP_Customizer.translateCompanionString('Media box settings'),
                    choices: null
                }
            );
            var $slideMediaBoxSettingsContainer = $container.find('div#slide_media_box_settings_group_button_' + slideID + '-popup > .section-settings-container');

            var slideMediaBoxSettingsImageSettingsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_media_box_settings_image_settings_separator_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Image Settings')
            );

            var slideMediaBoxSettingsVideoSettingsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_media_box_settings_video_settings_separator_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Video Settings')
            );

            var slideMediaBoxSettingsMakeImageRound = CP_Customizer.createControl.checkbox(
                'slide_media_box_settings_make_image_round_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Make Image round')
            );

            var slideMediaBoxSettingsImageBorderColor = CP_Customizer.createControl.color(
                'slide_media_box_settings_image_border_color_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    value: slide.slide_media_box_settings_image_border_color,
                    label: CP_Customizer.translateCompanionString('Image Border Color')
                }
            );

            var slideMediaBoxSettingsImageBorderThickness = CP_Customizer.createControl.slider(
                'slide_media_box_settings_image_border_thickness_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Image Border Thickness'),
                    choices: {
                        min: 0,
                        max: 20,
                        step: 1
                    },
                    default: slide.slide_media_box_settings_image_border_thickness,
                }
            );

            var slideMediaBoxSettingsContentVideo = CP_Customizer.createControl.generic(
                'slide_media_box_settings_content_video_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    value: slide.slide_media_box_settings_content_video,
                    label: CP_Customizer.translateCompanionString('Content Video'),
                }
            );

            var slideMediaBoxSettingsAutoplayVideo = CP_Customizer.createControl.checkbox(
                'slide_media_box_settings_autoplay_video_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Autoplay video')
            );

            var slideMediaBoxSettingsAutoplayVideoInfo = CP_Customizer.createControl.info(
                'slide_media_box_settings_autoplay_video_info_' + slideID,
                $slideMediaBoxSettingsContainer,
                '<span style="display:inline-block;">' + CP_Customizer.translateCompanionString('In customizer the video auto play is turned off for performance improvements') + '</span>'
            );

            var slideMediaBoxSettingsLoopVideo = CP_Customizer.createControl.checkbox(
                'slide_media_box_settings_loop_video_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Loop video')
            );

            var slideMediaBoxSettingsVideoIconColor = CP_Customizer.createControl.color(
                'slide_media_box_settings_video_icon_color_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    value: slide.slide_media_box_settings_video_icon_color,
                    label: CP_Customizer.translateCompanionString('Video Icon Color')
                }
            );

            var slideMediaBoxSettingsVideoIconHoverColor = CP_Customizer.createControl.color(
                'slide_media_box_settings_video_icon_hover_color_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    value: slide.slide_media_box_settings_video_icon_hover_color,
                    label: CP_Customizer.translateCompanionString('Video Icon Hover Color')
                }
            );

            var slideMediaBoxSettingsHideVideoPoster = CP_Customizer.createControl.checkbox(
                'slide_media_box_settings_hide_video_poster_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Hide Video Poster')
            );

            var slideMediaBoxSettingsVideoPoster = CP_Customizer.createControl.image(
                'slide_media_box_settings_video_poster_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    value: slide.slide_media_box_settings_video_poster,
                    label: CP_Customizer.translateCompanionString('Video Poster'),
                    url: slide.slide_media_box_settings_video_poster,
                }
            );

            var slideMediaBoxSettingsVideoPosterOverlayColor = CP_Customizer.createControl.color(
                'slide_media_box_settings_video_poster_overlay_color_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    value: slide.slide_media_box_settings_video_poster_overlay_color,
                    label: CP_Customizer.translateCompanionString('Video Poster Overlay Color')
                }
            );

            var slideMediaBoxSettingsEnableMediaShadow = CP_Customizer.createControl.checkbox(
                'slide_media_box_settings_enable_media_shadow_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Enable media shadow')
            );

            var slideMediaBoxSettingsMediaTopBottomWidth = CP_Customizer.createControl.slider(
                'slide_media_box_settings_media_top_bottom_width_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Media Width'),
                    choices: {
                        min: 0,
                        max: 100,
                        step: 1
                    },
                    default: slide.slide_media_box_settings_media_top_bottom_width,
                }
            );

            var slideMediaBoxSettingsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_media_box_settings_separator_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Media Box Settings')
            );

            var slideMediaBoxSettingsMediaVerticalAlign = CP_Customizer.createControl.select(
                'slide_media_box_settings_media_vertical_align_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    value: slide.slide_media_box_settings_media_vertical_align,
                    label: CP_Customizer.translateCompanionString('Media Vertical Align'),
                    choices: {
                        'top-sm': CP_Customizer.translateCompanionString('Top'),
                        'middle-sm': CP_Customizer.translateCompanionString('Middle'),
                        'bottom-sm': CP_Customizer.translateCompanionString('Bottom')
                    }
                }
            );

            var slideMediaBoxSettingsMediaImage = CP_Customizer.createControl.croppedImage(
                'slide_media_box_settings_media_image_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    value: slide.slide_media_box_settings_media_image,
                    label: CP_Customizer.translateCompanionString('Image'),
                    height: 600,
                    width: 420,
                    flex_height: true,
                    flex_width: true
                }
            );

            var slideMediaBoxSettingsMediaLeftRightWidth = CP_Customizer.createControl.slider(
                'slide_media_box_settings_media_left_right_width_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Media Width'),
                    choices: {
                        min: 0,
                        max: 100,
                        step: 1
                    },
                    default: slide.slide_media_box_settings_media_left_right_width,
                }
            );

            var slideMediaBoxSettingsMediaBoxSpacing = CP_Customizer.createControl.spacing(
                'slide_media_box_settings_media_box_spacing_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Media Box Spacing'),
                    sides: ['top', 'bottom']
                }
            );

            var slideMediaBoxSettingsFrameOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_media_box_settings_frame_options_separator_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Frame Options')
            );

            var slideMediaBoxSettingsFrameType = CP_Customizer.createControl.select(
                'slide_media_box_settings_frame_type_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    value: slide.slide_media_box_settings_frame_type,
                    label: CP_Customizer.translateCompanionString('Frame Type'),
                    choices: {
                        'none': CP_Customizer.translateCompanionString('None'),
                        'background': CP_Customizer.translateCompanionString('Background'),
                        'border': CP_Customizer.translateCompanionString('Border')
                    }
                }
            );

            var slideMediaBoxSettingsFrameWidth = CP_Customizer.createControl.slider(
                'slide_media_box_settings_frame_width_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Frame Width'),
                    choices: {
                        min: 0,
                        max: 200,
                        step: 1
                    },
                    default: slide.slide_media_box_settings_frame_width,
                }
            );

            var slideMediaBoxSettingsFrameHeight = CP_Customizer.createControl.slider(
                'slide_media_box_settings_frame_height_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Frame Height'),
                    choices: {
                        min: 0,
                        max: 200,
                        step: 1
                    },
                    default: slide.slide_media_box_settings_frame_height,
                }
            );

            var slideMediaBoxSettingsFrameOffsetLeft = CP_Customizer.createControl.slider(
                'slide_media_box_settings_frame_offset_left_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Frame Offset left'),
                    choices: {
                        min: -50,
                        max: 50,
                        step: 1
                    },
                    default: slide.slide_media_box_settings_frame_offset_left,
                }
            );

            var slideMediaBoxSettingsFrameOffsetTop = CP_Customizer.createControl.slider(
                'slide_media_box_settings_frame_offset_top_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Frame Offset top'),
                    choices: {
                        min: -50,
                        max: 50,
                        step: 1
                    },
                    default: slide.slide_media_box_settings_frame_offset_top,
                }
            );

            var slideMediaBoxSettingsFrameThickness = CP_Customizer.createControl.slider(
                'slide_media_box_settings_frame_thickness_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Frame Thickness'),
                    choices: {
                        min: 1,
                        max: 50,
                        step: 1
                    },
                    default: slide.slide_media_box_settings_frame_thickness,
                }
            );

            var slideMediaBoxSettingsFrameColor = CP_Customizer.createControl.color(
                'slide_media_box_settings_frame_color_' + slideID,
                $slideMediaBoxSettingsContainer,
                {
                    value: slide.slide_media_box_settings_frame_color,
                    label: CP_Customizer.translateCompanionString('Frame Color')
                }
            );

            var slideMediaBoxSettingsFrameShowOverImage = CP_Customizer.createControl.checkbox(
                'slide_media_box_settings_frame_show_over_image_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Show frame over image')
            );

            var slideMediaBoxSettingsFrameShowShadow = CP_Customizer.createControl.checkbox(
                'slide_media_box_settings_frame_show_shadow_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Show frame shadow')
            );

            var slideMediaBoxSettingsFrameHideOnMobile = CP_Customizer.createControl.checkbox(
                'slide_media_box_settings_frame_hide_on_mobile_' + slideID,
                $slideMediaBoxSettingsContainer,
                CP_Customizer.translateCompanionString('Hide frame on mobile')
            );

            // Title Controls

            var slideShowTitle = CP_Customizer.createControl.checkbox(
                'slide_show_title_' + slideID,
                $container,
                CP_Customizer.translateCompanionString('Show title')
            );

            var slideTitleOptionsGroupButton = CP_Customizer.createControl.buttonGroup(
                'slide_title_options_group_button_' + slideID,
                $container,
                {
                    label: CP_Customizer.translateCompanionString('Options'),
                    in_row_with: ['slide_show_title_' + slideID],
                    choices: null
                }
            );
            var $slideTitleOptionsContainer = $container.find('div#slide_title_options_group_button_' + slideID + '-popup > .section-settings-container');

            // mimic in_row_with for slideTitleOptionsGroupButton
            slideTitleOptionsGroupButton.control.container.css({
                "width": "40%",
                "clear": "right",
                "float": "right",
            });
            slideShowTitle.control.container.css({
                "width": "auto",
                "max-width": "calc(60% - 12px)"
            });

            if (slide.slide_show_title === false) {
                slideTitleOptionsGroupButton.hide();
            }

            var slideTitleOptionsTitleText = CP_Customizer.createControl.textarea(
                'slide_title_options_title_text_' + slideID,
                $slideTitleOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Title text'),
                    value: slide.slide_title_options_title_text
                }
            );

            var slideTitleOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_title_options_separator_' + slideID,
                $slideTitleOptionsContainer,
                CP_Customizer.translateCompanionString('Title Options')
            );

            var slideTitleOptionsTitleTypography = CP_Customizer.createControl.typography(
                'slide_title_options_title_typography_' + slideID,
                $slideTitleOptionsContainer,
                {
                    value: slide.slide_title_options_title_typography,
                    label: CP_Customizer.translateCompanionString('Title Typography'),
                    choices: {
                        fonts: {
                            'Open Sans': 'Open Sans',
                            'Muli': 'Muli',
                            'Playfair Display': 'Playfair Display',
                            'Roboto': 'Roboto',
                            'Georgia,Times, Times New Roman ,serif': 'Serif',
                            'Helvetica,Arial,sans-serif': 'Helvetica,Arial,sans-serif',
                            'Monaco,"Lucida Sans Typewriter","Lucida Typewriter","Courier New",Courier,monospace': 'Monospace'
                        }
                    },
                    show_variants: true,
                    show_subsets: false
                }
            );

            var slideTitleOptionsTitleSpacing = CP_Customizer.createControl.spacing(
                'slide_title_options_title_spacing_' + slideID,
                $slideTitleOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Title Spacing'),
                    sides: ['top', 'bottom']
                }
            );

            var slideTitleOptionsBackgroundOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_title_options_background_options_separator_' + slideID,
                $slideTitleOptionsContainer,
                CP_Customizer.translateCompanionString('Background Options')
            );

            var slideTitleOptionsBackgroundColor = CP_Customizer.createControl.color(
                'slide_title_options_background_color_' + slideID,
                $slideTitleOptionsContainer,
                {
                    value: slide.slide_title_options_background_color,
                    label: CP_Customizer.translateCompanionString('Background Color')
                }
            );

            var slideTitleOptionsBackgroundSpacing = CP_Customizer.createControl.spacing(
                'slide_title_options_background_spacing_' + slideID,
                $slideTitleOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Background Spacing'),
                    sides: ['top', 'bottom', 'left', 'right']
                }
            );

            var slideTitleOptionsBackgroundBorderRadius = CP_Customizer.createControl.number(
                'slide_title_options_background_border_radius_' + slideID,
                $slideTitleOptionsContainer,
                {
                    default: slide.slide_title_options_background_border_radius,
                    label: CP_Customizer.translateCompanionString('Background Border Radius'),
                    choices: {
                        min: 0,
                        max: 250,
                        step: 1
                    }
                }
            );

            var slideTitleOptionsBackgroundBorderColor = CP_Customizer.createControl.color(
                'slide_title_options_background_border_color_' + slideID,
                $slideTitleOptionsContainer,
                {
                    value: slide.slide_title_options_background_border_color,
                    label: CP_Customizer.translateCompanionString('Background Border Color')
                }
            );

            var slideTitleOptionsBackgroundBorderThickness = CP_Customizer.createControl.spacing(
                'slide_title_options_background_border_thickness_' + slideID,
                $slideTitleOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Background Border Thickness'),
                    sides: ['top', 'bottom', 'left', 'right']
                }
            );

            var slideTitleOptionsTextAnimationSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_title_options_text_animation_separator_' + slideID,
                $slideTitleOptionsContainer,
                CP_Customizer.translateCompanionString('Text Animation')
            );

            var slideTitleOptionsEnableTextAnimation = CP_Customizer.createControl.checkbox(
                'slide_title_options_enable_text_animation_' + slideID,
                $slideTitleOptionsContainer,
                CP_Customizer.translateCompanionString('Enable text animation')
            );

            var slideTitleOptionsTextAnimationInfo = CP_Customizer.createControl.info(
                'slide_title_options_text_animation_info_' + slideID,
                $slideTitleOptionsContainer,
                CP_Customizer.translateCompanionString('The text between the curly braces will be replaced with the alternative texts in the following text area. Type one alternative text per line.')
            );

            var slideTitleOptionsTextAnimationAlternatives = CP_Customizer.createControl.textarea(
                'slide_title_options_text_animation_alternatives_' + slideID,
                $slideTitleOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Alternative text (one per row)'),
                    value: slide.slide_title_options_text_animation_alternatives
                }
            );

            // Subtitle controls

            var slideShowSubtitle = CP_Customizer.createControl.checkbox(
                'slide_show_subtitle_' + slideID,
                $container,
                CP_Customizer.translateCompanionString('Show subtitle')
            );

            var slideSubtitleOptionsGroupButton = CP_Customizer.createControl.buttonGroup(
                'slide_subtitle_options_group_button_' + slideID,
                $container,
                {
                    label: CP_Customizer.translateCompanionString('Options'),
                    in_row_with: ['slide_show_subtitle_' + slideID],
                    choices: null
                }
            );
            var $slideSubtitleOptionsContainer = $container.find('div#slide_subtitle_options_group_button_' + slideID + '-popup > .section-settings-container');

            // mimic in_row_with for slideTitleOptionsGroupButton
            slideSubtitleOptionsGroupButton.control.container.css({
                "width": "40%",
                "clear": "right",
                "float": "right",
            });
            slideShowSubtitle.control.container.css({
                "width": "auto",
                "max-width": "calc(60% - 12px)"
            });

            if (slide.slide_show_subtitle === false) {
                slideSubtitleOptionsGroupButton.hide();
            }

            var slideSubtitleOptionsSubtitleText = CP_Customizer.createControl.textarea(
                'slide_subtitle_options_subtitle_text_' + slideID,
                $slideSubtitleOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Subtitle text'),
                    value: slide.slide_subtitle_options_subtitle_text
                }
            );

            var slideSubtitleOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_subtitle_options_separator_' + slideID,
                $slideSubtitleOptionsContainer,
                CP_Customizer.translateCompanionString('Subtitle Options')
            );

            var slideSubtitleOptionsSubtitleTypography = CP_Customizer.createControl.typography(
                'slide_subtitle_options_subtitle_typography_' + slideID,
                $slideSubtitleOptionsContainer,
                {
                    value: slide.slide_subtitle_options_subtitle_typography,
                    label: CP_Customizer.translateCompanionString('Subtitle Typography'),
                    choices: {
                        fonts: {
                            'Open Sans': 'Open Sans',
                            'Muli': 'Muli',
                            'Playfair Display': 'Playfair Display',
                            'Roboto': 'Roboto',
                            'Georgia,Times, Times New Roman ,serif': 'Serif',
                            'Helvetica,Arial,sans-serif': 'Helvetica,Arial,sans-serif',
                            'Monaco,"Lucida Sans Typewriter","Lucida Typewriter","Courier New",Courier,monospace': 'Monospace'
                        }
                    },
                    show_variants: true,
                    show_subsets: false
                }
            );

            var slideSubtitleOptionsSubtitleSpacing = CP_Customizer.createControl.spacing(
                'slide_subtitle_options_subtitle_spacing_' + slideID,
                $slideSubtitleOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Subtitle Spacing'),
                    sides: ['top', 'bottom']
                }
            );

            var slideSubtitleOptionsBackgroundOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_subtitle_options_background_options_separator_' + slideID,
                $slideSubtitleOptionsContainer,
                CP_Customizer.translateCompanionString('Background Options')
            );

            var slideSubtitleOptionsBackgroundColor = CP_Customizer.createControl.color(
                'slide_subtitle_options_background_color_' + slideID,
                $slideSubtitleOptionsContainer,
                {
                    value: slide.slide_subtitle_options_background_color,
                    label: CP_Customizer.translateCompanionString('Background Color')
                }
            );

            var slideSubtitleOptionsBackgroundSpacing = CP_Customizer.createControl.spacing(
                'slide_subtitle_options_background_spacing_' + slideID,
                $slideSubtitleOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Background Spacing'),
                    sides: ['top', 'bottom', 'left', 'right']
                }
            );

            var slideSubtitleOptionsBackgroundBorderRadius = CP_Customizer.createControl.number(
                'slide_subtitle_options_background_border_radius_' + slideID,
                $slideSubtitleOptionsContainer,
                {
                    default: slide.slide_subtitle_options_background_border_radius,
                    label: CP_Customizer.translateCompanionString('Background Border Radius'),
                    choices: {
                        min: 0,
                        max: 250,
                        step: 1
                    }
                }
            );

            var slideSubtitleOptionsBackgroundBorderColor = CP_Customizer.createControl.color(
                'slide_subtitle_options_background_border_color_' + slideID,
                $slideSubtitleOptionsContainer,
                {
                    value: slide.slide_subtitle_options_background_border_color,
                    label: CP_Customizer.translateCompanionString('Background Border Color')
                }
            );

            var slideSubtitleOptionsBackgroundBorderThickness = CP_Customizer.createControl.spacing(
                'slide_subtitle_options_background_border_thickness_' + slideID,
                $slideSubtitleOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Background Border Thickness'),
                    sides: ['top', 'bottom', 'left', 'right']
                }
            );


            // Motto controls

            var slideShowSubtitle2 = CP_Customizer.createControl.checkbox(
                'slide_show_subtitle2_' + slideID,
                $container,
                CP_Customizer.translateCompanionString('Show Motto')
            );

            var slideSubtitle2OptionsGroupButton = CP_Customizer.createControl.buttonGroup(
                'slide_subtitle2_options_group_button_' + slideID,
                $container,
                {
                    label: CP_Customizer.translateCompanionString('Options'),
                    in_row_with: ['slide_show_subtitle2_' + slideID],
                    choices: null
                }
            );
            var $slideSubtitle2OptionsContainer = $container.find('div#slide_subtitle2_options_group_button_' + slideID + '-popup > .section-settings-container');

            // mimic in_row_with for slideTitleOptionsGroupButton
            slideSubtitle2OptionsGroupButton.control.container.css({
                "width": "40%",
                "clear": "right",
                "float": "right",
            });
            slideShowSubtitle2.control.container.css({
                "width": "auto",
                "max-width": "calc(60% - 12px)"
            });

            if (slide.slide_show_subtitle2 === false) {
                slideSubtitle2OptionsGroupButton.hide();
            }

            var slideSubtitle2OptionsSubtitle2Text = CP_Customizer.createControl.textarea(
                'slide_subtitle2_options_subtitle2_text_' + slideID,
                $slideSubtitle2OptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Motto text'),
                    value: slide.slide_subtitle2_options_subtitle2_text
                }
            );

            var slideSubtitle2OptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_subtitle2_options_separator_' + slideID,
                $slideSubtitle2OptionsContainer,
                CP_Customizer.translateCompanionString('Motto Options')
            );

            var slideSubtitle2OptionsSubtitle2Typography = CP_Customizer.createControl.typography(
                'slide_subtitle2_options_subtitle2_typography_' + slideID,
                $slideSubtitle2OptionsContainer,
                {
                    value: slide.slide_subtitle2_options_subtitle2_typography,
                    label: CP_Customizer.translateCompanionString('Motto Typography'),
                    choices: {
                        fonts: {
                            'Open Sans': 'Open Sans',
                            'Muli': 'Muli',
                            'Playfair Display': 'Playfair Display',
                            'Roboto': 'Roboto',
                            'Georgia,Times, Times New Roman ,serif': 'Serif',
                            'Helvetica,Arial,sans-serif': 'Helvetica,Arial,sans-serif',
                            'Monaco,"Lucida Sans Typewriter","Lucida Typewriter","Courier New",Courier,monospace': 'Monospace'
                        }
                    },
                    show_variants: true,
                    show_subsets: false
                }
            );

            var slideSubtitle2OptionsSubtitle2Spacing = CP_Customizer.createControl.spacing(
                'slide_subtitle2_options_subtitle2_spacing_' + slideID,
                $slideSubtitle2OptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Motto Spacing'),
                    sides: ['top', 'bottom']
                }
            );

            var slideSubtitle2OptionsBackgroundOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_subtitle2_options_background_options_separator_' + slideID,
                $slideSubtitle2OptionsContainer,
                CP_Customizer.translateCompanionString('Background Options')
            );

            var slideSubtitle2OptionsBackgroundColor = CP_Customizer.createControl.color(
                'slide_subtitle2_options_background_color_' + slideID,
                $slideSubtitle2OptionsContainer,
                {
                    value: slide.slide_subtitle2_options_background_color,
                    label: CP_Customizer.translateCompanionString('Background Color')
                }
            );

            var slideSubtitle2OptionsBackgroundSpacing = CP_Customizer.createControl.spacing(
                'slide_subtitle2_options_background_spacing_' + slideID,
                $slideSubtitle2OptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Background Spacing'),
                    sides: ['top', 'bottom', 'left', 'right']
                }
            );

            var slideSubtitle2OptionsBackgroundBorderRadius = CP_Customizer.createControl.number(
                'slide_subtitle2_options_background_border_radius_' + slideID,
                $slideSubtitle2OptionsContainer,
                {
                    default: slide.slide_subtitle2_options_background_border_radius,
                    label: CP_Customizer.translateCompanionString('Background Border Radius'),
                    choices: {
                        min: 0,
                        max: 250,
                        step: 1
                    }
                }
            );

            var slideSubtitle2OptionsBackgroundBorderColor = CP_Customizer.createControl.color(
                'slide_subtitle2_options_background_border_color_' + slideID,
                $slideSubtitle2OptionsContainer,
                {
                    value: slide.slide_subtitle2_options_background_border_color,
                    label: CP_Customizer.translateCompanionString('Background Border Color')
                }
            );

            var slideSubtitle2OptionsBackgroundBorderThickness = CP_Customizer.createControl.spacing(
                'slide_subtitle2_options_background_border_thickness_' + slideID,
                $slideSubtitle2OptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Background Border Thickness'),
                    sides: ['top', 'bottom', 'left', 'right']
                }
            );


            // Buttons controls

            var slideShowButtons = CP_Customizer.createControl.checkbox(
                'slide_show_buttons_' + slideID,
                $container,
                CP_Customizer.translateCompanionString('Show buttons')
            );

            var slideButtonsOptionsGroupButton = CP_Customizer.createControl.buttonGroup(
                'slide_buttons_options_group_button_' + slideID,
                $container,
                {
                    label: CP_Customizer.translateCompanionString('Options'),
                    in_row_with: ['slide_show_buttons_' + slideID],
                    choices: null
                }
            );
            var $slideButtonsOptionsContainer = $container.find('div#slide_buttons_options_group_button_' + slideID + '-popup > .section-settings-container');

            // mimic in_row_with for slideButtonsOptionsGroupButton
            slideButtonsOptionsGroupButton.control.container.css({
                "width": "40%",
                "clear": "right",
                "float": "right",
            });
            slideShowButtons.control.container.css({
                "width": "auto",
                "max-width": "calc(60% - 12px)"
            });

            if (slide.slide_show_buttons === false) {
                slideButtonsOptionsGroupButton.hide();
            }

            var slideButtonsOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_buttons_options_separator_' + slideID,
                $slideButtonsOptionsContainer,
                CP_Customizer.translateCompanionString('Buttons Options')
            );

            var slideButtonsOptionsButtonsType = CP_Customizer.createControl.select(
                'slide_buttons_options_buttons_type_' + slideID,
                $slideButtonsOptionsContainer,
                {
                    value: slide.slide_buttons_options_buttons_type,
                    label: CP_Customizer.translateCompanionString('Buttons Type'),
                    choices: {
                        'normal': CP_Customizer.translateCompanionString('Normal Buttons'),
                        'store': CP_Customizer.translateCompanionString('App Store buttons')
                    }
                }
            );

            var slideButtonsOptionsNormalButtons = CP_Customizer.createControl.repeater(
                'slide_buttons_options_normal_buttons_' + slideID,
                $slideButtonsOptionsContainer,
                {
                    value: slide.slide_buttons_options_normal_buttons,
                    label: CP_Customizer.translateCompanionString('Buttons'),
                    // choices: {
                    //     limit: 1
                    // },
                    row_label: {
                        type: 'text',
                        value: 'Button'
                    },
                    fields: {
                        label: {
                            type: 'hidden',
                            label: 'Label',
                            default: 'Action Button',
                            id: 'label'
                        },
                        url: {
                            type: 'hidden',
                            label: 'Link',
                            default: '#',
                            id: 'url'
                        },
                        target: {
                            type: 'hidden',
                            label: 'Target',
                            default: '_self'
                        },
                        class: {
                            type: 'hidden',
                            label: 'Class',
                            default: 'button color1 big'
                        }
                    },
                    getValue: function () {
                        var slideData = CP_Customizer.getMod('slider_elements')[slideID];
                        var data = slideData['slide_buttons_options_normal_buttons'];
                        return CP_Customizer.utils.normalizeValue(data, true);
                    }
                }
            );

            var slideButtonsOptionsStoreButtons = CP_Customizer.createControl.repeater(
                'slide_buttons_options_store_buttons_' + slideID,
                $slideButtonsOptionsContainer,
                {
                    value: slide.slide_buttons_options_store_buttons,
                    label: CP_Customizer.translateCompanionString('Store Badges'),
                    choices: {
                        limit: 2
                    },
                    row_label: {
                        type: 'field',
                        field: 'store',
                        value: 'Store Badge'
                    },
                    fields: {
                        store: {
                            type: 'select',
                            label: 'Badge Type',
                            choices: {
                                'google-store': 'Google Play Badge',
                                'apple-store': 'App Store Badge'
                            },
                            id: 'store',
                            default: 'google-store'
                        },
                        link: {
                            type: 'text',
                            label: 'Link',
                            default: '#',
                            id: 'link'
                        }
                    },
                    getValue: function () {
                        var slideData = CP_Customizer.getMod('slider_elements')[slideID];
                        var data = slideData['slide_buttons_options_store_buttons'];
                        return CP_Customizer.utils.normalizeValue(data, true);
                    }
                }
            );


            var slideButtonsOptionsBackgroundOptionsSeparator = CP_Customizer.createControl.sectionSeparator(
                'slide_buttons_options_background_options_separator_' + slideID,
                $slideButtonsOptionsContainer,
                CP_Customizer.translateCompanionString('Background Options')
            );

            var slideButtonsOptionsBackgroundColor = CP_Customizer.createControl.color(
                'slide_buttons_options_background_color_' + slideID,
                $slideButtonsOptionsContainer,
                {
                    value: slide.slide_buttons_options_background_color,
                    label: CP_Customizer.translateCompanionString('Background Color')
                }
            );

            var slideButtonsOptionsBackgroundSpacing = CP_Customizer.createControl.spacing(
                'slide_buttons_options_background_spacing_' + slideID,
                $slideButtonsOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Background Spacing'),
                    sides: ['top', 'bottom', 'left', 'right']
                }
            );

            var slideButtonsOptionsBackgroundBorderRadius = CP_Customizer.createControl.number(
                'slide_buttons_options_background_border_radius_' + slideID,
                $slideButtonsOptionsContainer,
                {
                    default: slide.slide_buttons_options_background_border_radius,
                    label: CP_Customizer.translateCompanionString('Background Border Radius'),
                    choices: {
                        min: 0,
                        max: 250,
                        step: 1
                    }
                }
            );

            var slideButtonsOptionsBackgroundBorderColor = CP_Customizer.createControl.color(
                'slide_buttons_options_background_border_color_' + slideID,
                $slideButtonsOptionsContainer,
                {
                    value: slide.slide_buttons_options_background_border_color,
                    label: CP_Customizer.translateCompanionString('Background Border Color')
                }
            );

            var slideButtonsOptionsBackgroundBorderThickness = CP_Customizer.createControl.spacing(
                'slide_buttons_options_background_border_thickness_' + slideID,
                $slideButtonsOptionsContainer,
                {
                    label: CP_Customizer.translateCompanionString('Background Border Thickness'),
                    sides: ['top', 'bottom', 'left', 'right']
                }
            );

            // setters

            slideContentLayout.attachWithSetter(slide.slide_content_layout, function (value) {

                if (jQuery.inArray(value, ['content-on-center', 'content-on-right', 'content-on-left']) >= 0) {

                    slideContentMediaType.hide();
                    slideMediaBoxSettingsGroupButton.hide();

                    var __value = 'center';

                    if (value == 'content-on-left' || value == 'media-on-left' || value == 'media-on-right') {
                        __value = 'left';
                    }
                    if (value == 'content-on-center' || value == 'media-on-top' || value == 'media-on-bottom') {
                        __value = 'center';
                    }
                    if (value == 'content-on-right') {
                        __value = 'right';
                    }
                    slideTextBoxSettingsTextAlign.control.container.find('input[value="' + __value + '"]').prop('checked', true);
                    slideTextBoxSettingsTextAlign.set(__value);
                    slideTextBoxSettingsTextVerticalAlign.hide();

                    setSlideValue(slideID, 'slide_text_box_settings_text_align', __value, true);
                }
                else { // this for text with media layout

                    slideContentMediaType.show();
                    slideMediaBoxSettingsGroupButton.show();

                    if (jQuery.inArray(value, ['media-on-left', 'media-on-right']) >= 0) {
                        slideTextBoxSettingsTextVerticalAlign.show();
                        slideMediaBoxSettingsMediaVerticalAlign.show();
                        slideMediaBoxSettingsMediaTopBottomWidth.hide();
                        slideMediaBoxSettingsMediaLeftRightWidth.show();
                        slideMediaBoxSettingsMediaBoxSpacing.hide();
                    }
                    else {
                        slideTextBoxSettingsTextVerticalAlign.hide();
                        slideMediaBoxSettingsMediaVerticalAlign.hide();
                        slideMediaBoxSettingsMediaTopBottomWidth.show();
                        slideMediaBoxSettingsMediaLeftRightWidth.hide();
                        slideMediaBoxSettingsMediaBoxSpacing.show();
                    }

                    if (slideContentMediaType.control.setting.get() == 'image') {
                        slideMediaBoxSettingsFrameOptionsSeparator.show();
                        slideMediaBoxSettingsFrameType.show();

                        if (jQuery.inArray(slideMediaBoxSettingsFrameType.control.setting.get(), ['border', 'background']) >= 0) {
                            slideMediaBoxSettingsFrameWidth.show();
                            slideMediaBoxSettingsFrameHeight.show();
                            slideMediaBoxSettingsFrameOffsetLeft.show();
                            slideMediaBoxSettingsFrameOffsetTop.show();

                            if (slideMediaBoxSettingsFrameType == 'border') {
                                slideMediaBoxSettingsFrameThickness.show();
                            }
                            else {
                                slideMediaBoxSettingsFrameThickness.hide();
                            }

                            slideMediaBoxSettingsFrameColor.show();
                            slideMediaBoxSettingsFrameShowOverImage.show();
                            slideMediaBoxSettingsFrameShowShadow.show();
                            slideMediaBoxSettingsFrameHideOnMobile.show();
                        }
                        else {
                            slideMediaBoxSettingsFrameWidth.hide();
                            slideMediaBoxSettingsFrameHeight.hide();
                            slideMediaBoxSettingsFrameOffsetLeft.hide();
                            slideMediaBoxSettingsFrameOffsetTop.hide();
                            slideMediaBoxSettingsFrameThickness.hide();
                            slideMediaBoxSettingsFrameColor.hide();
                            slideMediaBoxSettingsFrameShowOverImage.hide();
                            slideMediaBoxSettingsFrameShowShadow.hide();
                            slideMediaBoxSettingsFrameHideOnMobile.hide();
                        }

                    }
                    else {
                        slideMediaBoxSettingsFrameOptionsSeparator.hide();
                        slideMediaBoxSettingsFrameType.hide();
                        slideMediaBoxSettingsFrameWidth.hide();
                        slideMediaBoxSettingsFrameHeight.hide();
                        slideMediaBoxSettingsFrameOffsetLeft.hide();
                        slideMediaBoxSettingsFrameOffsetTop.hide();
                        slideMediaBoxSettingsFrameThickness.hide();
                        slideMediaBoxSettingsFrameColor.hide();
                        slideMediaBoxSettingsFrameShowOverImage.hide();
                        slideMediaBoxSettingsFrameShowShadow.hide();
                        slideMediaBoxSettingsFrameHideOnMobile.hide();
                    }

                }

                // slide.slide_content_layout = value;
                setSlideValue(slideID, 'slide_content_layout', value, true);
            });
            slideContentMediaType.attachWithSetter(slide.slide_content_media_type, function (value) {

                if (value == 'image') {
                    slideMediaBoxSettingsImageSettingsSeparator.show();
                    slideMediaBoxSettingsVideoSettingsSeparator.hide();
                    slideMediaBoxSettingsMakeImageRound.show();
                    if (slideMediaBoxSettingsMakeImageRound.control.setting.get() === true) {
                        slideMediaBoxSettingsImageBorderColor.show();
                        slideMediaBoxSettingsImageBorderThickness.show();
                    }
                    slideMediaBoxSettingsContentVideo.hide();
                    slideMediaBoxSettingsMediaImage.show();
                    slideMediaBoxSettingsFrameOptionsSeparator.show();
                    slideMediaBoxSettingsFrameType.show();

                    if (jQuery.inArray(slideMediaBoxSettingsFrameType.control.setting.get(), ['border', 'background']) >= 0) {
                        slideMediaBoxSettingsFrameWidth.show();
                        slideMediaBoxSettingsFrameHeight.show();
                        slideMediaBoxSettingsFrameOffsetLeft.show();
                        slideMediaBoxSettingsFrameOffsetTop.show();

                        if (slideMediaBoxSettingsFrameType == 'border') {
                            slideMediaBoxSettingsFrameThickness.show();
                        }
                        else {
                            slideMediaBoxSettingsFrameThickness.hide();
                        }

                        slideMediaBoxSettingsFrameColor.show();
                        slideMediaBoxSettingsFrameShowOverImage.show();
                        slideMediaBoxSettingsFrameShowShadow.show();
                        slideMediaBoxSettingsFrameHideOnMobile.show();
                    }
                    else {
                        slideMediaBoxSettingsFrameWidth.hide();
                        slideMediaBoxSettingsFrameHeight.hide();
                        slideMediaBoxSettingsFrameOffsetLeft.hide();
                        slideMediaBoxSettingsFrameOffsetTop.hide();
                        slideMediaBoxSettingsFrameThickness.hide();
                        slideMediaBoxSettingsFrameColor.hide();
                        slideMediaBoxSettingsFrameShowOverImage.hide();
                        slideMediaBoxSettingsFrameShowShadow.hide();
                        slideMediaBoxSettingsFrameHideOnMobile.hide();
                    }

                }
                else {
                    slideMediaBoxSettingsImageSettingsSeparator.hide();
                    slideMediaBoxSettingsVideoSettingsSeparator.show();
                    slideMediaBoxSettingsMakeImageRound.hide();
                    slideMediaBoxSettingsImageBorderColor.hide();
                    slideMediaBoxSettingsImageBorderThickness.hide();
                    slideMediaBoxSettingsContentVideo.show();
                    slideMediaBoxSettingsMediaImage.hide();
                    slideMediaBoxSettingsFrameOptionsSeparator.hide();
                    slideMediaBoxSettingsFrameType.hide();
                    slideMediaBoxSettingsFrameWidth.hide();
                    slideMediaBoxSettingsFrameHeight.hide();
                    slideMediaBoxSettingsFrameOffsetLeft.hide();
                    slideMediaBoxSettingsFrameOffsetTop.hide();
                    slideMediaBoxSettingsFrameThickness.hide();
                    slideMediaBoxSettingsFrameColor.hide();
                    slideMediaBoxSettingsFrameShowOverImage.hide();
                    slideMediaBoxSettingsFrameShowShadow.hide();
                    slideMediaBoxSettingsFrameHideOnMobile.hide();
                }

                if (value == 'video_popup') {
                    slideMediaBoxSettingsVideoIconColor.show();
                    slideMediaBoxSettingsVideoIconHoverColor.show();
                    slideMediaBoxSettingsHideVideoPoster.show();
                    if (slideMediaBoxSettingsHideVideoPoster.control.setting.get() === false) {
                        slideMediaBoxSettingsVideoPoster.show();
                        slideMediaBoxSettingsVideoPosterOverlayColor.show();
                    }
                    else {
                        slideMediaBoxSettingsVideoPoster.hide();
                        slideMediaBoxSettingsVideoPosterOverlayColor.hide();
                    }
                }
                else {
                    slideMediaBoxSettingsVideoIconColor.hide();
                    slideMediaBoxSettingsVideoIconHoverColor.hide();
                    slideMediaBoxSettingsHideVideoPoster.hide();
                    slideMediaBoxSettingsVideoPoster.hide();
                    slideMediaBoxSettingsVideoPosterOverlayColor.hide();
                }

                if (value == 'video') {
                    slideMediaBoxSettingsAutoplayVideo.show();
                    slideMediaBoxSettingsAutoplayVideoInfo.show();
                    slideMediaBoxSettingsLoopVideo.show();
                }
                else {
                    slideMediaBoxSettingsAutoplayVideo.hide();
                    slideMediaBoxSettingsAutoplayVideoInfo.hide();
                    slideMediaBoxSettingsLoopVideo.hide();
                }

                // slide.slide_content_media_type = value;
                setSlideValue(slideID, 'slide_content_media_type', value, true);
            });
            slideContentSpacing.attachWithSetter(slide.slide_content_spacing, function (value) {
                setSlideValue(slideID, 'slide_content_spacing', value);
                addSlideJSVars(slideID, ' .header-description-row', 'padding', 'all', value.top + ' 0 ' + value.bottom + ' 0');
                CP_Customizer.preview.find('#header-slides-container').trigger('refresh.owl.carousel');
            });
            slideMobileContentSpacing.attachWithSetter(slide.slide_mobile_content_spacing, function (value) {
                // slide.slide_mobile_content_spacing = value;
                setSlideValue(slideID, 'slide_mobile_content_spacing', value);
                addSlideJSVars(slideID, ' .header-description-row', 'padding', 'mobile', value.top + ' 0 ' + value.bottom + ' 0');
            });
            slideTextBoxSettingsTextAlign.attachWithSetter(slide.slide_text_box_settings_text_align, function (newValue, oldValue) {
                // slide.slide_text_box_settings_text_align = newValue;
                setSlideValue(slideID, 'slide_text_box_settings_text_align', newValue);
                CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.header-content .align-holder').removeClass(oldValue).addClass(newValue);
            });
            slideTextBoxSettingsBackgroundColor.attachWithSetter(slide.slide_text_box_settings_background_color, function (value) {
                // slide.slide_text_box_settings_background_color = value;
                setSlideValue(slideID, 'slide_text_box_settings_background_color', value);
                addSlideJSVars(slideID, ' .header-content .align-holder', 'background', 'all', value);
            });
            slideTextBoxSettingsBackgroundSpacing.attachWithSetter(slide.slide_text_box_settings_background_spacing, function (value) {
                // slide.slide_text_box_settings_background_spacing = value;
                setSlideValue(slideID, 'slide_text_box_settings_background_spacing', value);
                addSlideJSVars(slideID, ' .header-content .align-holder', 'padding', 'all', value.top + ' ' + value.right + ' ' + value.bottom + ' ' + value.left);
            });
            slideTextBoxSettingsBackgroundBorderRadius.attachWithSetter(slide.slide_text_box_settings_background_border_radius, function (value) {
                setSlideValue(slideID, 'slide_text_box_settings_background_border_radius', parseInt(value));
                addSlideJSVars(slideID, ' .header-content .align-holder', 'border-radius', 'all', parseInt(value) + 'px');
            });
            slideTextBoxSettingsBackgroundBorderColor.attachWithSetter(slide.slide_text_box_settings_background_border_color, function (value) {
                // slide.slide_text_box_settings_background_border_color = value;
                setSlideValue(slideID, 'slide_text_box_settings_background_border_color', value);
                addSlideJSVars(slideID, ' .header-content .align-holder', 'border-color', 'all', value);
            });
            slideTextBoxSettingsBackgroundBorderThickness.attachWithSetter(slide.slide_text_box_settings_background_border_thickness, function (value) {
                // slide.slide_text_box_settings_background_border_thickness = value;
                setSlideValue(slideID, 'slide_text_box_settings_background_border_thickness', value);
                addSlideJSVars(slideID, ' .header-content .align-holder', 'border-width', 'all', value.top + ' ' + value.right + ' ' + value.bottom + ' ' + value.left + '!important');
            });
            slideTextBoxSettingsTextWidth.attachWithSetter(slide.slide_text_box_settings_text_width, function (value) {
                // slide.slide_text_box_settings_text_width = value;
                setSlideValue(slideID, 'slide_text_box_settings_text_width', value);
                addSlideJSVars(slideID, ' .header-content .align-holder', 'width', 'desktop', value + '% !important');
            });
            slideTextBoxSettingsTextVerticalAlign.attachWithSetter(slide.slide_text_box_settings_text_vertical_align, function (newValue, oldValue) {
                // slide.slide_text_box_settings_text_vertical_align = newValue;
                setSlideValue(slideID, 'slide_text_box_settings_text_vertical_align', newValue);
                CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.header-hero-content-v-align').removeClass(oldValue).addClass(newValue);
            });
            slideMediaBoxSettingsMakeImageRound.attachWithSetter(slide.slide_media_box_settings_make_image_round, function (value) {
                if (value === true) {
                    slideMediaBoxSettingsImageBorderColor.show();
                    slideMediaBoxSettingsImageBorderThickness.show();
                }
                else {
                    slideMediaBoxSettingsImageBorderColor.hide();
                    slideMediaBoxSettingsImageBorderThickness.hide();
                }

                // slide.slide_media_box_settings_make_image_round = value;
                setSlideValue(slideID, 'slide_media_box_settings_make_image_round', value);
                if (value) {
                    CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.homepage-header-image').addClass('round');
                } else {
                    CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.homepage-header-image').removeClass('round');
                }
            });
            slideMediaBoxSettingsImageBorderColor.attachWithSetter(slide.slide_media_box_settings_image_border_color, function (value) {
                // slide.slide_media_box_settings_image_border_color = value;
                setSlideValue(slideID, 'slide_media_box_settings_image_border_color', value);
                addSlideJSVars(slideID, ' .homepage-header-image', 'border-color', 'all', value);
            });
            slideMediaBoxSettingsImageBorderThickness.attachWithSetter(slide.slide_media_box_settings_image_border_thickness, function (value) {
                // slide.slide_media_box_settings_image_border_thickness = value;
                setSlideValue(slideID, 'slide_media_box_settings_image_border_thickness', value);
                addSlideJSVars(slideID, ' .homepage-header-image', 'border-width', 'all', value + 'px');
            });
            slideMediaBoxSettingsContentVideo.attachWithSetter(slide.slide_media_box_settings_content_video, function (value) {
                slide.slide_media_box_settings_content_video = value;
                setSlideValue(slideID, 'slide_media_box_settings_content_video', value, true);
            });
            slideMediaBoxSettingsAutoplayVideo.attachWithSetter(slide.slide_media_box_settings_autoplay_video, function (value) {
                // slide.slide_media_box_settings_autoplay_video = value;
                setSlideValue(slideID, 'slide_media_box_settings_autoplay_video', value, false);
            });
            slideMediaBoxSettingsLoopVideo.attachWithSetter(slide.slide_media_box_settings_loop_video, function (value) {
                // slide.slide_media_box_settings_loop_video = value;
                setSlideValue(slideID, 'slide_media_box_settings_loop_video', value, false);
            });
            slideMediaBoxSettingsVideoIconColor.attachWithSetter(slide.slide_media_box_settings_video_icon_color, function (value) {
                // slide.slide_media_box_settings_video_icon_color = value;
                setSlideValue(slideID, 'slide_media_box_settings_video_icon_color', value);
                addSlideJSVars(slideID, ' a.video-popup-button-link', 'color', 'all', value);
            });
            slideMediaBoxSettingsVideoIconHoverColor.attachWithSetter(slide.slide_media_box_settings_video_icon_hover_color, function (value) {
                // slide.slide_media_box_settings_video_icon_hover_color = value;
                setSlideValue(slideID, 'slide_media_box_settings_video_icon_hover_color', value);
                addSlideJSVars(slideID, ' a.video-popup-button-link:hover', 'color', 'all', value);
            });
            slideMediaBoxSettingsHideVideoPoster.attachWithSetter(slide.slide_media_box_settings_hide_video_poster, function (value) {
                if (value === true) {
                    slideMediaBoxSettingsVideoPoster.hide();
                    slideMediaBoxSettingsVideoPosterOverlayColor.hide();
                    CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.video-popup-button').removeClass('with-image').find('.poster').remove();
                }
                else {
                    slideMediaBoxSettingsVideoPoster.show();
                    slideMediaBoxSettingsVideoPosterOverlayColor.show();

                    var posterImage = slideMediaBoxSettingsVideoPoster.control.setting.get();
                    posterImageElement = $('<img class="poster" src="' + posterImage + '"/>');

                    CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.video-popup-button').addClass('with-image').prepend(posterImageElement);
                }

                setSlideValue(slideID, 'slide_media_box_settings_hide_video_poster', value, false);
            });
            slideMediaBoxSettingsVideoPoster.attachWithSetter(slide.slide_media_box_settings_video_poster, function (value) {
                CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.video-popup-button.with-image .poster').attr('src', value);
                setSlideValue(slideID, 'slide_media_box_settings_video_poster', value, false);
            });
            slideMediaBoxSettingsVideoPosterOverlayColor.attachWithSetter(slide.slide_media_box_settings_video_poster_overlay_color, function (value) {
                // slide.slide_media_box_settings_video_poster_overlay_color = value;
                setSlideValue(slideID, 'slide_media_box_settings_video_poster_overlay_color', value);
                addSlideJSVars(slideID, ' .video-popup-button.with-image:before', 'background-color', 'all', value);
            });
            slideMediaBoxSettingsEnableMediaShadow.attachWithSetter(slide.slide_media_box_settings_enable_media_shadow, function (value) {
                // slide.slide_media_box_settings_enable_media_shadow = value;
                setSlideValue(slideID, 'slide_media_box_settings_enable_media_shadow', value);
                if (value) {
                    addSlideJSVars(slideID, ' .header-description-row img.homepage-header-image', '-moz-box-shadow', 'all', '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)');
                    addSlideJSVars(slideID, ' .header-description-row img.homepage-header-image', '-webkit-box-shadow', 'all', '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)');
                    addSlideJSVars(slideID, ' .header-description-row img.homepage-header-image', 'box-shadow', 'all', '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)');
                    addSlideJSVars(slideID, ' .header-description-row .video-popup-button img', '-moz-box-shadow', 'all', '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)');
                    addSlideJSVars(slideID, ' .header-description-row .video-popup-button img', '-webkit-box-shadow', 'all', '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)');
                    addSlideJSVars(slideID, ' .header-description-row .video-popup-button img', 'box-shadow', 'all', '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)');
                    addSlideJSVars(slideID, ' .header-description-row iframe.header-hero-video', '-moz-box-shadow', 'all', '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)');
                    addSlideJSVars(slideID, ' .header-description-row iframe.header-hero-video', '-webkit-box-shadow', 'all', '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)');
                    addSlideJSVars(slideID, ' .header-description-row iframe.header-hero-video', 'box-shadow', 'all', '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)');
                    addSlideJSVars(slideID, ' .header-description-row .video-popup-button-link i', '-webkit-filter', 'all', 'drop-shadow(10px 12px 8px rgba(0,0,0,0.8))');
                    addSlideJSVars(slideID, ' .header-description-row .video-popup-button-link i', 'filter', 'all', 'drop-shadow(10px 12px 8px rgba(0,0,0,0.8))');
                }
                else {
                    addSlideJSVars(slideID, ' .header-description-row img.homepage-header-image', '-moz-box-shadow', 'all', 'none');
                    addSlideJSVars(slideID, ' .header-description-row img.homepage-header-image', '-webkit-box-shadow', 'all', 'none');
                    addSlideJSVars(slideID, ' .header-description-row img.homepage-header-image', 'box-shadow', 'all', 'none');
                    addSlideJSVars(slideID, ' .header-description-row .video-popup-button img', '-moz-box-shadow', 'all', 'none');
                    addSlideJSVars(slideID, ' .header-description-row .video-popup-button img', '-webkit-box-shadow', 'all', 'none');
                    addSlideJSVars(slideID, ' .header-description-row .video-popup-button img', 'box-shadow', 'all', 'none');
                    addSlideJSVars(slideID, ' .header-description-row iframe.header-hero-video', '-moz-box-shadow', 'all', 'none');
                    addSlideJSVars(slideID, ' .header-description-row iframe.header-hero-video', '-webkit-box-shadow', 'all', 'none');
                    addSlideJSVars(slideID, ' .header-description-row iframe.header-hero-video', 'box-shadow', 'all', 'none');
                    addSlideJSVars(slideID, ' .header-description-row .video-popup-button-link i', '-webkit-filter', 'all', 'none');
                    addSlideJSVars(slideID, ' .header-description-row .video-popup-button-link i', 'filter', 'all', 'none');
                }
            });
            slideMediaBoxSettingsMediaTopBottomWidth.attachWithSetter(slide.slide_media_box_settings_media_top_bottom_width, function (value) {
                // slide.slide_media_box_settings_media_top_bottom_width = value;
                setSlideValue(slideID, 'slide_media_box_settings_media_top_bottom_width', value);
                addSlideJSVars(slideID, ' .media-on-bottom .header-media-container', 'width', 'desktop', value + '%');
                addSlideJSVars(slideID, ' .media-on-top .header-media-container', 'width', 'desktop', value + '%');
            });
            slideMediaBoxSettingsMediaVerticalAlign.attachWithSetter(slide.slide_media_box_settings_media_vertical_align, function (newValue, oldValue) {
                // slide.slide_media_box_settings_media_vertical_align = newValue;
                setSlideValue(slideID, 'slide_media_box_settings_media_vertical_align', newValue);
                CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.header-hero-media-v-align').removeClass(oldValue).addClass(newValue);
            });
            slideMediaBoxSettingsMediaImage.attachWithSetter(slide.slide_media_box_settings_media_image, function (value) {
                // slide.slide_media_box_settings_media_image = value;
                CP_Customizer.preview.jQuery('[data-focus-control="' + this.id + '"]').attr('src', value)
                setSlideValue(slideID, 'slide_media_box_settings_media_image', value);
            });
            slideMediaBoxSettingsMediaLeftRightWidth.attachWithSetter(slide.slide_media_box_settings_media_left_right_width, function (value) {
                // slide.slide_media_box_settings_media_left_right_width = value;
                setSlideValue(slideID, 'slide_media_box_settings_media_left_right_width', value);
                addSlideJSVars(slideID, ' .header-hero-media', '-webkit-flex-basis', 'desktop', value + '% !important');
                addSlideJSVars(slideID, ' .header-hero-media', '-moz-flex-basis', 'desktop', value + '% !important');
                addSlideJSVars(slideID, ' .header-hero-media', '-ms-flex-preferred-size', 'desktop', value + '% !important');
                addSlideJSVars(slideID, ' .header-hero-media', 'flex-basis', 'desktop', value + '% !important');
                addSlideJSVars(slideID, ' .header-hero-media', 'max-width', 'desktop', value + '% !important');
                addSlideJSVars(slideID, ' .header-hero-media', 'width', 'desktop', value + '% !important');
                addSlideJSVars(slideID, ' .header-hero-content', '-webkit-flex-basis', 'desktop', (100 - value) + '% !important');
                addSlideJSVars(slideID, ' .header-hero-content', '-moz-flex-basis', 'desktop', (100 - value) + '% !important');
                addSlideJSVars(slideID, ' .header-hero-content', '-ms-flex-preferred-size', 'desktop', (100 - value) + '% !important');
                addSlideJSVars(slideID, ' .header-hero-content', 'flex-basis', 'desktop', (100 - value) + '% !important');
                addSlideJSVars(slideID, ' .header-hero-content', 'max-width', 'desktop', (100 - value) + '% !important');
                addSlideJSVars(slideID, ' .header-hero-content', 'width', 'desktop', (100 - value) + '% !important');
            });
            slideMediaBoxSettingsMediaBoxSpacing.attachWithSetter(slide.slide_media_box_settings_media_box_spacing, function (value) {
                slide.slide_media_box_settings_media_box_spacing = value;
                setSlideValue(slideID, 'slide_media_box_settings_media_box_spacing', value);
                addSlideJSVars(slideID, ' .header-description-bottom.media', 'margin', 'all', value.top + ' 0 ' + value.bottom + ' 0');
                addSlideJSVars(slideID, ' .header-description-top.media', 'margin', 'all', value.top + ' 0 ' + value.bottom + ' 0');
            });
            slideMediaBoxSettingsFrameType.attachWithSetter(slide.slide_media_box_settings_frame_type, function (value) {

                if (jQuery.inArray(value, ['border', 'background']) == -1) {
                    slideMediaBoxSettingsFrameWidth.hide();
                    slideMediaBoxSettingsFrameHeight.hide();
                    slideMediaBoxSettingsFrameOffsetLeft.hide();
                    slideMediaBoxSettingsFrameOffsetTop.hide();
                    slideMediaBoxSettingsFrameColor.hide();
                    slideMediaBoxSettingsFrameShowOverImage.hide();
                    slideMediaBoxSettingsFrameShowShadow.hide();
                    slideMediaBoxSettingsFrameHideOnMobile.hide();
                }
                else {
                    slideMediaBoxSettingsFrameWidth.show();
                    slideMediaBoxSettingsFrameHeight.show();
                    slideMediaBoxSettingsFrameOffsetLeft.show();
                    slideMediaBoxSettingsFrameOffsetTop.show();
                    slideMediaBoxSettingsFrameColor.show();
                    slideMediaBoxSettingsFrameShowOverImage.show();
                    slideMediaBoxSettingsFrameShowShadow.show();
                    slideMediaBoxSettingsFrameHideOnMobile.show();
                }

                if (value !== 'border') {
                    slideMediaBoxSettingsFrameThickness.hide();
                }
                else {
                    slideMediaBoxSettingsFrameThickness.show();
                }

                // slide.slide_media_box_settings_frame_type = value;
                setSlideValue(slideID, 'slide_media_box_settings_frame_type', value, true);
            });
            slideMediaBoxSettingsFrameWidth.attachWithSetter(slide.slide_media_box_settings_frame_width, function (value) {
                // slide.slide_media_box_settings_frame_width = value;
                setSlideValue(slideID, 'slide_media_box_settings_frame_width', value);
                addSlideJSVars(slideID, ' .header-description .overlay-box-offset', 'width', 'all', value + '%!important');
            });
            slideMediaBoxSettingsFrameHeight.attachWithSetter(slide.slide_media_box_settings_frame_height, function (value) {
                // slide.slide_media_box_settings_frame_height = value;
                setSlideValue(slideID, 'slide_media_box_settings_frame_height', value);
                addSlideJSVars(slideID, ' .header-description .overlay-box-offset', 'height', 'all', value + '%!important');
            });
            slideMediaBoxSettingsFrameOffsetLeft.attachWithSetter(slide.slide_media_box_settings_frame_offset_left, function (value) {
                // slide.slide_media_box_settings_frame_offset_left = value;
                setSlideValue(slideID, 'slide_media_box_settings_frame_offset_left', value);
                addSlideJSVars(slideID, ' .header-description .overlay-box-offset', 'transform', 'all', 'translate(' + value + '%,' + slideMediaBoxSettingsFrameOffsetTop.control.setting.get() + '%)!important');
            });
            slideMediaBoxSettingsFrameOffsetTop.attachWithSetter(slide.slide_media_box_settings_frame_offset_top, function (value) {
                // slide.slide_media_box_settings_frame_offset_top = value;
                setSlideValue(slideID, 'slide_media_box_settings_frame_offset_top', value);
                addSlideJSVars(slideID, ' .header-description .overlay-box-offset', 'transform', 'all', 'translate(' + slideMediaBoxSettingsFrameOffsetLeft.control.setting.get() + '%,' + value + '%)!important');
            });
            slideMediaBoxSettingsFrameThickness.attachWithSetter(slide.slide_media_box_settings_frame_thickness, function (value) {
                // slide.slide_media_box_settings_frame_thickness = value;
                setSlideValue(slideID, 'slide_media_box_settings_frame_thickness', value);
                addSlideJSVars(slideID, ' .header-description .overlay-box-offset', 'border-width', 'all', value + 'px!important');
            });
            slideMediaBoxSettingsFrameColor.attachWithSetter(slide.slide_media_box_settings_frame_color, function (value) {
                // slide.slide_media_box_settings_frame_color = value;
                setSlideValue(slideID, 'slide_media_box_settings_frame_color', value);
                addSlideJSVars(slideID, ' .header-description .overlay-box-offset', slideMediaBoxSettingsFrameType.control.setting.get() + '-color', 'all', value + '!important');
            });
            slideMediaBoxSettingsFrameShowOverImage.attachWithSetter(slide.slide_media_box_settings_frame_show_over_image, function (value) {
                // slide.slide_media_box_settings_frame_show_over_image = value;
                setSlideValue(slideID, 'slide_media_box_settings_frame_show_over_image', value);
                addSlideJSVars(slideID, ' .header-description .overlay-box-offset', 'z-index', 'all', value ? "1!important" : "-1!important");
            });
            slideMediaBoxSettingsFrameShowShadow.attachWithSetter(slide.slide_media_box_settings_frame_show_shadow, function (value) {
                // slide.slide_media_box_settings_frame_show_shadow = value;
                setSlideValue(slideID, 'slide_media_box_settings_frame_show_shadow', value);

                if (value) {
                    CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.header-description .overlay-box-offset').addClass('shadow-medium');
                }
                else {
                    CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.header-description .overlay-box-offset').removeClass('shadow-medium');
                }
            });
            slideMediaBoxSettingsFrameHideOnMobile.attachWithSetter(slide.slide_media_box_settings_frame_hide_on_mobile, function (value) {
                // slide.slide_media_box_settings_frame_hide_on_mobile = value;
                setSlideValue(slideID, 'slide_media_box_settings_frame_hide_on_mobile', value);

                if (value) {
                    CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.header-description .overlay-box-offset').addClass('hide-xs');
                }
                else {
                    CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID).find('.header-description .overlay-box-offset').removeClass('hide-xs');
                }
            });

            // Title, subtitles, buttons setters
            slideShowTitle.attachWithSetter(slide.slide_show_title, function (value) {
                if (value === true) slideTitleOptionsGroupButton.show();
                else slideTitleOptionsGroupButton.hide();

                // slide.slide_show_title = value;
                setSlideValue(slideID, 'slide_show_title', value, true);
            });
            slideTitleOptionsTitleTypography.attachWithSetter(slide.slide_title_options_title_typography, function (value) {
                // slide.slide_title_options_title_typography = value;
                setSlideValue(slideID, 'slide_title_options_title_typography', value);

                jQuery.each(value, function (property, cssvalue) {
                    if (property != 'mobile-font-size' && property != 'addwebfont' && property != 'variant') {
                        addSlideJSVars(slideID, ' h1.slide-title', property, 'all', cssvalue);
                    }
                    if (property == 'variant') {
                        if (isNaN(cssvalue)) {
                            addSlideJSVars(slideID, ' h1.slide-title', 'font-weight', 'all', parseInt(cssvalue));
                            addSlideJSVars(slideID, ' h1.slide-title', 'font-style', 'all', 'italic');
                        }
                        else {
                            addSlideJSVars(slideID, ' h1.slide-title', 'font-weight', 'all', cssvalue);
                            addSlideJSVars(slideID, ' h1.slide-title', 'font-style', 'all', 'normal');
                        }
                    }
                });

                if (value['mobile-font-size']) {
                    addSlideJSVars(slideID, ' h1.slide-title', 'font-size', 'mobile', value['mobile-font-size']);
                }
            });
            slideTitleOptionsTitleSpacing.attachWithSetter(slide.slide_title_options_title_spacing, function (value) {
                // slide.slide_title_options_title_spacing = value;
                setSlideValue(slideID, 'slide_title_options_title_spacing', value);
                addSlideJSVars(slideID, ' h1.slide-title', 'margin-top', 'all', value.top);
                addSlideJSVars(slideID, ' h1.slide-title', 'margin-bottom', 'all', value.bottom);
            });
            slideTitleOptionsBackgroundColor.attachWithSetter(slide.slide_title_options_background_color, function (value) {
                // slide.slide_title_options_background_color = value;
                setSlideValue(slideID, 'slide_title_options_background_color', value);
                addSlideJSVars(slideID, ' h1.slide-title', 'background', 'all', value);
            });
            slideTitleOptionsBackgroundSpacing.attachWithSetter(slide.slide_title_options_background_spacing, function (value) {
                // slide.slide_title_options_background_spacing = value;
                setSlideValue(slideID, 'slide_title_options_background_spacing', value);
                addSlideJSVars(slideID, ' h1.slide-title', 'padding', 'all', value.top + ' ' + value.right + ' ' + value.bottom + ' ' + value.left);
            });
            slideTitleOptionsBackgroundBorderRadius.attachWithSetter(slide.slide_title_options_background_border_radius, function (value) {
                setSlideValue(slideID, 'slide_title_options_background_border_radius', parseInt(value));
                addSlideJSVars(slideID, ' h1.slide-title', 'border-radius', 'all', parseInt(value) + 'px');
            });
            slideTitleOptionsBackgroundBorderColor.attachWithSetter(slide.slide_title_options_background_border_color, function (value) {
                // slide.slide_title_options_background_border_color = value;
                setSlideValue(slideID, 'slide_title_options_background_border_color', value);
                addSlideJSVars(slideID, ' h1.slide-title', 'border-color', 'all', value);
            });
            slideTitleOptionsBackgroundBorderThickness.attachWithSetter(slide.slide_title_options_background_border_thickness, function (value) {
                // slide.slide_title_options_background_border_thickness = value;
                setSlideValue(slideID, 'slide_title_options_background_border_thickness', value);
                addSlideJSVars(slideID, ' h1.slide-title', 'border-width', 'all', value.top + ' ' + value.right + ' ' + value.bottom + ' ' + value.left);
            });
            slideTitleOptionsEnableTextAnimation.attachWithSetter(slide.slide_title_options_enable_text_animation, function (value) {
                if (value === true) {
                    slideTitleOptionsTextAnimationInfo.show();
                    slideTitleOptionsTextAnimationAlternatives.show();
                }
                else {
                    slideTitleOptionsTextAnimationInfo.hide();
                    slideTitleOptionsTextAnimationAlternatives.hide();
                }

                // slide.slide_title_options_enable_text_animation = value;
                setSlideValue(slideID, 'slide_title_options_enable_text_animation', value);
            });
            slideTitleOptionsTextAnimationAlternatives.attachWithSetter(slide.slide_title_options_text_animation_alternatives, function (value) {
                // slide.slide_title_options_text_animation_alternatives = value;
                setSlideValue(slideID, 'slide_title_options_text_animation_alternatives', value);
            });

            slideShowSubtitle.attachWithSetter(slide.slide_show_subtitle, function (value) {
                if (value === true) slideSubtitleOptionsGroupButton.show();
                else slideSubtitleOptionsGroupButton.hide();

                // slide.slide_show_subtitle = value;
                setSlideValue(slideID, 'slide_show_subtitle', value, true);
            });
            slideSubtitleOptionsSubtitleTypography.attachWithSetter(slide.slide_subtitle_options_subtitle_typography, function (value) {
                // slide.slide_subtitle_options_subtitle_typography = value;
                setSlideValue(slideID, 'slide_subtitle_options_subtitle_typography', value);

                jQuery.each(value, function (property, cssvalue) {

                    if (property != 'mobile-font-size' && property != 'addwebfont' && property != 'variant') {
                        addSlideJSVars(slideID, ' .slide-subtitle', property, 'all', cssvalue);
                    }
                    if (property == 'variant') {
                        if (isNaN(cssvalue)) {
                            addSlideJSVars(slideID, ' .slide-subtitle', 'font-weight', 'all', parseInt(cssvalue));
                            addSlideJSVars(slideID, ' .slide-subtitle', 'font-style', 'all', 'italic');
                        }
                        else {
                            addSlideJSVars(slideID, ' .slide-subtitle', 'font-weight', 'all', cssvalue);
                            addSlideJSVars(slideID, ' .slide-subtitle', 'font-style', 'all', 'normal');
                        }
                    }
                });


                if (value['mobile-font-size']) {
                    addSlideJSVars(slideID, ' h1.slide-title', 'font-size', 'mobile', value['mobile-font-size']);
                }
            });
            slideSubtitleOptionsSubtitleSpacing.attachWithSetter(slide.slide_subtitle_options_subtitle_spacing, function (value) {
                // slide.slide_subtitle_options_subtitle_spacing = value;
                setSlideValue(slideID, 'slide_subtitle_options_subtitle_spacing', value);
                addSlideJSVars(slideID, ' .slide-subtitle', 'margin-top', 'all', value.top);
                addSlideJSVars(slideID, ' .slide-subtitle', 'margin-bottom', 'all', value.bottom);
            });
            slideSubtitleOptionsBackgroundColor.attachWithSetter(slide.slide_subtitle_options_background_color, function (value) {
                // slide.slide_subtitle_options_background_color = value;
                setSlideValue(slideID, 'slide_subtitle_options_background_color', value);
                addSlideJSVars(slideID, ' .slide-subtitle', 'background', 'all', value);
            });
            slideSubtitleOptionsBackgroundSpacing.attachWithSetter(slide.slide_subtitle_options_background_spacing, function (value) {
                // slide.slide_subtitle_options_background_spacing = value;
                setSlideValue(slideID, 'slide_subtitle_options_background_spacing', value);
                addSlideJSVars(slideID, ' .slide-subtitle', 'padding', 'all', value.top + ' ' + value.right + ' ' + value.bottom + ' ' + value.left);
            });
            slideSubtitleOptionsBackgroundBorderRadius.attachWithSetter(slide.slide_subtitle_options_background_border_radius, function (value) {
                // slide.slide_subtitle_options_background_border_radius = parseInt(value);
                setSlideValue(slideID, 'slide_subtitle_options_background_border_radius', parseInt(value));
                addSlideJSVars(slideID, ' .slide-subtitle', 'border-radius', 'all', parseInt(value) + 'px');
            });
            slideSubtitleOptionsBackgroundBorderColor.attachWithSetter(slide.slide_subtitle_options_background_border_color, function (value) {
                // slide.slide_subtitle_options_background_border_color = value;
                setSlideValue(slideID, 'slide_subtitle_options_background_border_color', value);
                addSlideJSVars(slideID, ' .slide-subtitle', 'border-color', 'all', value);
            });
            slideSubtitleOptionsBackgroundBorderThickness.attachWithSetter(slide.slide_subtitle_options_background_border_thickness, function (value) {
                // slide.slide_subtitle_options_background_border_thickness = value;
                setSlideValue(slideID, 'slide_subtitle_options_background_border_thickness', value);
                addSlideJSVars(slideID, ' .slide-subtitle', 'border-width', 'all', value.top + ' ' + value.right + ' ' + value.bottom + ' ' + value.left);
            });

            slideShowSubtitle2.attachWithSetter(slide.slide_show_subtitle2, function (value) {
                if (value === true) slideSubtitle2OptionsGroupButton.show();
                else slideSubtitle2OptionsGroupButton.hide();

                // slide.slide_show_subtitle2 = value;
                setSlideValue(slideID, 'slide_show_subtitle2', value, true);
            });
            slideSubtitle2OptionsSubtitle2Typography.attachWithSetter(slide.slide_subtitle2_options_subtitle2_typography, function (value) {
                // slide.slide_subtitle2_options_subtitle2_typography = value;
                setSlideValue(slideID, 'slide_subtitle2_options_subtitle2_typography', value);

                jQuery.each(value, function (property, cssvalue) {

                    if (property != 'mobile-font-size' && property != 'addwebfont' && property != 'variant') {
                        addSlideJSVars(slideID, ' .slide-subtitle2', property, 'all', cssvalue);
                    }
                    if (property == 'variant') {
                        if (isNaN(cssvalue)) {
                            addSlideJSVars(slideID, ' .slide-subtitle2', 'font-weight', 'all', parseInt(cssvalue));
                            addSlideJSVars(slideID, ' .slide-subtitle2', 'font-style', 'all', 'italic');
                        }
                        else {
                            addSlideJSVars(slideID, ' .slide-subtitle2', 'font-weight', 'all', cssvalue);
                            addSlideJSVars(slideID, ' .slide-subtitle2', 'font-style', 'all', 'normal');
                        }
                    }
                });

                if (value['mobile-font-size']) {
                    addSlideJSVars(slideID, ' h1.slide-title', 'font-size', 'mobile', value['mobile-font-size']);
                }
            });
            slideSubtitle2OptionsSubtitle2Spacing.attachWithSetter(slide.slide_subtitle2_options_subtitle2_spacing, function (value) {
                // slide.slide_subtitle2_options_subtitle2_spacing = value;
                setSlideValue(slideID, 'slide_subtitle2_options_subtitle2_spacing', value);
                addSlideJSVars(slideID, ' .slide-subtitle2', 'margin-top', 'all', value.top);
                addSlideJSVars(slideID, ' .slide-subtitle2', 'margin-bottom', 'all', value.bottom);
            });
            slideSubtitle2OptionsBackgroundColor.attachWithSetter(slide.slide_subtitle2_options_background_color, function (value) {
                // slide.slide_subtitle2_options_background_color = value;
                setSlideValue(slideID, 'slide_subtitle2_options_background_color', value);
                addSlideJSVars(slideID, ' .slide-subtitle2', 'background', 'all', value);
            });
            slideSubtitle2OptionsBackgroundSpacing.attachWithSetter(slide.slide_subtitle2_options_background_spacing, function (value) {
                // slide.slide_subtitle2_options_background_spacing = value;
                setSlideValue(slideID, 'slide_subtitle2_options_background_spacing', value);
                addSlideJSVars(slideID, ' .slide-subtitle2', 'padding', 'all', value.top + ' ' + value.right + ' ' + value.bottom + ' ' + value.left);
            });
            slideSubtitle2OptionsBackgroundBorderRadius.attachWithSetter(slide.slide_subtitle2_options_background_border_radius, function (value) {
                setSlideValue(slideID, 'slide_subtitle2_options_background_border_radius', parseInt(value));
                addSlideJSVars(slideID, ' .slide-subtitle2', 'border-radius', 'all', parseInt(value) + 'px');
            });
            slideSubtitle2OptionsBackgroundBorderColor.attachWithSetter(slide.slide_subtitle2_options_background_border_color, function (value) {
                // slide.slide_subtitle2_options_background_border_color = value;
                setSlideValue(slideID, 'slide_subtitle2_options_background_border_color', value);
                addSlideJSVars(slideID, ' .slide-subtitle2', 'border-color', 'all', value);
            });
            slideSubtitle2OptionsBackgroundBorderThickness.attachWithSetter(slide.slide_subtitle2_options_background_border_thickness, function (value) {
                // slide.slide_subtitle2_options_background_border_thickness = value;
                setSlideValue(slideID, 'slide_subtitle2_options_background_border_thickness', value);
                addSlideJSVars(slideID, ' .slide-subtitle2', 'border-width', 'all', value.top + ' ' + value.right + ' ' + value.bottom + ' ' + value.left);
            });

            slideShowButtons.attachWithSetter(slide.slide_show_buttons, function (value) {
                if (value === true) slideButtonsOptionsGroupButton.show();
                else slideButtonsOptionsGroupButton.hide();

                // slide.slide_show_buttons = value;
                setSlideValue(slideID, 'slide_show_buttons', value, true);
            });
            slideButtonsOptionsButtonsType.attachWithSetter(slide.slide_buttons_options_buttons_type, function (value) {
                if (value == 'normal') {
                    slideButtonsOptionsNormalButtons.show();
                    slideButtonsOptionsStoreButtons.hide();
                }
                else {
                    slideButtonsOptionsNormalButtons.hide();
                    slideButtonsOptionsStoreButtons.show();
                }

                // slide.slide_buttons_options_buttons_type = value;
                setSlideValue(slideID, 'slide_buttons_options_buttons_type', value, true);
            });


            var _normalButtonTemplate = CP_Customizer.jsTPL.compile('<a ' +
                'class="{{{ data.class }}}" ' +
                'target="{{{ data.target }}}" ' +
                'href="{{{ data.url }}}" ' +
                'data-theme="{{{ data.identifier }}}|label" ' +
                'data-theme-href="{{{ data.identifier }}}|url" ' +
                'data-theme-target="{{{ data.identifier }}}|target" ' +
                'data-theme-class="{{{ data.identifier }}}|class" ' +
                'data-dynamic-mod="true" data-cpid="new_cp_node_342" ' +
                'data-container-editable="true" ' +
                'data-content-code-editable="true"><# print(data.content) #></a>');

            slideButtonsOptionsNormalButtons.attachWithSetter(slide.slide_buttons_options_normal_buttons, function (newValue, oldValue) {

                newValue = CP_Customizer.utils.normalizeValue(newValue, true);


                var $buttons = CP_Customizer.preview.jQuery('[data-theme^="slider_elements|' + slideID + '|slide_buttons_options_normal_buttons"]'),
                    $container = CP_Customizer.preview.jQuery('#header-slide-' + slideID + ' .header-buttons-wrapper');

                $buttons.remove();

                newValue.forEach(function (item, index) {
                    var viewData = {
                        identifier: 'slider_elements|' + slideID + '|slide_buttons_options_normal_buttons|' + index,
                        content: item.label,
                        class: item.class,
                        target: item.target,
                        url: item.url
                    };

                    var button = CP_Customizer.preview.jQuery(_normalButtonTemplate({
                        data: viewData
                    }));

                    button.data('was-changed', true);
                    CP_Customizer.preview.markNode(button, 'slider-button-');
                    $container.append(button);
                });

                CP_Customizer.preview.decorateElements($container);


                //
                // $buttons.each(function (index) {
                //     var $button = CP_Customizer.preview.jQuery(this);
                //
                //     $button.attr('class', newValue[index].class);
                //     $button.attr('href', newValue[index].url);
                //     $button.attr('target', newValue[index].target || "_self");
                //
                //     $button.html(newValue[index].label);
                //     $button.data('was-changed', true);
                //
                //     CP_Customizer.preview.markNode($button);
                // });

                setSlideValue(slideID, 'slide_buttons_options_normal_buttons', newValue);
                // }
                // else {
                //     setSlideValue(slideID, 'slide_buttons_options_normal_buttons', newValue, true);
                // }

            });

            slideButtonsOptionsStoreButtons.attachWithSetter(slide.slide_buttons_options_store_buttons, function (newValue, oldValue) {

                // slide.slide_buttons_options_store_buttons = newValue;
                setSlideValue(slideID, 'slide_buttons_options_store_buttons', newValue, true);

            });

            slideButtonsOptionsBackgroundColor.attachWithSetter(slide.slide_buttons_options_background_color, function (value) {
                // slide.slide_buttons_options_background_color = value;
                setSlideValue(slideID, 'slide_buttons_options_background_color', value);
                addSlideJSVars(slideID, ' .header-buttons-wrapper', 'background', 'all', value);
            });
            slideButtonsOptionsBackgroundSpacing.attachWithSetter(slide.slide_buttons_options_background_spacing, function (value) {
                // slide.slide_buttons_options_background_spacing = value;
                setSlideValue(slideID, 'slide_buttons_options_background_spacing', value);
                addSlideJSVars(slideID, ' .header-buttons-wrapper', 'padding', 'all', value.top + ' ' + value.right + ' ' + value.bottom + ' ' + value.left);
            });
            slideButtonsOptionsBackgroundBorderRadius.attachWithSetter(slide.slide_buttons_options_background_border_radius, function (value) {
                setSlideValue(slideID, 'slide_buttons_options_background_border_radius', parseInt(value));
                addSlideJSVars(slideID, ' .header-buttons-wrapper', 'border-radius', 'all', parseInt(value) + 'px');
            });
            slideButtonsOptionsBackgroundBorderColor.attachWithSetter(slide.slide_buttons_options_background_border_color, function (value) {
                // slide.slide_buttons_options_background_border_color = value;
                setSlideValue(slideID, 'slide_buttons_options_background_border_color', value);
                addSlideJSVars(slideID, ' .header-buttons-wrapper', 'border-color', 'all', value);
            });
            slideButtonsOptionsBackgroundBorderThickness.attachWithSetter(slide.slide_buttons_options_background_border_thickness, function (value) {
                // slide.slide_buttons_options_background_border_thickness = value;
                setSlideValue(slideID, 'slide_buttons_options_background_border_thickness', value);
                addSlideJSVars(slideID, ' .header-buttons-wrapper', 'border-width', 'all', value.top + ' ' + value.right + ' ' + value.bottom + ' ' + value.left);
            });


            // hide content media type if no media layout is selected
            if (jQuery.inArray(slideContentLayout.control.setting.get(), ['content-on-center', 'content-on-right', 'content-on-left']) >= 0) {
                slideContentMediaType.hide();
                slideMediaBoxSettingsGroupButton.hide();
            }

            // only show text/media vertical align for media on left/right
            if (jQuery.inArray(slideContentLayout.control.setting.get(), ['media-on-left', 'media-on-right']) == -1) {
                slideTextBoxSettingsTextVerticalAlign.hide();
                slideMediaBoxSettingsMediaVerticalAlign.hide();
            }

            // show correct media controls based on media type
            if (slideContentMediaType.control.setting.get() == 'image') {
                slideMediaBoxSettingsVideoSettingsSeparator.hide();
                slideMediaBoxSettingsContentVideo.hide();
            }
            else {
                slideMediaBoxSettingsImageSettingsSeparator.hide();
                slideMediaBoxSettingsMakeImageRound.hide();
                slideMediaBoxSettingsMediaImage.hide();
            }

            // only show image border color/thickness for media type image and make image round checked
            if (slideContentMediaType.control.setting.get() !== 'image' || slideMediaBoxSettingsMakeImageRound.control.setting.get() != true) {
                slideMediaBoxSettingsImageBorderColor.hide();
                slideMediaBoxSettingsImageBorderThickness.hide();
            }

            // only show autoplay/loop video for media type video
            if (slideContentMediaType.control.setting.get() !== 'video') {
                slideMediaBoxSettingsAutoplayVideo.hide();
                slideMediaBoxSettingsAutoplayVideoInfo.hide();
                slideMediaBoxSettingsLoopVideo.hide();
            }

            // only show video popup button's icon color, icon hover color, poster when media type is video popup button
            if (slideContentMediaType.control.setting.get() !== 'video_popup') {
                slideMediaBoxSettingsVideoIconColor.hide();
                slideMediaBoxSettingsVideoIconHoverColor.hide();
                slideMediaBoxSettingsHideVideoPoster.hide();
            }

            // only show video poster controls for media type popup button and hide video poster unchecked
            if (slideContentMediaType.control.setting.get() !== 'video_popup' || slideMediaBoxSettingsHideVideoPoster.control.setting.get() === true) {
                slideMediaBoxSettingsVideoPoster.hide();
                slideMediaBoxSettingsVideoPosterOverlayColor.hide();
            }

            // only show media width for media on top/Bottom
            if (jQuery.inArray(slideContentLayout.control.setting.get(), ['media-on-top', 'media-on-bottom']) == -1) {
                slideMediaBoxSettingsMediaTopBottomWidth.hide();
            }

            // only show media width for media on left/right
            if (jQuery.inArray(slideContentLayout.control.setting.get(), ['media-on-left', 'media-on-right']) == -1) {
                slideMediaBoxSettingsMediaLeftRightWidth.hide();
            }

            // only show media box spacing for media on top/Bottom
            if (jQuery.inArray(slideContentLayout.control.setting.get(), ['media-on-top', 'media-on-bottom']) == -1) {
                slideMediaBoxSettingsMediaBoxSpacing.hide();
            }

            // only show frame options separator/frame type for media type image
            if (slideContentMediaType.control.setting.get() !== 'image') {
                slideMediaBoxSettingsFrameOptionsSeparator.hide();
                slideMediaBoxSettingsFrameType.hide();
            }

            // only show frame width/height/offsetleft/offsettop/color for media type image and frame type border/background
            if (slideContentMediaType.control.setting.get() !== 'image' ||
                jQuery.inArray(slideMediaBoxSettingsFrameType.control.setting.get(), ['border', 'background']) == -1) {
                slideMediaBoxSettingsFrameWidth.hide();
                slideMediaBoxSettingsFrameHeight.hide();
                slideMediaBoxSettingsFrameOffsetLeft.hide();
                slideMediaBoxSettingsFrameOffsetTop.hide();
                slideMediaBoxSettingsFrameColor.hide();
                slideMediaBoxSettingsFrameShowOverImage.hide();
                slideMediaBoxSettingsFrameShowShadow.hide();
                slideMediaBoxSettingsFrameHideOnMobile.hide();
            }

            // only show frame thickness for media type image and frame type border
            if (slideContentMediaType.control.setting.get() !== 'image' || slideMediaBoxSettingsFrameType.control.setting.get() !== 'border') {
                slideMediaBoxSettingsFrameThickness.hide();
            }

            // hide title, subtitle textarea
            slideTitleOptionsTitleText.hide();
            slideSubtitleOptionsSubtitleText.hide();
            slideSubtitle2OptionsSubtitle2Text.hide();

            // only show text animation info/textarea when enable text animation is checked
            if (slideTitleOptionsEnableTextAnimation.control.setting.get() === false) {
                slideTitleOptionsTextAnimationInfo.hide();
                slideTitleOptionsTextAnimationAlternatives.hide();
            }

            // show correct button
            if (slideButtonsOptionsButtonsType.control.setting.get() == 'normal') {
                slideButtonsOptionsStoreButtons.hide();
            }
            else {
                slideButtonsOptionsNormalButtons.hide();
            }


            /*--------> END SLIDE CONTENT LAYOUT CONTROLS AND FUNCTIONS <-----------------*/


            return true;

        }

        function generateSlideContent(slideLabel, slide, index) {

            var slideContent = jQuery('' +
                '<li class="slider-row minimized" data-id="' + slide + '" data-field="' + sliderID + '">' +
                '   <div class="slider-row-header">' +
                '       <div class="reorder-handler"></div>' +
                '       <span class="slider-row-label">' + slideLabel + ' ' + (index + 1) + '</span>' +
                '       <i class="dashicons dashicons-arrow-down"></i>' +
                '   </div>' +
                '   <div class="slider-row-actions">' +
                '       <i title="' + CP_Customizer.translateCompanionString('Duplicate') + '" class="dashicons dashicons-admin-page slider-row-duplicate"></i>' +
                '       <i title="' + CP_Customizer.translateCompanionString('Remove') + '" class="dashicons dashicons-trash slider-row-remove"></i>' +
                '   </div>' +
                '   <div id="slide-content-' + slide + '" class="slide-content"></div>' +
                '</li>');

            return slideContent;
        }

        function setSlideValue(slideID, prop, value, refresh) {


            if (value === undefined) {
                CP_Customizer.logError('Empty slider value', {
                    prop: prop,
                    refresh: refresh,
                    slideID: slideID
                });
            }

            CP_Customizer.log('Set slider option. Slide id = ' + slideID, {
                prop: prop,
                value: value,
                refresh: refresh
            });

            var currentSlides = CP_Customizer.utils.deepClone(CP_Customizer.getMod('slider_elements'));
            currentSlides[slideID][prop] = value;


            CP_Customizer.setMod('slider_elements', currentSlides);
            if (refresh) {
                CP_Customizer.showLoader();
                CP_Customizer.setContent(function () {
                    CP_Customizer.setMod('slider_elements', currentSlides, 'refresh');
                });
            } else {
                CP_Customizer.setMod('slider_elements', currentSlides);
            }

            return true;
        }

        function addSlideJSVars(slideID, element, property, size, value) {

            if (CP_Customizer.preview.jQuery()) {
                addStyle();
            } else {
                CP_Customizer.on(CP_Customizer.events.PREVIEW_LOADED, addStyle);
            }

            function addStyle() {
                // var styleClass = (slideID + '-' + element + '-' + property + '-' + size).replace('.', '-').replace(':', '-').replace('#', '-').replace('--', '-');
                var styleClass = CP_Customizer.getSlug(["slider-", slideID, element, property, size].join('-'));

                if ((value + "").indexOf('!important') === -1) {
                    value += "!important";
                }

                if (size === 'all') {
                    // CP_Customizer.preview.find('#header-slides-container #header-slide-' + slideID + element).css(property, value);
                    if (CP_Customizer.preview.find('#' + styleClass).length) {
                        CP_Customizer.preview.find('#' + styleClass).html(
                            "#header-slides-container #header-slide-" + slideID + element + "{ " + property + ": " + value + ";" + "}"
                        );
                    }
                    else {
                        var toAppendOnHead =
                            "<style id=" + styleClass + ">" +
                            "#header-slides-container #header-slide-" + slideID + element + "{ " + property + ": " + value + ";" + "}" +
                            "</style>";
                        CP_Customizer.preview.find('head').append(toAppendOnHead);
                    }
                }
                if (size === 'mobile') {
                    if (CP_Customizer.preview.find('#' + styleClass).length) {
                        CP_Customizer.preview.find('#' + styleClass).html(
                            "@media screen and (max-width:767px) {" +
                            "#header-slides-container #header-slide-" + slideID + element + "{ " + property + ": " + value + ";" + "}" +
                            "}"
                        );
                    }
                    else {
                        var toAppendOnHead =
                            "<style id=" + styleClass + ">" +
                            "@media screen and (max-width:767px) {" +
                            "#header-slides-container #header-slide-" + slideID + element + "{ " + property + ": " + value + ";" + "}" +
                            "}" +
                            "</style>";
                        CP_Customizer.preview.find('head').append(toAppendOnHead);
                    }
                }
                if (size === 'desktop') {
                    if (CP_Customizer.preview.find('#' + styleClass).length) {
                        CP_Customizer.preview.find('#' + styleClass).html(
                            "@media screen and (min-width:768px) {" +
                            "#header-slides-container #header-slide-" + slideID + element + "{ " + property + ": " + value + ";" + "}" +
                            "}"
                        );
                    }
                    else {
                        var toAppendOnHead =
                            "<style id=" + styleClass + ">" +
                            "@media screen and (min-width:768px) {" +
                            "#header-slides-container #header-slide-" + slideID + element + "{ " + property + ": " + value + ";" + "}" +
                            "}" +
                            "</style>";
                        CP_Customizer.preview.find('head').append(toAppendOnHead);
                    }
                }

            }

            return;

        }

        function getSlideGradientValue(settingValue) {
            var colors = settingValue.colors;
            var angle = settingValue.angle;
            angle = parseFloat(angle);
            return Mesmerize.Utils.getGradientString(colors, angle);
        }

        function reorderSlidesNumbers() {
            sliderContainer.children().each(function (index) {
                jQuery(this).find('.slider-row-label').html(slideLabel + ' ' + (index + 1));

                jQuery(this).find('.slide-content > .customize-control-sectionseparator .customize-control-title').each(function () {
                    jQuery(this).html(jQuery(this).html().replace(new RegExp("[0-9]", "g"), (index + 1)));
                });
            });
        }

        function arraysHaveSameObjects(oldVal, newVal) {

            var foundCounter = 0;
            var same = false;

            jQuery.each(newVal, function (nkey, nelement) {

                var found = false;

                jQuery.each(oldVal, function (okey, oelement) {
                    if (JSON.stringify(nelement) === JSON.stringify(oelement)) {
                        found = true;
                    }
                });

                if (found) {
                    foundCounter++;
                }

            });

            if (foundCounter == newVal.length && JSON.stringify(oldVal) !== JSON.stringify(newVal)) {
                same = true;
            }

            return same;
        }

    }
});


(function (root, CP_Customizer, $) {
    CP_Customizer.addModule(function () {
        CP_Customizer.hooks.addFilter('can_edit_icon', function (value, element) {

            if ($(element).closest('.header-slider-navigation').length) {
                CP_Customizer.preview.blur(true);
                return false;
            }

            return value;
        });

        CP_Customizer.on(CP_Customizer.events.FOCUS_CONTROL, function (event, settingID) {

            if (settingID.indexOf('slide_') !== 0) {
                return;
            }

            var setting = CP_Customizer.createControl.getInstance(settingID);

            if (setting) {
                var control = setting.control;
                var slide = control.container.closest('[data-field="slider_elements"]');
                CP_Customizer.wpApi.control('slider_elements').focus();
                if (slide.hasClass('minimized')) {
                    slide.find('.slider-row-label').trigger('click');
                }

                if (control.container.closest('.customizer-right-section').length) {
                    var sidebarID = control.container.closest('.customizer-right-section').attr('id').replace('-popup', '');
                    jQuery('[data-sidebar-container="' + sidebarID + '"]').click();
                }
            }
        });
    })


})(window, CP_Customizer, jQuery);
