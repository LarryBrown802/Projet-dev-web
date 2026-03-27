<?php

namespace App\Controllers;

use App\Models\WishlistModel;
use Twig\Environment;

class WishlistController
{
    private Environment $twig;
    private WishlistModel $wishlistModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->wishlistModel = new WishlistModel();
    }

    // 1. Afficher la page avec les favoris
    public function index(): void
    {
        $userId = $_SESSION['id'];
        $profileId = $this->wishlistModel->getOrCreateProfile($userId);
        
        $savedOffers = $this->wishlistModel->getSavedOffers($profileId);

        echo $this->twig->render('wishlist.html.twig', [
            'current_page' => 'wishlist',
            'offers' => $savedOffers
        ]);
    }

    // 2. Traiter le clic sur le marque-page (Requête AJAX)
    public function toggleAjax(): void
    {
        header('Content-Type: application/json');

        // On s'assure que c'est bien un étudiant connecté
        if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'etudiant') {
            echo json_encode(['success' => false, 'error' => 'Veuillez vous connecter en tant qu\'étudiant.']);
            return;
        }

        // On lit les données envoyées par le JavaScript
        $data = json_decode(file_get_contents('php://input'), true);
        $offerId = (int)($data['offer_id'] ?? 0);

        if ($offerId > 0) {
            $profileId = $this->wishlistModel->getOrCreateProfile($_SESSION['id']);
            $isAdded = $this->wishlistModel->toggleWishlist($offerId, $profileId);
            
            echo json_encode(['success' => true, 'added' => $isAdded]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID de l\'offre invalide.']);
        }
    }
}