<?php

namespace App\Models;

class CompanyManagementModel
{
    private array $companies;
    private int $parPage = 5;

    public function __construct()
    {
        $this->companies = [
            [
                'nom' => 'Tech Solutions', 
                'email' => 'contact@techsolutions.fr', 
                'tel' => '01 23 45 67 89', 
                'stagiaires' => 6, 
                'note' => 4, 
                'desc' => 'Entreprise spécialisée dans le développement web et mobile.'
            ],
            [
                'nom' => 'Data Insights', 
                'email' => 'contact@datainsights.fr', 
                'tel' => '01 34 56 78 90', 
                'stagiaires' => 3, 
                'note' => 5, 
                'desc' => 'Leader de l\'analyse de données décisionnelles.'
            ],
            [
                'nom' => 'SecureTech', 
                'email' => 'contact@securetech.fr', 
                'tel' => '01 45 67 89 01', 
                'stagiaires' => 4, 
                'note' => 3, 
                'desc' => 'Experts en cybersécurité et protection des systèmes d\'information.'
            ],
            [
                'nom' => 'Cloud Solutions', 
                'email' => 'contact@cloudsol.fr', 
                'tel' => '01 56 78 90 12', 
                'stagiaires' => 2, 
                'note' => 4, 
                'desc' => 'Solutions cloud et DevOps pour les entreprises en transformation numérique.'
            ],
            [
                'nom' => 'AI Labs', 
                'email' => 'contact@ailabs.fr', 
                'tel' => '01 67 89 01 23', 
                'stagiaires' => 5, 
                'note' => 5, 
                'desc' => 'Développement de solutions d\'intelligence artificielle pour l\'industrie.'
            ],
            [
                'nom' => 'App Innovate', 
                'email' => 'contact@appinnovate.fr', 
                'tel' => '01 78 90 12 34', 
                'stagiaires' => 5, 
                'note' => 4, 
                ' desc' => ' Applications mobiles pour la santé et le sport.'
            ],
            [
                'nom' => 'WebAgency', 
                'email' => 'contact@webagency.fr', 
                'tel' => '01 89 01 23 45', 
                'stagiaires' => 7, 
                'note' => 4, 
                'desc' => 'Solutions web sur mesure pour les PME françaises.'
            ],
            [
                'nom' => 'Creative Studio', 
                'email' => 'contact@creativestudio.fr', 
                'tel' => '01 90 12 34 56', 
                'stagiaires' => 9, 
                'note' => 5, 
                'desc' => 'Agence de design spécialisée en expérience utilisateur.'
            ],
            [
                'nom' => 'NetWork Pro', 
                'email' => 'contact@networkpro.fr', 
                'tel' => '02 01 23 45 67', 
                'stagiaires' => 3, 
                'note' => 3, 
                'desc' => 'Gestion des infrastructures réseau pour les entreprises en Alsace.'
            ],
            [
                'nom' => 'Innova Group', 
                'email' => 'contact@innovagroup.fr', 
                'tel' => '02 12 34 56 78', 
                'stagiaires' => 6, 
                'note' => 4, 
                'desc' => ' Cabinet de conseil en transformation digitale.'
            ],
            [
                'nom' => 'StartupX', 
                'email' => 'contact@startupx.fr', 
                'tel' => '02 23 45 67 89', 
                'stagiaires' => 11, 
                'note' => 5, 
                'desc' => ' Plateforme SaaS innovante pour la gestion RH.'
            ],
            [
                'nom' => 'HelpDesk Plus', 
                'email' => 'contact@helpdeskplus.fr', 
                'tel' => '02 34 56 78 90', 
                'stagiaires' => 11, 
                'note' => 5, 
                'desc' => ' Plateforme SaaS innovante pour la gestion RH.'
            ],
            [
                'nom' => 'HelpDesk Plus', 
                'email' => 'contact@helpdeskplus.fr', 
                'tel' => '02 34 56 78 90', 
                'stagiaires' => 11, 
                'note' => 5, 
                'desc' => ' Plateforme SaaS innovante pour la gestion RH.'
            ]
        ];
    }

    public function getAllCompanies(): array
    {
        return $this->companies;
    }

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