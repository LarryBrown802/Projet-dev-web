<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\OfferController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

$page = $_GET['page'] ?? 'accueil';

switch ($page) {
    case 'accueil':
        $controller = new HomeController($twig);
        $controller->index();
        break;

    case 'offres':
        $controller = new OfferController($twig);
        $controller->index();
        break;

    default:
        http_response_code(404);
        echo 'Page non trouvée';
        break;
}