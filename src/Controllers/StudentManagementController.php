<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Utils\Pagination; // ✅ IMPORTATION DE L'UTILITAIRE
use Twig\Environment;
use PDO;

class StudentManagementController
{
    private Environment $twig;
    private StudentModel $studentModel;

    public function __construct(Environment $twig, PDO $bdd)
    {
        $this->twig         = $twig;
        $this->studentModel = new StudentModel($bdd);
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
                    'password'     => $_POST['password'], // Pas de trim sur le password
                    'id_promotion' => !empty($_POST['id_promotion']) ? (int) $_POST['id_promotion'] : null,
                ]);
                if ($success) {
                    // PRG Pattern : redirection pour éviter le double submit
                    header('Location: /index.php?page=student_management&success=created');
                    exit;
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
                'password'     => trim($_POST['password'] ?? ''), // vide = pas de changement
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
                header('Location: /index.php?page=student_management&success=deleted');
                exit;
            } else {
                $error = "Erreur lors de la suppression.";
            }
        }

        // Message de succès par URL (Pattern Post/Redirect/Get)
        if (isset($_GET['success'])) {
            if ($_GET['success'] === 'created') $message = "L'étudiant a été créé avec succès.";
            if ($_GET['success'] === 'deleted') $message = "L'étudiant a été supprimé.";
        }

        // ===== PAGINATION ET AFFICHAGE =====
        $pageDemandee = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $perPage      = 10; // Pour un dashboard, on en affiche plus

        // 1. Comptage SQL propre
        $totalStudents = $this->studentModel->countAllStudents();

        // 2. Calculs via notre Utilitaire
        $paginationData = Pagination::getPaginationData($totalStudents, $pageDemandee, $perPage);
        $pageCourante   = $paginationData['pageCourante'];
        $offset         = Pagination::getOffset($pageCourante, $perPage);

        // 3. Récupération des étudiants paginés uniquement (10 max)
        $students = $this->studentModel->getAllStudents($perPage, $offset);

        // 4. Boucle optimisée : on ne charge les JSON que pour les 10 étudiants à l'écran !
        foreach ($students as &$student) {
            $candidatures = $this->studentModel->getCandidaturesByProfile($student['ID_profile']);
            $student['candidatures_detail'] = json_encode($candidatures);
        }
        unset($student); // Toujours casser la référence après un foreach par référence

        // 5. Récupération des promotions pour les selects HTML
        $promotions = $this->studentModel->getPromotions();

        // 6. Rendu Twig
        echo $this->twig->render('student_management.html.twig', [
            'current_page' => 'student_management',
            'students'     => $students,
            'promotions'   => $promotions,
            'message'      => $message,
            'error'        => $error,
            
            // Pagination
            'pageCourante' => $pageCourante,
            'totalPages'   => $paginationData['totalPages'],
            'pages'        => $paginationData['pages'],
        ]);
    }
}