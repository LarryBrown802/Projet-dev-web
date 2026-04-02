<?php

namespace App\Models;

use PDO;
use Exception;

class StudentManagementModel
{
    private int $parPage = 10;

    /**
     * Récupère toutes les candidatures d'un étudiant spécifique
     */
    /**
     * Récupère toutes les candidatures d'un étudiant spécifique
     */
    public function getCandidaturesByProfile(int $profileId): array
    {
        $db = Database::connect();
        
        $sql = "
            SELECT a.*, o.title as offer_title, c.name as company_name 
            FROM Apply a
            INNER JOIN Offer o ON a.ID_offer = o.ID_offer
            INNER JOIN Company c ON o.ID_company = c.ID
            WHERE a.ID_profile = :id
            ORDER BY o.title ASC /* <-- C'est ici qu'on a retiré le faux ID_apply ! */
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $profileId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les étudiants avec leurs infos, leur promo et le nombre de candidatures
     */
    /**
     * Récupère tous les étudiants avec leurs infos, leur promo et le nombre de candidatures
     */
    public function getAllStudents(?string $search = null): array
    {
        $db = Database::connect();
        
        $sql = "
            SELECT 
                p.ID_profile, 
                p.name AS nom, 
                p.surname AS prenom, 
                u.email, 
                p.ID_center,
                p.ID_promotion,
                pr.name AS promotion,
                COUNT(a.ID_offer) AS candidatures,  /* <-- C'est ici qu'on a corrigé le tir ! */
                'wait' AS statut
            FROM Profile p
            INNER JOIN User u ON p.ID_user = u.ID_user
            INNER JOIN Role r ON u.ID_role = r.ID_role
            LEFT JOIN Promotion pr ON p.ID_promotion = pr.ID_promotion
            LEFT JOIN Apply a ON p.ID_profile = a.ID_profile
            WHERE r.name_role = 'etudiant'
        ";
        
        $params = [];

        // Si on fait une recherche dans la barre en haut
        if (!empty($search)) {
            $sql .= " AND (p.name LIKE :search OR p.surname LIKE :search OR u.email LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }

        $sql .= " GROUP BY p.ID_profile ORDER BY p.name ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $pdo = Database::connect();

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

    // ===== FONCTIONS CRUD AJOUTÉES POUR TON DASHBOARD =====

    public function updateStudent(int $profileId, array $data): bool
    {
        $pdo = Database::connect();
        try {
            $pdo->beginTransaction();

            $stmtP = $pdo->prepare("UPDATE Profile SET name = :nom, surname = :prenom, ID_center = :center, ID_promotion = :promo WHERE ID_profile = :id");
            $stmtP->execute([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'center' => !empty($data['id_center']) ? $data['id_center'] : null,
                'promo' => !empty($data['id_promotion']) ? $data['id_promotion'] : null,
                'id' => $profileId
            ]);

            $stmtId = $pdo->prepare("SELECT ID_user FROM Profile WHERE ID_profile = :id");
            $stmtId->execute(['id' => $profileId]);
            $userId = $stmtId->fetchColumn();

            if ($userId) {
                $stmtU = $pdo->prepare("UPDATE User SET email = :email WHERE ID_user = :uid");
                $stmtU->execute(['email' => $data['email'], 'uid' => $userId]);
            }

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function deleteStudent(int $profileId): bool
    {
        $pdo = Database::connect();
        try {
            $pdo->beginTransaction();
            
            $stmtId = $pdo->prepare("SELECT ID_user FROM Profile WHERE ID_profile = :id");
            $stmtId->execute(['id' => $profileId]);
            $userId = $stmtId->fetchColumn();

            // Supprime d'abord le profil
            $pdo->prepare("DELETE FROM Profile WHERE ID_profile = :id")->execute(['id' => $profileId]);
            // Puis le user
            if ($userId) {
                $pdo->prepare("DELETE FROM User WHERE ID_user = :uid")->execute(['uid' => $userId]);
            }

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    // ===== GESTION DE LA PAGINATION =====

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