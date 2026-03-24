<?php
namespace App\Models;

use App\Database;
use PDO;

class OfferModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAllOffers(): array {
        $stmt = $this->pdo->query("
            SELECT o.*, l.ville, l.code_postal, e.nom AS entreprise_nom
            FROM Offre o
            JOIN lieu l ON o.ID_lieu = l.ID_lieu
            JOIN Entreprise e ON o.ID_entreprise = e.ID_entreprise
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOfferById(int $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT o.*, l.ville, l.code_postal, l.nom_rue,
                   e.nom AS entreprise_nom, e.description AS entreprise_desc
            FROM Offre o
            JOIN lieu l ON o.ID_lieu = l.ID_lieu
            JOIN Entreprise e ON o.ID_entreprise = e.ID_entreprise
            WHERE o.ID_offre = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}