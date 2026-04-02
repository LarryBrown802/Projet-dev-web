window.LEMS.onReady(function () {
  var dialogIds = [
    "dialogViewOffre",
    "dialogCreateOffre",
    "dialogEditOffre",
    "dialogDeleteOffre"
  ];

  window.LEMS.bindDialogBackdropClose(dialogIds);

  var createButton = document.getElementById("btnCreateOffre");
  var closeCreateButton = document.getElementById("btnCloseCreateOffre");
  var cancelCreateButton = document.getElementById("btnCancelCreateOffre");
  var closeViewButton = document.getElementById("btnCloseViewOffre");
  var closeViewButtonAlt = document.getElementById("btnCloseViewOffre2");
  var closeEditButton = document.getElementById("btnCloseEditOffre");
  var cancelEditButton = document.getElementById("btnCancelEditOffre");
  var closeDeleteButton = document.getElementById("btnCloseDeleteOffre");
  var cancelDeleteButton = document.getElementById("btnCancelDeleteOffre");

  if (createButton) createButton.onclick = function () { window.LEMS.openDialog("dialogCreateOffre"); };
  if (closeCreateButton) closeCreateButton.onclick = function () { window.LEMS.closeDialog("dialogCreateOffre"); };
  if (cancelCreateButton) cancelCreateButton.onclick = function () { window.LEMS.closeDialog("dialogCreateOffre"); };
  if (closeViewButton) closeViewButton.onclick = function () { window.LEMS.closeDialog("dialogViewOffre"); };
  if (closeViewButtonAlt) closeViewButtonAlt.onclick = function () { window.LEMS.closeDialog("dialogViewOffre"); };
  if (closeEditButton) closeEditButton.onclick = function () { window.LEMS.closeDialog("dialogEditOffre"); };
  if (cancelEditButton) cancelEditButton.onclick = function () { window.LEMS.closeDialog("dialogEditOffre"); };
  if (closeDeleteButton) closeDeleteButton.onclick = function () { window.LEMS.closeDialog("dialogDeleteOffre"); };
  if (cancelDeleteButton) cancelDeleteButton.onclick = function () { window.LEMS.closeDialog("dialogDeleteOffre"); };

  Array.prototype.forEach.call(document.querySelectorAll(".btn-view-offre"), function (button) {
    button.onclick = function () {
      var title = document.getElementById("viewOffreTitre");
      var company = document.getElementById("viewOffreEntreprise");
      var location = document.getElementById("viewLieu");
      var type = document.getElementById("viewType");
      var level = document.getElementById("viewNiveau");
      var duration = document.getElementById("viewDuree");
      var remuneration = document.getElementById("viewRemu");
      var publicationDate = document.getElementById("viewDate");
      var description = document.getElementById("viewDesc");

      if (title) title.textContent = button.dataset.titre;
      if (company) company.textContent = button.dataset.entreprise;
      if (location) location.textContent = button.dataset.lieu;
      if (type) type.textContent = button.dataset.type;
      if (level) level.textContent = button.dataset.niveau;
      if (duration) duration.textContent = button.dataset.duree;
      if (remuneration) remuneration.textContent = button.dataset.remu;
      if (publicationDate) publicationDate.textContent = button.dataset.date;
      if (description) description.textContent = button.dataset.desc;

      window.LEMS.openDialog("dialogViewOffre");
    };
  });

  Array.prototype.forEach.call(document.querySelectorAll(".btn-edit-offre"), function (button) {
    button.onclick = function () {
      var subtitle = document.getElementById("editOffreSubtitle");
      var offerId = document.getElementById("eOffreId");
      var titleInput = document.getElementById("eTitle");
      var domainInput = document.getElementById("eDomain");
      var durationInput = document.getElementById("eDuration");
      var remunerationInput = document.getElementById("eRemu");
      var cityInput = document.getElementById("eCity");
      var descriptionInput = document.getElementById("eDesc");
      var typeSelect = document.getElementById("eType");
      var levelSelect = document.getElementById("eLevel");

      if (subtitle) subtitle.textContent = button.dataset.titre;
      if (offerId) offerId.value = button.dataset.id || "";
      if (titleInput) titleInput.value = button.dataset.titre || "";
      if (domainInput) domainInput.value = button.dataset.domain || "";
      if (durationInput) durationInput.value = button.dataset.duree || "";
      if (remunerationInput) remunerationInput.value = button.dataset.remu || "";
      if (cityInput) cityInput.value = button.dataset.lieu || "";
      if (descriptionInput) descriptionInput.value = button.dataset.desc || "";

      window.LEMS.setSelectValue(typeSelect, button.dataset.type || "");
      window.LEMS.setSelectValue(levelSelect, button.dataset.niveau || "");
      window.LEMS.openDialog("dialogEditOffre");
    };
  });

  Array.prototype.forEach.call(document.querySelectorAll(".btn-delete-offre"), function (button) {
    button.onclick = function () {
      var deleteTitle = document.getElementById("deleteOffreTitre");
      var deleteId = document.getElementById("dOffreId");

      if (deleteTitle) deleteTitle.textContent = button.dataset.titre;
      if (deleteId) deleteId.value = button.dataset.id || "";

      window.LEMS.openDialog("dialogDeleteOffre");
    };
  });
});
