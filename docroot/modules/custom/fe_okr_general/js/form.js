(function ($, Drupal) {
  Drupal.behaviors.jsAssignment = {
    attach: function attach(context, settings) {
      // Disable submit button on change select list.
      $('#edit-type').on('keyup change',function() {
        $('#assignmentSubmit').prop('disabled', true).addClass('is-disabled');
      });

      $("[id|='edit-childtag']").on("autocompletechange", function(event,ui) {
        // Enable submit button if autocomplete has value.
        if ($(this).val() != "") {
          $('#assignmentSubmit').prop('disabled', false).removeClass('is-disabled');
        }
        else {
          $('#assignmentSubmit').prop('disabled', true).addClass('is-disabled');
        }
      });
    }
  };
})(jQuery, Drupal);
