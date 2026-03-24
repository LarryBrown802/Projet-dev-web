<?php
namespace App\Models;

use App\Database;
use PDO;

class WishlistModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getWishlistByProfile(int $profileId): array {
        $stmt = $this->pdo->prepare("
            SELECT o.*, e.nom AS entreprise_nom
            FROM sauvegarder_wishlist_ sw
            JOIN Offre o ON sw.ID_offre = o.ID_offre
            JOIN Entreprise e ON o.ID_entreprise = e.ID_entreprise
            WHERE sw.ID_profile = :profile_id
        ");
        $stmt->execute([':profile_id' => $profileId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addToWishlist(int $profileId, int $offerId): void {
        $stmt = $this->pdo->prepare("
            INSERT IGNORE INTO sauvegarder_wishlist_ (ID_offre, ID_profile)
            VALUES (:offre, :profile)
        ");
        $stmt->execute([':offre' => $offerId, ':profile' => $profileId]);
    }

    public function removeFromWishlist(int $profileId, int $offerId): void {
        $stmt = $this->pdo->prepare("
            DELETE FROM sauvegarder_wishlist_
            WHERE ID_offre = :offre AND ID_profile = :profile
        ");
        $stmt->execute([':offre' => $offerId, ':profile' => $profileId]);
    }
}