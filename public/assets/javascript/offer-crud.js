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

    function setSelectValue(select, value) {
        if (!select) return;
        for (var i = 0; i < select.options.length; i++) {
            select.options[i].selected = select.options[i].value === value;
        }
    }

    // ===== FERMETURE AU CLIC SUR LE BACKDROP =====
    var dialogIds = [
        'dialogViewOffre',
        'dialogCreateOffre',
        'dialogEditOffre',
        'dialogDeleteOffre'
    ];

    dialogIds.forEach(function (id) {
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
    var createButton = document.getElementById('btnCreateOffre');
    var closeCreateButton = document.getElementById('btnCloseCreateOffre');
    var cancelCreateButton = document.getElementById('btnCancelCreateOffre');
    var closeViewButton = document.getElementById('btnCloseViewOffre');
    var closeViewButtonAlt = document.getElementById('btnCloseViewOffre2');
    var closeEditButton = document.getElementById('btnCloseEditOffre');
    var cancelEditButton = document.getElementById('btnCancelEditOffre');
    var closeDeleteButton = document.getElementById('btnCloseDeleteOffre');
    var cancelDeleteButton = document.getElementById('btnCancelDeleteOffre');

    if (createButton) createButton.onclick = function () { openDialog('dialogCreateOffre'); };
    if (closeCreateButton) closeCreateButton.onclick = function () { closeDialog('dialogCreateOffre'); };
    if (cancelCreateButton) cancelCreateButton.onclick = function () { closeDialog('dialogCreateOffre'); };
    if (closeViewButton) closeViewButton.onclick = function () { closeDialog('dialogViewOffre'); };
    if (closeViewButtonAlt) closeViewButtonAlt.onclick = function () { closeDialog('dialogViewOffre'); };
    if (closeEditButton) closeEditButton.onclick = function () { closeDialog('dialogEditOffre'); };
    if (cancelEditButton) cancelEditButton.onclick = function () { closeDialog('dialogEditOffre'); };
    if (closeDeleteButton) closeDeleteButton.onclick  = function () { closeDialog('dialogDeleteOffre'); };
    if (cancelDeleteButton) cancelDeleteButton.onclick = function () { closeDialog('dialogDeleteOffre'); };

    // ===== VOIR UNE OFFRE =====
    document.querySelectorAll('.btn-view-offre').forEach(function (button) {
        button.onclick = function () {
            var title = document.getElementById('viewOffreTitre');
            var company = document.getElementById('viewOffreEntreprise');
            var location = document.getElementById('viewLieu');
            var type = document.getElementById('viewType');
            var level = document.getElementById('viewNiveau');
            var duration = document.getElementById('viewDuree');
            var remuneration = document.getElementById('viewRemu');
            var publicationDate = document.getElementById('viewDate');
            var description = document.getElementById('viewDesc');

            if (title) title.textContent = button.dataset.titre;
            if (company) company.textContent = button.dataset.entreprise;
            if (location) location.textContent = button.dataset.lieu;
            if (type) type.textContent = button.dataset.type;
            if (level) level.textContent = button.dataset.niveau;
            if (duration) duration.textContent = button.dataset.duree;
            if (remuneration) remuneration.textContent = button.dataset.remu;
            if (publicationDate) publicationDate.textContent = button.dataset.date;
            if (description) description.textContent = button.dataset.desc;

            openDialog('dialogViewOffre');
        };
    });

    // ===== MODIFIER UNE OFFRE =====
    document.querySelectorAll('.btn-edit-offre').forEach(function (button) {
        button.onclick = function () {
            var subtitle = document.getElementById('editOffreSubtitle');
            var offerId = document.getElementById('eOffreId');
            var titleInput = document.getElementById('eTitle');
            var domainInput = document.getElementById('eDomain');
            var durationInput = document.getElementById('eDuration');
            var remuInput = document.getElementById('eRemu');
            var cityInput = document.getElementById('eCity');
            var descInput = document.getElementById('eDesc');
            var typeSelect = document.getElementById('eType');
            var levelSelect = document.getElementById('eLevel');

            if (subtitle) subtitle.textContent  = button.dataset.titre;
            if (offerId) offerId.value = button.dataset.id     || '';
            if (titleInput) titleInput.value = button.dataset.titre  || '';
            if (domainInput) domainInput.value = button.dataset.domain || '';
            if (durationInput) durationInput.value = button.dataset.duree  || '';
            if (remuInput) remuInput.value = button.dataset.remu   || '';
            if (cityInput) cityInput.value = button.dataset.lieu   || '';
            if (descInput) descInput.value = button.dataset.desc   || '';

            setSelectValue(typeSelect,  button.dataset.type   || '');
            setSelectValue(levelSelect, button.dataset.niveau || '');

            openDialog('dialogEditOffre');
        };
    });

    // ===== SUPPRIMER UNE OFFRE =====
    document.querySelectorAll('.btn-delete-offre').forEach(function (button) {
        button.onclick = function () {
            var deleteTitle = document.getElementById('deleteOffreTitre');
            var deleteId = document.getElementById('dOffreId');

            if (deleteTitle) deleteTitle.textContent = button.dataset.titre;
            if (deleteId) deleteId.value = button.dataset.id || '';

            openDialog('dialogDeleteOffre');
        };
    });

});