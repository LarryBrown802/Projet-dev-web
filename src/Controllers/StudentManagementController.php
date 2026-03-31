<?php

namespace App\Controllers;

use App\Models\StudentManagementModel;
use App\Models\StudentModel;
use App\Models\UserModel;
use App\Models\ProfileModel;
use App\Models\PromotionModel;
use Twig\Environment;

class StudentManagementController
{
    private Environment $twig;
    private StudentModel $studentModel;
    private UserModel $userModel;
    private ProfileModel $profileModel;
    private PromotionModel $promotionModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig = $twig;
        $this->studentModel = new StudentManagementModel($bdd);
        $this->userModel = new UserModel($bdd);
        $this->profileModel = new ProfileModel($bdd);
        $this->promotionModel = new PromotionModel($bdd);
    }

    public function index(): void
    {
        $message = null;
        $error = null;

        // Déterminer le rôle et les restrictions d'accès
        $userRole = $_SESSION['role'] ?? null;
        $userId = $_SESSION['id_user'] ?? null;
        $promotionId = null;

        // Si pilote : récupérer sa promotion
        if ($userRole === 'pilote' && $userId) {
            $promotionId = $this->profileModel->getPromotion($userId);
        }

        // ===== CRÉER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
            $id_user = $this->userModel->createWithRole(
                $_POST['email'] ?? '',
                $_POST['password'] ?? '',
                'etudiant'
            );
            if ($id_user) {
                $this->profileModel->create($_POST['name'] ?? '', $_POST['surname'] ?? '', $id_user);

                $promoToUse = $promotionId; // Si pilote : sa promotion
                if ($userRole === 'administrateur' && !empty($_POST['id_promotion'])) {
                    $promoToUse = (int) $_POST['id_promotion'];
                }

                if ($promoToUse) {
                    $this->profileModel->setPromotion($id_user, $promoToUse);
                }
            }
            header('Location: /index.php?page=student_management');
            exit;
        }

        // ===== MODIFIER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
            $id_user = (int) ($_POST['id_user'] ?? 0);
            if ($id_user > 0) {
                // Vérification de sécurité : pilote ne peut modifier que ses propres étudiants
                if ($userRole === 'pilote' && $promotionId) {
                    $studentPromoId = $this->profileModel->getPromotion($id_user);
                    if ($studentPromoId !== $promotionId) {
                        $error = "Vous ne pouvez modifier que les étudiants de votre promotion.";
                        goto render;
                    }
                }

                $this->userModel->updateEmail($id_user, $_POST['email'] ?? '');
                if (!empty($_POST['password'])) {
                    $this->userModel->updatePassword($id_user, $_POST['password']);
                }
                $this->profileModel->update($id_user, $_POST['name'] ?? '', $_POST['surname'] ?? '');

                if ($userRole === 'administrateur' && !empty($_POST['id_promotion'])) {
                    $this->profileModel->setPromotion($id_user, (int) $_POST['id_promotion']);
                }
            }
            header('Location: /index.php?page=student_management');
            exit;
        }

        // ===== SUPPRIMER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $id_user = (int) ($_POST['id_user'] ?? 0);
            if ($id_user > 0) {
                // Vérification de sécurité : pilote ne peut supprimer que ses propres étudiants
                if ($userRole === 'pilote' && $promotionId) {
                    $studentPromoId = $this->profileModel->getPromotion($id_user);
                    if ($studentPromoId !== $promotionId) {
                        $error = "Vous ne pouvez supprimer que les étudiants de votre promotion.";
                        goto render;
                    }
                }

                $this->profileModel->delete($id_user);
                $this->userModel->delete($id_user);
            }
            header('Location: /index.php?page=student_management');
            exit;
        }

        // Récupérer les données
        $allStudents = $this->studentModel->getAllStudents();
        
        // Si pilote : filtrer les étudiants par promotion
        if ($userRole === 'pilote' && $promotionId) {
            $allStudents = array_filter($allStudents, function($student) use ($promotionId) {
                return isset($student['ID_promotion']) && $student['ID_promotion'] == $promotionId;
            });
        }
        
        $promotions = $this->studentModel->getPromotions();
        $centers = $this->studentModel->getCenters();

        $totalPages = $this->studentModel->totalPages($allStudents);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $students = $this->studentModel->getPage($allStudents, $pageCourante);
        $pages = $this->studentModel->getPageNumbers($pageCourante, $totalPages ?: 1);

        render:
        echo $this->twig->render('student_management.html.twig', [
            'current_page' => 'student_management',
            'students' => $students,
            'centers' => $centers,
            'promotions' => $promotions,
            'message' => $message,
            'error' => $error,
            'pageCourante' => $pageCourante,
            'totalPages' => $totalPages,
            'pages' => $pages,
            'user_role' => $userRole,
            'pilot_promotion_id' => $promotionId,
            'role' => $userRole,
        ]);
    }
}