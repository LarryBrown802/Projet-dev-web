document.addEventListener("DOMContentLoaded", function () {
  var dialog = document.getElementById("offreDialog");
  var openButtons = document.querySelectorAll(".open-dialog");

  openButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var title = document.getElementById("dialogTitle");
      var company = document.getElementById("dialogCompany");
      var lieu = document.getElementById("dialogLieu");
      var type = document.getElementById("dialogType");
      var niveau = document.getElementById("dialogNiveau");
      var duree = document.getElementById("dialogDuree");
      var remu = document.getElementById("dialogRemu");
      var companyDesc = document.getElementById("dialogEntrepriseDesc");
      var icon = document.getElementById("dialogIcon");

      if (title) title.textContent = button.dataset.poste;
      if (company) company.textContent = button.dataset.entreprise;
      if (lieu) lieu.textContent = button.dataset.lieu;
      if (type) type.textContent = button.dataset.type;
      if (niveau) niveau.textContent = button.dataset.niveau;
      if (duree) duree.textContent = button.dataset.duree;
      if (remu) remu.textContent = button.dataset.remu;
      if (companyDesc) companyDesc.textContent = button.dataset.entreprisedesc;
      if (icon) icon.innerHTML = '<i class="fa-solid ' + (button.dataset.icon || "fa-briefcase") + '"></i>';

      if (dialog && typeof dialog.showModal === "function") {
        dialog.showModal();
      }
    });
  });

  var applyActionButton = document.querySelector(".dialog-actions .btn-voir");
  if (applyActionButton) {
    applyActionButton.addEventListener("click", function () {
      var posteElement = document.getElementById("dialogTitle");
      var companyElement = document.getElementById("dialogCompany");
      var poste = encodeURIComponent(posteElement ? posteElement.textContent : "");
      var entreprise = encodeURIComponent(companyElement ? companyElement.textContent : "");
      window.location.href = "/index.php?page=apply&poste=" + poste + "&entreprise=" + entreprise;
    });
  }

  if (dialog) {
    dialog.addEventListener("click", function (event) {
      var rect = dialog.getBoundingClientRect();
      var outside = event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom;
      if (outside) {
        dialog.close();
      }
    });
  }

  document.querySelectorAll(".bookmark-checkbox").forEach(function (checkbox) {
    checkbox.addEventListener("change", async function () {
      var offerId = checkbox.dataset.offerId;
      if (!offerId) {
        return;
      }

      try {
        var response = await fetch("/index.php?page=toggle_wishlist", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ offer_id: offerId })
        });

        var result = await response.json();
        if (result.success && result.added === false) {
          var card = checkbox.closest(".offre-card");
          if (!card) {
            return;
          }

          card.style.transition = "opacity 0.3s, transform 0.3s";
          card.style.opacity = "0";
          card.style.transform = "scale(0.9)";
          setTimeout(function () {
            card.remove();
            if (document.querySelectorAll(".offre-card").length === 0) {
              window.location.reload();
            }
          }, 300);
        }
      } catch (error) {
        checkbox.checked = true;
        console.error("Erreur d'enregistrement", error);
      }
    });
  });
});
