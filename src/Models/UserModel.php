<?php

namespace App\Models;

class UserModel
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    // ===== READ =====

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('
            SELECT u.*, r.name_role
            FROM User u
            JOIN Role r ON u.ID_role = r.ID_role
            WHERE u.ID_user = :id
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare('
            SELECT u.*, r.name_role
            FROM User u
            JOIN Role r ON u.ID_role = r.ID_role
            WHERE u.email = :email
        ');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('
            SELECT u.*, r.name_role
            FROM User u
            JOIN Role r ON u.ID_role = r.ID_role
            ORDER BY u.ID_user ASC
        ');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // ===== CREATE =====

    public function create(string $email, string $password, int $id_role): bool
    {
        $stmt = $this->db->prepare('
            INSERT INTO User (email, password, ID_role)
            VALUES (:email, :password, :id_role)
        ');
        return $stmt->execute([
            ':email'   => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':id_role' => $id_role,
        ]);
    }

    // ===== UPDATE =====

    public function updateEmail(int $id, string $email): bool
    {
        $stmt = $this->db->prepare('
            UPDATE User SET email = :email WHERE ID_user = :id
        ');
        return $stmt->execute([':email' => $email, ':id' => $id]);
    }

    public function updatePassword(int $id, string $password): bool
    {
        $stmt = $this->db->prepare('
            UPDATE User SET password = :password WHERE ID_user = :id
        ');
        return $stmt->execute([
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':id' => $id,
        ]);
    }

    // ===== DELETE =====

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM User WHERE ID_user = :id');
        return $stmt->execute([':id' => $id]);
    }
}