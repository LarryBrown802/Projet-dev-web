<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use Twig\Environment;

class CompanyManagementController
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
        // ===== CRÉER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
            $this->companyModel->create(
                $_POST['name'] ?? '',
                $_POST['email'] ?? '',
                $_POST['number'] ?? '',
                $_POST['description'] ?? ''
            );
            header('Location: /index.php?page=company_management');
            exit;
        }

        // ===== MODIFIER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
            $this->companyModel->update(
                (int) $_POST['id'],
                $_POST['name'] ?? '',
                $_POST['email'] ?? '',
                $_POST['number'] ?? '',
                $_POST['description'] ?? ''
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

        $search = trim($_GET['search'] ?? '');
        $allCompanies = $this->companyModel->getAll($search);
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
            'search' => $search,
        ]);
    }
}