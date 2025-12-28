const PRODUCTION_BASE =
  "https://web-programming-ismar-camdzic.onrender.com/back-end/";

let Constants = {
  PROJECT_BASE_URL:
    location.hostname == "localhost"
      ? "http://localhost/web-programming-Ismar-Camdzic-/back-end/"
      : PRODUCTION_BASE,
  USER_ROLE: "user",
  ADMIN_ROLE: "admin",
  getApiUrl(path = "") {
    // ensure exactly one trailing slash on base and no leading slash on path
    const base = String(this.PROJECT_BASE_URL).replace(/\/+$/, "") + "/";
    return base + String(path).replace(/^\/+/, "");
  },
};
