<?php

namespace App\Utils;

class Pagination
{
    /**
     * Calcule le OFFSET pour la requête SQL (à partir de quelle ligne on commence à lire)
     */
    public static function getOffset(int $page, int $perPage): int
    {
        // Si on est page 1, offset = 0. Si page 2 (avec 6 par page), offset = 6.
        return max(0, ($page - 1) * $perPage);
    }

    /**
     * Calcule le nombre total de pages
     */
    public static function getTotalPages(int $totalItems, int $perPage): int
    {
        return (int) ceil($totalItems / $perPage);
    }

    /**
     * Génère le tableau pour Twig [1, 2, '...', 5, 6]
     */
    public static function getPageNumbers(int $current, int $total): array
    {
        if ($total <= 1) {
            return []; // Pas besoin de pagination si on a qu'une seule page !
        }

        if ($total <= 5) {
            return range(1, $total);
        }

        $pages = [1];

        if ($current > 3) {
            $pages[] = '...';
        }

        for ($i = max(2, $current - 1); $i <= min($total - 1, $current + 1); $i++) {
            $pages[] = $i;
        }

        if ($current < $total - 2) {
            $pages[] = '...';
        }

        $pages[] = $total;

        return $pages;
    }

    /**
     * Regroupe toutes les infos prêtes à être envoyées à la Vue (Twig)
     */
    public static function getPaginationData(int $totalItems, int $page, int $perPage): array
    {
        $total = self::getTotalPages($totalItems, $perPage);

        // Sécurité : si l'utilisateur tape ?page=100 dans l'URL mais qu'il n'y a que 3 pages, on le ramène à la dernière
        $safePage = max(1, min($page, $total));

        return [
            'pageCourante' => $safePage,
            'totalPages'   => $total,
            'pages'        => self::getPageNumbers($safePage, $total),
            'perPage'      => $perPage // Toujours utile de le renvoyer à la vue
        ];
    }
}