<?php

namespace App\Controllers;

use App\Models\WishlistModel;
use Twig\Environment;

class WishlistController
{
    private Environment $twig;
    private WishlistModel $wishlistModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig          = $twig;
        $this->wishlistModel = new WishlistModel($bdd);
    }

    public function index(): void
    {
        $userId      = $_SESSION['id_user']; 
        $profileId   = $this->wishlistModel->getOrCreateProfile($userId);
        $savedOffers = $this->wishlistModel->getSavedOffers($profileId);

        echo $this->twig->render('wishlist.html.twig', [
            'current_page' => 'wishlist',
            'offers'       => $savedOffers,
        ]);
    }

    public function toggleAjax(): void
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'etudiant') { 
            echo json_encode(['success' => false, 'error' => 'Veuillez vous connecter en tant qu\'étudiant.']);
            return;
        }

        $data    = json_decode(file_get_contents('php://input'), true);
        $offerId = (int) ($data['offer_id'] ?? 0);

        if ($offerId > 0) {
            $profileId = $this->wishlistModel->getOrCreateProfile($_SESSION['id_user']); // ← corrigé
            $isAdded   = $this->wishlistModel->toggleWishlist($offerId, $profileId);
            echo json_encode(['success' => true, 'added' => $isAdded]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID de l\'offre invalide.']);
        }
    }
}