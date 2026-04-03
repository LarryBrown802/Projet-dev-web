<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use Twig\Environment;

class CompanyController
{
    private Environment $twig;
    private CompanyModel $companyModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig = $twig;
        $this->companyModel = new CompanyModel($bdd);
    }

    public function index(): void
    {
        $allCompanies = $this->companyModel->getAll();
        $totalPages = $this->companyModel->totalPages($allCompanies);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $companies = $this->companyModel->getPage($allCompanies, $pageCourante);

        echo $this->twig->render('pages/company.html.twig', [
            'current_page' => 'company',
            'companies' => $companies,
            'pageCourante' => $pageCourante,
            'totalPages' => $totalPages,
        ]);
    }

    public function detail(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $company = $this->companyModel->getById($id);

        if (!$company) {
            http_response_code(404);
            echo "Entreprise introuvable.";
            return;
        }

        echo $this->twig->render('pages/company_detail.html.twig', [
            'current_page' => 'company',
            'company' => $company,
        ]);
    }
}