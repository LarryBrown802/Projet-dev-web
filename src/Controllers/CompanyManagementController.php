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
        $message = null;
        $error = null;

        // 1. TRAITEMENT DU FORMULAIRE : CRÉER UNE ENTREPRISE
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_company') {
            $name = trim($_POST['name'] ?? '');
            
            if (!empty($name)) {
                $success = $this->companyModel->createCompany(
                    $name,
                    $_POST['description'] ?? '',
                    $_POST['email'] ?? '',
                    $_POST['tel'] ?? '',
                    $_SESSION['id'] // L'ID du pilote connecté !
                );

                if ($success) {
                    $message = "L'entreprise $name a été ajoutée avec succès !";
                } else {
                    $error = "Erreur lors de l'ajout de l'entreprise.";
                }
            } else {
                $error = "Le nom de l'entreprise est obligatoire.";
            }
        }

        // 2. RÉCUPÉRATION DES DONNÉES
        if ($_SESSION['role'] === 'admin') {
            $allCompanies = $this->companyModel->getAllCompanies();
        } else {
            $allCompanies = $this->companyModel->getCompaniesByPilot($_SESSION['id']);
        }

        $totalPages = $this->companyModel->totalPages($allCompanies);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $companies = $this->companyModel->getPage($allCompanies, $pageCourante);
        $pages = $this->companyModel->getPageNumbers($pageCourante, $totalPages ?: 1);

        echo $this->twig->render('company_management.html.twig', [
            'current_page' => 'company_management',
            'companies' => $companies,
            'message' => $message,
            'error' => $error,
            'pageCourante' => $pageCourante,
            'totalPages' => $totalPages,
            'pages' => $pages,
        ]);
    }
}