// Validation function for email
function validateEmail() {
  const emailInput = document.getElementById("emailLogin");
  const emailHelp = document.getElementById("emailLoginHelp");
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
  .getElementById("emailLogin")
  .addEventListener("input", validateEmail);

const passwordInputLogin = document.getElementById("passwordLogin");
const passwordHelpLogin = document.getElementById("passwordLoginHelp");

function checkPasswordStrength(password) {
  if (password.length >= 8) return true;
  else return false;
}

function validatePassword() {
  const password = passwordInputLogin.value;

  if (!checkPasswordStrength(password)) {
    passwordHelpLogin.innerText = "Expected 8 or more characters";
    passwordInputLogin.classList.add("is-invalid");
    passwordInputLogin.classList.remove("is-valid");
	return false;
  } else {
    passwordInputLogin.classList.remove("is-invalid");
    passwordInputLogin.classList.add("is-valid");
    passwordHelpLogin.innerText = "";
	return true;
  }
}

const tryFormPostLogin = async () => {
  const password = document.getElementById("passwordLogin").value;
  const email = document.getElementById("emailLogin").value;

  try {
    const url = `${config.apiBaseUrl}/api/login/`;
    
    const response = await makeRequest(false, url, {
      method: "POST",
      mode: "cors",
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
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

const form = document.getElementById("loginForm");
form.addEventListener(
  "submit",
  function (event) {
    event.preventDefault();
    if (!validateEmail() || !validatePassword()) {
      return;
    }
    tryFormPostLogin();
  },
  false
);
