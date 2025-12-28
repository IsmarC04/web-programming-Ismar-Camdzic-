const UserService = {
  init: function () {
    let token = localStorage.getItem("user_token");
    if (token && token !== undefined) {
      window.location.replace("index.html");
    }
    $("#login-form").validate({
      submitHandler: function (form) {
        var entity = Object.fromEntries(new FormData(form).entries());
        UserService.login(entity);
      },
    });

    $("#register-form").validate({
      submitHandler: function (form) {
        var entity = Object.fromEntries(new FormData(form).entries());
        UserService.register(entity);
      },
    });
  },
  login: function (entity) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "login",
      type: "POST",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function (result) {
        console.log(result);
        localStorage.setItem("user_token", result.token);
        localStorage.setItem("user", UserService.parseJwt(result.token));
        window.location.replace("index.html");
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(
          XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : "Error"
        );
      },
    });
  },

  getMyUser: function () {
    var usertoken = JSON.parse(localStorage.getItem("user"));
    RestClient.get(
      `users/${usertoken.user.id}`,
      (response) => {
        const user = response;
        $("#first_name").val(user.first_name);
        $("#last_name").val(user.last_name);
        $("#email").val(user.email);
      },
      (error) => {
        toastr.error("Failed to load barber details");
      }
    );
  },

  isUserAdmin: function () {
    var user = JSON.parse(localStorage.getItem("user"));
    if (!user) {
      return false;
    } else {
      return user.user.role == "admin";
    }
  },

  editUser: function (data) {
    var usertoken = JSON.parse(localStorage.getItem("user"));
    RestClient.put(
      `users/${usertoken.user.id}`,
      data,
      (response) => {
        toastr.success("User updated  successfuly");
      },
      (error) => {
        toastr.error("Failed to load barber details");
      }
    );
  },

  parseJwt: function (token) {
    var base64Url = token.split(".")[1];
    var base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    var jsonPayload = decodeURIComponent(
      window
        .atob(base64)
        .split("")
        .map(function (c) {
          return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
        })
        .join("")
    );

    return jsonPayload;
  },

  register: function (entity) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "register",
      type: "POST",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function (result) {
        console.log(result);
        toastr.success("Registeration is successfuly");
        UserService.login(entity);
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(
          XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : "Error"
        );
      },
    });
  },

  logout: function () {
    localStorage.clear();
    window.location.replace("./#login");
  },
};
