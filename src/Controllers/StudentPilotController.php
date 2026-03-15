<?php

namespace App\Controllers;

use App\Models\StudentModel;
use Twig\Environment;

class StudentPilotController
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
        $allStudents  = $this->studentModel->getAllStudents();
        $totalPages   = $this->studentModel->totalPages($allStudents);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $students     = $this->studentModel->getPage($allStudents, $pageCourante);

        echo $this->twig->render('student_pilot.html.twig', [
            'current_page' => 'student_pilot',
            'students'     => $students,
            'pageCourante' => $pageCourante,
            'totalPages'   => $totalPages,
        ]);
    }
}