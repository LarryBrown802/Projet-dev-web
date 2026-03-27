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
use App\Controllers\OfferAdminController;
use App\Controllers\PilotAdminController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig   = new Environment($loader);

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
        $controller = new HomeController($twig);
        $controller->index();
        break;

    case 'connexion':
        $controller = new ConnexionController($twig);
        $controller->index();
        break;

    case 'offers':
        $controller = new OfferController($twig);
        $controller->index();
        break;
        
    case 'apply':
        requireRole('etudiant'); // We will uncomment this when login is fully ready!
        $controller = new \App\Controllers\ApplyController($twig);
        $controller->index();
        break;

    case 'company':
        $controller = new CompanyController($twig);
        $controller->index();
        break;

    case 'company_detail':
        $controller = new CompanyController($twig);
        $controller->detail();
        break;

     // ===== ETUDIANT SEULEMENT =====
    case 'wishlist':
        requireRole('etudiant');
        $controller = new WishlistController($twig);
        $controller->index();
        break;

    // ===== PILOTE SEULEMENT =====
    case 'dashboard_pilot':
        requireRole('pilote');
        $controller = new DashboardPilotController($twig);
        $controller->index();
        break;

    case 'offer_management':
        requireRole('admin', 'pilote');
        $controller = new OfferManagementController($twig);
        $controller->index();
        break;

    case 'company_management':
        requireRole('admin', 'pilote');
        $controller = new CompanyManagementController($twig);
        $controller->index();
        break;

    case 'student_management':
        requireRole('admin', 'pilote'); // Admin peut aussi voir tous les étudiants
        $controller = new StudentManagementController($twig);
        $controller->index();
        break;

    // ===== ADMIN SEULEMENT =====
    case 'dashboard_admin':
        requireRole('admin');
        $controller = new DashboardAdminController($twig);
        $controller->index();
        break;

    /*case 'offer_admin':
        requireRole('admin');
        $controller = new OfferAdminController($twig);
        $controller->index();
        break;*/

    /*case 'company_admin':
        requireRole('admin');
        $controller = new CompanyAdminController($twig);
        $controller->index();
        break;*/
        
    case 'pilot_admin':
        requireRole('admin');
        $controller = new PilotAdminController($twig);
        $controller->index();
        break;

    /*case 'student_admin':
        requireRole('admin');
        $controller = new StudentAdminController($twig);
        $controller->index();
        break;*/

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