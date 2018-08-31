(function ($) {
  'use strict';
  Drupal.behaviors.careerPager = {
    attach: function (context, settings) {
      if($('ul.pagination li.pager__item--next a').length) {
        $('.skipta-career').infiniteScroll({
          // options
          path: 'ul.pagination li.pager__item--next a',
          append: '.stream-posts',
          history: false,
          hideNav: '.pagination',
          status: '.scroller-status',
        });
      }
    }
  }
})(jQuery);