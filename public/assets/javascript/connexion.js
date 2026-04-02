document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".toggle-password").forEach(function (button) {
    button.addEventListener("click", function () {
      var input = button.previousElementSibling;
      if (!input) {
        return;
      }

      if (input.type === "password") {
        input.type = "text";
        button.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
      } else {
        input.type = "password";
        button.innerHTML = '<i class="fa-solid fa-eye"></i>';
      }
    });
  });
});
