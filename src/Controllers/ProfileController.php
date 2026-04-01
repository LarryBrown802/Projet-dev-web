<?php

namespace App\Controllers;

use App\Models\ProfileModel;
use Twig\Environment;

class ProfileController
{
    private Environment $twig;
    private ProfileModel $profileModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig         = $twig;
        $this->profileModel = new ProfileModel($bdd);
    }

    public function index(): void
    {
        $userId  = $_SESSION['user_id'];
        $profile = $this->profileModel->getProfileWithDetails($userId);

        $applications  = [];
        $wishlistCount = 0;

        if ($profile) {
            $applications  = $this->profileModel->getApplications($profile['ID_profile']);
            $wishlistCount = $this->profileModel->getWishlistCount($profile['ID_profile']);
        }

        echo $this->twig->render('profile.html.twig', [
            'current_page'  => 'profile',
            'profile'       => $profile,
            'applications'  => $applications,
            'wishlistCount' => $wishlistCount,
        ]);
    }
}