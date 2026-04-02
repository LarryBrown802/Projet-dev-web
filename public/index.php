<?php
// _____ CONFIGURATION DES ERREURS _____

ini_set('display_errors', 1);  // ACTIVE L'AFFICHAGE DES ERREURS
ini_set('display_startup_errors', 1);  // ACTIVE LES ERREURS LORS DU DÉMARRAGE
error_reporting(E_ALL);  // RAPPORT COMPLÈT DE TOUTES LES ERREURS

session_start();  // DÉMARRE LA SESSION PHP

require_once __DIR__ . '/../vendor/autoload.php';  // CHARGE AUTOLOAD DE COMPOSER


// _____ UTILISE LES CONTROLLERS ET MODELS _____

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
use App\Controllers\ApplyController;
use App\Controllers\ProfileController;
use App\Models\Database;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// _____ CONFIGURATION_TWIG _____

$loader = new FilesystemLoader(__DIR__ . '/../templates'); // CHARGE LE REPERTOIRE TEMPLATES
$twig   = new Environment($loader);

$dotnev = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // CHARGE FICHIER .ENV
$dotnev->load();

$bdd = Database::connect();  // CONNEXION A LA BASE DE DONNEES

// REND LA SESSION DISPONIBLE DANS TWIG
$twig->addGlobal('session', $_SESSION);

// Fonction de protection des routes par rôle
function requireRole(string ...$roles): void
{
    global $twig; // PERMET L'UTILISATION TWIG DANS LA FONCTION
    
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
        http_response_code(403);  // ERREUR_403 SI ROLE NON AUTORISE
        echo $twig->render('403.html.twig'); // AFFICHE PAGE 403
        exit;
    }
}

$page = $_GET['page'] ?? 'accueil'; // RECUPERE PAGE DE L'URL OU DEFAULT

switch ($page) {

     //_______________________________________
    // __________ ACCESSIBLE À TOUS __________
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
        
    case 'company':
        $controller = new CompanyController($twig, $bdd);
        $controller->index();
        break;

    case 'company_detail':
        $controller = new CompanyController($twig, $bdd);
        $controller->detail();
        break;

      //_______________________________________
     // __________ ETUDIANT SEULEMENT __________
    case 'wishlist':
        requireRole('etudiant');
        $controller = new WishlistController($twig, $bdd);
        $controller->index();
        break;

    case 'toggle_wishlist':
        requireRole('etudiant');
        $controller = new WishlistController($twig, $bdd);
        $controller->toggleAjax();
        break;

    case 'apply':
        requireRole('etudiant'); 
        $controller = new ApplyController($twig, $bdd);
        $controller->index();
        break;

    case 'profile':
        requireRole('etudiant');
        $controller = new ProfileController($twig, $bdd);
        $controller->index();
        break;

     //_______________________________________
    // __________ PILOTE SEULEMENT __________
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

     //_____________________________________
    // __________ ADMIN SEULEMENT __________
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
        break; 
    
     //_____________________________________
    // __________ PILOTE & ADMIN __________
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
     //__________________________________
    // __________ DECONNEXION __________
    case 'logout':
        session_destroy();
        header('Location: /index.php?page=accueil');
        exit;

    default:
        http_response_code(404);  // ERREUR_404 PAGE NON TROUVEE
        echo $twig->render('404.html.twig');
        break;
}
