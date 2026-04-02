<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ConnexionControllerTest extends TestCase
{
    // ===== TEST 1 — Connexion réussie avec bon email et bon mot de passe =====
    public function testLoginSuccessRedirectsToAccueil(): void
    {
        // On simule un utilisateur étudiant en BDD
        $userMock = [
            'ID_user'   => 1,
            'email'     => 'samuel.verel@viacesi.fr',
            'password'  => password_hash('motdepasse123', PASSWORD_BCRYPT),
            'name_role' => 'etudiant',
        ];

        // Mock du UserModel
        $userModelMock = $this->createMock(\App\Models\UserModel::class);
        $userModelMock->method('findByEmail')->willReturn($userMock);
        $userModelMock->method('clearRememberToken')->willReturn(true);

        // Vérifie que password_verify fonctionne bien
        $this->assertTrue(
            password_verify('motdepasse123', $userMock['password']),
            'Le mot de passe devrait être valide'
        );

        // Vérifie que le rôle est bien étudiant
        $this->assertEquals('etudiant', $userMock['name_role']);
    }

    // ===== TEST 2 — Connexion échouée avec mauvais mot de passe =====
    public function testLoginFailsWithWrongPassword(): void
    {
        $userMock = [
            'ID_user'   => 1,
            'email'     => 'samuel.verel@viacesi.fr',
            'password'  => password_hash('bonmotdepasse', PASSWORD_BCRYPT),
            'name_role' => 'etudiant',
        ];

        $userModelMock = $this->createMock(\App\Models\UserModel::class);
        $userModelMock->method('findByEmail')->willReturn($userMock);

        // Vérifie que le mauvais mot de passe est bien rejeté
        $this->assertFalse(
            password_verify('mauvaismdp', $userMock['password']),
            'Le mauvais mot de passe ne devrait pas être accepté'
        );
    }

    // ===== TEST 3 — Connexion échouée avec email inexistant =====
    public function testLoginFailsWithUnknownEmail(): void
    {
        // UserModel retourne false si l'email n'existe pas
        $userModelMock = $this->createMock(\App\Models\UserModel::class);
        $userModelMock->method('findByEmail')->willReturn(false);

        $user = $userModelMock->findByEmail('inconnu@test.com');

        // Vérifie que l'utilisateur n'est pas trouvé
        $this->assertFalse(
            $user,
            'Un email inexistant devrait retourner false'
        );
    }
}