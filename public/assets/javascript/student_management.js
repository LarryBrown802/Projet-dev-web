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
    ['dialogViewEtu', 'dialogCreateEtu', 'dialogEditEtu', 'dialogDeleteEtu'].forEach(function (id) {
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
    var createButton = document.getElementById('btnCreateEtu');
    var closeViewButton = document.getElementById('btnCloseViewEtu');
    var closeViewButtonAlt = document.getElementById('btnCloseViewEtu2');
    var closeCreateButton = document.getElementById('btnCloseCreateEtu');
    var cancelCreateButton = document.getElementById('btnCancelCreateEtu');
    var closeEditButton = document.getElementById('btnCloseEditEtu');
    var cancelEditButton = document.getElementById('btnCancelEditEtu');
    var closeDeleteButton = document.getElementById('btnCloseDeleteEtu');
    var cancelDeleteButton = document.getElementById('btnCancelDeleteEtu');

    // Ouvre le dialog de création
    if (createButton) createButton.onclick = function () { openDialog('dialogCreateEtu'); };

    // Ferme le dialog de visualisation
    if (closeViewButton) closeViewButton.onclick = function () { closeDialog('dialogViewEtu'); };
    if (closeViewButtonAlt) closeViewButtonAlt.onclick = function () { closeDialog('dialogViewEtu'); };

    // Ferme le dialog de création
    if (closeCreateButton) closeCreateButton.onclick = function () { closeDialog('dialogCreateEtu'); };
    if (cancelCreateButton) cancelCreateButton.onclick = function () { closeDialog('dialogCreateEtu'); };

    // Ferme le dialog de modification
    if (closeEditButton) closeEditButton.onclick = function () { closeDialog('dialogEditEtu'); };
    if (cancelEditButton) cancelEditButton.onclick = function () { closeDialog('dialogEditEtu'); };

    // Ferme le dialog de suppression
    if (closeDeleteButton) closeDeleteButton.onclick = function () { closeDialog('dialogDeleteEtu'); };
    if (cancelDeleteButton) cancelDeleteButton.onclick = function () { closeDialog('dialogDeleteEtu'); };

    // ===== VOIR LES CANDIDATURES D'UN ÉTUDIANT =====
    // Remplit le tableau du dialog avec les candidatures de l'étudiant cliqué
    document.querySelectorAll('.btn-view-etu').forEach(function (button) {
        button.onclick = function () {
            var title = document.getElementById('viewEtuTitle');
            var info = document.getElementById('viewEtuInfo');
            var tbody = document.getElementById('viewEtuTbody');

            // Affiche le nom complet et l'email
            if (title) title.textContent = (button.dataset.prenom || '') + ' ' + (button.dataset.nom || '');
            if (info) info.textContent = button.dataset.email || '';

            if (tbody) {
                tbody.innerHTML = '';
                try {
                    var candidatures = JSON.parse(button.dataset.candidatures || '[]');

                    if (candidatures.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="2">Aucune candidature.</td></tr>';
                    } else {
                        // Affiche chaque candidature dans le tableau
                        candidatures.forEach(function (c) {
                            var row = document.createElement('tr');
                            row.innerHTML = '<td><strong>' + c.offre + '</strong></td><td>' + c.entreprise + '</td>';
                            tbody.appendChild(row);
                        });
                    }
                } catch (error) {
                    tbody.innerHTML = '<tr><td colspan="2">Aucune candidature.</td></tr>';
                }
            }

            openDialog('dialogViewEtu');
        };
    });

    // ===== TOGGLE MOT DE PASSE (afficher / masquer) =====
    // Supporte les deux classes : toggle-password et toogle-password (faute de frappe historique)
    document.querySelectorAll('.toggle-password, .toogle-password').forEach(function (button) {
        button.addEventListener('click', function () {
            var input = button.parentElement ? button.parentElement.querySelector('input') : null;
            var icon  = button.querySelector('i');
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // ===== MODIFIER UN ÉTUDIANT =====
    // Remplit le formulaire de modification avec les données de l'étudiant cliqué
    document.querySelectorAll('.btn-edit-etu').forEach(function (button) {
        button.onclick = function () {
            var subtitle = document.getElementById('editEtuSubtitle');
            var profileId = document.getElementById('eIdProfile');
            var lastNameInput = document.getElementById('eNom');
            var firstNameInput = document.getElementById('ePrenom');
            var emailInput = document.getElementById('eEmail');
            var statusSelect = document.getElementById('eStatus');

            // Affiche le nom complet dans le sous-titre du dialog
            if (subtitle) subtitle.textContent = (button.dataset.prenom || '') + ' ' + (button.dataset.nom || '');
            if (profileId) profileId.value = button.dataset.id     || '';
            if (lastNameInput) lastNameInput.value = button.dataset.nom    || '';
            if (firstNameInput) firstNameInput.value = button.dataset.prenom || '';
            if (emailInput) emailInput.value = button.dataset.email  || '';

            // Pré-sélectionne le statut actuel de l'étudiant
            setSelectValue(statusSelect, button.dataset.status || 'wait');

            openDialog('dialogEditEtu');
        };
    });

    // ===== SUPPRIMER UN ÉTUDIANT =====
    // Remplit le dialog de suppression avec le nom et l'ID de l'étudiant cliqué
    document.querySelectorAll('.btn-delete-etu').forEach(function (button) {
        button.onclick = function () {
            var deleteName = document.getElementById('deleteEtuName');
            var deleteId = document.getElementById('dIdProfile');

            // Affiche le nom complet dans le dialog de confirmation
            if (deleteName) deleteName.textContent = (button.dataset.prenom || '') + ' ' + (button.dataset.nom || '');
            if (deleteId) deleteId.value = button.dataset.id || '';

            openDialog('dialogDeleteEtu');
        };
    });

});