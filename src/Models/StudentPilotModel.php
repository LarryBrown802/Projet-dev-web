<?php

namespace App\Models;

class StudentPilotModel
{
    private int $perPage = 5;

    private array $Students = [
        ['id' => 1, 'nom' => 'Boucetta',  'prenom' => 'Enzo',       'email' => 'enzo.boucetta@viacesi.fr',   'statut' => 'en_cours',      'nb_candidatures' => 3],
        ['id' => 2, 'nom' => 'Battoktok', 'prenom' => 'Michel',     'email' => 'michel.battoktok@viacesi.fr','statut' => 'stage_trouve',  'nb_candidatures' => 5],
        ['id' => 3, 'nom' => 'Chefdjou',  'prenom' => 'Larry Brown', 'email' => 'larry.chefdjou@viacesi.fr', 'statut' => 'non_trouve',    'nb_candidatures' => 2],
        ['id' => 4, 'nom' => 'Verel',     'prenom' => 'Samuel',     'email' => 'samuel.verel@viacesi.fr',   'statut' => 'en_cours',      'nb_candidatures' => 4],
        ['id' => 5, 'nom' => 'Linard',    'prenom' => 'Raphael',    'email' => 'raphael.linard@viacesi.fr', 'statut' => 'en_cours',      'nb_candidatures' => 1],
        ['id' => 6, 'nom' => 'Dupont',    'prenom' => 'Lucas',      'email' => 'lucas.dupont@viacesi.fr',   'statut' => 'en_cours',      'nb_candidatures' => 2],
        ['id' => 7, 'nom' => 'Martin',    'prenom' => 'Emma',       'email' => 'emma.martin@viacesi.fr',    'statut' => 'stage_trouve',  'nb_candidatures' => 6],
        ['id' => 8, 'nom' => 'Bernard',   'prenom' => 'Hugo',       'email' => 'hugo.bernard@viacesi.fr',   'statut' => 'non_trouve',    'nb_candidatures' => 1],
        ['id' => 9, 'nom' => 'Leroy',     'prenom' => 'Camille',    'email' => 'camille.leroy@viacesi.fr',  'statut' => 'en_cours',      'nb_candidatures' => 3],
        ['id' => 10,'nom' => 'Moreau',    'prenom' => 'Théo',       'email' => 'theo.moreau@viacesi.fr',    'statut' => 'stage_trouve',  'nb_candidatures' => 4],
    ];

    public function countAll(): int
    {
        return count($this->Students);
    }

    public function getAll(int $page = 1): array
    {
        $offset = ($page - 1) * $this->perPage;
        return array_slice($this->Students, $offset, $this->perPage);
    }

    public function getPaginationData(int $page): array
    {
        $total = $this->countAll();
        $totalPages = (int) ceil($total / $this->perPage);

        return [
            'current' => $page,
            'total' => $totalPages,
            'hasPrev' => $page > 1,
            'hasNext' => $page < $totalPages,
            'pages' => $this->getPageNumbers($page, $totalPages),
        ];
    }

    private function getPageNumbers(int $current, int $total): array
    {
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
}