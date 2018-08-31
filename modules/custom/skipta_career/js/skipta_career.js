(function ($) {
  Drupal.behaviors.skipta_career = {
    attach: function (context, settings) {


      // career js starts

      radiusValue = $('input[data-drupal-selector="edit-career-radius"]').val();

      $('#redius-info').html('<b>Radius Value: </b>' + radiusValue + ' miles');

      $('input[data-drupal-selector="edit-career-radius"]').on('change', function () {
        radiusValue = $('input[data-drupal-selector="edit-career-radius"]').val();
        $('input[data-drupal-selector="edit-career-radius"]').attr('title', radiusValue + ' miles');
        $('#redius-info').html('<b>Radius Value: </b>' + radiusValue + ' miles');

        // console.log(radiusValue);
      });

      $('#edit-reset').on('click', function (event) {
        event.preventDefault();
        this.form.reset();
        radiusValue = $('input[data-drupal-selector="edit-career-radius"]').val();
        $('input[data-drupal-selector="edit-career-radius"]').attr('title', radiusValue + ' miles');
        $('#redius-info').html('<b>Radius Value: </b>' + radiusValue + ' miles');
        location.reload();
        return false;
      });
      
      stream_post_count = $('.skipta-news').attr('data-cnt');
      stream_post_ajax = $('.skipta-news').attr('data-ajax');
     // console.log(stream_post_count);
      
      if(stream_post_count < 6 && stream_post_ajax == 1){
        $('nav.pager-nav').remove();
        console.log('done');
      }

      $("#skipta-career-wrapper ul.pagination li a").each(function () {
        link = $(this).attr('href');

        if (link.indexOf("ajax_form=1&") > -1) {
          link = link.replace('ajax_form=1&', '')
        }
        if (link.indexOf("%2C0") > -1) {
          link = link.replace('%2C0', '')
        }

        $(this).attr('href', link);
//        console.log(link);
//
      });



// career js ends

    }
  }
})(jQuery);
