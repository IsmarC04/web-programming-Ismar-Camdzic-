const UserList = {
  currentUserId: null,

  init: function () {
    const token = localStorage.getItem("user_token");

    if (!token) {
      window.location.replace("login.html");
    }

    UserList.getAllUsers();
  },

  delete: function (id) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "admin/user/" + id,
      type: "DELETE",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("user_token"),
      },

      success: function () {
        toastr.success("User deleted successfully");
        UserList.getAllUsers();
      },

      error: function (xhr) {
        toastr.error(
          xhr?.responseText ? xhr.responseText : "Error deleting user"
        );
      },
    });
  },

  getAllUsers: function () {
    RestClient.get(
      `admin/user`,
      (response) => {
        if (response.success && response.data) {
          const users = response.data;

          const usersList = $("#usersList");
          usersList.empty();

          users.forEach((user) => {
            const row = `
            <tr>
                <td>${user.id}</td>
                <td>${user.first_name}</td>
                <td>${user.last_name}</td>
                <td>${user.email}</td>
                <td>${user.role}</td>
                <td><a href="javascript:UserList.delete(${user.id});" class="btn btn-danger btn-sm delete-stylist"
                  >Obriši</a
                ></td>
                
                
              </tr>

            
            `;
            usersList.append(row);
          });
        }
      },
      (error) => {
        Utils.showError("Failed to load user details");
      }
    );
  },
};
