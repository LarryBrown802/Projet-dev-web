<?php

namespace App\Models;

class ProfileModel
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function create(string $name, string $surname, int $id_user): bool
    {
        $stmt = $this->db->prepare('
            INSERT INTO Profile (name, surname, ID_user)
            VALUES (:name, :surname, :id_user)
        ');
        return $stmt->execute([
            ':name'    => $name,
            ':surname' => $surname,
            ':id_user' => $id_user,
        ]);
    }

    public function update(int $id_user, string $name, string $surname): bool
    {
        $stmt = $this->db->prepare('
            UPDATE Profile SET name = :name, surname = :surname WHERE ID_user = :id_user
        ');
        return $stmt->execute([':name' => $name, ':surname' => $surname, ':id_user' => $id_user]);
    }

    public function findByUser(int $id_user): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM Profile WHERE ID_user = :id_user');
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function delete(int $id_user): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Profile WHERE ID_user = :id_user');
        return $stmt->execute([':id_user' => $id_user]);
    }

    public function setPromotion(int $id_user, int $id_promotion): bool
    {
        $stmt = $this->db->prepare('
            UPDATE Profile SET ID_promotion = :id_promotion WHERE ID_user = :id_user
        ');
        return $stmt->execute([
            ':id_promotion' => $id_promotion,
            ':id_user'      => $id_user,
        ]);
    }

    public function getByPromotion(int $id_promotion): array
    {
        $stmt = $this->db->prepare('
            SELECT p.*, u.email
            FROM Profile p
            JOIN User u ON p.ID_user = u.ID_user
            WHERE p.ID_promotion = :id_promotion
        ');
        $stmt->execute([':id_promotion' => $id_promotion]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProfileWithDetails(int $id_user): ?array
    {
        $stmt = $this->db->prepare('
            SELECT p.ID_profile, p.name AS nom, p.surname AS prenom,
                u.email, p.status, pr.name AS promotion
            FROM Profile p
            JOIN User u ON p.ID_user = u.ID_user
            LEFT JOIN Promotion pr ON p.ID_promotion = pr.ID_promotion
            WHERE p.ID_user = :id_user
            LIMIT 1
        ');
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function getApplications(int $id_profile): array
    {
        $stmt = $this->db->prepare('
            SELECT o.title, o.duration, o.remuneration, o.type, o.level,
                c.name AS entreprise, l.city AS lieu, a.cv
            FROM Apply a
            JOIN Offer o ON a.ID_offer = o.ID_offer
            LEFT JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            WHERE a.ID_profile = :id_profile
        ');
        $stmt->execute([':id_profile' => $id_profile]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getWishlistCount(int $id_profile): int
    {
        $stmt = $this->db->prepare('
            SELECT COUNT(*) FROM Save_wishlist WHERE ID_profile = :id_profile
        ');
        $stmt->execute([':id_profile' => $id_profile]);
        return (int) $stmt->fetchColumn();
}
}