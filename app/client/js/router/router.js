const direc = "../../views/";

document.addEventListener("click", (e) => {
  const { target } = e;

  if (target.matches("a")) {
    handleRouting(e);
  }
});

const navigateTo = async (target) => {
  window.history.pushState({}, "", target);
  await urlLocationHandler();
};

const handleRouting = async (event) => {
  event.preventDefault();
  const target = event.target.href;
  await navigateTo(target);
};

const urlRoute = (event) => {
  event = event || window.event;
  event.preventDefault();
  // window.history.pushState(state, unused, target link);
  window.history.pushState({}, "", event.target.href);
  urlLocationHandler();
};

const previousLocation = window.location.pathname || "/";

const urlLocationHandler = async () => {
  const currentLocation = window.location.pathname || "/";

  ifLoggedRedirect(currentLocation);
  const route = urlRoutes[currentLocation] || urlRoutes["404"];

  try {
    const [html, styles] = await Promise.all([
      fetch(route.template).then((response) => response.text()),
      Promise.resolve(route.css || []),
    ]);
    document.getElementById("content").innerHTML = html;
    document.title = route.title;

    document
      .querySelector('meta[name="description"]')
      .setAttribute("content", route.description);

    loadStyles(styles);

    // if (isLogged() && route !== urlRoutes["404"]) {
    //   loadLobbyScripts();
    // }

    if (route.js) {
      await loadScripts(route.js);
    }

    // Handle Nav
    const navRouter = document.getElementById("nav-router");
    if (
      navRouter &&
      navRouter.innerHTML.trim() === "" &&
      isLogged() &&
      route !== urlRoutes["404"]
    ) {
      navRouter.innerHTML = await fetch(
        direc + "/components/navbar/nav-logged.html"
      ).then((response) => response.text());
    }
    const footerRouter = document.getElementById("footer-router");
    if (
      footerRouter &&
      footerRouter.innerHTML.trim() === "" &&
      isLogged() &&
      route !== urlRoutes["404"]
    ) {
      footerRouter.innerHTML = await fetch(
        direc + "/components/footer/footer-logged.html"
      ).then((response) => response.text());
      const script = document.createElement("script");
      script.type = "text/javascript";
      script.src = `${direc}/components/footer/footer.js`;
      script.async = false;
      const body = document.body;
      body.appendChild(script);
    }
  } catch (error) {
    console.error("Error loading content:", error);
  }

  if (route !== urlRoutes["404"]) {
    loadNavScripts();
  }
};

// add an event listener to the window that watches for url changes
window.onpopstate = urlLocationHandler;
// call the urlLocationHandler function to handle the initial url
window.route = urlRoute;
// call the urlLocationHandler function to handle the initial url
urlLocationHandler();
