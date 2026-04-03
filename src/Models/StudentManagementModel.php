<?php

namespace App\Models;
use App\Controllers\Utils\PaginationController;
use Exception;

class StudentManagementModel extends PaginationController
{
    private \PDO $db;
    protected int $parPage = 10;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getAllStudents(): array
    {
        $sql = '
            SELECT p.ID_profile, p.name AS nom, p.surname AS prenom, u.email,
                   p.status, p.ID_promotion,
                   pr.name AS promotion,
                   (SELECT COUNT(*) FROM Apply a WHERE a.ID_profile = p.ID_profile) AS candidatures
            FROM Profile p
            JOIN User u ON p.ID_user = u.ID_user
            JOIN Role r ON u.ID_role = r.ID_role
            LEFT JOIN Promotion pr ON p.ID_promotion = pr.ID_promotion
            WHERE r.name_role = "etudiant"
            ORDER BY p.name ASC
        ';
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPromotions(): array
    {
        return $this->db->query('SELECT ID_promotion, name FROM Promotion ORDER BY name')
                        ->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createStudent(array $data): bool
    {
        try {
            $this->db->beginTransaction();

            $stmtRole = $this->db->query("SELECT ID_role FROM Role WHERE name_role = 'etudiant' LIMIT 1");
            $roleId   = $stmtRole->fetchColumn();
            if (!$roleId) throw new Exception("Le rôle 'etudiant' n'existe pas.");

            $stmt = $this->db->prepare('
                INSERT INTO User (email, password, ID_role) VALUES (:email, :password, :role)
            ');
            $stmt->execute([
                ':email'    => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
                ':role'     => $roleId,
            ]);
            $userId = $this->db->lastInsertId();

            $stmt = $this->db->prepare('
                INSERT INTO Profile (name, surname, ID_user, ID_promotion)
                VALUES (:name, :surname, :user_id, :promo)
            ');
            $stmt->execute([
                ':name'    => $data['nom'],
                ':surname' => $data['prenom'],
                ':user_id' => $userId,
                ':promo'   => !empty($data['id_promotion']) ? $data['id_promotion'] : null,
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function updateStudent(array $data): bool
    {
        try {
            $this->db->beginTransaction();

            // Met à jour email et éventuellement le mot de passe
            if (!empty($data['password'])) {
                $stmt = $this->db->prepare('
                    UPDATE User SET email = :email, password = :password
                    WHERE ID_user = (SELECT ID_user FROM Profile WHERE ID_profile = :id_profile)
                ');
                $stmt->execute([
                    ':email'      => $data['email'],
                    ':password'   => password_hash($data['password'], PASSWORD_BCRYPT),
                    ':id_profile' => $data['id_profile'],
                ]);
            } else {
                $stmt = $this->db->prepare('
                    UPDATE User SET email = :email
                    WHERE ID_user = (SELECT ID_user FROM Profile WHERE ID_profile = :id_profile)
                ');
                $stmt->execute([
                    ':email'      => $data['email'],
                    ':id_profile' => $data['id_profile'],
                ]);
            }

            // Met à jour le profil — garde la promotion existante si non fournie
            $stmt = $this->db->prepare('
                UPDATE Profile
                SET name         = :nom,
                    surname      = :prenom,
                    ID_promotion = COALESCE(:id_promotion, ID_promotion),
                    status       = :status
                WHERE ID_profile = :id_profile
            ');
            $stmt->execute([
                ':nom'          => $data['nom'],
                ':prenom'       => $data['prenom'],
                ':id_promotion' => !empty($data['id_promotion']) ? $data['id_promotion'] : null,
                ':status'       => $data['status'] ?? 'wait',
                ':id_profile'   => $data['id_profile'],
            ]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function updateStatus(int $id_profile, string $status): bool
    {
        $stmt = $this->db->prepare('
            UPDATE Profile SET status = :status WHERE ID_profile = :id_profile
        ');
        return $stmt->execute([
            ':status'     => $status,
            ':id_profile' => $id_profile,
        ]);
    }

    public function deleteStudent(int $id_profile): bool
    {
        try {
            $this->db->beginTransaction();

            // Récupère l'ID user depuis le profil
            $stmt = $this->db->prepare('SELECT ID_user FROM Profile WHERE ID_profile = :id');
            $stmt->execute([':id' => $id_profile]);
            $userId = $stmt->fetchColumn();

            if (!$userId) throw new Exception("Profil introuvable.");

            // Supprime le profil (cascade supprime Apply et Save_wishlist)
            $this->db->prepare('DELETE FROM Profile WHERE ID_profile = :id')
                     ->execute([':id' => $id_profile]);

            // Supprime l'utilisateur
            $this->db->prepare('DELETE FROM User WHERE ID_user = :id')
                     ->execute([':id' => $userId]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getCandidaturesByProfile(int $id_profile): array
    {
        $stmt = $this->db->prepare('
            SELECT o.title AS offre, c.name AS entreprise
            FROM Apply a
            JOIN Offer o ON a.ID_offer = o.ID_offer
            LEFT JOIN Company c ON o.ID_company = c.ID
            WHERE a.ID_profile = :id_profile
        ');
        $stmt->execute([':id_profile' => $id_profile]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}