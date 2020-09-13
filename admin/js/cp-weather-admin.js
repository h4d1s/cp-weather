(function ($, window, document) {
  "use strict";

  $(document).ready(function () {
    var cityData = {};
    $("#input-city").autocomplete({
      delay: 300,
      minLength: 3,
      source: function (request, response) {
        var $errorMessage = $(".error-message");
        $errorMessage.html("");
        $.ajax({
          type: "POST",
          dataType: "json",
          url: cp_ajax_data.ajax_url,
          data: {
            action: cp_ajax_data.action,
            city: request.term,
            _ajax_nonce: cp_ajax_data.nonce,
          },
          success: function (serverResponse, status, xhr) {
            if (serverResponse.success) {
              $errorMessage.html("");
              response(
                $.map(serverResponse.data, function (value, key) {
                  return {
                    id: key,
                    value: value,
                    label: value,
                  };
                })
              );
            } else {
              $errorMessage.html(serverResponse.data[0].message);
              response([]);
            }
          },
          error: function (jqXhr, textStatus, errorMessage) {
            $errorMessage.html(errorMessage);
          },
        });
      },
      select: function (event, ui) {
        $("#woeid").val(ui.item.id);
        $(event.target).attr("value", ui.item.label);
        $(event.target).val(ui.item.label);
        return false;
      },
    });
  });
})(jQuery, window, document);
