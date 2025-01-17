const isLogged = () => {
	jwt = sessionStorage.getItem("jwt");

	if (!jwt) return false;
	else return true;
};

const ifLoggedRedirect = (location) => {
	if (!isLogged()) {
		if (!allowedLocations.includes(window.location.pathname)) {
			handleLogout();
		}
	} else {
		if (allowedLocations.includes(location)) {
			window.location.pathname = "/home";
		}
	}
};