window.LEMS.onReady(function () {
  window.LEMS.bindDialogBackdropClose([
    "dialogCreateEnt",
    "dialogEditEnt",
    "dialogRateEnt",
    "dialogDeleteEnt"
  ]);

  var createButton = document.getElementById("btnCreateEntreprise");
  var closeCreateButton = document.getElementById("btnCloseCreateEnt");
  var cancelCreateButton = document.getElementById("btnCancelCreateEnt");
  var closeEditButton = document.getElementById("btnCloseEditEnt");
  var cancelEditButton = document.getElementById("btnCancelEditEnt");
  var closeRateButton = document.getElementById("btnCloseRateEnt");
  var cancelRateButton = document.getElementById("btnCancelRateEnt");
  var closeDeleteButton = document.getElementById("btnCloseDeleteEnt");
  var cancelDeleteButton = document.getElementById("btnCancelDeleteEnt");

  if (createButton) createButton.onclick = function () { window.LEMS.openDialog("dialogCreateEnt"); };
  if (closeCreateButton) closeCreateButton.onclick = function () { window.LEMS.closeDialog("dialogCreateEnt"); };
  if (cancelCreateButton) cancelCreateButton.onclick = function () { window.LEMS.closeDialog("dialogCreateEnt"); };
  if (closeEditButton) closeEditButton.onclick = function () { window.LEMS.closeDialog("dialogEditEnt"); };
  if (cancelEditButton) cancelEditButton.onclick = function () { window.LEMS.closeDialog("dialogEditEnt"); };
  if (closeRateButton) closeRateButton.onclick = function () { window.LEMS.closeDialog("dialogRateEnt"); };
  if (cancelRateButton) cancelRateButton.onclick = function () { window.LEMS.closeDialog("dialogRateEnt"); };
  if (closeDeleteButton) closeDeleteButton.onclick = function () { window.LEMS.closeDialog("dialogDeleteEnt"); };
  if (cancelDeleteButton) cancelDeleteButton.onclick = function () { window.LEMS.closeDialog("dialogDeleteEnt"); };

  Array.prototype.forEach.call(document.querySelectorAll(".btn-edit"), function (button) {
    button.onclick = function () {
      var editName = document.getElementById("editEntName");
      var entityId = document.getElementById("eEId");
      var nameInput = document.getElementById("eENom");
      var emailInput = document.getElementById("eEEmail");
      var phoneInput = document.getElementById("eETel");
      var descriptionInput = document.getElementById("eEDesc");

      if (editName) editName.textContent = button.dataset.name;
      if (entityId) entityId.value = button.dataset.id || "";
      if (nameInput) nameInput.value = button.dataset.name || "";
      if (emailInput) emailInput.value = button.dataset.email || "";
      if (phoneInput) phoneInput.value = button.dataset.number || "";
      if (descriptionInput) descriptionInput.value = button.dataset.description || "";

      window.LEMS.openDialog("dialogEditEnt");
    };
  });

  Array.prototype.forEach.call(document.querySelectorAll(".btn-rate"), function (button) {
    button.onclick = function () {
      var rateName = document.getElementById("rateEntName");
      var rateId = document.getElementById("rateEId");

      if (rateName) rateName.textContent = button.dataset.name;
      if (rateId) rateId.value = button.dataset.id || "";

      window.LEMS.openDialog("dialogRateEnt");
    };
  });

  Array.prototype.forEach.call(document.querySelectorAll(".btn-delete"), function (button) {
    button.onclick = function () {
      var deleteName = document.getElementById("deleteEntName");
      var deleteId = document.getElementById("dEId");

      if (deleteName) deleteName.textContent = button.dataset.name;
      if (deleteId) deleteId.value = button.dataset.id || "";

      window.LEMS.openDialog("dialogDeleteEnt");
    };
  });
});
