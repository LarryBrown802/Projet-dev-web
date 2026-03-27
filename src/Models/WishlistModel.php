<?php

namespace App\Models;

use PDO;

class WishlistModel
{
    // 1. Récupère le Profil (ou le crée discrètement s'il n'existe pas encore)
    public function getOrCreateProfile(int $userId): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT ID_profile FROM Profile WHERE ID_user = :uid LIMIT 1");
        $stmt->execute(['uid' => $userId]);
        $profile = $stmt->fetch();

        if ($profile) {
            return (int)$profile['ID_profile'];
        }

        // Si pas de profil, on en crée un vide lié à cet utilisateur
        $stmt = $pdo->prepare("INSERT INTO Profile (name, surname, ID_user) VALUES ('', '', :uid)");
        $stmt->execute(['uid' => $userId]);
        return (int)$pdo->lastInsertId();
    }

    // 2. Le mode "Interrupteur" (Toggle) : Ajoute si ça n'y est pas, supprime si ça y est
    public function toggleWishlist(int $offerId, int $profileId): bool
    {
        $pdo = Database::getConnection();
        
        // On vérifie si l'offre est déjà en favori
        $stmt = $pdo->prepare("SELECT * FROM Save_wishlist WHERE ID_offer = :oid AND ID_profile = :pid");
        $stmt->execute(['oid' => $offerId, 'pid' => $profileId]);
        
        if ($stmt->fetch()) {
            // Elle y est -> On la supprime
            $del = $pdo->prepare("DELETE FROM Save_wishlist WHERE ID_offer = :oid AND ID_profile = :pid");
            $del->execute(['oid' => $offerId, 'pid' => $profileId]);
            return false; // False = "Retiré des favoris"
        } else {
            // Elle n'y est pas -> On l'ajoute
            $ins = $pdo->prepare("INSERT INTO Save_wishlist (ID_offer, ID_profile) VALUES (:oid, :pid)");
            $ins->execute(['oid' => $offerId, 'pid' => $profileId]);
            return true; // True = "Ajouté aux favoris"
        }
    }

    // 3. Récupérer toutes les offres sauvegardées pour la page "Ma Wishlist"
    public function getSavedOffers(int $profileId): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT o.ID_offer as id, o.title as poste, c.name as entreprise, l.city as lieu, 
                   o.type, o.level as niveau, o.duration as duree, o.remuneration, 
                   o.description, c.description as entrepriseDesc, o.domain
            FROM Save_wishlist w
            JOIN Offer o ON w.ID_offer = o.ID_offer
            LEFT JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            WHERE w.ID_profile = :pid
            ORDER BY o.publication_date DESC
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['pid' => $profileId]);
        $offers = $stmt->fetchAll();

        foreach ($offers as &$offre) {
            $offre['icon'] = $this->getIconForDomain($offre['domain']);
        }
        return $offers;
    }

    private function getIconForDomain(?string $domain): string
    {
        return match($domain) {
            'Développement' => 'fa-laptop-code', 'Data / BI' => 'fa-database',
            'Cybersécurité' => 'fa-shield-halved', 'DevOps / Cloud' => 'fa-cloud',
            'Réseau / Systèmes' => 'fa-network-wired', default => 'fa-briefcase'
        };
    }
}