<?php

namespace App\Controllers;

use App\Models\WishlistModel;
use App\Utils\Pagination; // ✅ IMPORTATION DE L'UTILITAIRE
use Twig\Environment;
use PDO; // ✅ Import de PDO

class WishlistController
{
    private Environment $twig;
    private WishlistModel $wishlistModel;

    public function __construct(Environment $twig, PDO $bdd)
    {
        $this->twig          = $twig;
        $this->wishlistModel = new WishlistModel($bdd);
    }

    public function index(): void
    {
        // ✅ SÉCURITÉ : Vérification de la session
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?page=connexion');
            exit;
        }

        $userId    = (int) $_SESSION['user_id']; 
        $profileId = $this->wishlistModel->getOrCreateProfile($userId);

        // ===== PAGINATION =====
        $pageDemandee = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $perPage      = 6; // Nombre de favoris affichés par page

        // 1. Comptage
        $totalSavedOffers = $this->wishlistModel->countSavedOffers($profileId);

        // 2. Utilitaire de calcul
        $paginationData = Pagination::getPaginationData($totalSavedOffers, $pageDemandee, $perPage);
        $pageCourante   = $paginationData['pageCourante'];
        $offset         = Pagination::getOffset($pageCourante, $perPage);

        // 3. Récupération optimisée
        $savedOffers = $this->wishlistModel->getSavedOffers($profileId, $perPage, $offset);

        echo $this->twig->render('wishlist.html.twig', [
            'current_page' => 'wishlist',
            'offers'       => $savedOffers,
            
            // Variables de pagination
            'pageCourante' => $pageCourante,
            'totalPages'   => $paginationData['totalPages'],
            'pages'        => $paginationData['pages'],
        ]);
    }

    public function toggleAjax(): void
    {
        // Déclare formellement au navigateur que la réponse est du JSON
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'etudiant') { 
            echo json_encode(['success' => false, 'error' => 'Veuillez vous connecter en tant qu\'étudiant.']);
            exit; // ✅ Coupe strictement l'exécution du script
        }

        $data    = json_decode(file_get_contents('php://input'), true);
        $offerId = (int) ($data['offer_id'] ?? 0);

        if ($offerId > 0) {
            $profileId = $this->wishlistModel->getOrCreateProfile((int) $_SESSION['user_id']);
            $isAdded   = $this->wishlistModel->toggleWishlist($offerId, $profileId);
            
            echo json_encode(['success' => true, 'added' => $isAdded]);
            exit; // ✅ Coupe strictement l'exécution du script
        } else {
            echo json_encode(['success' => false, 'error' => 'ID de l\'offre invalide.']);
            exit; // ✅ Coupe strictement l'exécution du script
        }
    }
}