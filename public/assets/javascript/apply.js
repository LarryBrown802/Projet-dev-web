document.addEventListener("DOMContentLoaded", function () {
  var messageBox = document.querySelector(".message-box") || document.querySelector(".alert");
  if (!messageBox) {
    return;
  }

  setTimeout(function () {
    messageBox.style.opacity = "0";
    setTimeout(function () {
      messageBox.remove();
    }, 500);
  }, 5000);
});
