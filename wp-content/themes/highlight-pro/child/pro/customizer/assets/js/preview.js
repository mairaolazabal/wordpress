(function ($) {

  wp.customize('side_navigation_design_preset', function (value) {
      value.bind(function (newval) {
          jQuery('#side-navigation ul').attr('data-preset', newval);
      });
  });

})(jQuery);
