function handleLogout() {
	sessionStorage.clear();
	window.location.href = "/register";
}