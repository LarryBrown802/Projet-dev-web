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

    // Sélectionne la bonne option dans un <select>
    function setSelectValue(select, value) {
        if (!select) return;
        for (var i = 0; i < select.options.length; i++) {
            select.options[i].selected = select.options[i].value === value;
        }
    }

    // ===== FERMETURE AU CLIC SUR LE BACKDROP =====
    ['dialogCreatePilote', 'dialogEditPilote', 'dialogDeletePilote'].forEach(function (id) {
        var d = document.getElementById(id);
        if (!d) return;
        d.addEventListener('click', function (e) {
            var r = d.getBoundingClientRect();
            if (e.clientX < r.left || e.clientX > r.right ||
                e.clientY < r.top  || e.clientY > r.bottom) {
                d.close();
            }
        });
    });

    // ===== BOUTONS OUVRIR / FERMER =====
    var createButton = document.getElementById('btnCreatePilote');
    var closeCreateButton = document.getElementById('btnCloseCreatePilote');
    var cancelCreateButton = document.getElementById('btnCancelCreatePilote');
    var closeEditButton = document.getElementById('btnCloseEditPilote');
    var cancelEditButton = document.getElementById('btnCancelEditPilote');
    var closeDeleteButton = document.getElementById('btnCloseDeletePilote');
    var cancelDeleteButton = document.getElementById('btnCancelDeletePilote');

    // Ouvre le dialog de création
    if (createButton) createButton.onclick = function () { openDialog('dialogCreatePilote'); };

    // Ferme le dialog de création
    if (closeCreateButton) closeCreateButton.onclick  = function () { closeDialog('dialogCreatePilote'); };
    if (cancelCreateButton) cancelCreateButton.onclick = function () { closeDialog('dialogCreatePilote'); };

    // Ferme le dialog de modification
    if (closeEditButton) closeEditButton.onclick = function () { closeDialog('dialogEditPilote'); };
    if (cancelEditButton) cancelEditButton.onclick = function () { closeDialog('dialogEditPilote'); };

    // Ferme le dialog de suppression
    if (closeDeleteButton) closeDeleteButton.onclick = function () { closeDialog('dialogDeletePilote'); };
    if (cancelDeleteButton) cancelDeleteButton.onclick = function () { closeDialog('dialogDeletePilote'); };

    // ===== MODIFIER UN PILOTE =====
    // Remplit le formulaire de modification avec les données du pilote cliqué
    document.querySelectorAll('.btn-edit').forEach(function (button) {
        button.onclick = function () {
            var subtitle = document.getElementById('editPiloteSubtitle');
            var userId = document.getElementById('eIdUser');
            var nameInput = document.getElementById('eName');
            var surnameInput = document.getElementById('eSurname');
            var emailInput = document.getElementById('eEmail');
            var promotionSelect = document.getElementById('ePromotion');

            // Affiche le nom complet dans le sous-titre du dialog
            if (subtitle) subtitle.textContent = (button.dataset.name || '') + ' ' + (button.dataset.surname || '');
            if (userId) userId.value = button.dataset.id      || '';
            if (nameInput) nameInput.value = button.dataset.name    || '';
            if (surnameInput) surnameInput.value = button.dataset.surname || '';
            if (emailInput) emailInput.value = button.dataset.email   || '';

            // Pré-sélectionne la promotion actuelle du pilote
            setSelectValue(promotionSelect, button.dataset.idPromotion || '');

            openDialog('dialogEditPilote');
        };
    });

    // ===== SUPPRIMER UN PILOTE =====
    // Remplit le dialog de suppression avec le nom et l'ID du pilote cliqué
    document.querySelectorAll('.btn-delete').forEach(function (button) {
        button.onclick = function () {
            var deleteName = document.getElementById('deletePiloteName');
            var deleteId = document.getElementById('dIdUser');

            // Affiche le nom complet dans le dialog de confirmation
            if (deleteName) deleteName.textContent = (button.dataset.name || '') + ' ' + (button.dataset.surname || '');
            if (deleteId) deleteId.value = button.dataset.id || '';

            openDialog('dialogDeletePilote');
        };
    });

});