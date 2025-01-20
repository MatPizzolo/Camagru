const fileInput = document.getElementById("fileInput");
const previewImage = document.getElementById("previewImage");
const previewText = document.getElementById("previewText");
fileInput.addEventListener("change", (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();

    reader.onload = (e) => {
      previewImage.src = e.target.result;
      previewImage.classList.remove("hidden");
      previewText.innerHTML = "Preview:";
    };

    reader.readAsDataURL(file);
  } else {
    previewImage.classList.add("hidden");
    previewImage.src = "";
    previewText.innerHTML = "Upload photo to get preview";
  }
});

const takePhotoButton = document.getElementById("takePhotoButton");
takePhotoButton.addEventListener("click", () => {
  navigator.mediaDevices
    .getUserMedia({ video: true })
    .then((stream) => {
      const videoElement = document.createElement("video");
      videoElement.srcObject = stream;
      videoElement.play();

      const captureContainer = document.createElement("div");
      captureContainer.classList.add(
        "absolute",
        "top-0",
        "left-0",
        "right-0",
        "bottom-0",
        "bg-gray-900",
        "bg-opacity-75",
        "flex",
        "justify-center",
        "items-center",
        "z-50"
      );

      const captureButton = document.createElement("button");
      captureButton.innerText = "Capture Photo";
      captureButton.classList.add(
        "bg-green-600",
        "text-white",
        "font-bold",
        "py-2",
        "px-4",
        "rounded"
      );

      captureContainer.append(videoElement, captureButton);
      document.body.appendChild(captureContainer);

      captureButton.addEventListener("click", () => {
        const canvas = document.createElement("canvas");
        const context = canvas.getContext("2d");
        canvas.width = videoElement.videoWidth;
        canvas.height = videoElement.videoHeight;
        context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

        const imageDataUrl = canvas.toDataURL("image/png");
        previewImage.src = imageDataUrl;
        previewContainer.classList.remove("hidden");

        // Stop the video stream
        stream.getTracks().forEach((track) => track.stop());
        document.body.removeChild(captureContainer);
      });
    })
    .catch((err) => {
      alert("Camera access denied or not available.");
      console.error(err);
    });
});

const tryFormPostUpload = async (file, caption) => {
  try {
    const url = `${config.apiBaseUrl}/api/pictures/upload/`;

    const formData = new FormData();
    formData.append("image", file);
    formData.append("description", caption);
    const response = await makeRequest(true, url, {
      method: "POST",
      mode: "cors",
      credentials: "include",
      body: formData,
    });
    if (response.status === "ok") {
      console.log("Photo uploaded successfully:", response.message);
	    window.location.href = "/home";
    } else {
      console.error("Error:", response.message);
      alert(`Error: ${response.message}`);
    }
  } catch (error) {
    console.error("Error:", error.message);
    alert("An unexpected error occurred. Please try again.");
  }
};

const captionInput = document.getElementById("caption");
const uploadForm = document.getElementById("uploadForm");
uploadForm.addEventListener("submit", async (event) => {
  event.preventDefault();

  const file = fileInput.files[0];
  const caption = captionInput.value;
  if (!file) {
    alert("Please select an image to upload.");
    return;
  }

  await tryFormPostUpload(file, caption);
});
