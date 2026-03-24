<?php
namespace App\Models;

use App\Database;
use PDO;

class ConnexionModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getUserByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare("
            SELECT u.*, r.nom_role, p.nom, p.prenom, p.ID_profile
            FROM Utilisateur u
            JOIN Role r ON u.ID_role = r.ID_role
            LEFT JOIN Profile p ON p.ID_utilisateur = u.ID_utilisateur
            WHERE u.email = :email
        ");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function verifyPassword(string $input, string $hash): bool {
        return password_verify($input, $hash);
    }
}