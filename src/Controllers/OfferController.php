<?php

namespace App\Controllers;

use App\Models\OfferModel;
use App\Models\WishlistModel;
use App\Utils\Pagination; // ✅ IMPORTATION DE NOTRE BOÎTE À OUTILS
use Twig\Environment;
use PDO;

class OfferController
{
    private Environment $twig;
    private OfferModel $offerModel;
    private WishlistModel $wishlistModel;

    public function __construct(Environment $twig, PDO $bdd)
    {
        $this->twig          = $twig;
        $this->offerModel    = new OfferModel($bdd);
        $this->wishlistModel = new WishlistModel($bdd);
    }

    public function index(): void
    {
        // 1. Récupération et nettoyage de tous les filtres
        $search     = trim($_GET['search'] ?? '');
        $location   = trim($_GET['location'] ?? '');
        $types      = is_array($_GET['types'] ?? null) ? array_map('trim', $_GET['types']) : [];
        $levels     = is_array($_GET['levels'] ?? null) ? array_map('trim', $_GET['levels']) : [];
        $categories = is_array($_GET['categories'] ?? null) ? array_map('trim', $_GET['categories']) : [];

        // 2. Paramètres de pagination
        $pageDemandee = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $perPage = 6; // Nombre d'offres par page

        // 3. Comptage du nombre TOTAL d'offres (avec les filtres appliqués !)
        // ✅ C'est le SQL qui compte, pas PHP !
        $totalOffers = $this->offerModel->countSearchOffers($search, $location, $types, $levels, $categories);

        // 4. Calculs de pagination via notre utilitaire
        $paginationData = Pagination::getPaginationData($totalOffers, $pageDemandee, $perPage);
        $pageCourante   = $paginationData['pageCourante'];
        $offset         = Pagination::getOffset($pageCourante, $perPage);

        // 5. Récupération des offres de la page courante UNIQUEMENT
        // ✅ On passe $perPage (limit) et $offset à la fin
        $offers = $this->offerModel->searchOffers($search, $location, $types, $levels, $categories, $perPage, $offset);

        // 6. Récupération de la Wishlist (parfaitement géré)
        $wishlistIds = [];
        if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'etudiant') {
            $profileId   = $this->wishlistModel->getOrCreateProfile($_SESSION['user_id']);
            $wishlistIds = $this->wishlistModel->getWishlistIds($profileId);
        }

        // 7. Envoi à la vue
        echo $this->twig->render('offer.html.twig', [
            'current_page' => 'offers',
            'offers'       => $offers,
            'totalOffers'  => $totalOffers, // ✅ Remplace count($allOffers)
            
            // Variables de pagination
            'pageCourante' => $pageCourante,
            'totalPages'   => $paginationData['totalPages'],
            'pages'        => $paginationData['pages'],
            
            // Maintien des filtres dans Twig (pour que les cases restent cochées)
            'search'       => $search,
            'location'     => $location,
            'types'        => $types,
            'levels'       => $levels,
            'categories'   => $categories,
            
            'wishlistIds'  => $wishlistIds,
        ]);
    }
}