<?php

namespace App\Controllers;

use App\Models\OfferModel;
use App\Models\CompanyModel;
use App\Models\LocationModel;
use App\Utils\Pagination; // ✅ IMPORTATION DE L'UTILITAIRE
use Twig\Environment;
use PDO;

class OfferManagementController
{
    private Environment $twig;
    private OfferModel $offerModel;
    private CompanyModel $companyModel;
    private LocationModel $locationModel;

    public function __construct(Environment $twig, PDO $bdd)
    {
        $this->twig          = $twig;
        $this->offerModel    = new OfferModel($bdd);
        $this->companyModel  = new CompanyModel($bdd);
        $this->locationModel = new LocationModel($bdd);
    }

    public function index(): void
    {
        // ===== SÉCURITÉ : Récupération du rôle et de l'ID =====
        $role   = $_SESSION['role'] ?? '';
        $userId = $_SESSION['user_id'] ?? 0;

        // ===== CRÉER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
            $id_location = $this->locationModel->findOrCreate(trim($_POST['city'] ?? ''));

            $this->offerModel->create(
                trim($_POST['title'] ?? ''),
                trim($_POST['description'] ?? ''),
                trim($_POST['duration'] ?? ''),
                (float) ($_POST['remuneration'] ?? 0),
                trim($_POST['type'] ?? ''),
                trim($_POST['level'] ?? ''),
                trim($_POST['domain'] ?? ''),
                trim($_POST['publication_date'] ?? date('Y-m-d')),
                (int) ($_POST['id_company'] ?? 0),
                $id_location
            );
            header('Location: /index.php?page=offer_management');
            exit;
        }

        // ===== MODIFIER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
            $this->offerModel->update(
                (int) $_POST['id'],
                trim($_POST['title'] ?? ''),
                trim($_POST['description'] ?? ''),
                trim($_POST['duration'] ?? ''),
                (float) ($_POST['remuneration'] ?? 0),
                trim($_POST['type'] ?? ''),
                trim($_POST['level'] ?? ''),
                trim($_POST['domain'] ?? '')
            );
            header('Location: /index.php?page=offer_management');
            exit;
        }

        // ===== SUPPRIMER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $this->offerModel->delete((int) $_POST['id']);
            header('Location: /index.php?page=offer_management');
            exit;
        }

        // ===== AFFICHAGE ET PAGINATION =====
        $search       = trim($_GET['search'] ?? '');
        $type         = trim($_GET['type'] ?? '');
        $pageDemandee = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $perPage      = 10; // 10 offres par page pour l'administration

        // 1. Comptage intelligent selon le rôle
        if ($role === 'administrateur') {
            $totalOffers = $this->offerModel->countAllOffers($search, $type);
        } else {
            $totalOffers = $this->offerModel->countOffersByPilot($userId, $search, $type);
        }

        // 2. Calculs de pagination
        $paginationData = Pagination::getPaginationData($totalOffers, $pageDemandee, $perPage);
        $pageCourante   = $paginationData['pageCourante'];
        $offset         = Pagination::getOffset($pageCourante, $perPage);

        // 3. Récupération des offres paginées selon le rôle
        if ($role === 'administrateur') {
            $offers = $this->offerModel->getAllOffers($search, $type, $perPage, $offset);
        } else {
            $offers = $this->offerModel->getOffersByPilot($userId, $search, $type, $perPage, $offset);
        }

        // 4. Récupération des entreprises pour le menu déroulant (formulaire de création)
        // On demande une limite très haute (ex: 1000) pour être sûr d'avoir toutes les entreprises dans le <select>
        $companies = $this->companyModel->getAll(null, 1000);

        // 5. Envoi à Twig
        echo $this->twig->render('offer_management.html.twig', [
            'current_page' => 'offer_management',
            'offers'       => $offers,
            'companies'    => $companies,
            'totalOffers'  => $totalOffers,
            
            // Pagination
            'pageCourante' => $pageCourante,
            'totalPages'   => $paginationData['totalPages'],
            'pages'        => $paginationData['pages'],
            
            // Filtres
            'search'       => $search,
            'type'         => $type,
        ]);
    }
}