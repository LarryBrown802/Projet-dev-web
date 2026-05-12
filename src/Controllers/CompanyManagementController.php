<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Utils\Pagination; // ✅ IMPORTATION DE L'UTILITAIRE
use Twig\Environment;
use PDO;

class CompanyManagementController
{
    private Environment $twig;
    private CompanyModel $companyModel;

    public function __construct(Environment $twig, PDO $bdd)
    {
        $this->twig = $twig;
        $this->companyModel = new CompanyModel($bdd);
    }

    public function index(): void
    {
        // ===== CRÉER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
            $this->companyModel->create(
                trim($_POST['name'] ?? ''),
                trim($_POST['email'] ?? ''),
                trim($_POST['number'] ?? ''),
                trim($_POST['description'] ?? '')
            );
            header('Location: /index.php?page=company_management');
            exit;
        }

        // ===== MODIFIER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
            $this->companyModel->update(
                (int) $_POST['id'],
                trim($_POST['name'] ?? ''),
                trim($_POST['email'] ?? ''),
                trim($_POST['number'] ?? ''),
                trim($_POST['description'] ?? '')
            );
            header('Location: /index.php?page=company_management');
            exit;
        }

        // ===== ÉVALUER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'rate') {
            $this->companyModel->updateMark(
                (int) $_POST['id'],
                (float) $_POST['note']
            );
            header('Location: /index.php?page=company_management');
            exit;
        }

        // ===== SUPPRIMER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $this->companyModel->delete((int) $_POST['id']);
            header('Location: /index.php?page=company_management');
            exit;
        }

        // ===== AFFICHAGE ET PAGINATION (Mise à jour MVC) =====
        $search = trim($_GET['search'] ?? '');
        $pageDemandee = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $perPage = 10; // Pour un dashboard d'administration, on peut afficher un peu plus d'éléments par page (ex: 10)

        // 1. On compte le total pour la pagination
        $totalCompanies = $this->companyModel->countAll($search);

        // 2. On calcule les données de pagination via notre Utilitaire
        $paginationData = Pagination::getPaginationData($totalCompanies, $pageDemandee, $perPage);
        $pageCourante = $paginationData['pageCourante'];
        $offset = Pagination::getOffset($pageCourante, $perPage);

        // 3. On récupère uniquement la page courante depuis la base de données
        $companies = $this->companyModel->getAll($search, $perPage, $offset);

        // 4. Envoi à la vue
        echo $this->twig->render('company_management.html.twig', [
            'current_page' => 'company_management',
            'companies'    => $companies,
            'search'       => $search,
            
            // Variables de pagination
            'pageCourante' => $pageCourante,
            'totalPages'   => $paginationData['totalPages'],
            'pages'        => $paginationData['pages'],
        ]);
    }
}