<?php
namespace App\Models;

use App\Database;
use PDO;

class CompanyModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAllCompanies(): array {
        $stmt = $this->pdo->query("SELECT * FROM Entreprise");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCompanyById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM Entreprise WHERE ID_entreprise = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}