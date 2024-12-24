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
    // js: [
    //   { file: directory + "home/intra-handler.js" },
    //   { file: directory + "home/home.js" },
    // ],
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
  }
};

const allowedLocations = [
  "/",
  "/login",
  "/register",
];
