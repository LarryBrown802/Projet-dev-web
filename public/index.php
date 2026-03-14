<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ConnexionController;
use App\Controllers\HomeController;
use App\Controllers\OfferController;
use App\Controllers\CompanyController;
use App\Controllers\WishlistController;
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