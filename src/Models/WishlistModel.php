<?php

namespace App\Models;

class WishlistModel
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getOrCreateProfile(int $userId): int
    {
        $stmt = $this->db->prepare('SELECT ID_profile FROM Profile WHERE ID_user = :uid LIMIT 1');
        $stmt->execute([':uid' => $userId]);
        $profile = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($profile) {
            return (int) $profile['ID_profile'];
        }

        $stmt = $this->db->prepare("INSERT INTO Profile (name, surname, ID_user) VALUES ('', '', :uid)");
        $stmt->execute([':uid' => $userId]);
        return (int) $this->db->lastInsertId();
    }

    public function toggleWishlist(int $offerId, int $profileId): bool
    {
        $stmt = $this->db->prepare('SELECT * FROM Save_wishlist WHERE ID_offer = :oid AND ID_profile = :pid');
        $stmt->execute([':oid' => $offerId, ':pid' => $profileId]);

        if ($stmt->fetch()) {
            $this->db->prepare('DELETE FROM Save_wishlist WHERE ID_offer = :oid AND ID_profile = :pid')
                     ->execute([':oid' => $offerId, ':pid' => $profileId]);
            return false;
        } else {
            $this->db->prepare('INSERT INTO Save_wishlist (ID_offer, ID_profile) VALUES (:oid, :pid)')
                     ->execute([':oid' => $offerId, ':pid' => $profileId]);
            return true;
        }
    }

    public function getSavedOffers(int $profileId): array
    {
        $stmt = $this->db->prepare('
            SELECT o.ID_offer AS id, o.title AS poste, c.name AS entreprise,
                   l.city AS lieu, o.type, o.level AS niveau, o.duration AS duree,
                   o.remuneration, o.description, c.description AS entrepriseDesc, o.domain
            FROM Save_wishlist w
            JOIN Offer o ON w.ID_offer = o.ID_offer
            LEFT JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            WHERE w.ID_profile = :pid
            ORDER BY o.publication_date DESC
        ');
        $stmt->execute([':pid' => $profileId]);
        $offers = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($offers as &$offre) {
            $offre['icon'] = $this->getIconForDomain($offre['domain']);
        }
        return $offers;
    }

    private function getIconForDomain(?string $domain): string
    {
        return match($domain) {
            'Développement'     => 'fa-laptop-code',
            'Data / BI'         => 'fa-database',
            'Cybersécurité'     => 'fa-shield-halved',
            'DevOps / Cloud'    => 'fa-cloud',
            'Réseau / Systèmes' => 'fa-network-wired',
            default             => 'fa-briefcase',
        };
    }

    public function getWishlistIds(int $profileId): array
    {
        $stmt = $this->db->prepare('
            SELECT ID_offer FROM Save_wishlist WHERE ID_profile = :pid
        ');
        $stmt->execute([':pid' => $profileId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN); // Retourne [1, 3, 7, ...]
    }
}