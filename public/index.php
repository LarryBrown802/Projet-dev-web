<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ConnexionController;
use App\Controllers\HomeController;
use App\Controllers\OfferController;
use App\Controllers\CompanyController;
use App\Controllers\WishlistController;
use App\Controllers\DashboardPilotController;
use App\Controllers\OfferPilotController;
use App\Controllers\CompanyPilotController;
use App\Controllers\StudentPilotController;
use App\Controllers\DashboardAdminController;
use App\Controllers\OfferAdminController;
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

    case 'company':
        $controller = new CompanyController($twig);
        $controller->index();
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

    case 'offer_pilot':
        requireRole('pilote');
        $controller = new OfferPilotController($twig);
        $controller->index();
        break;

    case 'company_pilot':
        requireRole('pilote');
        $controller = new CompanyPilotController($twig);
        $controller->index();
        break;

    case 'student_pilot':
        requireRole('pilote');
        $controller = new StudentPilotController($twig);
        $controller->index();
        break;

    // ===== ADMIN SEULEMENT =====
    case 'dashboard_admin':
        requireRole('admin');
        $controller = new DashboardAdminController($twig);
        $controller->index();
        break;

    case 'offer_admin':
        requireRole('admin');
        $controller = new OfferAdminController($twig);
        $controller->index();
        break;
        
    // ===== ADMIN ET PILOTES =====

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