<?php

namespace App\Controllers;

use App\Models\ProfileModel;
use Twig\Environment;
use PDO; // ✅ Import de PDO

class ProfileController
{
    private Environment $twig;
    private ProfileModel $profileModel;

    public function __construct(Environment $twig, PDO $bdd)
    {
        $this->twig         = $twig;
        $this->profileModel = new ProfileModel($bdd);
    }

    public function index(): void
    {
        // On s'assure d'avoir l'ID de l'utilisateur (le Routeur nous garantit déjà qu'il est connecté en tant qu'étudiant)
        $userId  = (int) $_SESSION['user_id'];
        
        // On récupère les infos du profil
        $profile = $this->profileModel->getProfileWithDetails($userId);

        $applications  = [];
        $wishlistCount = 0;

        // Si l'étudiant a bien rempli son profil, on charge ses candidatures et ses favoris
        if ($profile) {
            $applications  = $this->profileModel->getApplications($profile['ID_profile']);
            $wishlistCount = $this->profileModel->getWishlistCount($profile['ID_profile']);
        }

        // On envoie à la vue
        echo $this->twig->render('profile.html.twig', [
            'current_page'  => 'profile',
            'profile'       => $profile,
            'applications'  => $applications,
            'wishlistCount' => $wishlistCount,
        ]);
    }
}