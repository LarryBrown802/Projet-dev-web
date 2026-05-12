<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Utils\Pagination; // ✅ NOUVEAU : On importe notre boîte à outils mathématique
use Twig\Environment;
use PDO;

class CompanyController
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
        // 1. Paramètres de recherche et de pagination
        $search = $_GET['search'] ?? null;
        $pageDemandee = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $perPage = 6; // Nombre d'entreprises par page

        // 2. On compte le total d'entreprises (en prenant en compte la recherche !)
        $totalCompanies = $this->companyModel->countAll($search);

        // 3. Notre utilitaire fait tous les calculs complexes pour nous
        $paginationData = Pagination::getPaginationData($totalCompanies, $pageDemandee, $perPage);
        $pageCourante = $paginationData['pageCourante'];

        // 4. On calcule le OFFSET (à partir de quelle ligne on commence à lire)
        $offset = Pagination::getOffset($pageCourante, $perPage);

        // 5. On demande au modèle UNIQUEMENT les 6 entreprises de cette page
        $companies = $this->companyModel->getAll($search, $perPage, $offset);

        // 6. On envoie tout ça proprement à Twig
        echo $this->twig->render('company.html.twig', [
            'current_page' => 'company',
            'companies'    => $companies,
            'search'       => $search, // Utile pour garder le mot cherché dans la barre de recherche
            
            // Les variables pour ta barre de pagination Twig
            'pageCourante' => $pageCourante,
            'totalPages'   => $paginationData['totalPages'],
            'pages'        => $paginationData['pages'] // Ex: [1, 2, '...', 5]
        ]);
    }

    public function detail(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $company = $this->companyModel->getById($id);

        if (!$company) {
            // ✅ PETIT BONUS : Renvoyer une vraie page 404 Twig au lieu d'un texte brut
            http_response_code(404);
            echo $this->twig->render('404.html.twig', [
                'message' => "Cette entreprise n'existe pas ou a été supprimée."
            ]);
            return;
        }

        echo $this->twig->render('company_detail.html.twig', [
            'current_page' => 'company',
            'company' => $company,
        ]);
    }
}