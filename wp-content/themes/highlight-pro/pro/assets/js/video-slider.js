(function ($) {

    function getHandler() {

        for (var id in wp.sliderVideo.handlers) {
            var handle = wp.sliderVideo.handlers[id];

            if (handle.settings) {
                return handle;
            }
            else {
                return false;
            }
        }
    }

    function resizeVideo(videoElementContainer, type, animate, resizePoster) {

        var mesmerize_video_background = jQuery(videoElementContainer).data('mesmerize_video_background');

        videoElementContainer.each(function () {
            if (type == 'video') var $videoElement = jQuery(this).children('video');
            if (type == 'iframe') var $videoElement = jQuery(this).children('iframe');
            var size = mesmerize_video_background.getVideoRect();

            $videoElement.css({
                width: Math.round(size.width),
                height: Math.round(size.height),
                "opacity": 1,
                "left": size.left,
                "max-width": 'initial'
            });
            if (resizePoster) {
                mesmerize_video_background.resizePoster();
            }
        });

        if (animate === false) {
            return;
        }

    }

    window.addEventListener('resize', function () {
        var videoElementContainer = jQuery('.wp-custom-header');
        if (videoElementContainer) {
            resizeVideo(videoElementContainer, 'video', false, true);
            resizeVideo(videoElementContainer, 'iframe', false, true);
        }
    });


    jQuery(function () {

        var videoElementContainer = jQuery('.wp-custom-header');
        if (videoElementContainer) {
            resizeVideo(videoElementContainer, 'video', false, false);
            resizeVideo(videoElementContainer, 'iframe', false, false);
        }

    });

    __cpVideoElementFirstPlayed = false;

    document.addEventListener('wp-custom-header-video-loaded', function () {

        var videoElementContainer = jQuery('.wp-custom-header');

        if (videoElementContainer) {
            resizeVideo(videoElementContainer, 'video', false, false);
//            return;
        }

        document.getElementsByClassName('wp-custom-header')[0].addEventListener('play', function () {

            var videoElementContainer = jQuery('.wp-custom-header');

            if (videoElementContainer && !__cpVideoElementFirstPlayed) {
                __cpVideoElementFirstPlayed = true;
                resizeVideo(videoElementContainer, 'video', false, false);
                resizeVideo(videoElementContainer, 'iframe', false, false);
            }

            var handler = getHandler();
            if (handler) {
                handler.play();
            }

        });

    });

})(jQuery);
