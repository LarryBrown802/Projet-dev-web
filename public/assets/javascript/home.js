document.addEventListener('DOMContentLoaded', function () {

    // ===== ONGLETS (Description / Entreprise) =====
    var tabButtons = document.querySelectorAll('.tab-btn');
    var tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(function (button) {
        button.addEventListener('click', function () {

            // Désactive tous les onglets
            tabButtons.forEach(function (other) {
                other.classList.remove('active');
                other.setAttribute('aria-selected', 'false');
            });

            // Cache tous les contenus
            tabContents.forEach(function (content) {
                content.classList.remove('active');
                content.setAttribute('hidden', 'true');
            });

            // Active l'onglet cliqué
            button.classList.add('active');
            button.setAttribute('aria-selected', 'true');

            // Affiche le contenu correspondant
            var target = document.getElementById('tab-' + button.dataset.tab);
            if (target) {
                target.classList.add('active');
                target.removeAttribute('hidden');
            }
        });
    });

    // ===== PANNEAU DROIT — mise à jour au clic sur une mini-carte =====
    var offerTriggers = document.querySelectorAll('.js-offer-trigger');
    var companyElement = document.getElementById('detail-entreprise');
    var titleElement = document.getElementById('detail-poste');
    var locationTag = document.getElementById('detail-tag-lieu');
    var typeTag = document.getElementById('detail-tag-type');
    var durationTag = document.getElementById('detail-tag-duree');
    var levelTag = document.getElementById('detail-tag-niveau');
    var descriptionElement = document.getElementById('detail-desc-text');
    var levelRequirement = document.getElementById('detail-req-niveau');
    var typeRequirement = document.getElementById('detail-req-type');
    var durationRequirement = document.getElementById('detail-req-duree');
    var companyDescElement = document.getElementById('detail-entdesc-text');
    var applyButton = document.querySelector('.js-apply-btn');
    var iconElement = document.getElementById('detail-icon-main');
    var detailBookmarkCheckbox = document.getElementById('detail-bookmark-checkbox');

    offerTriggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {

            // Retire la classe active de toutes les mini-cartes
            offerTriggers.forEach(function (other) {
                other.classList.remove('active');
            });

            // Active la carte cliquée
            trigger.classList.add('active');

            // Met à jour les textes du panneau droit
            if (companyElement) companyElement.textContent = trigger.dataset.entreprise;
            if (titleElement) titleElement.textContent = trigger.dataset.poste;
            if (locationTag) locationTag.textContent = trigger.dataset.lieu;
            if (typeTag) typeTag.textContent = trigger.dataset.type;
            if (durationTag) durationTag.textContent = trigger.dataset.duree;
            if (levelTag) levelTag.textContent = trigger.dataset.niveau;
            if (descriptionElement)  descriptionElement.textContent = trigger.dataset.desc;
            if (levelRequirement) levelRequirement.textContent = trigger.dataset.niveau;
            if (typeRequirement) typeRequirement.textContent = trigger.dataset.type;
            if (durationRequirement) durationRequirement.textContent = trigger.dataset.duree;
            if (companyDescElement) companyDescElement.textContent = trigger.dataset.entdesc;

            // Met à jour l'icône du panneau droit
            if (iconElement) {
                iconElement.className = 'fa-solid ' + (trigger.dataset.icon || 'fa-briefcase');
            }

            // Met à jour l'ID de l'offre sur le bouton Postuler
            if (applyButton) {
                applyButton.dataset.id = trigger.dataset.id;
            }

            // Synchronise le bookmark du panneau droit avec celui de la mini-carte
            if (detailBookmarkCheckbox) {
                detailBookmarkCheckbox.dataset.offerId = trigger.dataset.id;
                var leftCheckbox = trigger.querySelector('.bookmark-checkbox');
                if (leftCheckbox) {
                    detailBookmarkCheckbox.checked = leftCheckbox.checked;
                }
            }
        });
    });

    // ===== BOUTON POSTULER =====
    if (applyButton) {
        applyButton.addEventListener('click', function () {
            window.location.href = '/index.php?page=apply&offer_id=' + applyButton.dataset.id;
        });
    }

    // ===== EMPÊCHE LE CLIC SUR BOOKMARK DE DÉCLENCHER LA MINI-CARTE =====
    document.querySelectorAll('.ui-bookmark').forEach(function (label) {
        label.addEventListener('click', function (event) {
            event.stopPropagation();
        });
    });

    // ===== WISHLIST AJAX =====
    // Synchronise tous les bookmarks du même offer_id et envoie la requête au serveur
    document.querySelectorAll('.bookmark-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', async function () {
            var offerId = checkbox.dataset.offerId;
            if (!offerId) return;

            var isChecked = checkbox.checked;

            // Synchronise visuellement tous les bookmarks du même offer_id
            document.querySelectorAll('.bookmark-checkbox[data-offer-id="' + offerId + '"]').forEach(function (other) {
                if (other !== checkbox) other.checked = isChecked;
            });

            try {
                var response = await fetch('/index.php?page=toggle_wishlist', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ offer_id: parseInt(offerId, 10) })
                });
                var result = await response.json();

                // En cas d'erreur serveur, annule le changement visuel
                if (!result.success) {
                    document.querySelectorAll('.bookmark-checkbox[data-offer-id="' + offerId + '"]').forEach(function (other) {
                        other.checked = !isChecked;
                    });
                    alert(result.error || 'Erreur lors de la mise à jour des favoris.');
                }
            } catch (error) {
                // En cas d'erreur réseau, annule le changement visuel
                document.querySelectorAll('.bookmark-checkbox[data-offer-id="' + offerId + '"]').forEach(function (other) {
                    other.checked = !isChecked;
                });
                console.error('Erreur wishlist', error);
            }
        });
    });

});