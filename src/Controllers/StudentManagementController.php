<?php

namespace App\Controllers;

use App\Models\StudentManagementModel;
use Twig\Environment;

class StudentManagementController
{
    private Environment $twig;
    private StudentManagementModel $studentModel;

    // On retire PDO ici, on utilise le nouveau format plus propre !
    public function __construct(Environment $twig)
    {
        $this->twig         = $twig;
        $this->studentModel = new StudentManagementModel();
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
                    'id_center'    => !empty($_POST['id_center']) ? (int)$_POST['id_center'] : null,
                    'id_promotion' => !empty($_POST['id_promotion']) ? (int)$_POST['id_promotion'] : null,
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
            
            // On sépare bien l'ID (Argument 1) et les données (Argument 2)
            $profileId = (int) $_POST['id_profile'];
            
            $success = $this->studentModel->updateStudent($profileId, [
                'nom'          => trim($_POST['nom'] ?? ''),
                'prenom'       => trim($_POST['prenom'] ?? ''),
                'email'        => trim($_POST['email'] ?? ''),
                'id_center'    => !empty($_POST['id_center']) ? (int) $_POST['id_center'] : null,
                'id_promotion' => !empty($_POST['id_promotion']) ? (int) $_POST['id_promotion'] : null,
            ]);

            if ($success) {
                $message = "Les informations de l'étudiant ont été mises à jour.";
            } else {
                $error = "Erreur lors de la mise à jour.";
            }
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

        // ===== RÉCUPÉRATION POUR L'AFFICHAGE =====
        $search       = $_GET['search'] ?? null;
        $allStudents  = $this->studentModel->getAllStudents($search);
        
        foreach ($allStudents as &$student) {
            $candidatures = $this->studentModel->getCandidaturesByProfile($student['ID_profile']);
            $student['candidatures_detail'] = json_encode($candidatures);
        }
        unset($student); 

        // On récupère les listes pour les select des modales
        $promotions   = $this->studentModel->getPromotions();
        $centers      = $this->studentModel->getCenters(); 
        
        $totalPages   = $this->studentModel->totalPages($allStudents);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $students     = $this->studentModel->getPage($allStudents, $pageCourante);
        $pages        = $this->studentModel->getPageNumbers($pageCourante, $totalPages ?: 1);

        echo $this->twig->render('student_management.html.twig', [
            'current_page' => 'student_management',
            'students'     => $students,
            'promotions'   => $promotions,
            'centers'      => $centers, // On n'oublie pas d'envoyer les centres à Twig !
            'search'       => $search,
            'message'      => $message,
            'error'        => $error,
            'pageCourante' => $pageCourante,
            'totalPages'   => $totalPages,
            'pages'        => $pages,
        ]);
    }
}