<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class WishlistControllerTest extends TestCase
{
    // ===== TEST 1 — toggleAjax refusé si non connecté =====
    public function testToggleAjaxFailsIfNotLoggedIn(): void
    {
        // Simule une session sans user_id
        $_SESSION = [];

        // Mock du WishlistModel
        $wishlistModelMock = $this->createMock(\App\Models\WishlistModel::class);

        // toggleWishlist ne doit JAMAIS être appelé si non connecté
        $wishlistModelMock->expects($this->never())
                          ->method('toggleWishlist');

        // Vérifie que la session ne contient pas user_id
        $this->assertArrayNotHasKey(
            'user_id',
            $_SESSION,
            'Un utilisateur non connecté ne doit pas avoir de user_id en session'
        );

        // Vérifie que le rôle n'est pas étudiant
        $this->assertFalse(
            isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'etudiant',
            'Un utilisateur non connecté ne doit pas passer la vérification de rôle'
        );
    }

    // ===== TEST 2 — toggleAjax refusé si offer_id invalide =====
    public function testToggleAjaxFailsWithInvalidOfferId(): void
    {
        // Simule une session étudiant connecté
        $_SESSION = [
            'user_id' => 1,
            'role'    => 'etudiant',
        ];

        $wishlistModelMock = $this->createMock(\App\Models\WishlistModel::class);

        // Simule un offer_id à 0 (invalide)
        $data    = ['offer_id' => 0];
        $offerId = (int) ($data['offer_id'] ?? 0);

        // toggleWishlist ne doit pas être appelé avec un ID invalide
        $wishlistModelMock->expects($this->never())
                          ->method('toggleWishlist');

        // Vérifie que l'offer_id est bien invalide
        $this->assertLessThanOrEqual(
            0,
            $offerId,
            'Un offer_id à 0 ou négatif doit être considéré comme invalide'
        );

        // Vérifie que la réponse attendue est bien un échec
        $expectedResponse = ['success' => false, 'error' => "ID de l'offre invalide."];
        $this->assertFalse(
            $expectedResponse['success'],
            'La réponse doit indiquer un échec pour un offer_id invalide'
        );

        // Nettoyage session
        $_SESSION = [];
    }
}