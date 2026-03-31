<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfileModel;
use App\Models\PromotionModel;
use Twig\Environment;

class PilotAdminController
{
    private Environment $twig;
    private UserModel $userModel;
    private ProfileModel $profileModel;
    private PromotionModel $promotionModel;

    public function __construct(Environment $twig, \PDO $bdd)
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
                $_POST['email']    ?? '',
                $_POST['password'] ?? '',
                'pilote'
            );
            if ($id_user) {
                $this->profileModel->create($_POST['name'] ?? '', $_POST['surname'] ?? '', $id_user);

                if (!empty($_POST['id_promotion'])) {
                    // ← Rejoindre une promotion existante
                    $this->profileModel->setPromotion($id_user, (int) $_POST['id_promotion']);
                } elseif (!empty($_POST['promotion'])) {
                    // ← Créer une nouvelle promotion
                    $id_promotion = $this->promotionModel->create($_POST['promotion']);
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
            $this->userModel->updateEmail($id_user, $_POST['email'] ?? '');
            $this->profileModel->update($id_user, $_POST['name'] ?? '', $_POST['surname'] ?? '');

            if (!empty($_POST['promotion'])) {
                // Vérifie si le pilote a déjà une promotion
                $pilot = $this->userModel->getAllByRole('pilote');
                $existing = $this->promotionModel->findById((int)($_POST['id_promotion'] ?? 0));
                if ($existing) {
                    $this->promotionModel->update($existing['ID_promotion'], $_POST['promotion']);
                } else {
                    $id_promotion = $this->promotionModel->create($_POST['promotion']);
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

        $promotions = $this->promotionModel->getAll();
        $search     = trim($_GET['search'] ?? '');
        $allUsers   = $this->userModel->getAllByRole('pilote', $search);
        $totalPages  = $this->userModel->totalPages($allUsers);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $pilots      = $this->userModel->getPage($allUsers, $pageCourante);
        $pages       = $this->userModel->getPageNumbers($pageCourante, $totalPages ?: 1);

        echo $this->twig->render('pilot_admin.html.twig', [
            'current_page' => 'pilot_admin',
            'pilots'       => $pilots,
            'promotions'   => $promotions,
            'pageCourante' => $pageCourante,
            'totalPages'   => $totalPages,
            'pages'        => $pages,
            'search'       => $search,
        ]);
    }
}