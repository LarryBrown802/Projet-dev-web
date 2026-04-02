window.LEMS.onReady(function () {
  window.LEMS.bindDialogBackdropClose([
    "dialogViewEtu",
    "dialogCreateEtu",
    "dialogEditEtu",
    "dialogDeleteEtu"
  ]);

  var createButton = document.getElementById("btnCreateEtu");
  var closeViewButton = document.getElementById("btnCloseViewEtu");
  var closeViewButtonAlt = document.getElementById("btnCloseViewEtu2");
  var closeCreateButton = document.getElementById("btnCloseCreateEtu");
  var cancelCreateButton = document.getElementById("btnCancelCreateEtu");
  var closeEditButton = document.getElementById("btnCloseEditEtu");
  var cancelEditButton = document.getElementById("btnCancelEditEtu");
  var closeDeleteButton = document.getElementById("btnCloseDeleteEtu");
  var cancelDeleteButton = document.getElementById("btnCancelDeleteEtu");

  if (createButton) createButton.onclick = function () { window.LEMS.openDialog("dialogCreateEtu"); };
  if (closeViewButton) closeViewButton.onclick = function () { window.LEMS.closeDialog("dialogViewEtu"); };
  if (closeViewButtonAlt) closeViewButtonAlt.onclick = function () { window.LEMS.closeDialog("dialogViewEtu"); };
  if (closeCreateButton) closeCreateButton.onclick = function () { window.LEMS.closeDialog("dialogCreateEtu"); };
  if (cancelCreateButton) cancelCreateButton.onclick = function () { window.LEMS.closeDialog("dialogCreateEtu"); };
  if (closeEditButton) closeEditButton.onclick = function () { window.LEMS.closeDialog("dialogEditEtu"); };
  if (cancelEditButton) cancelEditButton.onclick = function () { window.LEMS.closeDialog("dialogEditEtu"); };
  if (closeDeleteButton) closeDeleteButton.onclick = function () { window.LEMS.closeDialog("dialogDeleteEtu"); };
  if (cancelDeleteButton) cancelDeleteButton.onclick = function () { window.LEMS.closeDialog("dialogDeleteEtu"); };

  Array.prototype.forEach.call(document.querySelectorAll(".btn-view-etu"), function (button) {
    button.onclick = function () {
      var title = document.getElementById("viewEtuTitle");
      var info = document.getElementById("viewEtuInfo");
      var tbody = document.getElementById("viewEtuTbody");

      if (title) title.textContent = (button.dataset.prenom || "") + " " + (button.dataset.nom || "");
      if (info) info.textContent = button.dataset.email || "";
      if (tbody) {
        tbody.innerHTML = "";
        try {
          var candidatures = JSON.parse(button.dataset.candidatures || "[]");
          candidatures.forEach(function (candidature) {
            var row = document.createElement("tr");
            row.innerHTML = "<td><strong>" + candidature.offre + "</strong></td><td>" + candidature.entreprise + "</td>";
            tbody.appendChild(row);
          });
          if (candidatures.length === 0) {
            tbody.innerHTML = '<tr><td colspan="2">Aucune candidature.</td></tr>';
          }
        } catch (error) {
          tbody.innerHTML = '<tr><td colspan="2">Aucune candidature.</td></tr>';
        }
      }

      window.LEMS.openDialog("dialogViewEtu");
    };
  });

  Array.prototype.forEach.call(document.querySelectorAll(".toggle-password, .toogle-password"), function (button) {
    button.addEventListener("click", function () {
      var input = button.parentElement ? button.parentElement.querySelector("input") : null;
      var icon = button.querySelector("i");
      if (!input || !icon) {
        return;
      }

      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    });
  });

  Array.prototype.forEach.call(document.querySelectorAll(".btn-edit-etu"), function (button) {
    button.onclick = function () {
      var subtitle = document.getElementById("editEtuSubtitle");
      var profileId = document.getElementById("eIdProfile");
      var lastNameInput = document.getElementById("eNom");
      var firstNameInput = document.getElementById("ePrenom");
      var emailInput = document.getElementById("eEmail");
      var statusSelect = document.getElementById("eStatus");

      if (subtitle) subtitle.textContent = (button.dataset.prenom || "") + " " + (button.dataset.nom || "");
      if (profileId) profileId.value = button.dataset.id || "";
      if (lastNameInput) lastNameInput.value = button.dataset.nom || "";
      if (firstNameInput) firstNameInput.value = button.dataset.prenom || "";
      if (emailInput) emailInput.value = button.dataset.email || "";

      window.LEMS.setSelectValue(statusSelect, button.dataset.status || "wait");
      window.LEMS.openDialog("dialogEditEtu");
    };
  });

  Array.prototype.forEach.call(document.querySelectorAll(".btn-delete-etu"), function (button) {
    button.onclick = function () {
      var deleteName = document.getElementById("deleteEtuName");
      var deleteId = document.getElementById("dIdProfile");

      if (deleteName) deleteName.textContent = (button.dataset.prenom || "") + " " + (button.dataset.nom || "");
      if (deleteId) deleteId.value = button.dataset.id || "";

      window.LEMS.openDialog("dialogDeleteEtu");
    };
  });
});
