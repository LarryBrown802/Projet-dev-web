<?php
namespace App\Models;

use App\Database;
use PDO;

class StudentModel extends PaginationModel {
    protected int $parPage = 5;
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAllStudents(): array {
        $stmt = $this->pdo->query("
            SELECT p.ID_profile, p.nom, p.prenom, u.email
            FROM Profile p
            JOIN Utilisateur u ON p.ID_utilisateur = u.ID_utilisateur
            JOIN Role r ON u.ID_role = r.ID_role
            WHERE r.nom_role = 'etudiant'
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentsByPilote(int $piloteId): array {
        $stmt = $this->pdo->prepare("
            SELECT p.ID_profile, p.nom, p.prenom, u.email
            FROM Profile p
            JOIN Utilisateur u ON p.ID_utilisateur = u.ID_utilisateur
            WHERE u.ID_utilisateur = :pilote_id
        ");
        $stmt->execute([':pilote_id' => $piloteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}