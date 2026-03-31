<?php

namespace App\Models;

use PDO;
use Exception;

class StudentManagementModel extends StudentModel
{
    protected int $parPage = 10;
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getAllStudents(): array
    {
        $sql = "
            SELECT p.ID_profile, p.ID_user, p.name as nom, p.surname as prenom, u.email,
                   c.name as centre, pr.name as promotion, pr.ID_promotion as promotion_id,
                   (SELECT COUNT(*) FROM Apply a WHERE a.ID_profile = p.ID_profile) as candidatures,
                   '[]' as candidatures_detail
            FROM Profile p
            JOIN User u ON p.ID_user = u.ID_user
            JOIN Role r ON u.ID_role = r.ID_role
            LEFT JOIN Center c ON p.ID_center = c.ID_center
            LEFT JOIN Promotion pr ON p.ID_promotion = pr.ID_promotion
            WHERE r.name_role = 'etudiant'
            ORDER BY p.name ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function getCenters(): array
    {
        return $this->db->query("SELECT ID_center, name FROM Center ORDER BY name")->fetchAll();
    }

    public function getPromotions(): array
    {
        return $this->db->query("SELECT ID_promotion, name FROM Promotion ORDER BY name")->fetchAll();
    }

    public function createStudent(array $data): bool
    {
        try {
            $this->db->beginTransaction();

            $stmtRole = $this->db->query("SELECT ID_role FROM Role WHERE name_role = 'etudiant' LIMIT 1");
            $roleId = $stmtRole->fetchColumn();

            if (!$roleId) {
                throw new Exception("Le rôle 'etudiant' n'existe pas.");
            }

            $stmtUser = $this->db->prepare("INSERT INTO User (email, password, ID_role) VALUES (:email, :password, :role)");
            $stmtUser->execute([
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $roleId
            ]);

            $userId = (int) $this->db->lastInsertId();

            $stmtProfile = $this->db->prepare("INSERT INTO Profile (name, surname, ID_user, ID_center, ID_promotion) 
                                          VALUES (:name, :surname, :user_id, :center, :promo)");
            $stmtProfile->execute([
                'name' => $data['nom'],
                'surname' => $data['prenom'],
                'user_id' => $userId,
                'center' => !empty($data['id_center']) ? $data['id_center'] : null,
                'promo' => !empty($data['id_promotion']) ? $data['id_promotion'] : null
            ]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
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