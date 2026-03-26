<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use Twig\Environment;

class CompanyController
{
    private Environment $twig;
    private CompanyModel $companyModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->companyModel = new CompanyModel();
    }

    public function index(): void
    {
        $allCompanies = $this->companyModel->getAll();
        
        // Gestion de la pagination
        $totalPages = $this->companyModel->totalPages($allCompanies);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $companies = $this->companyModel->getPage($allCompanies, $pageCourante);

        echo $this->twig->render('company.html.twig', [
            'current_page' => 'company',
            'companies' => $companies,
            'pageCourante' => $pageCourante,
            'totalPages' => $totalPages
        ]);
    }

    public function detail(): void
    {
        // On récupère l'ID dans l'URL (?id=1)
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        // On va chercher l'entreprise
        $company = $this->companyModel->getById($id);

        if (!$company) {
            // Si l'entreprise n'existe pas, on redirige ou on affiche une erreur 404
            http_response_code(404);
            echo "Entreprise introuvable.";
            return;
        }

        echo $this->twig->render('company_detail.html.twig', [
            'current_page' => 'company',
            'company' => $company
        ]);
    }
}