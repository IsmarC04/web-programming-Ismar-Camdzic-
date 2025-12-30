const ServiceAdmin = {
  currentServiceId: null,

  init: function () {
    const token = localStorage.getItem("user_token");

    if (!token) {
      window.location.replace("login.html");
    }

    ServiceAdmin.getAllServices();
  },

  delete: function (id) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "admin/services/" + id,
      type: "DELETE",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("user_token"),
      },

      success: function () {
        toastr.success("Service deleted successfully");
        ServiceAdmin.getAllServices();
      },

      error: function (xhr) {
        toastr.error(
          xhr?.responseText ? xhr.responseText : "Error deleting service"
        );
      },
    });
  },

  getAllServices: function () {
    RestClient.get(
      `services/`,
      (response) => {
        if (response.success && response.data) {
          const services = response.data;

          const serviceList = $("#serviceList");
          serviceList.empty();

          services.forEach((service) => {
            const row = `

           <div class="col-md-4">
          <div class="card" data-id="${service.id}">
            <img
              src="./images/beard trim.jpg"
              class="card-img-top"
              alt="Beard Fade"
            />
            <div class="card-body">
              <h5 class="card-title">${service.name}</h5>
              <p class="card-text">
                ${service.description}
              </p>
              <p class="card-text">${service.price}</p>
              <a href="javascript:ServiceAdmin.delete(${service.id});" class="btn btn-danger btn-sm delete-stylist"
                  >Obriši</a
                >
              <a
                  href="javascript:ServiceAdmin.showServiceModal(${service.id});"
                  class="btn btn-warning btn-sm update-servicee"
                  >Uredi</a
                >
            </div>
          </div>
        </div>
            `;
            serviceList.append(row);
          });
        }
      },
      (error) => {
        Utils.showError("Failed to load service details");
      }
    );
  },

  showServiceModal: function (serviceId = null) {
    const modal = new bootstrap.Modal(document.getElementById("serviceModal"));
    const modalTitle = document.getElementById("serviceModalTitle");
    const form = document.getElementById("serviceForm");

    if (serviceId) {
      modalTitle.textContent = "Edit Stylist";
      this.currentServiceId = serviceId;

      // Load barber data
      RestClient.get(
        `admin/services/${serviceId}`,
        (response) => {
          if (response.success && response.data) {
            const service = response.data;
            $("#serviceName").val(service.name);
            $("#serviceDescription").val(service.description);
            $("#servicePrice").val(service.price);
          }
        },
        (error) => {
          Utils.showError("Failed to load service details");
        }
      );
    } else {
      modalTitle.textContent = "Add Service";
      this.currentServiceId = null;
      form.reset();
    }

    modal.show();
  },

  saveService: function () {
    const name = $("#serviceName").val();
    const description = $("#serviceDescription").val();
    const price = $("#servicePrice").val();

    if (!name || !description || !price) {
      Utils.showError("Please fill in all required fields");
      return;
    }

    const serviceData = {
      name: name,
      description: description,
      price: price,
    };

    if (this.currentServiceId) {
      RestClient.put(
        `admin/services/${this.currentServiceId}`,
        serviceData,
        (response) => {
          if (response.success) {
            Utils.showSuccess("Service updated successfully");
            bootstrap.Modal.getInstance(
              document.getElementById("serviceModal")
            ).hide();
            ServiceAdmin.getAllServices();
          }
        },
        (error) => {
          Utils.showError(
            error.responseJSON?.message || "Failed to update service"
          );
        }
      );
    } else {
      RestClient.post(
        "admin/services",
        serviceData,
        (response) => {
          if (response.success) {
            toastr.success("Service created successfully");
            bootstrap.Modal.getInstance(
              document.getElementById("serviceModal")
            ).hide();
            ServiceAdmin.getAllServices();
          }
        },
        (error) => {
          toastr.error(
            error.responseJSON?.message || "Failed to create service"
          );
        }
      );
    }
  },
};
