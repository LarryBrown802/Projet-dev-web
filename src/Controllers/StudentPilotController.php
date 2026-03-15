<?php

namespace App\Controllers;

use Twig\Environment;

class StudentPilotController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('student_pilot.html.twig', [
            'current_page' => 'student_pilot',
        ]);
        
    }
}