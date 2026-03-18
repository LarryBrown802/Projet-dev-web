<?php

namespace App\Controllers;

use App\Models\StudentModel;
use Twig\Environment;

class StudentManagementController
{
    private Environment $twig;
    private StudentModel $studentModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->studentModel = new StudentModel();
    }

    public function index(): void
    {
        // Admin voit tout, pilote voit seulement ses étudiants
        if ($_SESSION['role'] === 'admin') {
            $allStudents = $this->studentModel->getAllStudents();
        } else {
            $allStudents = $this->studentModel->getStudentsByPilote($_SESSION['id']);
        }

        $totalPages   = $this->studentModel->totalPages($allStudents);
        $pageCourante = max(1, min((int)($_GET['p'] ?? 1), $totalPages ?: 1));
        $students     = $this->studentModel->getPage($allStudents, $pageCourante);

        echo $this->twig->render('student_management.html.twig', [
            'current_page' => 'student_management',
            'students'     => $students,
            'pageCourante' => $pageCourante,
            'totalPages'   => $totalPages,
        ]);
    }
}