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
        $pdo = Database::getConnection();
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
        $pdo = Database::getConnection();
        
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