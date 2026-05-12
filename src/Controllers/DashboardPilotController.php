<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CompanyModel;
use App\Models\OfferModel;
use App\Models\ProfileModel;
use Twig\Environment;
use PDO; // ✅ Import propre

class DashboardPilotController
{
    private Environment $twig;
    private UserModel $userModel;
    private CompanyModel $companyModel;
    private OfferModel $offerModel;
    private ProfileModel $profileModel;

    public function __construct(Environment $twig, PDO $bdd)
    {
        $this->twig         = $twig;
        $this->userModel    = new UserModel($bdd);
        $this->companyModel = new CompanyModel($bdd);
        $this->offerModel   = new OfferModel($bdd);
        $this->profileModel = new ProfileModel($bdd);
    }

    public function index(): void
    {
        // 1. Sécurité : Vérifier si l'utilisateur est bien connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?page=connexion');
            exit;
        }

        $id_user = (int) $_SESSION['user_id'];

        // ===== INFOS DU PILOTE CONNECTÉ =====
        $pilot   = $this->userModel->findById($id_user);
        
        // ✅ CORRECTION : On demande directement le profil à la DB au lieu de filtrer tout l'annuaire !
        $profile = $this->profileModel->getProfileWithDetails($id_user);
        
        // ===== KPIs (Optimisés avec SQL COUNT) =====
        $nbEtudiants    = $this->userModel->countAllByRole('etudiant');
        $nbEntreprises  = $this->companyModel->countAll();
        $nbOffres       = $this->offerModel->countAllOffers();
        $nbCandidatures = $this->offerModel->countAllApplications();

        // ===== TABLEAUX =====
        // ✅ Utilisation de la méthode optimisée pour les dernières offres
        $latestOffers = $this->offerModel->getLatestOffers(5);

        echo $this->twig->render('dashboard_pilot.html.twig', [
            'current_page'   => 'dashboard_pilot',
            'pilot'          => $pilot,
            'profile'        => $profile,
            'nbEtudiants'    => $nbEtudiants,
            'nbEntreprises'  => $nbEntreprises,
            'nbOffres'       => $nbOffres,
            'nbCandidatures' => $nbCandidatures,
            'latestOffers'   => $latestOffers,
        ]);
    }
}