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

// _____ TABLE DE ROUTAGE _____
// On définit toutes nos routes, le contrôleur associé, la méthode, et les rôles autorisés (vide = public)
$routes = [
    'accueil'                => ['controller' => HomeController::class, 'method' => 'index', 'roles' => []],
    'connexion'              => ['controller' => ConnexionController::class, 'method' => 'index', 'roles' => []],
    'offers'                 => ['controller' => OfferController::class, 'method' => 'index', 'roles' => []],
    'company'                => ['controller' => CompanyController::class, 'method' => 'index', 'roles' => []],
    'company_detail'         => ['controller' => CompanyController::class, 'method' => 'detail', 'roles' => []],
    
    'wishlist'               => ['controller' => WishlistController::class, 'method' => 'index', 'roles' => ['etudiant']],
    'toggle_wishlist'        => ['controller' => WishlistController::class, 'method' => 'toggleAjax', 'roles' => ['etudiant']],
    'apply'                  => ['controller' => ApplyController::class, 'method' => 'index', 'roles' => ['etudiant']],
    'profile'                => ['controller' => ProfileController::class, 'method' => 'index', 'roles' => ['etudiant']],
    
    'dashboard_pilot'        => ['controller' => DashboardPilotController::class, 'method' => 'index', 'roles' => ['pilote']],
    'student_management'     => ['controller' => StudentManagementController::class, 'method' => 'index', 'roles' => ['administrateur', 'pilote']],
    
    'dashboard_admin'        => ['controller' => DashboardAdminController::class, 'method' => 'index', 'roles' => ['administrateur']],
    'pilot_admin'            => ['controller' => PilotAdminController::class, 'method' => 'index', 'roles' => ['administrateur']],
    
    'offer_management'       => ['controller' => OfferManagementController::class, 'method' => 'index', 'roles' => ['administrateur', 'pilote']],
    'company_management'     => ['controller' => CompanyManagementController::class, 'method' => 'index', 'roles' => ['administrateur', 'pilote']],
];

// _____ LOGIQUE DE DISPATCH (L'Aiguilleur) _____

$page = $_GET['page'] ?? 'accueil';

// 1. GESTION DES PAGES SIMPLES ET DECONNEXION (Exceptions)
if ($page === 'logout') {
    session_destroy();
    header('Location: /index.php?page=accueil');
    exit;
}
if ($page === 'mentions-legales' || $page === 'conditions-utilisation') {
    echo $twig->render($page . '.html.twig');
    exit;
}

// 2. VÉRIFICATION DE L'EXISTENCE DE LA ROUTE (Erreur 404)
if (!array_key_exists($page, $routes)) {
    http_response_code(404);
    echo $twig->render('404.html.twig');
    exit;
}

// 3. VÉRIFICATION DES PERMISSIONS (Erreur 403)
$routeConfig = $routes[$page];
$requiredRoles = $routeConfig['roles'];

if (!empty($requiredRoles)) { // Si la route demande un rôle spécifique
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $requiredRoles)) {
        http_response_code(403);
        echo $twig->render('403.html.twig');
        exit;
    }
}

// 4. APPEL DYNAMIQUE DU CONTRÔLEUR
$controllerName = $routeConfig['controller'];
$methodName = $routeConfig['method'];

// On instancie le bon contrôleur en lui passant Twig et la base de données
$controllerInstance = new $controllerName($twig, $bdd);
$controllerInstance->$methodName();