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

const passwordInput = document.getElementById("passwordLogin");
const passwordHelp = document.getElementById("passwordLoginHelp");

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
  const password = document.getElementById("passwordLogin").value;
  const email = document.getElementById("emailLogin").value;

  try {
    const url = `${config.apiBaseUrl}/api/login/`;

    console.log(`email: ${email}`);
    console.log(`password: ${password}`);

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
    tryFormPost();
  },
  false
);
