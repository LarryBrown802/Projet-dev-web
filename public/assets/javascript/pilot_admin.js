window.LEMS.onReady(function () {
  window.LEMS.bindDialogBackdropClose([
    "dialogCreatePilote",
    "dialogEditPilote",
    "dialogDeletePilote"
  ]);

  var createButton = document.getElementById("btnCreatePilote");
  var closeCreateButton = document.getElementById("btnCloseCreatePilote");
  var cancelCreateButton = document.getElementById("btnCancelCreatePilote");
  var closeEditButton = document.getElementById("btnCloseEditPilote");
  var cancelEditButton = document.getElementById("btnCancelEditPilote");
  var closeDeleteButton = document.getElementById("btnCloseDeletePilote");
  var cancelDeleteButton = document.getElementById("btnCancelDeletePilote");

  if (createButton) createButton.onclick = function () { window.LEMS.openDialog("dialogCreatePilote"); };
  if (closeCreateButton) closeCreateButton.onclick = function () { window.LEMS.closeDialog("dialogCreatePilote"); };
  if (cancelCreateButton) cancelCreateButton.onclick = function () { window.LEMS.closeDialog("dialogCreatePilote"); };
  if (closeEditButton) closeEditButton.onclick = function () { window.LEMS.closeDialog("dialogEditPilote"); };
  if (cancelEditButton) cancelEditButton.onclick = function () { window.LEMS.closeDialog("dialogEditPilote"); };
  if (closeDeleteButton) closeDeleteButton.onclick = function () { window.LEMS.closeDialog("dialogDeletePilote"); };
  if (cancelDeleteButton) cancelDeleteButton.onclick = function () { window.LEMS.closeDialog("dialogDeletePilote"); };

  Array.prototype.forEach.call(document.querySelectorAll(".btn-edit"), function (button) {
    button.onclick = function () {
      var subtitle = document.getElementById("editPiloteSubtitle");
      var userId = document.getElementById("eIdUser");
      var nameInput = document.getElementById("eName");
      var surnameInput = document.getElementById("eSurname");
      var emailInput = document.getElementById("eEmail");
      var promotionSelect = document.getElementById("ePromotion");

      if (subtitle) subtitle.textContent = (button.dataset.name || "") + " " + (button.dataset.surname || "");
      if (userId) userId.value = button.dataset.id || "";
      if (nameInput) nameInput.value = button.dataset.name || "";
      if (surnameInput) surnameInput.value = button.dataset.surname || "";
      if (emailInput) emailInput.value = button.dataset.email || "";

      window.LEMS.setSelectValue(promotionSelect, button.dataset.idPromotion || "");
      window.LEMS.openDialog("dialogEditPilote");
    };
  });

  Array.prototype.forEach.call(document.querySelectorAll(".btn-delete"), function (button) {
    button.onclick = function () {
      var deleteName = document.getElementById("deletePiloteName");
      var deleteId = document.getElementById("dIdUser");

      if (deleteName) deleteName.textContent = (button.dataset.name || "") + " " + (button.dataset.surname || "");
      if (deleteId) deleteId.value = button.dataset.id || "";

      window.LEMS.openDialog("dialogDeletePilote");
    };
  });
});
