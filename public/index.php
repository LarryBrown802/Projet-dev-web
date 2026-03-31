<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ConnexionController;
use App\Controllers\HomeController;
use App\Controllers\OfferController;
use App\Controllers\CompanyController;
use App\Controllers\WishlistController;
use App\Controllers\DashboardPilotController;
use App\Controllers\OfferManagementController;
use App\Controllers\CompanyManagementController;
use App\Controllers\StudentManagementController;
use App\Controllers\DashboardAdminController;
use App\Controllers\PilotAdminController;
use App\Models\Database;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig   = new Environment($loader);

$bdd = Database::connect();

// Rend la session disponible dans tous les templates Twig
$twig->addGlobal('session', $_SESSION);

// Fonction de protection des routes par rôle
function requireRole(string ...$roles): void
{
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
        http_response_code(403);
        echo 'Accès interdit';
        exit;
    }
}

$page = $_GET['page'] ?? 'accueil';

switch ($page) {

    // ===== ACCESSIBLE À TOUS =====
    case 'accueil':
        $controller = new HomeController($twig, $bdd);
        $controller->index();
        break;

    case 'connexion':
        $controller = new ConnexionController($twig, $bdd);
        $controller->index();
        break;

    case 'offers':
        $controller = new OfferController($twig, $bdd);
        $controller->index();
        break;
        
    case 'apply':
        requireRole('etudiant'); 
        $controller = new \App\Controllers\ApplyController($twig, $bdd);
        $controller->index();
        break;

    case 'company':
        $controller = new CompanyController($twig, $bdd);
        $controller->index();
        break;

    case 'company_detail':
        $controller = new CompanyController($twig, $bdd);
        $controller->detail();
        break;

     // ===== ETUDIANT SEULEMENT =====
    case 'wishlist':
        requireRole('etudiant');
        $controller = new WishlistController($twig, $bdd);
        $controller->index();
        break;

    case 'toggle_wishlist':
        requireRole('etudiant');
        $controller = new \App\Controllers\WishlistController($twig, $bdd);
        $controller->toggleAjax();
        break;

    // ===== PILOTE SEULEMENT =====
    case 'dashboard_pilot':
        requireRole('pilote');
        $controller = new DashboardPilotController($twig, $bdd);
        $controller->index();
        break;

    case 'student_management':
        requireRole('administrateur', 'pilote'); // Admin peut aussi voir tous les étudiants
        $controller = new StudentManagementController($twig, $bdd);
        $controller->index();
        break;

    // ===== ADMIN SEULEMENT =====
    case 'dashboard_admin':
        requireRole('administrateur');
        $controller = new DashboardAdminController($twig, $bdd);
        $controller->index();
        break;

    case 'pilot_admin':
        requireRole('administrateur');
        $controller = new PilotAdminController($twig, $bdd);
        $controller->index();
        break;

    /*case 'student_admin':
        requireRole('administrateur');
        $controller = new StudentAdminController($twig, $bdd);
        $controller->index();
        break;*/

    case 'mentions-legales':
        echo $twig->render('mentions-legales.html.twig');
        break;

    case 'conditions-utilisation':
        echo $twig->render('conditions-utilisation.html.twig');
    // ===== PILOTE & ADMIN =====
    case 'offer_management':
        requireRole('administrateur', 'pilote');
        $controller = new OfferManagementController($twig, $bdd);
        $controller->index();
        break;

    case 'company_management':
        requireRole('administrateur','pilote');
        $controller = new CompanyManagementController($twig, $bdd);
        $controller->index();
        break;

    // ===== DECONNEXION =====
    case 'logout':
        session_destroy();
        header('Location: /index.php?page=accueil');
        exit;

    default:
        http_response_code(404);
        echo 'Page non trouvée';
        break;
}