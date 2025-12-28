const StylistAdmin = {
  currentBarberId: null,

  init: function () {
    let token = localStorage.getItem("user_token");

    if (!token) {
      window.location.replace("login.html");
    }

    StylistAdmin.getAllBarbers();
  },

  delete: function (id) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "admin/stylists/" + id,
      type: "DELETE",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("user_token"),
      },

      success: function () {
        toastr.success("Stylist deleted successfully");
        StylistAdmin.getAllBarbers();
      },

      error: function (xhr) {
        toastr.error(
          xhr?.responseText ? xhr.responseText : "Error deleting stylist"
        );
      },
    });
  },

  getAllBarbers: function () {
    RestClient.get(
      `stylists`,
      (response) => {
        if (response.success && response.data) {
          const barbers = response.data;

          const barberList = $("#barberList");
          barberList.empty();

          barbers.forEach((barber) => {
            const row = `

            <div class="col-md-3">
          <div class="card h-100" data-id="${barber.id}">
            <img src="./images/stylist_${barber.id}.jpg" class="card-img-top" alt="Ismar" />
            <div class="card-body text-center">
              <h5 class="card-title">${barber.name}</h5>
              <p class="card-text fw-bold">${barber.bio}</p>
              <p class="card-text fw-bold text-success">STATUS: AKTIVAN</p>

              <div class="d-flex justify-content-center gap-2">
                <a href="javascript:StylistAdmin.delete(${barber.id});" class="btn btn-danger btn-sm delete-stylist"
                  >Obriši</a
                >
                <a
                  href="javascript:StylistAdmin.showBarberModal(${barber.id});"
                  class="btn btn-warning btn-sm update-stylist"
                  >Uredi</a
                >
              </div>
            </div>
          </div>
        </div>
            `;
            barberList.append(row);
          });
        }
      },
      (error) => {
        Utils.showError("Failed to load barber details");
      }
    );
  },

  showBarberModal: function (barberId = null) {
    const modal = new bootstrap.Modal(document.getElementById("barberModal"));
    const modalTitle = document.getElementById("barberModalTitle");
    const form = document.getElementById("barberForm");

    if (barberId) {
      modalTitle.textContent = "Edit Barber";
      this.currentBarberId = barberId;

      
      RestClient.get(
        `admin/stylists/${barberId}`,
        (response) => {
          if (response.success && response.data) {
            const barber = response.data;
            $("#barberName").val(barber.name);
            $("#barberBio").val(barber.bio);
          }
        },
        (error) => {
          Utils.showError("Failed to load barber details");
        }
      );
    } else {
      modalTitle.textContent = "Add Barber";
      this.currentBarberId = null;
      form.reset();
    }

    modal.show();
  },

  saveBarber: function () {
    const name = $("#barberName").val();
    const bio = $("#barberBio").val();

    if (!name || !bio) {
      Utils.showError("Please fill in all required fields");
      return;
    }

    const barberData = {
      name: name,
      bio: bio,
    };

    if (this.currentBarberId) {
      RestClient.put(
        `admin/stylists/${this.currentBarberId}`,
        barberData,
        (response) => {
          if (response.success) {
            Utils.showSuccess("Stylis updated successfully");
            bootstrap.Modal.getInstance(
              document.getElementById("barberModal")
            ).hide();
            StylistAdmin.getAllBarbers();
          }
        },
        (error) => {
          Utils.showError(
            error.responseJSON?.message || "Failed to update stylist"
          );
        }
      );
    } else {
      RestClient.post(
        "admin/stylists",
        barberData,
        (response) => {
          if (response.success) {
            toastr.success("Stylist created successfully");
            bootstrap.Modal.getInstance(
              document.getElementById("barberModal")
            ).hide();
            StylistAdmin.getAllBarbers();
          }
        },
        (error) => {
          toastr.error(
            error.responseJSON?.message || "Failed to create stylist"
          );
        }
      );
    }
  },
};
