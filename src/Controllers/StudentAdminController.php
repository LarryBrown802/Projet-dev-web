<?php

namespace App\Controllers;

use Twig\Environment;

class StudentAdminController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('student_admin.html.twig', [
            'current_page' => 'student',
        ]);
        
    }
}