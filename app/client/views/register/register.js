function validateUsername() {
  const usernameInput = document.getElementById("usernameRegister");
  const usernameHelp = document.getElementById("usernameRegisterHelp");
  const username = usernameInput.value;

  const maxCharLimit = 20; // Set the maximum character limit

  if (username.length < 4) {
    usernameHelp.innerText = "Username must be at least 4 characters";
    usernameInput.classList.add("is-invalid");
    usernameInput.classList.remove("is-valid");
    return false;
  } else if (username.length > maxCharLimit) {
    usernameHelp.innerText = `Username cannot exceed ${maxCharLimit} characters`;
    usernameInput.classList.add("is-invalid");
    usernameInput.classList.remove("is-valid");
    return false;
  } else {
    usernameHelp.innerText = "";
    usernameInput.classList.remove("is-invalid");
    usernameInput.classList.add("is-valid");
    return true;
  }
}

// Validation function for email
function validateEmail() {
  const emailInput = document.getElementById("emailRegister");
  const emailHelp = document.getElementById("emailRegisterHelp");
  const email = emailInput.value;

  // You can add more sophisticated email validation logic here
  if (!email.includes("@")) {
    emailHelp.innerText = "Invalid email address";
    emailInput.classList.add("is-invalid");
    emailInput.classList.remove("is-valid");
    return false;
  } else {
    emailHelp.innerText = "";
    emailInput.classList.remove("is-invalid");
    emailInput.classList.add("is-valid");
    return true;
  }
}

// Event listeners for input validation
document
  .getElementById("usernameRegister")
  .addEventListener("input", validateUsername);
document
  .getElementById("emailRegister")
  .addEventListener("input", validateEmail);

const passwordInput = document.getElementById("passwordRegister");
const passwordHelp = document.getElementById("passwordRegisterHelp");

function checkPasswordStrength(password) {
  if (password.length >= 8) return true;
  else return false;
}

function validatePassword() {
  const password = passwordInput.value;

  if (!checkPasswordStrength(password)) {
    passwordHelp.innerText = "Expected 8 or more characters";
    passwordInput.classList.add("is-invalid");
    passwordInput.classList.remove("is-valid");
	return false;
  } else {
    passwordInput.classList.remove("is-invalid");
    passwordInput.classList.add("is-valid");
    passwordHelp.innerText = "";
	return true;
  }
}

const tryFormPost = async () => {
  const username = document.getElementById("usernameRegister").value;
  const password = document.getElementById("passwordRegister").value;
  const email = document.getElementById("emailRegister").value;

  try {
    const url = `${config.apiBaseUrl}/api/register/`;

    const response = await makeRequest(false, url, {
      method: "POST",
      mode: "cors",
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        username: username,
        email: email,
        password: password,
      }),
    });
    if (response.status === "ok") {
      sessionStorage.setItem("jwt", response.data.token);
      window.location.href = "/home";
    } else {
      console.error("Error:", response.message);
      displayError(`${response.message}. Please try again.`);
    }
  } catch (error) {
    console.error("Error:", error.message);
    displayError("Invalid credentials. Please try again.");
  }
};

function displayError(message) {
  console.log(`ERROR: ${message}`);
}

const form = document.getElementById("signupForm");
form.addEventListener(
  "submit",
  function (event) {
    event.preventDefault();
    if (!validateUsername() || !validateEmail() || !validatePassword()) {
      return;
    }
    tryFormPost();
  },
  false
);
