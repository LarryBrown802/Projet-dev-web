<?php

namespace App\Controllers;

use App\Models\OfferManagementModel;
use Twig\Environment;

class OfferManagementController
{
    private Environment $twig;
    private OfferManagementModel $offerModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->offerModel = new OfferManagementModel(); 
    }

    public function index(): void
    {
        $message = null;
        $error = null;

        // 1. TRAITEMENT DU FORMULAIRE DE CRÉATION D'OFFRE (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
            if (!empty($_POST['title']) && !empty($_POST['id_company'])) {
                
                $success = $this->offerModel->createOffer([
                    'title' => $_POST['title'],
                    'description' => $_POST['description'] ?? '',
                    'duration' => $_POST['duration'] ?? '',
                    'remuneration' => $_POST['remuneration'] ?? 0,
                    'type' => $_POST['type'] ?? '',
                    'level' => $_POST['level'] ?? '',
                    'domain' => $_POST['domain'] ?? '',
                    'location' => $_POST['id_location'] ?? 1,
                    'company' => $_POST['id_company']
                ]);

                if ($success) {
                    $message = "L'offre a été publiée avec succès !";
                }
                // S'il y a une erreur SQL, ton `die()` dans le modèle prendra le relais pour l'afficher !
            } else {
                $error = "Veuillez remplir les champs obligatoires (Titre et Entreprise).";
            }
        }

        // 2. RÉCUPÉRATION DES DONNÉES (C'est ici qu'on utilise tes supers fonctions !)
        if ($_SESSION['role'] === 'admin') {
            $allOffers = $this->offerModel->getAllOffers();
            $companies = $this->offerModel->getAllCompanies(); // Pour le menu déroulant Admin
        } else {
            // Le pilote connecté
            $allOffers = $this->offerModel->getOffersByPilot($_SESSION['id']);
            $companies = $this->offerModel->getCompaniesByPilot($_SESSION['id']); // Ses entreprises !
        }

        // On récupère les villes pour le menu déroulant
        $locations = $this->offerModel->getLocations();

        // 3. PAGINATION
        $totalPages = $this->offerModel->totalPages($allOffers);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $offers = $this->offerModel->getPage($allOffers, $pageCourante);
        $pages = $this->offerModel->getPageNumbers($pageCourante, $totalPages ?: 1);

        // 4. ON ENVOIE TOUT À LA VUE TWIG
        echo $this->twig->render('offer_management.html.twig', [
            'current_page' => 'offer_management',
            'offers' => $offers,
            'companies' => $companies,   // <-- Ça va remplir le menu "Entreprise"
            'locations' => $locations,   // <-- Ça va remplir le menu "Lieu"
            'message' => $message,
            'error' => $error,
            'pageCourante' => $pageCourante,
            'totalPages' => $totalPages,
            'pages' => $pages,
        ]);
    }
}