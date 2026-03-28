<?php

namespace App\Models;

class UserModel extends PaginationModel
{
    private \PDO $db;
    protected int $perPage = 5;

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


    public function createWithRole(string $email, string $password, string $role): int|false
    {
        $stmt = $this->db->prepare('
            INSERT INTO User (email, password, ID_role)
            VALUES (:email, :password, (SELECT ID_role FROM Role WHERE name_role = :role))
        ');
        $result = $stmt->execute([
            ':email'    => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':role'     => $role,
        ]);
        return $result ? (int) $this->db->lastInsertId() : false;
    }

    public function getAllByRole(string $role, ?string $search = null): array
    {
        $sql = '
            SELECT u.ID_user, u.email, p.name, p.surname,
                pr.name AS promotion, pr.ID_promotion,
                COUNT(DISTINCT s.ID_profile) AS nb_etudiants
            FROM User u
            LEFT JOIN Profile p ON u.ID_user = p.ID_user
            LEFT JOIN Promotion pr ON p.ID_promotion = pr.ID_promotion
            LEFT JOIN Profile s ON s.ID_promotion = pr.ID_promotion
                AND s.ID_user IN (
                    SELECT ID_user FROM User u2
                    JOIN Role r2 ON u2.ID_role = r2.ID_role
                    WHERE r2.name_role = "etudiant"
                )
            JOIN Role r ON u.ID_role = r.ID_role
            WHERE r.name_role = :role
        ';
        $params = [':role' => $role];

        if (!empty($search)) {
            $sql .= ' AND (p.name LIKE :search OR p.surname LIKE :search OR u.email LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= ' GROUP BY u.ID_user, u.email, p.name, p.surname, pr.name, pr.ID_promotion
                ORDER BY p.name ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}