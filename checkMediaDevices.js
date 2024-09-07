function checkMediaDevices(initialCheck = true) {
  return navigator.mediaDevices
    .getUserMedia({ video: { facingMode: "user" }, audio: true })
    .then((stream) => {
      // Get the video element and set its source to the stream
      const video = document.getElementById("video");
      video.srcObject = stream;
      video.style.display = "block";
      video.play(); // Ensure the video starts playing

      // You can choose to stop tracks later, or keep them running if needed
      // For example, you might want to stop the tracks only when the user logs out
      //

      return true;
    })
    .catch(async (err) => {
      console.error("Error accessing media devices:", err);

      if (initialCheck) {
        const shouldRetry = confirm(
          "Camera and/or microphone access is denied. Do you want to try again?"
        );

        if (shouldRetry) {
          return checkMediaDevices(false);
        } else {
          alert(
            "You need to enable camera and/or microphone to use this page. Please check your browser's permissions."
          );
          window.location.href = "logout.php";
          return false;
        }
      } else {
        alert(
          "Media devices are still not accessible. Please check your media device settings and try again."
        );

        window.location.href = "logout.php";
        return false;
      }
    });
}

// Automatically run the check when the page loads
document.addEventListener("DOMContentLoaded", () => {
  checkMediaDevices().then((accessGranted) => {
    if (!accessGranted) {
      // Show alert if media devices are not accessible
      alert(
        "Media devices are required to use this page. Please check your media device settings and try again."
      );
      window.location.href = "logout.php";
    } else {
      console.log("Proceeding with the page functionality.");
    }
  });
});
