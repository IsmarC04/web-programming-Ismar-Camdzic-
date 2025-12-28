const PRODUCTION_HOST = "https://web-programming-ismar-camdzic.onrender.com";
// set to 'back-end' if your API is served at /back-end/; set to '' if API is at site root '/'
const BACKEND_SUBPATH = "back-end";

let Constants = {
  PROJECT_BASE_URL:
    location.hostname == "localhost"
      ? "http://localhost/web-programming-Ismar-Camdzic-/back-end/"
      : PRODUCTION_HOST + "/",
  BACKEND_SUBPATH,
  USER_ROLE: "user",
  ADMIN_ROLE: "admin",
  getApiUrl(path = "") {
    const base = String(this.PROJECT_BASE_URL).replace(/\/+$/, "") + "/";
    const sub = String(this.BACKEND_SUBPATH || "").replace(/^\/+|\/+$/g, "");
    return base + (sub ? sub + "/" : "") + String(path).replace(/^\/+/, "");
  },
};
