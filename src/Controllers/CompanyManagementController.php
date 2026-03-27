<?php

namespace App\Controllers;

use App\Models\CompanyManagementModel;
use Twig\Environment;

class CompanyManagementController
{
    private Environment $twig;
    private CompanyManagementModel $companyModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->companyModel = new CompanyManagementModel();
    }

    public function index(): void
    {
        $allCompanies = $this->companyModel->getAllCompanies();
        $totalPages = $this->companyModel->totalPages($allCompanies);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $companies = $this->companyModel->getPage($allCompanies, $pageCourante);
        $pages = $this->companyModel->getPageNumbers($pageCourante, $totalPages ?: 1);

        echo $this->twig->render('company_management.html.twig', [
            'current_page' => 'company_management',
            'companies' => $companies,
            'pageCourante' => $pageCourante,
            'totalPages' => $totalPages,
            'pages' => $pages,
        ]);
    }
}