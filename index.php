<?php
/**
 * LE ROUTEUR (Le point d'entrée unique du site LEMS)
 */

// 1. Démarrer la session (indispensable pour les connexions plus tard)
session_start();

// 2. Charger les dépendances (AltoRouter, Twig, etc.)
require "vendor/autoload.php";

// 3. Initialisation de Twig
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true // Permet d'afficher les erreurs Twig en phase de développement
]);

// ASTUCE DE CHEF : On donne accès à la variable $_SESSION à tous les fichiers Twig.
// C'est grâce à ça que notre base.html.twig saura s'il faut afficher le menu Admin, Pilote ou Étudiant !
$twig->addGlobal('session', $_SESSION);

// 4. Initialisation du Routeur (AltoRouter)
$router = new AltoRouter();

/* ATTENTION : Si ton projet est dans un sous-dossier de WAMP/XAMPP (ex: localhost/LEMS)
 * Tu DOIS décommenter la ligne ci-dessous et mettre le nom de ton dossier !
 */
// $router->setBasePath('/LEMS'); 

// =========================================================================
// 5. DÉFINITION DES ROUTES LEMS
// (Méthode, URL tapée, Contrôleur#Méthode, Nom de la route)
// =========================================================================

// Pages publiques
$router->map('GET', '/', 'MainController#accueil', 'accueil');
$router->map('GET', '/offres', 'OffreController#listePublic', 'offres_public');
$router->map('GET', '/entreprises', 'EntrepriseController#listePublic', 'entreprises_public');
$router->map('GET', '/connexion', 'AuthController#connexion', 'connexion');

// Pages Étudiant (Bientôt)
$router->map('GET', '/wishlist', 'EtudiantController#wishlist', 'wishlist');

// Pages Administrateur (Bientôt)
$router->map('GET', '/dashboard-admin', 'AdminController#dashboard', 'dash_admin');

// =========================================================================
// 6. EXÉCUTION DU ROUTEUR (L'Aiguillage)
// =========================================================================
$match = $router->match();

if (is_array($match)) {
    // Si la route existe, on sépare le nom du contrôleur et la méthode (ex: MainController et accueil)
    list($controllerName, $action) = explode('#', $match['target']);
    
    // On définit le chemin complet de la classe (N'oublie pas les namespace dans tes futurs fichiers !)
    $controllerClass = "App\\Controllers\\" . $controllerName;
    
    // On crée le contrôleur EN LUI PASSANT TWIG pour qu'il puisse dessiner les pages
    $controller = new $controllerClass($twig);
    
    // On lance la méthode correspondante
    call_user_func_array([$controller, $action], $match['params']);
} else {
    // Si l'utilisateur tape une URL qui n'existe pas -> Erreur 404
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo $twig->render('error.html.twig', [
        'code' => '404',
        'message' => 'Oups ! La page que vous cherchez est introuvable.'
    ]);
}
?>