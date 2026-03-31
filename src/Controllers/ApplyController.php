<?php

namespace App\Controllers;

use App\Models\ApplyModel;
use Twig\Environment;

class ApplyController
{
    private Environment $twig;
    private ApplyModel $applyModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->applyModel = new ApplyModel();
    }

    public function index(): void
    {
        // On récupère les infos de l'URL (GET) ou du formulaire (POST)
        $poste = $_POST['poste'] ?? $_GET['poste'] ?? 'Candidature spontanée';
        $entreprise = $_POST['entreprise'] ?? $_GET['entreprise'] ?? '';
        
        $message = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $lettre = trim($_POST['lettre'] ?? '');
            $userId = $_SESSION['id']; // L'ID de l'étudiant connecté

            $offerId = $this->applyModel->getOfferByName($poste);

            if (!$offerId) {
                $error = "Impossible de trouver cette offre dans la base de données.";
            } else {
                // Gestion du Profil
                $profileId = $this->applyModel->getProfileByUserId($userId);
                if (!$profileId) {
                    // S'il n'a pas de profil, on le crée !
                    $profileId = $this->applyModel->createProfile($userId, $nom, $prenom);
                }

                // Gestion du téléchargement du CV
                $cvFilename = "cv_non_fourni.pdf";
                if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
                    // On crée un nom unique pour éviter d'écraser un autre CV
                    $cvFilename = time() . '_' . basename($_FILES['cv']['name']);
                    
                    // Assure-toi de créer ce dossier 'uploads' dans ton dossier 'public/' !
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    move_uploaded_file($_FILES['cv']['tmp_name'], $uploadDir . $cvFilename);
                }

                // Sauvegarde finale
                $success = $this->applyModel->saveApplication($offerId, $profileId, $cvFilename, $lettre);

                if ($success) {
                    $message = "Félicitations ! Votre candidature pour le poste de $poste a été envoyée avec succès.";
                } else {
                    $error = "Vous avez déjà postulé à cette offre !";
                }
            }
        }

        echo $this->twig->render('apply.html.twig', [
            'current_page' => 'apply',
            'poste' => $poste,
            'entreprise' => $entreprise,
            'message' => $message,
            'error' => $error
        ]);
    }
}