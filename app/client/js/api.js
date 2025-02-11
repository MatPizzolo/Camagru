const config = {
  apiBaseUrl: "http://localhost:8000",
};

async function makeRequest(useCsrf, url, options, queries) {
  //console.log(useCsrf, url, options, queries);
  // if (useCsrf) {
  //   const csrfToken = getCSRFCookie();
  //   if (csrfToken) {
  //     options.headers = {
  //       ...options.headers,
  //       "X-CSRFToken": csrfToken,
  //     };
  //   } else {
  //     console.log("LADRON ! Cross Site Request Forgery Detected");
  //     return;
  //   }

  //   const JWTToken = sessionStorage.getItem("jwt");
  //   if (JWTToken) {
  //     options.headers = {
  //       ...options.headers,
  //       Authorization: `Bearer ${JWTToken}`,
  //     };
  //   } else {
  //     console.log("LADRON ! JWT not correct");
  //     return;
  //   }
  // }

  if (useCsrf) {
    const JWTToken = sessionStorage.getItem("jwt");
    if (JWTToken) {
      options.headers = {
        ...options.headers,
        Authorization: `Bearer ${JWTToken}`,
      };
    } else {
      console.log("LADRON ! JWT not correct");
      handleLogout();
      return;
    }
  }

  if (queries) {
    url = `${url}?${queries}`;
  }

  const response = await fetch(url, options);
  console.log(response)
  if (response.status === 401) {
    // Handle logout or prompt user to log in again
    alert(
      "Your session has expired or you are not authorized. Please log in again."
    );
    handleLogout();
    window.location.href = "/login"; // Redirect to login page
  }

  const contentType = response.headers.get("content-type");
  if (contentType && contentType.includes("application/json")) {
    return await response.json();
  } else {
    return await response.text(); // Return non-JSON response as text
  }
}

const getJWTUserUsername = () => {
  const jwtToken = sessionStorage.getItem("jwt");
  if (!jwtToken) {
    console.error("No JWT token found.");
    return null;
  }
  const [header, payload] = jwtToken.split(".").slice(0, 2);
  const decodedPayload = JSON.parse(atob(payload));
  const username = decodedPayload.username;
  return username;
};

const getJWTUserUserId = () => {
  const jwtToken = sessionStorage.getItem("jwt");
  if (!jwtToken) {
    console.error("No JWT token found.");
    return null;
  }
  const [header, payload] = jwtToken.split(".").slice(0, 2);
  const decodedPayload = JSON.parse(atob(payload));
  const username = decodedPayload.user_id;
  return username;
};

const getUserInfo = async () => {
  const userId = getJWTUserUserId();
  try {
    const url = `${config.apiBaseUrl}/api/info-me/`;

    const options = {
      method: "GET",
      mode: "cors",
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
      },
    };

    const data = await makeRequest(true, url, options);
    return data.data;
  } catch (error) {
    console.error(error);
  }
};
