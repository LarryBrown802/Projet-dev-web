<?php

namespace App\Controllers;

use App\Models\ApplyModel;
use Twig\Environment;

class ApplyController
{
    private Environment $twig;
    private ApplyModel $applyModel;

    public function __construct(Environment $twig, \PDO $bdd) // ← ajoute $bdd
    {
        $this->twig       = $twig;
        $this->applyModel = new ApplyModel($bdd); // ← transmets $bdd
    }

    public function index(): void
    {
        // Redirige vers connexion si non connceté 
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?page=connexion');
            exit;
        }
        
        // Récupère l'offre via ID ou via nom
        $offerId = $_POST['offer_id'] ?? (isset($_GET['offer_id']) ? (int)$_GET['offer_id'] : null);
        $poste = $_GET['poste'] ?? 'Candidature spontanée';
        $entreprise = $_GET['entreprise'] ?? '';

        // Si on a un offer_id, on récupère les vraies infos
        if ($offerId) {
            $offer = $this->applyModel->getOfferById($offerId);
            if ($offer) {
                $poste      = $offer['title'];
                $entreprise = $offer['entreprise'];
            }
        }

        $message = null;
        $error   = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom      = trim($_POST['nom']    ?? '');
            $prenom   = trim($_POST['prenom'] ?? '');
            $lettre   = trim($_POST['lettre'] ?? '');
            $userId   = $_SESSION['user_id']; // ← corrigé

            $offerId = $offerId ?? $this->applyModel->getOfferByName($poste);

            if (!$offerId) {
                $error = "Impossible de trouver cette offre dans la base de données.";
            } else {
                $profileId = $this->applyModel->getProfileByUserId($userId);
                if (!$profileId) {
                    $profileId = $this->applyModel->createProfile($userId, $nom, $prenom);
                }

                $cvFilename = 'cv_non_fourni.pdf';
                if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
                $originalName = basename($_FILES['cv']['name']);
                $cleanName    = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                $cvFilename   = time() . '_' . $cleanName;
                $uploadDir  = __DIR__ . '/../../../uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    move_uploaded_file($_FILES['cv']['tmp_name'], $uploadDir . $cvFilename);
                }

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
            'offer_id' => $offerId,
            'poste' => $poste,
            'entreprise' => $entreprise,
            'message' => $message,
            'error' => $error,
        ]);
    }
}