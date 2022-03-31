(function($, Drupal) {
  $(window).on('load', function() {
    var count = {div: $("div").length, span: $("span").length};
    console.table(count);
  });
})(jQuery, Drupal);
