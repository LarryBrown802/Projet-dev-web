<?php

namespace App\Models;

class ApplyModel
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getOfferByName(string $title): ?int
    {
        $stmt = $this->db->prepare('SELECT ID_offer FROM Offer WHERE title = :title LIMIT 1');
        $stmt->execute([':title' => $title]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? (int) $result['ID_offer'] : null;
    }

    public function getOfferById(int $id): ?array
    {
        $stmt = $this->db->prepare('
            SELECT o.*, c.name AS entreprise
            FROM Offer o
            LEFT JOIN Company c ON o.ID_company = c.ID
            WHERE o.ID_offer = :id LIMIT 1
        ');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getProfileByUserId(int $userId): ?int
    {
        $stmt = $this->db->prepare('SELECT ID_profile FROM Profile WHERE ID_user = :user_id LIMIT 1');
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? (int) $result['ID_profile'] : null;
    }

    public function createProfile(int $userId, string $name, string $surname): int
    {
        $stmt = $this->db->prepare('INSERT INTO Profile (name, surname, ID_user) VALUES (:name, :surname, :user_id)');
        $stmt->execute([
            ':name'    => $name,
            ':surname' => $surname,
            ':user_id' => $userId,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function saveApplication(int $offerId, int $profileId, string $cvPath, string $motivation): bool
    {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO Apply (ID_offer, ID_profile, cv, motivation_letter)
                VALUES (:offer_id, :profile_id, :cv, :motivation)
            ');
            return $stmt->execute([
                ':offer_id'   => $offerId,
                ':profile_id' => $profileId,
                ':cv'         => $cvPath,
                ':motivation' => $motivation,
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }
}