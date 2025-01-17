const getUserInfoProfile = async () => {
	const data =  await getUserInfo();
	const nameProfile = document.getElementById("nameProfile");
	nameProfile.innerHTML = data.username;
	const emailProfile = document.getElementById("emailProfile");
	emailProfile.innerHTML = data.email;
}


getUserInfoProfile();