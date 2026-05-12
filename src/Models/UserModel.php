<?php

namespace App\Models;

use PDO;

// ❌ L'erreur venait d'ici : il ne faut plus aucun "use PaginationController" ou "extends" !
class UserModel
{
    private PDO $db;
    
    public function __construct(PDO $db)
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
            WHERE u.ID_user  = :id
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->db->prepare('
            SELECT u.*, r.name_role
            FROM User u
            JOIN Role r ON u.ID_role = r.ID_role
            ORDER BY u.ID_user ASC
            LIMIT :limit OFFSET :offset
        ');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(ID_user) as total FROM User');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }

    // ===== CREATE =====
    public function create(string $email, string $password, int $id_role): bool
    {
        $stmt = $this->db->prepare('
            INSERT INTO User (email, password, ID_role)
            VALUES (:email, :password, :id_role)
        ');
        return $stmt->execute([
            ':email'    => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':id_role'  => $id_role,
        ]);
    }

    public function createWithRole(string $email, string $password, string $roleName): int|false
    {
        // 1. Trouver l'ID du rôle
        $stmtRole = $this->db->prepare('SELECT ID_role FROM Role WHERE name_role = :role');
        $stmtRole->execute([':role' => $roleName]);
        $id_role = $stmtRole->fetchColumn();

        if (!$id_role) return false;

        // 2. Créer l'utilisateur
        $stmt = $this->db->prepare('
            INSERT INTO User (email, password, ID_role)
            VALUES (:email, :password, :id_role)
        ');
        $success = $stmt->execute([
            ':email'    => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':id_role'  => $id_role,
        ]);

        return $success ? (int) $this->db->lastInsertId() : false;
    }

    // ===== UPDATE & DELETE =====
    public function updateEmail(int $id_user, string $email): bool
    {
        $stmt = $this->db->prepare('UPDATE User SET email = :email WHERE ID_user = :id');
        return $stmt->execute([':email' => $email, ':id' => $id_user]);
    }

    public function delete(int $id_user): bool
    {
        $stmt = $this->db->prepare('DELETE FROM User WHERE ID_user = :id');
        return $stmt->execute([':id' => $id_user]);
    }

    // ===== REMEMBER ME =====
    public function setRememberToken(int $id_user, string $token): bool
    {
        $stmt = $this->db->prepare('UPDATE User SET remember_token = :token WHERE ID_user = :id');
        return $stmt->execute([':token' => $token, ':id' => $id_user]);
    }

    public function clearRememberToken(int $id_user): bool
    {
        $stmt = $this->db->prepare('UPDATE User SET remember_token = NULL WHERE ID_user = :id');
        return $stmt->execute([':id' => $id_user]);
    }

    // ===== SPÉCIFIQUE ROLES ET PAGINATION =====
    public function getAllByRole(string $role, ?string $search = null, int $limit = 50, int $offset = 0): array
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
                  ORDER BY p.name ASC
                  LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ La fonction de comptage indispensable pour tes tableaux de bord !
    public function countAllByRole(string $role, ?string $search = null): int
    {
        $sql = 'SELECT COUNT(DISTINCT u.ID_user) FROM User u
                JOIN Role r ON u.ID_role = r.ID_role
                LEFT JOIN Profile p ON u.ID_user = p.ID_user
                WHERE r.name_role = :role';
        $params = [':role' => $role];

        if (!empty($search)) {
            $sql .= ' AND (p.name LIKE :search OR p.surname LIKE :search OR u.email LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
}