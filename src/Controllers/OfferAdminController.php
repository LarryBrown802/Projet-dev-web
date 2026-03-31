<?php
/*
namespace App\Controllers;

use App\Models\OfferModel;
use App\Models\CompanyModel;
use App\Models\LocationModel;
use Twig\Environment;

class OfferAdminController
{
    private Environment $twig;
    private OfferModel $offerModel;
    private CompanyModel $companyModel;
    private LocationModel $locationModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig = $twig;
        $this->offerModel = new OfferModel($bdd);
        $this->companyModel = new CompanyModel($bdd);
        $this->locationModel = new LocationModel($bdd);
    }

    public function index(): void
    {
        // ===== CRÉER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
            $id_location = $this->locationModel->findOrCreate($_POST['city'] ?? '');

            $this->offerModel->create(
                $_POST['title'] ?? '',
                $_POST['description'] ?? '',
                $_POST['duration'] ?? '',
                (float) ($_POST['remuneration'] ?? 0),
                $_POST['type'] ?? '',
                $_POST['level'] ?? '',
                $_POST['domain'] ?? '',
                $_POST['publication_date'] ?? date('Y-m-d'),
                (int) ($_POST['id_company'] ?? 0),
                $id_location
            );
            header('Location: /index.php?page=offer_admin');
            exit;
        }

        // ===== MODIFIER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
            $this->offerModel->update(
                (int) $_POST['id'],
                $_POST['title'] ?? '',
                $_POST['description'] ?? '',
                $_POST['duration'] ?? '',
                (float) ($_POST['remuneration'] ?? 0),
                $_POST['type'] ?? '',
                $_POST['level'] ?? '',
                $_POST['domain'] ?? ''
            );
            header('Location: /index.php?page=offer_admin');
            exit;
        }

        // ===== SUPPRIMER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $this->offerModel->delete((int) $_POST['id']);
            header('Location: /index.php?page=offer_admin');
            exit;
        }

        $search = trim($_GET['search'] ?? '');
        $type = trim($_GET['type'] ?? '');
        $allOffers = $this->offerModel->getAllOffers($search, $type);
        $companies = $this->companyModel->getAll();
        $totalPages = $this->offerModel->totalPages($allOffers);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $offers = $this->offerModel->getPage($allOffers, $pageCourante);
        $pages = $this->offerModel->getPageNumbers($pageCourante, $totalPages ?: 1);

        echo $this->twig->render('offer_admin.html.twig', [
            'current_page' => 'offer_admin',
            'offers' => $offers,
            'companies' => $companies,
            'totalOffers' => count($allOffers),
            'pageCourante' => $pageCourante,
            'totalPages' => $totalPages,
            'pages' => $pages,
            'search' => $search,
            'type' => $type,
        ]);
    }
}*/
