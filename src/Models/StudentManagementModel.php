<?php

namespace App\Models;

use PDO;
use Exception;

class StudentManagementModel
{
    private int $parPage = 10;

    public function getAllStudents(): array
    {
        $pdo = Database::connect();
        $sql = "
            SELECT p.ID_profile, p.name as nom, p.surname as prenom, u.email, 
                   c.name as centre, pr.name as promotion,
                   (SELECT COUNT(*) FROM Apply a WHERE a.ID_profile = p.ID_profile) as candidatures
            FROM Profile p
            JOIN User u ON p.ID_user = u.ID_user
            JOIN Role r ON u.ID_role = r.ID_role
            LEFT JOIN Center c ON p.ID_center = c.ID_center
            LEFT JOIN Promotion pr ON p.ID_promotion = pr.ID_promotion
            WHERE r.name_role = 'etudiant'
            ORDER BY p.name ASC
        ";
        return $pdo->query($sql)->fetchAll();
    }

    public function getCenters(): array
    {
        $pdo = Database::connect();
        return $pdo->query("SELECT ID_center, name FROM Center ORDER BY name")->fetchAll();
    }

    public function getPromotions(): array
    {
        $pdo = Database::connect();
        return $pdo->query("SELECT ID_promotion, name FROM Promotion ORDER BY name")->fetchAll();
    }

    public function createStudent(array $data): bool
    {
        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $stmtRole = $pdo->query("SELECT ID_role FROM Role WHERE name_role = 'etudiant' LIMIT 1");
            $roleId = $stmtRole->fetchColumn();
            
            if (!$roleId) throw new Exception("Le rôle 'etudiant' n'existe pas.");

            $stmtUser = $pdo->prepare("INSERT INTO User (email, password, ID_role) VALUES (:email, :password, :role)");
            $stmtUser->execute([
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $roleId
            ]);
            
            $userId = $pdo->lastInsertId();

            $stmtProfile = $pdo->prepare("INSERT INTO Profile (name, surname, ID_user, ID_center, ID_promotion) 
                                          VALUES (:name, :surname, :user_id, :center, :promo)");
            $stmtProfile->execute([
                'name' => $data['nom'],
                'surname' => $data['prenom'],
                'user_id' => $userId,
                'center' => !empty($data['id_center']) ? $data['id_center'] : null,
                'promo' => !empty($data['id_promotion']) ? $data['id_promotion'] : null
            ]);

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    // Pagination
    public function getPage(array $items, int $page): array {
        $offset = ($page - 1) * $this->parPage;
        return array_slice($items, $offset, $this->parPage);
    }
    public function totalPages(array $items): int {
        return (int) ceil(count($items) / $this->parPage);
    }
    public function getPageNumbers(int $currentPage, int $totalPages): array {
        if ($totalPages <= 1) return [1];
        if ($totalPages <= 5) return range(1, $totalPages);
        if ($currentPage <= 3) return [1, 2, 3, 4, '...', $totalPages];
        if ($currentPage >= $totalPages - 2) return [1, '...', $totalPages - 3, $totalPages - 2, $totalPages - 1, $totalPages];
        return [1, '...', $currentPage - 1, $currentPage, $currentPage + 1, '...', $totalPages];
    }
}