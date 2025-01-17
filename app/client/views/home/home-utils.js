const likedPost = (data, postId) => {
  const postDiv = document.getElementById(`post-${postId}`);
  postDiv.querySelector(".fa-heart").classList.add("liked");
  const likesValueElement = document.getElementById(
    `likes-value-post-${postId}`
  );
  let currentLikes = data.likes
  likesValueElement.textContent = currentLikes;
};

const likePost = async (postId) => {
  try {
    const url = `${config.apiBaseUrl}/api/pictures/like`;

    const response = await makeRequest(true, url, {
      method: "POST",
      mode: "cors",
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        picture_id: postId,
      }),
    });
    if (response.status === "ok") {
      likedPost(response.data, postId);
    } else {
      console.error("Error:", response.message);
      console.log(`${response.message}. Please try again.`);
    }
  } catch (error) {
    console.error("Error:", error.message);
    console.log("Invalid credentials. Please try again.");
  }
};

const unlikedPost = (data, postId) => {
  const postDiv = document.getElementById(`post-${postId}`);
  postDiv.querySelector(".fa-heart").classList.remove("liked");
  const likesValueElement = document.getElementById(
    `likes-value-post-${postId}`
  );
  let currentLikes = data.likes
  likesValueElement.textContent = currentLikes;
};

const unlikePost = async (postId) => {
  try {
    const url = `${config.apiBaseUrl}/api/pictures/unlike`;

    const response = await makeRequest(true, url, {
      method: "POST",
      mode: "cors",
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        picture_id: postId,
      }),
    });
    if (response.status === "ok") {
      unlikedPost(response.data, postId);
    } else {
      console.error("Error:", response.message);
      console.log(`${response.message}. Please try again.`);
    }
  } catch (error) {
    console.error("Error:", error.message);
    console.log("Invalid credentials. Please try again.");
  }
};

const checkPostInfo = async (postId) => {
  try {
    const url = `${config.apiBaseUrl}/api/pictures/postLikes`;

    const response = await makeRequest(true, url, {
      method: "POST",
      mode: "cors",
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        picture_id: postId,
      }),
    });
    if (response.status === "ok") {
      const data =  await getUserInfo();
      return response.data.some(user => user.username === data.username);
    } else {
      console.error("Error:", response.message);
      console.log(`${response.message}. Please try again.`);
    }
  } catch (error) {
    console.error("Error:", error.message);
    console.log("Invalid credentials. Please try again.");
  }
}

const hasClickLikePost = async (postId) => {
  const hasLicked = await checkPostInfo(postId);
  if (!hasLicked)
    likePost(postId);
  else
    unlikePost(postId);
}

const commentPost = async (postId) => {
  const commentsValueElement = document.getElementById(
    `comments-value-post-${postId}`
  );
  let currentComments = parseInt(commentsValueElement.textContent);
  currentComments++;
  commentsValueElement.textContent = currentComments;

  console.log(`Commented on post with ID: ${postId}`);
};
