<?php

namespace App\Models;

use PDO;

class CompanyModel
{
    private int $parPage = 6;

    /**
     * Récupère toutes les entreprises et compte le nombre d'offres pour chacune
     */
    public function getAll(): array
    {
        $pdo = Database::getConnection();
        
        // Requête avancée : On joint Company avec Offer pour compter (COUNT) les offres
        $sql = "
            SELECT 
                c.ID as id, 
                c.name as nom, 
                c.average_mark as note, 
                c.number as tel, 
                c.email, 
                c.description as `desc`, 
                COUNT(o.ID_offer) as offres_count
            FROM Company c
            LEFT JOIN Offer o ON c.ID = o.ID_company
            GROUP BY c.ID
            ORDER BY c.name ASC
        ";

        $stmt = $pdo->query($sql);
        $companies = $stmt->fetchAll();

        // On recrée l'image dynamique (le placeholder) en se basant sur le nom de l'entreprise
        foreach ($companies as &$company) {
            $seed = urlencode(strtolower(str_replace(' ', '', $company['nom'])));
            $company['logo'] = "https://picsum.photos/seed/{$seed}/400/200";
        }

        return $companies;
    }

    /**
     * Récupère une seule entreprise par son ID (pour la page "Découvrir")
     */
    public function getById(int $id): ?array
    {
        $pdo = Database::getConnection();
        
        $sql = "
            SELECT 
                c.ID as id, 
                c.name as nom, 
                c.average_mark as note, 
                c.number as tel, 
                c.email, 
                c.description as `desc`, 
                COUNT(o.ID_offer) as offres_count
            FROM Company c
            LEFT JOIN Offer o ON c.ID = o.ID_company
            WHERE c.ID = :id
            GROUP BY c.ID
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $company = $stmt->fetch();

        if ($company) {
            $seed = urlencode(strtolower(str_replace(' ', '', $company['nom'])));
            $company['logo'] = "https://picsum.photos/seed/{$seed}/400/200";
            return $company;
        }

        return null;
    }

    /**
     * Fonctions de pagination
     */
    public function getPage(array $companies, int $page): array
    {
        $offset = ($page - 1) * $this->parPage;
        return array_slice($companies, $offset, $this->parPage);
    }

    public function totalPages(array $companies): int
    {
        return (int) ceil(count($companies) / $this->parPage);
    }
}