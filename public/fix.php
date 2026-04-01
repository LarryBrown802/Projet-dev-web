<?php
// public/fix.php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Models\Database;

try {
    $db = Database::connect();

    // La liste de tes comptes par défaut avec leurs mots de passe en clair
    $comptes = [
        'admin1@test.com' => 'admin123',
        'pilote1@test.com' => 'pilote123',
        'etudiant1@test.com' => 'etudiant123'
    ];

    foreach ($comptes as $email => $mdpClair) {
        // On génère le hash sécurisé !
        $hash = password_hash($mdpClair, PASSWORD_BCRYPT);
        
        // On met à jour la base de données
        $stmt = $db->prepare("UPDATE User SET password = :hash WHERE email = :email");
        $stmt->execute(['hash' => $hash, 'email' => $email]);
        
        echo "✅ Mot de passe sécurisé pour : $email <br>";
    }

    echo "<h3>C'est terminé ! Tu peux retourner sur la page de connexion.</h3>";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}