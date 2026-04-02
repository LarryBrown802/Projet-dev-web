window.LEMS.onReady(function () {
  var dialog = document.getElementById("offreDialog");
  var postButton = document.getElementById("btnPostuler");

  Array.prototype.forEach.call(document.querySelectorAll(".open-dialog"), function (button) {
    button.addEventListener("click", function () {
      var title = document.getElementById("dialogTitle");
      var company = document.getElementById("dialogCompany");
      var location = document.getElementById("dialogLieu");
      var type = document.getElementById("dialogType");
      var level = document.getElementById("dialogNiveau");
      var duration = document.getElementById("dialogDuree");
      var remuneration = document.getElementById("dialogRemu");
      var companyDescription = document.getElementById("dialogEntrepriseDesc");

      if (title) title.textContent = button.dataset.poste;
      if (company) company.textContent = button.dataset.entreprise;
      if (location) location.textContent = button.dataset.lieu;
      if (type) type.textContent = button.dataset.type;
      if (level) level.textContent = button.dataset.niveau;
      if (duration) duration.textContent = button.dataset.duree;
      if (remuneration) remuneration.textContent = button.dataset.remu;
      if (companyDescription) companyDescription.textContent = button.dataset.entreprisedesc;

      if (postButton) {
        postButton.onclick = function () {
          window.location.href = "/index.php?page=apply&offer_id=" + button.dataset.id;
        };
      }

      if (dialog && typeof dialog.showModal === "function") {
        dialog.showModal();
      }
    });
  });

  if (dialog) {
    dialog.addEventListener("click", function (event) {
      var rect = dialog.getBoundingClientRect();
      var clickedOutside = event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom;
      if (clickedOutside) {
        dialog.close();
      }
    });
  }

  var track = document.querySelector(".carousel-track");
  var cards = document.querySelectorAll(".stat-card");
  var previousButton = document.querySelector(".carousel-btn.prev");
  var nextButton = document.querySelector(".carousel-btn.next");
  var dots = document.querySelectorAll(".carousel-dots .dot");

  if (track && cards.length > 0) {
    var currentIndex = 0;

    function updateCarousel() {
      var cardWidth = cards[0].offsetWidth;
      var gap = parseFloat(window.getComputedStyle(track).gap) || 0;
      track.style.transform = "translateX(-" + ((cardWidth + gap) * currentIndex) + "px)";
      track.style.transition = "transform 0.4s ease-in-out";
      Array.prototype.forEach.call(dots, function (dot, index) {
        dot.classList.toggle("active", index === currentIndex);
      });
    }

    if (nextButton) {
      nextButton.addEventListener("click", function () {
        if (currentIndex < cards.length - 1) {
          currentIndex += 1;
          updateCarousel();
        }
      });
    }

    if (previousButton) {
      previousButton.addEventListener("click", function () {
        if (currentIndex > 0) {
          currentIndex -= 1;
          updateCarousel();
        }
      });
    }

    Array.prototype.forEach.call(dots, function (dot, index) {
      dot.addEventListener("click", function () {
        currentIndex = index;
        updateCarousel();
      });
    });
  }

  Array.prototype.forEach.call(document.querySelectorAll(".bookmark-checkbox"), function (checkbox) {
    checkbox.addEventListener("change", async function () {
      var offerId = checkbox.dataset.offerId;
      if (!offerId) {
        return;
      }

      var previousState = !checkbox.checked;

      try {
        var response = await fetch("/index.php?page=toggle_wishlist", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ offer_id: parseInt(offerId, 10) })
        });
        var result = await response.json();

        if (!result.success) {
          checkbox.checked = previousState;
          alert(result.error || "Erreur lors de la mise à jour des favoris.");
        }
      } catch (error) {
        checkbox.checked = previousState;
        console.error("Erreur wishlist", error);
      }
    });
  });
});
