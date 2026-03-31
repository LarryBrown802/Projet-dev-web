<?php

namespace App\Models;

use PDO;
use PDOException;

class ApplyModel
{
    // 1. Trouver l'ID de l'offre grâce à son titre
    public function getOfferByName(string $title): ?int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT ID_offer FROM Offer WHERE title = :title LIMIT 1");
        $stmt->execute(['title' => $title]);
        $result = $stmt->fetch();
        return $result ? (int)$result['ID_offer'] : null;
    }

    // 2. Trouver le Profil de l'étudiant
    public function getProfileByUserId(int $userId): ?int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT ID_profile FROM Profile WHERE ID_user = :user_id LIMIT 1");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch();
        return $result ? (int)$result['ID_profile'] : null;
    }

    // 3. Créer un Profil si l'étudiant n'en a pas encore
    public function createProfile(int $userId, string $name, string $surname): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO Profile (name, surname, ID_user) VALUES (:name, :surname, :user_id)");
        $stmt->execute([
            'name' => $name,
            'surname' => $surname,
            'user_id' => $userId
        ]);
        return (int)$pdo->lastInsertId(); // Renvoie l'ID du profil tout juste créé
    }

    // 4. Enregistrer la candidature finale !
    public function saveApplication(int $offerId, int $profileId, string $cvPath, string $motivation): bool
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO Apply (ID_offer, ID_profile, cv, motivation_letter) 
                VALUES (:offer_id, :profile_id, :cv, :motivation)";

        try {
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'offer_id'   => $offerId,
                'profile_id' => $profileId,
                'cv'         => $cvPath,
                'motivation' => $motivation
            ]);
        } catch (PDOException $e) {
            // Si une erreur arrive (ex: il a déjà postulé), on renvoie false
            return false;
        }
    }
}