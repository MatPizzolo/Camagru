
const puttigUserInfoInNav = async () => {
	const data =  await getUserInfo();
	const userInNav = document.getElementById("navUsername");
	userInNav.innerHTML = data.username;
}


puttigUserInfoInNav();