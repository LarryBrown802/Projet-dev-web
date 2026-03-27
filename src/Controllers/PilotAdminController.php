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
                $this->profileModel->create(
                    $_POST['name']    ?? '',
                    $_POST['surname'] ?? '',
                    $id_user
                );
                // Crée la promotion si renseignée
                if (!empty($_POST['promotion'])) {
                    $this->promotionModel->create($_POST['promotion'], $id_user);
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

            // Met à jour ou crée la promotion
            $promotion = $this->promotionModel->getByUser($id_user);
            if ($promotion) {
                $this->promotionModel->update($promotion['ID_promotion'], $_POST['promotion'] ?? '');
            } elseif (!empty($_POST['promotion'])) {
                $this->promotionModel->create($_POST['promotion'], $id_user);
            }

            header('Location: /index.php?page=pilot_admin');
            exit;
        }

        // ===== SUPPRIMER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $id_user = (int) $_POST['id_user'];
            $this->promotionModel->deleteByUser($id_user); // ← supprime la promotion d'abord
            $this->profileModel->delete($id_user);
            $this->userModel->delete($id_user);
            header('Location: /index.php?page=pilot_admin');
            exit;
        }

        $pilots = $this->userModel->getAllByRole('pilote');

        echo $this->twig->render('pilot_admin.html.twig', [
            'current_page' => 'pilot_admin',
            'pilots'       => $pilots,
        ]);
    }
}