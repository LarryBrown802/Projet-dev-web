<?php

namespace App\Controllers;

use App\Models\ApplyModel;
use Twig\Environment;
use PDO;

class ApplyController
{
    private Environment $twig;
    private ApplyModel $applyModel;

    public function __construct(Environment $twig, PDO $bdd)
    {
        $this->twig       = $twig;
        $this->applyModel = new ApplyModel($bdd);
    }

    public function index(): void
    {
        // 1. VÉRIFICATION DES ACCÈS
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?page=connexion');
            exit;
        }
        
        // 2. RÉCUPÉRATION DES DONNÉES DE L'OFFRE (Affichage du formulaire)
        $offerId = $_POST['offer_id'] ?? (isset($_GET['offer_id']) ? (int)$_GET['offer_id'] : null);
        $poste = $_GET['poste'] ?? 'Candidature spontanée';
        $entreprise = $_GET['entreprise'] ?? '';

        if ($offerId) {
            $offer = $this->applyModel->getOfferById($offerId);
            if ($offer) {
                $poste      = $offer['title'];
                $entreprise = $offer['entreprise'];
            }
        }

        $message = null;
        $error   = null;

        // 3. TRAITEMENT DU FORMULAIRE DE CANDIDATURE (S'il est soumis)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom      = trim($_POST['nom']    ?? '');
            $prenom   = trim($_POST['prenom'] ?? '');
            $lettre   = trim($_POST['lettre'] ?? '');
            $userId   = $_SESSION['user_id'];

            // On s'assure d'avoir l'ID de l'offre
            $offerId = $offerId ?? $this->applyModel->getOfferByName($poste);

            if (!$offerId) {
                $error = "Impossible de trouver cette offre dans la base de données.";
            } else {
                // On récupère ou on crée le profil de l'étudiant
                $profileId = $this->applyModel->getProfileByUserId($userId);
                if (!$profileId) {
                    $profileId = $this->applyModel->createProfile($userId, $nom, $prenom);
                }

                // ✅ SÉCURITÉ 1 : On vérifie s'il a déjà postulé AVANT de traiter le fichier !
                if ($this->applyModel->hasAlreadyApplied($profileId, $offerId)) {
                    $error = "Vous avez déjà postulé à cette offre !";
                } else {
                    // ✅ SÉCURITÉ 2 : Traitement sécurisé de l'upload du CV
                    $cvFilename = 'cv_non_fourni.pdf';
                    
                    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
                        
                        // Vérification stricte du type MIME (on veut que du PDF !)
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimeType = finfo_file($finfo, $_FILES['cv']['tmp_name']);
                        finfo_close($finfo);

                        if ($mimeType === 'application/pdf') {
                            $originalName = basename($_FILES['cv']['name']);
                            $cleanName    = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                            $cvFilename   = time() . '_' . $cleanName;
                            
                            // On définit le dossier (attention au chemin relatif !)
                            $uploadDir  = __DIR__ . '/../../public/uploads/';
                            
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                            }
                            
                            move_uploaded_file($_FILES['cv']['tmp_name'], $uploadDir . $cvFilename);
                            
                            // On sauvegarde en base de données
                            $success = $this->applyModel->saveApplication($offerId, $profileId, $cvFilename, $lettre);

                            if ($success) {
                                $message = "Félicitations ! Votre candidature pour le poste de $poste a été envoyée avec succès.";
                            } else {
                                $error = "Une erreur est survenue lors de l'enregistrement de votre candidature.";
                            }
                            
                        } else {
                            $error = "Erreur de sécurité : Seuls les fichiers PDF sont acceptés pour le CV.";
                        }
                    } else {
                        // L'étudiant n'a pas mis de CV (si c'est autorisé) ou il y a eu une erreur de poids de fichier
                         $success = $this->applyModel->saveApplication($offerId, $profileId, $cvFilename, $lettre);
                         if ($success) {
                             $message = "Candidature envoyée sans CV personnalisé.";
                         }
                    }
                }
            }
        }

        // 4. AFFICHAGE DE LA VUE
        echo $this->twig->render('apply.html.twig', [
            'current_page' => 'apply',
            'offer_id'     => $offerId,
            'poste'        => $poste,
            'entreprise'   => $entreprise,
            'message'      => $message,
            'error'        => $error,
        ]);
    }
}