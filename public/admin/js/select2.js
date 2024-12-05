(function($) {
  'use strict';

  if ($(".js-example-basic-single").length) {
    $(".js-example-basic-single").select2();
  }
  if ($(".js-example-basic-multiple").length) {
    $(".js-example-basic-multiple").select2();
  }


  $('.js-example-basic-multiple').on('select2:select', function (e) {
    var data = e.params.data;
    if (data.id === 'all') {
        $('.js-example-basic-multiple option').prop('selected', true);
        $('.js-example-basic-multiple').trigger('change');
    }
});

$('.js-example-basic-multiple').on('select2:unselect', function (e) {
    var data = e.params.data;
    if (data.id === 'all') {
        $('.js-example-basic-multiple option').prop('selected', false);
        $('.js-example-basic-multiple').trigger('change');
    }
});
})(jQuery);
