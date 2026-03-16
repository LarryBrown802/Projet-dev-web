<?php

namespace App\Models;

class StudentModel
{
    private array $students;
    private int $parPage = 5;

    public function __construct()
    {
        $this->students = [
            [
                'nom' => 'Boucetta',
                'prenom' => 'Enzo',
                'email' => 'enzo.boucetta@viacesi.fr',
                'candidatures' => 3,
                'statut' => 'wait',
                'candidatures_detail' => '[{"offre":"Développeur Web","entreprise":"Tech Solutions","date":"20/02/2026","statut":"wait"},{"offre":"Data Analyst","entreprise":"Data Insights","date":"15/02/2026","statut":"ok"},{"offre":"DevOps","entreprise":"Cloud Solutions","date":"10/02/2026","statut":"wait"}]'
            ],
            [
                'nom' => 'Battoktok',
                'prenom' => 'Michel',
                'email' => 'michel.battoktok@viacesi.fr',
                'candidatures' => 5,
                'statut' => 'ok',
                'candidatures_detail' => '[{"offre":"Data Analyst","entreprise":"Data Insights","date":"19/02/2026","statut":"ok"},{"offre":"Développeur Web","entreprise":"Tech Solutions","date":"10/02/2026","statut":"no"}]'
            ],
            [
                'nom' => 'Chefdjou',
                'prenom' => 'Larry Brown',
                'email' => 'larry.chefdjou@viacesi.fr',
                'candidatures' => 2,
                'statut' => 'no',
                'candidatures_detail' => '[{"offre":"Cybersécurité","entreprise":"SecureTech","date":"18/02/2026","statut":"no"}]'
            ],
            [
                'nom' => 'Verel',
                'prenom' => 'Samuel',
                'email' => 'samuel.verel@viacesi.fr',
                'candidatures' => 4,
                'statut' => 'wait',
                'candidatures_detail' => '[{"offre":"DevOps","entreprise":"Cloud Solutions","date":"17/02/2026","statut":"wait"},{"offre":"Ingénieur IA","entreprise":"AI Labs","date":"12/02/2026","statut":"wait"}]'
            ],
            [
                'nom' => 'Linard',
                'prenom' => 'Raphael',
                'email' => 'raphael.linard@viacesi.fr',
                'candidatures' => 1,
                'statut' => 'wait',
                'candidatures_detail' => '[{"offre":"Ingénieur IA","entreprise":"AI Labs","date":"16/02/2026","statut":"wait"}]'
            ],
        ];
    }

    public function getAllStudents(): array
    {
        return $this->students;
    }

    public function getPage(array $students, int $page): array
    {
        $offset = ($page - 1) * $this->parPage;
        return array_slice($students, $offset, $this->parPage);
    }

    public function totalPages(array $students): int
    {
        return (int) ceil(count($students) / $this->parPage);
    }
}