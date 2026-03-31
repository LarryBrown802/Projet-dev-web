<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CompanyModel;
use App\Models\OfferModel;
use App\Models\ProfileModel;
use Twig\Environment;

class DashboardPilotController
{
    private Environment $twig;
    private UserModel $userModel;
    private CompanyModel $companyModel;
    private OfferModel $offerModel;
    private ProfileModel $profileModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig = $twig;
        $this->userModel = new UserModel($bdd);
        $this->companyModel = new CompanyModel($bdd);
        $this->offerModel = new OfferModel($bdd);
        $this->profileModel = new ProfileModel($bdd);
    }

    public function index(): void
    {
        $id_user = $_SESSION['id_user'];

        // Infos du pilote connecté
        $pilot   = $this->userModel->findById($id_user);
        // Récupère le pilote connecté avec sa promotion
        $pilots = $this->userModel->getAllByRole('pilote');
        $profile = array_filter($pilots, fn($p) => $p['ID_user'] === $id_user);
        $profile = reset($profile); // Premier élément du tableau filtré
        
        // KPIs
        $nbEtudiants = count($this->userModel->getAllByRole('etudiant'));
        $nbEntreprises = count($this->companyModel->getAll());
        $nbOffres = count($this->offerModel->getAllOffers());
        $nbCandidatures = $this->offerModel->countAllApplications();

        // 5 dernières offres
        $latestOffers = $this->offerModel->getAllOffers(null, null, 5);

        echo $this->twig->render('dashboard_pilot.html.twig', [
            'current_page' => 'dashboard_pilot',
            'pilot' => $pilot,
            'profile' => $profile,
            'nbEtudiants' => $nbEtudiants,
            'nbEntreprises' => $nbEntreprises,
            'nbOffres' => $nbOffres,
            'nbCandidatures' => $nbCandidatures,
            'latestOffers' => $latestOffers,
        ]);
    }
}