const urlPageTitle = "Camagru";
const directory = "../../views/";

const urlRoutes = {
  404: {
    template: directory + "404/404.html",
    title: urlPageTitle + " - 404",
    description: "Page not found",
  },

  "/": {
    template: directory + "landing/landing.html",
    title: urlPageTitle,
    description: "This is the landing page",
    // js: [{ file: directory + "landing/landing.js" }],
    // css: [directory + "landing/landing.css"],
  },

  "/home": {
    template: directory + "home/home.html",
    title: urlPageTitle,
    description: "This is the home page",
    js: [{ file: directory + "home/home.js" }, { file: directory + "home/home-utils.js" }],
    // css: [directory + "home/home.css"],
  },

  "/login": {
    template: directory + "login/login.html",
    title: urlPageTitle + " - Sing in",
    description: "This is the login page",
    js: [{ file: directory + "login/login.js" }],
    // css: [directory + "login/login.css"],
  },

  "/register": {
    template: directory + "register/register.html",
    title: urlPageTitle + " - Sing up",
    description: "This is the register page",
    js: [{ file: directory + "register/register.js" }],
    // css: [directory + "register/register.css"],
  },
  "/upload": {
    template: directory + "upload/upload.html",
    title: urlPageTitle + " - Upload",
    description: "This is the upload page",
    js: [{ file: directory + "upload/upload.js" }],
    // css: [directory + "upload/upload.css"],
  },
  "/profile": {
    template: directory + "profile/profile.html",
    title: urlPageTitle + " - profile",
    description: "This is the profile page",
    js: [{ file: directory + "profile/profile.js" }],
    // css: [directory + "upload/upload.css"],
  }
};

const allowedLocations = [
  "/",
  "/login",
  "/register",
];
