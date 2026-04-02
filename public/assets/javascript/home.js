window.LEMS.onReady(function () {
  var tabButtons = document.querySelectorAll(".tab-btn");
  var tabContents = document.querySelectorAll(".tab-content");

  Array.prototype.forEach.call(tabButtons, function (button) {
    button.addEventListener("click", function () {
      Array.prototype.forEach.call(tabButtons, function (otherButton) {
        otherButton.classList.remove("active");
        otherButton.setAttribute("aria-selected", "false");
      });

      Array.prototype.forEach.call(tabContents, function (content) {
        content.classList.remove("active");
        content.setAttribute("hidden", "true");
      });

      button.classList.add("active");
      button.setAttribute("aria-selected", "true");

      var target = document.getElementById("tab-" + button.dataset.tab);
      if (target) {
        target.classList.add("active");
        target.removeAttribute("hidden");
      }
    });
  });

  var offerTriggers = document.querySelectorAll(".js-offer-trigger");
  var companyElement = document.getElementById("detail-entreprise");
  var titleElement = document.getElementById("detail-poste");
  var locationTag = document.getElementById("detail-tag-lieu");
  var typeTag = document.getElementById("detail-tag-type");
  var durationTag = document.getElementById("detail-tag-duree");
  var levelTag = document.getElementById("detail-tag-niveau");
  var descriptionElement = document.getElementById("detail-desc-text");
  var levelRequirementElement = document.getElementById("detail-req-niveau");
  var typeRequirementElement = document.getElementById("detail-req-type");
  var durationRequirementElement = document.getElementById("detail-req-duree");
  var companyDescriptionElement = document.getElementById("detail-entdesc-text");
  var applyButton = document.querySelector(".js-apply-btn");
  var iconElement = document.getElementById("detail-icon-main");
  var detailBookmarkCheckbox = document.getElementById("detail-bookmark-checkbox");

  Array.prototype.forEach.call(offerTriggers, function (trigger) {
    trigger.addEventListener("click", function () {
      Array.prototype.forEach.call(offerTriggers, function (otherTrigger) {
        otherTrigger.classList.remove("active");
      });

      trigger.classList.add("active");

      if (companyElement) companyElement.textContent = trigger.dataset.entreprise;
      if (titleElement) titleElement.textContent = trigger.dataset.poste;
      if (locationTag) locationTag.textContent = trigger.dataset.lieu;
      if (typeTag) typeTag.textContent = trigger.dataset.type;
      if (durationTag) durationTag.textContent = trigger.dataset.duree;
      if (levelTag) levelTag.textContent = trigger.dataset.niveau;
      if (descriptionElement) descriptionElement.textContent = trigger.dataset.desc;
      if (levelRequirementElement) levelRequirementElement.textContent = trigger.dataset.niveau;
      if (typeRequirementElement) typeRequirementElement.textContent = trigger.dataset.type;
      if (durationRequirementElement) durationRequirementElement.textContent = trigger.dataset.duree;
      if (companyDescriptionElement) companyDescriptionElement.textContent = trigger.dataset.entdesc;

      if (iconElement) {
        iconElement.className = "fa-solid " + (trigger.dataset.icon || "fa-briefcase");
      }

      if (applyButton) {
        applyButton.dataset.id = trigger.dataset.id;
      }

      if (detailBookmarkCheckbox) {
        detailBookmarkCheckbox.dataset.offerId = trigger.dataset.id;
        var leftCheckbox = trigger.querySelector(".bookmark-checkbox");
        if (leftCheckbox) {
          detailBookmarkCheckbox.checked = leftCheckbox.checked;
        }
      }
    });
  });

  if (applyButton) {
    applyButton.addEventListener("click", function () {
      window.location.href = "/index.php?page=apply&offer_id=" + applyButton.dataset.id;
    });
  }

  Array.prototype.forEach.call(document.querySelectorAll(".ui-bookmark"), function (label) {
    label.addEventListener("click", function (event) {
      event.stopPropagation();
    });
  });

  Array.prototype.forEach.call(document.querySelectorAll(".bookmark-checkbox"), function (checkbox) {
    checkbox.addEventListener("change", async function () {
      var offerId = checkbox.dataset.offerId;
      if (!offerId) {
        return;
      }

      var isChecked = checkbox.checked;
      Array.prototype.forEach.call(document.querySelectorAll('.bookmark-checkbox[data-offer-id="' + offerId + '"]'), function (otherCheckbox) {
        if (otherCheckbox !== checkbox) {
          otherCheckbox.checked = isChecked;
        }
      });

      try {
        var response = await fetch("/index.php?page=toggle_wishlist", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ offer_id: parseInt(offerId, 10) })
        });
        var result = await response.json();

        if (!result.success) {
          Array.prototype.forEach.call(document.querySelectorAll('.bookmark-checkbox[data-offer-id="' + offerId + '"]'), function (otherCheckbox) {
            otherCheckbox.checked = !isChecked;
          });
          alert(result.error || "Erreur lors de la mise à jour des favoris.");
        }
      } catch (error) {
        Array.prototype.forEach.call(document.querySelectorAll('.bookmark-checkbox[data-offer-id="' + offerId + '"]'), function (otherCheckbox) {
          otherCheckbox.checked = !isChecked;
        });
        console.error("Erreur wishlist", error);
      }
    });
  });
});
