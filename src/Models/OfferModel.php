<?php

namespace App\Models;

use PDO;

class OfferModel
{
    private int $parPage = 6;

    /**
     * Récupère TOUTES les offres (méthode de base)
     */
    public function getAll(): array
    {
        return $this->searchOffers(); // On redirige vers searchOffers avec des paramètres vides
    }

    /**
     * Récupère les offres en fonction des filtres (Recherche, Lieu, etc.)
     */
    public function searchOffers(string $search = '', string $location = '', array $categories = [], array $types = [], array $levels = []): array
    {
        $pdo = Database::getConnection();

        // La base de la requête SQL
        $sql = "
            SELECT 
                o.ID_offer as id,
                o.title as poste,
                c.name as entreprise,
                l.city as lieu,
                o.type,
                o.level as niveau,
                o.duration as duree,
                o.remuneration,
                o.description,
                c.description as entrepriseDesc,
                o.domain
            FROM Offer o
            LEFT JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            WHERE 1=1
        ";

        $params = [];

        // Filtre de recherche texte (Mots-clés)
        if (!empty($search)) {
            $sql .= " AND (o.title LIKE :search1 OR c.name LIKE :search2)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        // Filtre de lieu
        if (!empty($location)) {
            $sql .= " AND l.city = :location";
            $params['location'] = $location;
        }

        // Filtre de catégories (domaines)
        if (!empty($categories)) {
            $placeholders = [];
            foreach ($categories as $i => $cat) {
                $key = "cat$i";
                $placeholders[] = ":$key";
                $params[$key] = $cat;
            }
            $sql .= " AND o.domain IN (" . implode(',', $placeholders) . ")";
        }

        // Filtre de types (Stage, Alternance)
        if (!empty($types)) {
            $placeholders = [];
            foreach ($types as $i => $type) {
                $key = "type$i";
                $placeholders[] = ":$key";
                $params[$key] = $type;
            }
            $sql .= " AND o.type IN (" . implode(',', $placeholders) . ")";
        }

        // Filtre de niveaux (Bac+3, Bac+5...)
        if (!empty($levels)) {
            $placeholders = [];
            foreach ($levels as $i => $level) {
                $key = "level$i";
                $placeholders[] = ":$key";
                $params[$key] = $level;
            }
            $sql .= " AND o.level IN (" . implode(',', $placeholders) . ")";
        }

        // On trie toujours les plus récentes en premier
        $sql .= " ORDER BY o.publication_date DESC";

        // Exécution de la requête sécurisée
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $offres = $stmt->fetchAll();

        // Ajout des icônes dynamiques
        foreach ($offres as &$offre) {
            $offre['icon'] = $this->getIconForDomain($offre['domain']);
        }

        return $offres;
    }

    /**
     * Récupère les 4 dernières offres pour la page d'accueil
     */
    public function getLatestOffers(): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT 
                o.ID_offer as id,
                o.title as poste,
                c.name as entreprise,
                l.city as lieu,
                o.type,
                o.level as niveau,
                o.duration as duree,
                o.remuneration,
                o.description,
                c.description as entrepriseDesc,
                o.domain
            FROM Offer o
            LEFT JOIN Company c ON o.ID_company = c.ID
            LEFT JOIN Location l ON o.ID_location = l.ID_location
            ORDER BY o.publication_date DESC
            LIMIT 4
        ";

        $stmt = $pdo->query($sql);
        $offres = $stmt->fetchAll();

        foreach ($offres as &$offre) {
            $offre['icon'] = $this->getIconForDomain($offre['domain']);
        }

        return $offres;
    }

    /**
     * Pagination et utilitaires
     */
    public function getPage(array $offers, int $page): array
    {
        $offset = ($page - 1) * $this->parPage;
        return array_slice($offers, $offset, $this->parPage);
    }

    public function totalPages(array $offers): int
    {
        return (int) ceil(count($offers) / $this->parPage);
    }

    private function getIconForDomain(?string $domain): string
    {
        return match($domain) {
            'Développement' => 'fa-laptop-code',
            'Data / BI' => 'fa-database',
            'Cybersécurité' => 'fa-shield-halved',
            'DevOps / Cloud' => 'fa-cloud',
            'Réseau / Systèmes' => 'fa-network-wired',
            'Support IT' => 'fa-headset',
            'Gestion de projet' => 'fa-tasks',
            'RH' => 'fa-users',
            'Marketing / Com' => 'fa-bullhorn',
            'Finance' => 'fa-chart-line',
            'Commercial' => 'fa-handshake',
            'Conduite de travaux' => 'fa-helmet-safety',
            'Génie civil' => 'fa-building',
            'Topographie' => 'fa-map',
            'HSE / Sécurité' => 'fa-triangle-exclamation',
            default => 'fa-briefcase'
        };
    }

    /**
     * Génère la liste des pages pour la pagination (avec les "...")
     */
    public function getPageNumbers(int $totalPages, int $currentPage = 1): array
    {
        // S'il n'y a pas d'offres ou 1 seule page
        if ($totalPages <= 1) {
            return [1];
        }

        // Si on a 5 pages ou moins, on affiche tout [1, 2, 3, 4, 5]
        if ($totalPages <= 5) {
            return range(1, $totalPages);
        }

        // Si on est au début (ex: page 1, 2 ou 3) -> [1, 2, 3, 4, '...', 10]
        if ($currentPage <= 3) {
            return [1, 2, 3, 4, '...', $totalPages];
        }

        // Si on est à la fin (ex: page 8, 9 ou 10) -> [1, '...', 7, 8, 9, 10]
        if ($currentPage >= $totalPages - 2) {
            return [1, '...', $totalPages - 3, $totalPages - 2, $totalPages - 1, $totalPages];
        }

        // Si on est au milieu (ex: page 5) -> [1, '...', 4, 5, 6, '...', 10]
        return [1, '...', $currentPage - 1, $currentPage, $currentPage + 1, '...', $totalPages];
    }
}