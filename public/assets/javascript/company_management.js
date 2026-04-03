document.addEventListener('DOMContentLoaded', function () {

    // ===== HELPERS =====

    function openDialog(id) {
        var d = document.getElementById(id);
        if (d) d.showModal();
    }

    function closeDialog(id) {
        var d = document.getElementById(id);
        if (d) d.close();
    }

    // ===== FERMETURE AU CLIC SUR LE BACKDROP =====
    ['dialogCreateEnt', 'dialogEditEnt', 'dialogRateEnt', 'dialogDeleteEnt'].forEach(function (id) {
        var d = document.getElementById(id);
        if (!d) return;
        d.addEventListener('click', function (e) {
            var r = d.getBoundingClientRect();
            if (e.clientX < r.left || e.clientX > r.right ||
                e.clientY < r.top || e.clientY > r.bottom) {
                d.close();
            }
        });
    });

    // ===== BOUTONS OUVRIR / FERMER =====
    var createButton = document.getElementById('btnCreateEntreprise');
    var closeCreateButton = document.getElementById('btnCloseCreateEnt');
    var cancelCreateButton = document.getElementById('btnCancelCreateEnt');
    var closeEditButton = document.getElementById('btnCloseEditEnt');
    var cancelEditButton = document.getElementById('btnCancelEditEnt');
    var closeRateButton = document.getElementById('btnCloseRateEnt');
    var cancelRateButton = document.getElementById('btnCancelRateEnt');
    var closeDeleteButton = document.getElementById('btnCloseDeleteEnt');
    var cancelDeleteButton = document.getElementById('btnCancelDeleteEnt');

    // Ouvre le dialog de création
    if (createButton) createButton.onclick = function () { openDialog('dialogCreateEnt'); };

    // Ferme le dialog de création
    if (closeCreateButton) closeCreateButton.onclick = function () { closeDialog('dialogCreateEnt'); };
    if (cancelCreateButton) cancelCreateButton.onclick = function () { closeDialog('dialogCreateEnt'); };

    // Ferme le dialog de modification
    if (closeEditButton) closeEditButton.onclick = function () { closeDialog('dialogEditEnt'); };
    if (cancelEditButton)cancelEditButton.onclick = function () { closeDialog('dialogEditEnt'); };

    // Ferme le dialog de notation
    if (closeRateButton) closeRateButton.onclick = function () { closeDialog('dialogRateEnt'); };
    if (cancelRateButton) cancelRateButton.onclick = function () { closeDialog('dialogRateEnt'); };

    // Ferme le dialog de suppression
    if (closeDeleteButton) closeDeleteButton.onclick = function () { closeDialog('dialogDeleteEnt'); };
    if (cancelDeleteButton) cancelDeleteButton.onclick = function () { closeDialog('dialogDeleteEnt'); };

    // ===== MODIFIER UNE ENTREPRISE =====
    // Remplit le formulaire de modification avec les données de l'entreprise cliquée
    document.querySelectorAll('.btn-edit').forEach(function (button) {
        button.onclick = function () {
            var editName = document.getElementById('editEntName');
            var entityId = document.getElementById('eEId');
            var nameInput = document.getElementById('eENom');
            var emailInput = document.getElementById('eEEmail');
            var phoneInput = document.getElementById('eETel');
            var descriptionInput = document.getElementById('eEDesc');

            if (editName) editName.textContent = button.dataset.name;
            if (entityId) entityId.value = button.dataset.id || '';
            if (nameInput) nameInput.value = button.dataset.name || '';
            if (emailInput) emailInput.value = button.dataset.email || '';
            if (phoneInput) phoneInput.value = button.dataset.number || '';
            if (descriptionInput) descriptionInput.value = button.dataset.description || '';

            openDialog('dialogEditEnt');
        };
    });

    // ===== NOTER UNE ENTREPRISE =====
    // Remplit le dialog de notation avec le nom et l'ID de l'entreprise cliquée
    document.querySelectorAll('.btn-rate').forEach(function (button) {
        button.onclick = function () {
            var rateName = document.getElementById('rateEntName');
            var rateId = document.getElementById('rateEId');

            if (rateName) rateName.textContent = button.dataset.name;
            if (rateId) rateId.value = button.dataset.id || '';

            openDialog('dialogRateEnt');
        };
    });

    // ===== SUPPRIMER UNE ENTREPRISE =====
    // Remplit le dialog de suppression avec le nom et l'ID de l'entreprise cliquée
    document.querySelectorAll('.btn-delete').forEach(function (button) {
        button.onclick = function () {
            var deleteName = document.getElementById('deleteEntName');
            var deleteId = document.getElementById('dEId');

            if (deleteName) deleteName.textContent = button.dataset.name;
            if (deleteId) deleteId.value = button.dataset.id || '';

            openDialog('dialogDeleteEnt');
        };
    });

});