const getPosts = async () => {
  try {
    const url = `${config.apiBaseUrl}/api/pictures/posts`;

    const response = await makeRequest(true, url, {
      method: "GET",
      mode: "cors",
      credentials: "include",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (response.status === "ok") {
      return response.data;
    } else {
      console.error("Error:", response.message);
    }
  } catch (error) {
    console.error("Error:", error.message);
  }
};

const showPosts = async () => {
  const data = await getPosts();
  const postsContainer = document.getElementById("postsContainer");
  data.posts.forEach((post) => {
    const postTemplate = document.getElementById("post-template");
    const postDiv = postTemplate.content.firstElementChild.cloneNode(true);

    postDiv.querySelector("img").src = post.image_url;
    postDiv.querySelector(".description").textContent = post.description;
    postDiv.querySelector(
      ".username"
    ).textContent = `Posted by: ${post.username}`;
    postDiv.querySelector(".likes-value").textContent = post.likes_count;
    postDiv.querySelector(".comments-value").textContent = post.comments_count;

    const postId = post.id; // Use picture_id from the response
    postDiv.id = `post-${postId}`; // Set a unique ID for the post element

    postDiv.querySelector(".likes-value").id = `likes-value-post-${postId}`;
    postDiv.querySelector(
      ".comments-value"
    ).id = `comments-value-post-${postId}`;
    postDiv
      .querySelector(".fa-heart")
      .setAttribute("onclick", `hasClickLikePost(${postId})`);
    postDiv
      .querySelector(".fa-comment")
      .setAttribute("onclick", `hasClickCommentPost(${postId})`);

    if (post.user_has_liked)
      postDiv.querySelector(".fa-heart").classList.add("liked");

    postsContainer.appendChild(postDiv);
  });
};

showPosts();
