<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CompanyModel;
use App\Models\OfferModel;
use Twig\Environment;
use PDO; // ✅ Import de PDO

class DashboardAdminController
{
    private Environment $twig;
    private UserModel $userModel;
    private CompanyModel $companyModel;
    private OfferModel $offerModel;

    public function __construct(Environment $twig, PDO $bdd)
    {
        $this->twig         = $twig;
        $this->userModel    = new UserModel($bdd);
        $this->companyModel = new CompanyModel($bdd);
        $this->offerModel   = new OfferModel($bdd);
    }

    public function index(): void
    {
        // ===== KPIs (Optimisés avec des requêtes SQL natives COUNT) =====
        // La base de données ne renvoie qu'un seul chiffre (ex: "42"), c'est instantané.
        $nbPilotes      = $this->userModel->countAllByRole('pilote'); 
        $nbEtudiants    = $this->userModel->countAllByRole('etudiant');
        $nbEntreprises  = $this->companyModel->countAll(); // ✅ Utilisation de notre nouvelle fonction
        $nbOffres       = $this->offerModel->countAllOffers(); // ✅ Idem
        $nbCandidatures = $this->offerModel->countAllApplications();

        // ===== TABLEAUX (Optimisés avec LIMIT) =====
        
        // ✅ On utilise la fonction getLatestOffers qu'on a vue dans OfferModel
        $latestOffers = $this->offerModel->getLatestOffers(5);
        
        // ✅ On utilise les paramètres de getAllByRole ($role, $search, $limit, $offset)
        // On demande "pilote", pas de recherche (null), limite de 3, en commençant à 0.
        $pilots = $this->userModel->getAllByRole('pilote', null, 3, 0);

        echo $this->twig->render('dashboard_admin.html.twig', [
            'current_page'   => 'dashboard_admin',
            'nbPilotes'      => $nbPilotes,
            'nbEtudiants'    => $nbEtudiants,
            'nbEntreprises'  => $nbEntreprises,
            'nbOffres'       => $nbOffres,
            'nbCandidatures' => $nbCandidatures,
            'latestOffers'   => $latestOffers,
            'pilots'         => $pilots,
        ]);
    }
}