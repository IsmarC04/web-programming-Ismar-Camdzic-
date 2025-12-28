const userService = {
  currentServiceId: null,
  init: function () {
    let token = localStorage.getItem("user_token");

    if (!token) {
      window.location.replace("login.html");
    }

    userService.getAllServicesPublic();
  },

  getAllServicesPublic: function () {
    RestClient.get(
      `services`,
      (response) => {
        if (response.success && response.data) {
          const services = response.data;

          const servicesList = $("#servicesList");
          barberList.empty();

          services.forEach((service) => {
            const row = `
            <div class="col-md-3">
          <div class="card h-100" data-id="${service.id}">
            <img src="./images/stylist_${service.id}.jpg" class="card-img-top" alt="Ismar" />
            <div class="card-body text-center">
              <h5 class="card-title">${service.name}</h5>
              <p class="card-text fw-bold">${service.bio}</p>
              <p class="card-text fw-bold text-success">STATUS: AKTIVAN</p>

              <div class="d-flex justify-content-center gap-2">
                <a href="#" class="btn btn-primary" style="margin-top: 10px">Rezervisi</a>
                </div>
              </div>
            </div>
          </div>
        </div>
            `;
            servicesList.append(row);
          });
        }
      },
      (error) => {
        Utils.showError("Failed to load service details");
      }
    );
  },
};
