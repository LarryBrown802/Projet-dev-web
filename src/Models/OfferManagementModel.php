<?php

namespace App\Models;

use PDO;

class OfferManagementModel
{
    private int $parPage = 6;

    /**
     * Récupère TOUTES les offres (Pour l'Admin)
     */
    public function getAllOffers(): array
    {
        $pdo = Database::connect();
        $sql = "
            SELECT o.*, c.name as entreprise, l.city as lieu
            FROM Offer o
            LEFT JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            ORDER BY o.publication_date DESC
        ";
        return $pdo->query($sql)->fetchAll();
    }

    /**
     * Récupère UNIQUEMENT les offres des entreprises gérées par ce pilote
     */
    public function getOffersByPilot(int $pilotId): array
    {
        $pdo = Database::connect();
        
        // On cherche les offres (o) dont l'entreprise (c) appartient au pilote (ID_user)
        $sql = "
            SELECT o.*, c.name as entreprise, l.city as lieu
            FROM Offer o
            JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            WHERE c.ID_user = :pilot_id
            ORDER BY o.publication_date DESC
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['pilot_id' => $pilotId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère la liste des villes pour le formulaire
     */
    public function getLocations(): array
    {
        $pdo = Database::connect();
        return $pdo->query("SELECT ID_location, city FROM Location ORDER BY city")->fetchAll();
    }

    /**
     * Récupère TOUTES les entreprises (Pour l'Admin)
     */
    public function getAllCompanies(): array
    {
        $pdo = Database::connect();
        return $pdo->query("SELECT ID, name FROM Company ORDER BY name")->fetchAll();
    }

    /**
     * Récupère UNIQUEMENT les entreprises du Pilote connecté
     */
    public function getCompaniesByPilot(int $pilotId): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT ID, name FROM Company WHERE ID_user = :pid ORDER BY name");
        $stmt->execute(['pid' => $pilotId]);
        return $stmt->fetchAll();
    }

    /**
     * Insère une nouvelle offre dans la base de données
     */
    public function createOffer(array $data): bool
    {
        $pdo = Database::connect();
        // CURDATE() met automatiquement la date du jour MySQL
        $sql = "INSERT INTO Offer (title, description, duration, remuneration, type, level, domain, publication_date, ID_location, ID_company) 
                VALUES (:title, :description, :duration, :remuneration, :type, :level, :domain, CURDATE(), :id_location, :id_company)";
        
        try {
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'title' => $data['title'],
                'description' => $data['description'],
                'duration' => $data['duration'],
                'remuneration' => (float)$data['remuneration'],
                'type' => $data['type'],
                'level' => $data['level'],
                'domain' => $data['domain'],
                'id_location' => (int)$data['location'],
                'id_company' => (int)$data['company']
            ]);
        } catch (\PDOException $e) {
            // Au lieu de retourner "false" en silence, on bloque l'écran pour lire l'erreur exacte de MySQL !
            die("🚨 ERREUR SQL LORS DE LA CRÉATION : " . $e->getMessage());
        }
    }

    /**
     * Utilitaires de pagination (Identiques à ceux d'OfferModel)
     */
    public function getPage(array $offers, int $page): array
    {
        $offset = ($page - 1) * $this->parPage;
        return array_slice($offers, $offset, $this->parPage);
    }

    public function totalPages(array $offers): int
    {
        return (int) ceil(count($offers) / $this->parPage);
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