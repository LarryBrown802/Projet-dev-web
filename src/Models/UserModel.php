<?php

namespace App\Models;

use PDO;

class UserModel
{
    /**
     * Vérifie si l'email et le mot de passe correspondent à un utilisateur en base
     */
    public function authenticate(string $email, string $password): ?array
    {
        $pdo = Database::getConnection();
        
        // On cherche l'utilisateur par son email et on récupère le nom de son rôle
        $sql = "
            SELECT 
                u.ID_user as id, 
                u.email, 
                u.password, 
                r.name_role as role
            FROM User u
            LEFT JOIN Role r ON u.ID_role = r.ID_role
            WHERE u.email = :email
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        // Si on trouve un utilisateur avec cet email
        if ($user) {
            /* * ⚠️ NOTE DE SÉCURITÉ :
             * Dans ton fichier SQL, les mots de passe sont "en clair" (ex: 'etudiant123').
             * On compare donc directement les textes. 
             * Plus tard, pour un projet pro, il faudra utiliser password_verify() et hasher les mots de passe !
             */
            if ($password === $user['password']) {
                // On supprime le mot de passe du tableau avant de le renvoyer, par sécurité
                unset($user['password']);
                if ($user['role'] === 'administrateur') {
                    $user['role'] = 'admin';
                }
                return $user; // Retourne [id => 3, email => 'etudiant1@test.com', role => 'etudiant']
            }
        }

        return null; // Échec de la connexion
    }
}