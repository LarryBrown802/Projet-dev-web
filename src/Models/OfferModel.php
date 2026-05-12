<?php

namespace App\Models;
use App\Controllers\Utils\PaginationController;

class OfferModel extends PaginationController
{
    private \PDO $db;
    protected int $perPage = 6;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function searchOffers(
        ?string $search = null,
        ?string $location = null,
        array $types = [],
        array $levels = [],
        array $categories = [],
        ?string $sort = null
    ): array {
        $sql = '
            SELECT o.*, c.name AS entreprise, c.description AS entrepriseDesc,
                   l.city AS lieu, COUNT(DISTINCT a.ID_profile) AS candidatures
            FROM Offer o
            LEFT JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            LEFT JOIN Apply a ON a.ID_offer = o.ID_offer
            WHERE 1=1
        ';
        $params = [];

        if (!empty($search)) {
            $sql .= ' AND (o.title LIKE :search OR c.name LIKE :search OR o.domain LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }
        if (!empty($location)) {
            $sql .= ' AND l.city = :location';
            $params[':location'] = $location;
        }
        if (!empty($types)) {
            $placeholders = implode(',', array_map(fn($i) => ":type$i", array_keys($types)));
            $sql .= " AND o.type IN ($placeholders)";
            foreach ($types as $i => $type) $params[":type$i"] = $type;
        }
        if (!empty($levels)) {
            $placeholders = implode(',', array_map(fn($i) => ":level$i", array_keys($levels)));
            $sql .= " AND o.level IN ($placeholders)";
            foreach ($levels as $i => $level) $params[":level$i"] = $level;
        }
        if (!empty($categories)) {
            $placeholders = implode(',', array_map(fn($i) => ":cat$i", array_keys($categories)));
            $sql .= " AND o.domain IN ($placeholders)";
            foreach ($categories as $i => $cat) $params[":cat$i"] = $cat;
        }

        $orderBy = match ($sort) {
            'recent' => 'o.publication_date DESC',
            'remu'   => 'o.remuneration DESC',
            'az'     => 'o.title ASC',
            default  => 'o.publication_date DESC',
        };
        $sql .= ' GROUP BY o.ID_offer ORDER BY ' . $orderBy;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllOffers(?string $search = null, ?string $type = null, ?int $limit = null): array
    {
        $sql = '
            SELECT o.*, c.name AS entreprise, l.city AS lieu,
                   COUNT(DISTINCT a.ID_profile) AS candidatures
            FROM Offer o
            LEFT JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            LEFT JOIN Apply a ON a.ID_offer = o.ID_offer
            WHERE 1=1
        ';
        $params = [];

        if (!empty($search)) {
            $sql .= ' AND (o.title LIKE :search OR c.name LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }
        if (!empty($type)) {
            $sql .= ' AND o.type = :type';
            $params[':type'] = $type;
        }

        $sql .= ' GROUP BY o.ID_offer ORDER BY o.publication_date DESC';

        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // ← Nouveau : offres filtrées par pilote
    public function getOffersByPilot(int $pilotId, ?string $search = null, ?string $type = null): array
    {
        $sql = '
            SELECT o.*, c.name AS entreprise, l.city AS lieu,
                   COUNT(DISTINCT a.ID_profile) AS candidatures
            FROM Offer o
            JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            LEFT JOIN Apply a ON a.ID_offer = o.ID_offer
            WHERE c.ID_user = :pilot_id
        ';
        $params = [':pilot_id' => $pilotId];

        if (!empty($search)) {
            $sql .= ' AND (o.title LIKE :search OR c.name LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }
        if (!empty($type)) {
            $sql .= ' AND o.type = :type';
            $params[':type'] = $type;
        }

        $sql .= ' GROUP BY o.ID_offer ORDER BY o.publication_date DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getLatestOffers(int $limit = 4): array
    {
        $stmt = $this->db->prepare('
            SELECT o.*, c.name AS entreprise, c.description AS entrepriseDesc, l.city AS lieu
            FROM Offer o
            LEFT JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            ORDER BY o.publication_date DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(
        string $title, string $description, string $duration,
        float $remuneration, string $type, string $level, string $domain,
        string $publication_date, int $id_company, int $id_location
    ): bool {
        $stmt = $this->db->prepare('
            INSERT INTO Offer (title, description, duration, remuneration, type, level, domain, publication_date, ID_company, ID_location)
            VALUES (:title, :description, :duration, :remuneration, :type, :level, :domain, :publication_date, :id_company, :id_location)
        ');
        return $stmt->execute([
            ':title'            => $title,
            ':description'      => $description,
            ':duration'         => $duration,
            ':remuneration'     => $remuneration,
            ':type'             => $type,
            ':level'            => $level,
            ':domain'           => $domain,
            ':publication_date' => $publication_date,
            ':id_company'       => $id_company,
            ':id_location'      => $id_location,
        ]);
    }

    public function update(
        int $id, string $title, string $description, string $duration,
        float $remuneration, string $type, string $level, string $domain
    ): bool {
        $stmt = $this->db->prepare('
            UPDATE Offer SET title = :title, description = :description, duration = :duration,
            remuneration = :remuneration, type = :type, level = :level, domain = :domain
            WHERE ID_offer = :id
        ');
        return $stmt->execute([
            ':title'        => $title,
            ':description'  => $description,
            ':duration'     => $duration,
            ':remuneration' => $remuneration,
            ':type'         => $type,
            ':level'        => $level,
            ':domain'       => $domain,
            ':id'           => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        try {
            $this->db->beginTransaction();
            $this->db->prepare('DELETE FROM Apply WHERE ID_offer = :id')->execute([':id' => $id]);
            $this->db->prepare('DELETE FROM Save_wishlist WHERE ID_offer = :id')->execute([':id' => $id]);
            $this->db->prepare('DELETE FROM Offer WHERE ID_offer = :id')->execute([':id' => $id]);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function countAllApplications(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM Apply');
        return (int) $stmt->fetchColumn();
    }
}