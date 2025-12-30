const AppointmentsAdmin = {
  currentAppointmentId: null,

  init: function () {
    const token = localStorage.getItem("user_token");

    if (!token) {
      window.location.replace("login.html");
    }

    AppointmentsAdmin.getAllAppointmentsForAdmin();
  },

  delete: function (id) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "admin/appointments/" + id,
      type: "DELETE",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("user_token"),
      },

      success: function () {
        toastr.success("Appointment deleted successfully");
        AppointmentsAdmin.getAllAppointmentsForAdmin();
      },

      error: function (xhr) {
        toastr.error(
          xhr?.responseText ? xhr.responseText : "Error deleting appointment"
        );
      },
    });
  },

  getAllAppointmentsForAdmin: function () {
    RestClient.get(
      `admin/appointments`,
      (response) => {
        const appointments = response;

        const appointmentsList = $("#appointmentList");
        appointmentsList.empty();

        appointments.forEach((a) => {
          const row = `
            <tr>
                <td>${a.id}</td>
              <td>${a.first_name}</td>
              <td>${a.last_name}</td>
              <td>${a.service_name}</td>
              <td>${a.stylist_name}</td>
              <td>${a.date}</td>
              <td><a href="javascript:AppointmentsAdmin.delete(${a.id});" class="btn btn-danger btn-sm delete-appointment"
                  >Obriši</a
                ></td>
                
                
              </tr>

            
            `;
          appointmentsList.append(row);
        });
      },
      (error) => {
        Utils.showError("Failed to load appointments");
      }
    );
  },
};
