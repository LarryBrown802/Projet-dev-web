<?php

namespace App\Controllers;

use App\Models\StudentManagementModel;
use Twig\Environment;

class StudentManagementController
{
    private Environment $twig;
    private StudentManagementModel $studentModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->studentModel = new StudentManagementModel();
    }

    public function index(): void
    {
        $message = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_student') {
            if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                
                $success = $this->studentModel->createStudent([
                    'nom' => trim($_POST['nom']),
                    'prenom' => trim($_POST['prenom']),
                    'email' => trim($_POST['email']),
                    'password' => $_POST['password'],
                    'id_center' => $_POST['id_center'] ?? null,
                    'id_promotion' => $_POST['id_promotion'] ?? null
                ]);

                if ($success) {
                    $message = "Le compte étudiant de " . $_POST['prenom'] . " " . $_POST['nom'] . " a été créé !";
                } else {
                    $error = "Erreur lors de la création. L'email existe peut-être déjà.";
                }
            } else {
                $error = "Veuillez remplir tous les champs obligatoires.";
            }
        }

        $allStudents = $this->studentModel->getAllStudents();
        $centers = $this->studentModel->getCenters();
        $promotions = $this->studentModel->getPromotions();

        $totalPages = $this->studentModel->totalPages($allStudents);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $students = $this->studentModel->getPage($allStudents, $pageCourante);
        $pages = $this->studentModel->getPageNumbers($pageCourante, $totalPages ?: 1);

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
        ]);
    }
}