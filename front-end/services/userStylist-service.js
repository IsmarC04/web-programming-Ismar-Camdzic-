const userStylist = {
  currentBarberId: null,

  init: function () {
    let token = localStorage.getItem("user_token");

    if (!token) {
      window.location.replace("login.html");
    }

    userStylist.getAllBarbersPublic();
  },

  getAllBarbersPublic: function () {
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
                <a href="#" class="btn btn-primary" style="margin-top: 10px">Rezervisi</a>
                </div>
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
};
