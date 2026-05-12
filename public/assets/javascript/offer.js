document.addEventListener('DOMContentLoaded', function () {

    // ===== DIALOG VOIR PLUS =====
    var dialog = document.getElementById('offreDialog');
    var postButton = document.getElementById('btnPostuler');

    // Remplit le dialog avec les données de l'offre cliquée
    document.querySelectorAll('.open-dialog').forEach(function (button) {
        button.addEventListener('click', function () {
            var title = document.getElementById('dialogTitle');
            var company = document.getElementById('dialogCompany');
            var location = document.getElementById('dialogLieu');
            var type = document.getElementById('dialogType');
            var level = document.getElementById('dialogNiveau');
            var duration = document.getElementById('dialogDuree');
            var remuneration = document.getElementById('dialogRemu');
            var description = document.getElementById('dialogDesc');
            var companyDescription = document.getElementById('dialogEntrepriseDesc');

            if (title) title.textContent = button.dataset.poste;
            if (company) company.textContent = button.dataset.entreprise;
            if (location) location.textContent = button.dataset.lieu;
            if (type) type.textContent = button.dataset.type;
            if (level) level.textContent = button.dataset.niveau;
            if (duration) duration.textContent = button.dataset.duree;
            if (remuneration) remuneration.textContent = button.dataset.remu;
            if (description) description.textContent = button.dataset.desc;
            if (companyDescription) companyDescription.textContent = button.dataset.entreprisedesc;

            // Met à jour le bouton Postuler avec l'ID de l'offre
            if (postButton) {
                postButton.onclick = function () {
                    window.location.href = '/index.php?page=apply&offer_id=' + button.dataset.id;
                };
            }

            // Ouvre le dialog
            if (dialog && typeof dialog.showModal === 'function') {
                dialog.showModal();
            }
        });
    });

    // Ferme le dialog au clic en dehors
    if (dialog) {
        dialog.addEventListener('click', function (event) {
            var rect = dialog.getBoundingClientRect();
            var clickedOutside = event.clientX < rect.left || event.clientX > rect.right ||
                                 event.clientY < rect.top  || event.clientY > rect.bottom;
            if (clickedOutside) dialog.close();
        });
    }

    // ===== CAROUSEL STATISTIQUES =====
    var track = document.querySelector('.carousel-track');
    var cards = document.querySelectorAll('.stat-card');
    var previousButton = document.querySelector('.carousel-btn.prev');
    var nextButton = document.querySelector('.carousel-btn.next');
    var dots = document.querySelectorAll('.carousel-dots .dot');

    if (track && cards.length > 0) {
        var currentIndex = 0;

        // Déplace le carousel et met à jour les points de navigation
        function updateCarousel() {
            var cardWidth = cards[0].offsetWidth;
            var gap = parseFloat(window.getComputedStyle(track).gap) || 0;
            track.style.transform  = 'translateX(-' + ((cardWidth + gap) * currentIndex) + 'px)';
            track.style.transition = 'transform 0.4s ease-in-out';
            dots.forEach(function (dot, index) {
                dot.classList.toggle('active', index === currentIndex);
            });
        }

        // Bouton suivant
        if (nextButton) {
            nextButton.addEventListener('click', function () {
                if (currentIndex < cards.length - 1) {
                    currentIndex += 1;
                    updateCarousel();
                }
            });
        }

        // Bouton précédent
        if (previousButton) {
            previousButton.addEventListener('click', function () {
                if (currentIndex > 0) {
                    currentIndex -= 1;
                    updateCarousel();
                }
            });
        }

        // Navigation par les points
        dots.forEach(function (dot, index) {
            dot.addEventListener('click', function () {
                currentIndex = index;
                updateCarousel();
            });
        });
    }

    // ===== WISHLIST AJAX =====
    // Envoie le toggle au serveur et annule visuellement en cas d'erreur
    document.querySelectorAll('.bookmark-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', async function () {
            var offerId = checkbox.dataset.offerId;
            if (!offerId) return;

            var previousState = !checkbox.checked;

            try {
                var response = await fetch('/index.php?page=toggle_wishlist', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ offer_id: parseInt(offerId, 10) })
                });
                var result = await response.json();

                // Annule le changement visuel si erreur serveur
                if (!result.success) {
                    checkbox.checked = previousState;
                    alert(result.error || 'Erreur lors de la mise à jour des favoris.');
                }
            } catch (error) {
                // Annule le changement visuel si erreur réseau
                checkbox.checked = previousState;
                console.error('Erreur wishlist', error);
            }
        });
    });

    function applySortFilter(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            url.searchParams.set('p', '1');
            window.location.href = url.toString();
        }

});