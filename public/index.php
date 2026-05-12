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
use App\Controllers\ApplyController;
use App\Controllers\ProfileController;
use App\Models\Database;
use App\Models\UserModel;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// _____ CONFIGURATION TWIG ET BDD _____
$loader = new FilesystemLoader(__DIR__ . '/../templates'); // CHARGE LE REPERTOIRE TEMPLATES
$twig   = new Environment($loader);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$bdd = Database::connect();

// _____ GESTION DES COOKIES "REMEMBER ME" (Code du collègue) _____
function autoLoginFromRememberCookie(\PDO $bdd): void {
    if (isset($_SESSION['user_id']) || empty($_COOKIE['remember_token'])) {
        return;
    }

    $userModel = new UserModel($bdd);
    $user = $userModel->findByRememberToken($_COOKIE['remember_token']);

    if (!$user) {
        setcookie('remember_token', '', ['expires' => time() - 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
        return;
    }

    $_SESSION['user_id'] = $user['ID_user'];
    $_SESSION['email']   = $user['email'];
    $_SESSION['role']    = $user['name_role'];
}

autoLoginFromRememberCookie($bdd);
$twig->addGlobal('session', $_SESSION);

// _____ LE NOUVEAU ROUTEUR MVC _____
$page = $_GET['page'] ?? 'accueil';

// 1. Pages statiques et Déconnexion
if ($page === 'mentions-legales') { echo $twig->render('pages/mentions-legales.html.twig'); exit; }
if ($page === 'conditions-utilisation') { echo $twig->render('pages/conditions-utilisation.html.twig'); exit; }

if ($page === 'logout') {
    if (isset($_SESSION['user_id'])) {
        $userModel = new UserModel($bdd);
        $userModel->clearRememberToken($_SESSION['user_id']);
    }
    setcookie('remember_token', '', ['expires' => time() - 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
    session_destroy();
    header('Location: /index.php?page=accueil');
    exit;
}

// 2. Tableau de routage : 'url' => [Contrôleur, 'méthode', ['rôles_autorisés']]
$routes = [
    // --- Public ---
    'accueil'            => [HomeController::class, 'index', []],
    'connexion'          => [ConnexionController::class, 'index', []],
    'offers'             => [OfferController::class, 'index', []],
    'company'            => [CompanyController::class, 'index', []],
    'company_detail'     => [CompanyController::class, 'detail', []], // Appel de detail()
    // --- Étudiants ---
    'wishlist'           => [WishlistController::class, 'index', ['etudiant']],
    'toggle_wishlist'    => [WishlistController::class, 'toggleAjax', ['etudiant']], // Appel AJAX
    'apply'              => [ApplyController::class, 'index', ['etudiant']],
    'profile'            => [ProfileController::class, 'index', ['etudiant']],
    // --- Pilotes ---
    'dashboard_pilot'    => [DashboardPilotController::class, 'index', ['pilote']],
    // --- Admins ---
    'dashboard_admin'    => [DashboardAdminController::class, 'index', ['administrateur']],
    'pilot_admin'        => [PilotAdminController::class, 'index', ['administrateur']],
    // --- Mixte (Admin & Pilote) ---
    'student_management' => [StudentManagementController::class, 'index', ['administrateur', 'pilote']],
    'offer_management'   => [OfferManagementController::class, 'index', ['administrateur', 'pilote']],
    'company_management' => [CompanyManagementController::class, 'index', ['administrateur', 'pilote']],
];

// 3. Exécution de la route
if (array_key_exists($page, $routes)) {
    $controllerClass = $routes[$page][0];
    $methodName      = $routes[$page][1];
    $requiredRoles   = $routes[$page][2];

    // Vérification de la sécurité (rôles)
    if (!empty($requiredRoles)) {
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $requiredRoles)) {
            http_response_code(403);
            echo $twig->render('error/403.html.twig');
            exit;
        }
    }

    // Lancement du contrôleur
    $controller = new $controllerClass($twig, $bdd);
    $controller->$methodName();
} else {
    // Page 404
    http_response_code(404);
    echo $twig->render('error/404.html.twig');
}