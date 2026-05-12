<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfileModel;
use App\Models\PromotionModel;
use App\Utils\Pagination; // ✅ IMPORTATION DE L'UTILITAIRE
use Twig\Environment;
use PDO;

class PilotAdminController
{
    private Environment $twig;
    private UserModel $userModel;
    private ProfileModel $profileModel;
    private PromotionModel $promotionModel;

    public function __construct(Environment $twig, PDO $bdd)
    {
        $this->twig           = $twig;
        $this->userModel      = new UserModel($bdd);
        $this->profileModel   = new ProfileModel($bdd);
        $this->promotionModel = new PromotionModel($bdd);
    }

    public function index(): void
    {
        // ===== CRÉER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
            $id_user = $this->userModel->createWithRole(
                trim($_POST['email'] ?? ''),
                $_POST['password'] ?? '', // Ne pas faire de trim() sur un mot de passe !
                'pilote'
            );
            
            if ($id_user) {
                $this->profileModel->create(trim($_POST['name'] ?? ''), trim($_POST['surname'] ?? ''), $id_user);

                if (!empty($_POST['id_promotion'])) {
                    // ← Rejoindre une promotion existante
                    $this->profileModel->setPromotion($id_user, (int) $_POST['id_promotion']);
                } elseif (!empty($_POST['promotion'])) {
                    // ← Créer une nouvelle promotion
                    $id_promotion = $this->promotionModel->create(trim($_POST['promotion']));
                    if ($id_promotion) {
                        $this->profileModel->setPromotion($id_user, $id_promotion);
                    }
                }
            }
            header('Location: /index.php?page=pilot_admin');
            exit;
        }

        // ===== MODIFIER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
            $id_user = (int) $_POST['id_user'];
            $this->userModel->updateEmail($id_user, trim($_POST['email'] ?? ''));
            $this->profileModel->update($id_user, trim($_POST['name'] ?? ''), trim($_POST['surname'] ?? ''));

            if (!empty($_POST['promotion'])) {
                // ✅ SUPPRESSION du "code mort" qui chargeait tous les pilotes pour rien !
                $existing = $this->promotionModel->findById((int)($_POST['id_promotion'] ?? 0));
                
                if ($existing) {
                    $this->promotionModel->update($existing['ID_promotion'], trim($_POST['promotion']));
                } else {
                    $id_promotion = $this->promotionModel->create(trim($_POST['promotion']));
                    if ($id_promotion) {
                        $this->profileModel->setPromotion($id_user, $id_promotion);
                    }
                }
            }
            header('Location: /index.php?page=pilot_admin');
            exit;
        }

        // ===== SUPPRIMER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $id_user = (int) $_POST['id_user'];
            $this->profileModel->delete($id_user);
            $this->userModel->delete($id_user);
            header('Location: /index.php?page=pilot_admin');
            exit;
        }

        // ===== AFFICHAGE ET PAGINATION =====
        $search       = trim($_GET['search'] ?? '');
        $pageDemandee = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $perPage      = 10;

        // On récupère toutes les promotions pour le menu déroulant du formulaire
        // On demande une limite large (ex: 500) pour tout avoir dans le select
        $promotions = $this->promotionModel->getAll(500);

        // 1. On compte les pilotes
        $totalPilots = $this->userModel->countAllByRole('pilote', $search);

        // 2. Calculs via notre Utilitaire
        $paginationData = Pagination::getPaginationData($totalPilots, $pageDemandee, $perPage);
        $pageCourante   = $paginationData['pageCourante'];
        $offset         = Pagination::getOffset($pageCourante, $perPage);

        // 3. Récupération des pilotes de la page courante
        $pilots = $this->userModel->getAllByRole('pilote', $search, $perPage, $offset);

        echo $this->twig->render('pilot_admin.html.twig', [
            'current_page' => 'pilot_admin',
            'pilots'       => $pilots,
            'promotions'   => $promotions,
            
            // Variables de pagination
            'pageCourante' => $pageCourante,
            'totalPages'   => $paginationData['totalPages'],
            'pages'        => $paginationData['pages'],
            
            'search'       => $search,
        ]);
    }
}