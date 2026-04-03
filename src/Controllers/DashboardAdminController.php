<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CompanyModel;
use App\Models\OfferModel;
use Twig\Environment;

class DashboardAdminController
{
    private Environment $twig;
    private UserModel $userModel;
    private CompanyModel $companyModel;
    private OfferModel $offerModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig         = $twig;
        $this->userModel    = new UserModel($bdd);
        $this->companyModel = new CompanyModel($bdd);
        $this->offerModel   = new OfferModel($bdd);
    }

    public function index(): void
    {
        // ===== KPIs =====
        $nbPilotes      = count($this->userModel->getAllByRole('pilote'));
        $nbEtudiants    = count($this->userModel->getAllByRole('etudiant'));
        $nbEntreprises  = count($this->companyModel->getAll());
        $nbOffres       = count($this->offerModel->getAllOffers());
        $nbCandidatures = $this->offerModel->countAllApplications();

        // ===== TABLEAUX =====
        $latestOffers = $this->offerModel->getAllOffers(null, null, 5);
        $pilots       = $this->userModel->getAllByRole('pilote');
        $pilots       = array_slice($pilots, 0, 3); // 3 premiers pilotes

        echo $this->twig->render('admin/dashboard_admin.html.twig', [
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