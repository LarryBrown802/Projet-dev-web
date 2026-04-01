<?php

namespace App\Controllers;

use App\Models\StudentManagementModel;
use Twig\Environment;

class StudentManagementController
{
    private Environment $twig;
    private StudentManagementModel $studentModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig         = $twig;
        $this->studentModel = new StudentManagementModel($bdd);
    }

    public function index(): void
    {
        $message = null;
        $error   = null;

        // ===== CRÉER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_student') {
            if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                $success = $this->studentModel->createStudent([
                    'nom'          => trim($_POST['nom']),
                    'prenom'       => trim($_POST['prenom']),
                    'email'        => trim($_POST['email']),
                    'password'     => $_POST['password'],
                    'id_promotion' => $_POST['id_promotion'] ?? null,
                ]);
                if ($success) {
                    $message = "Le compte de " . $_POST['prenom'] . " " . $_POST['nom'] . " a été créé !";
                } else {
                    $error = "Erreur lors de la création. L'email existe peut-être déjà.";
                }
            } else {
                $error = "Veuillez remplir tous les champs obligatoires.";
            }
        }

        // ===== MODIFIER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_student') {
            $this->studentModel->updateStudent([
                'id_profile'   => (int) $_POST['id_profile'],
                'nom'          => trim($_POST['nom']      ?? ''),
                'prenom'       => trim($_POST['prenom']   ?? ''),
                'email'        => trim($_POST['email']    ?? ''),
                'password'     => trim($_POST['password'] ?? ''), // ← vide = pas de changement
                'id_promotion' => !empty($_POST['id_promotion']) ? (int) $_POST['id_promotion'] : null,
                'status'       => $_POST['status'] ?? 'wait',
            ]);
            header('Location: /index.php?page=student_management');
            exit;
        }

        // ===== MODIFIER STATUT =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_status') {
            $this->studentModel->updateStatus(
                (int) $_POST['id_profile'],
                $_POST['status'] ?? 'wait'
            );
            header('Location: /index.php?page=student_management');
            exit;
        }

        // ===== SUPPRIMER =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_student') {
            $success = $this->studentModel->deleteStudent((int) $_POST['id_profile']);
            if ($success) {
                $message = "L'étudiant a été supprimé.";
            } else {
                $error = "Erreur lors de la suppression.";
            }
        }

        $allStudents  = $this->studentModel->getAllStudents();
        foreach ($allStudents as &$student) {
            $candidatures = $this->studentModel->getCandidaturesByProfile($student['ID_profile']);
            $student['candidatures_detail'] = json_encode($candidatures);
        }
        unset($student); 

        $promotions   = $this->studentModel->getPromotions();
        $totalPages   = $this->studentModel->totalPages($allStudents);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $students     = $this->studentModel->getPage($allStudents, $pageCourante);
        $pages        = $this->studentModel->getPageNumbers($pageCourante, $totalPages ?: 1);

        echo $this->twig->render('student_management.html.twig', [
            'current_page' => 'student_management',
            'students'     => $students,
            'promotions'   => $promotions,
            'message'      => $message,
            'error'        => $error,
            'pageCourante' => $pageCourante,
            'totalPages'   => $totalPages,
            'pages'        => $pages,
        ]);
    }
}