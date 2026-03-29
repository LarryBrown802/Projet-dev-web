<?php

namespace App\Models;

use PDO;

class CompanyManagementModel
{
    private int $parPage = 10;

    // L'Admin voit toutes les entreprises
    public function getAllCompanies(): array
    {
        $pdo = Database::getConnection();
        return $pdo->query("SELECT * FROM Company ORDER BY name")->fetchAll();
    }

    // Le Pilote ne voit que SES entreprises
    public function getCompaniesByPilot(int $pilotId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM Company WHERE ID_user = :pid ORDER BY name");
        $stmt->execute(['pid' => $pilotId]);
        return $stmt->fetchAll();
    }

    /**
     * Insère une NOUVELLE entreprise reliée au pilote
     */
    public function createCompany(string $name, string $description, string $email, string $tel, int $pilotId): bool
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO Company (name, description, email, number, ID_user) 
                VALUES (:name, :desc, :email, :tel, :pid)";
        
        try {
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'name' => $name,
                'desc' => $description,
                'email' => $email,
                'tel' => $tel,
                'pid' => $pilotId
            ]);
        } catch (\PDOException $e) {
            // On force la page à afficher l'erreur exacte de MySQL en gros sur fond blanc !
            die("🚨 ERREUR MYSQL : " . $e->getMessage());
        }
    }

    // Pagination (Identique aux autres)
    public function getPage(array $companies, int $page): array
    {
        $offset = ($page - 1) * $this->parPage;
        return array_slice($companies, $offset, $this->parPage);
    }

    public function totalPages(array $companies): int
    {
        return (int) ceil(count($companies) / $this->parPage);
    }

    public function getPageNumbers(int $currentPage, int $totalPages): array
    {
        if ($totalPages <= 1) return [1];
        if ($totalPages <= 5) return range(1, $totalPages);
        if ($currentPage <= 3) return [1, 2, 3, 4, '...', $totalPages];
        if ($currentPage >= $totalPages - 2) return [1, '...', $totalPages - 3, $totalPages - 2, $totalPages - 1, $totalPages];
        return [1, '...', $currentPage - 1, $currentPage, $currentPage + 1, '...', $totalPages];
    }
}