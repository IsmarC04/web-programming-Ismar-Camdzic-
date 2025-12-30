const BookingService = {
  init: function () {
    const token = localStorage.getItem("user_token");

    if (!token) {
      window.location.replace("login.html");
      return;
    }

    $("#booking-form").validate({
      submitHandler: function (form) {
        const entity = Object.fromEntries(new FormData(form).entries());
        BookingService.create(entity);
      },
    });
  },

  create: function (entity) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "appointments",
      type: "POST",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",

      headers: {
        Authorization: "Bearer " + localStorage.getItem("user_token"),
      },

      success: function () {
        toastr.success("Booking successfully created");
      },

      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(
          XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : "Error"
        );
      },
    });
  },
};
