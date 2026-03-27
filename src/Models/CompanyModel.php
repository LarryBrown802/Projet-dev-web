<?php

namespace App\Models;

class CompanyModel
{
    private array $companies;
    private int $parPage = 6;

    public function __construct()
    {
        // J'ai ajouté un lien d'image aléatoire pour chaque entreprise pour tester !
        $this->companies = [
            ['id' => 1, 'nom' => 'Tech Solutions', 'note' => 4, 'tel' => '01 23 45 67 89', 'email' => 'contact@techsolutions.fr', 'desc' => 'Spécialiste dans le développement web et mobile. Nous créons des applications sur-mesure.', 'offres_count' => 6, 'logo' => 'https://picsum.photos/seed/tech/400/200'],
            ['id' => 2, 'nom' => 'Data Insights', 'note' => 5, 'tel' => '01 34 56 78 90', 'email' => 'jobs@datainsights.fr', 'desc' => 'Leader de l\'analyse de données décisionnelles. Accompagnement dans la transformation Data.', 'offres_count' => 3, 'logo' => 'https://picsum.photos/seed/data/400/200'],
            ['id' => 3, 'nom' => 'SecureTech', 'note' => 3, 'tel' => '01 45 67 89 01', 'email' => 'rh@securetech.fr', 'desc' => 'Experts en cybersécurité et protection des systèmes d\'information contre les menaces.', 'offres_count' => 4, 'logo' => 'https://picsum.photos/seed/sec/400/200'],
            ['id' => 4, 'nom' => 'Cloud Solutions', 'note' => 4, 'tel' => '01 56 78 90 12', 'email' => 'hello@cloudsol.fr', 'desc' => 'Solutions cloud et DevOps pour les entreprises en pleine transformation numérique.', 'offres_count' => 2, 'logo' => 'https://picsum.photos/seed/cloud/400/200'],
            ['id' => 5, 'nom' => 'AI Labs', 'note' => 5, 'tel' => '01 67 89 01 23', 'email' => 'contact@ailabs.fr', 'desc' => 'Développement de solutions d\'intelligence artificielle de pointe pour l\'industrie.', 'offres_count' => 8, 'logo' => 'https://picsum.photos/seed/ai/400/200'],
            ['id' => 6, 'nom' => 'WebAgency', 'note' => 4, 'tel' => '02 40 50 60 70', 'email' => 'recrutement@webagency.fr', 'desc' => 'Agence de communication digitale, création de sites vitrines et e-commerce.', 'offres_count' => 5, 'logo' => 'https://picsum.photos/seed/web/400/200'],
            ['id' => 7, 'nom' => 'Creative Studio', 'note' => 5, 'tel' => '04 78 90 12 34', 'email' => 'design@creativestudio.fr', 'desc' => 'Studio de design UX/UI centré sur l\'expérience utilisateur et l\'accessibilité.', 'offres_count' => 2, 'logo' => 'https://picsum.photos/seed/design/400/200'],
            ['id' => 8, 'nom' => 'NetWork Pro', 'note' => 3, 'tel' => '03 88 99 00 11', 'email' => 'admin@networkpro.fr', 'desc' => 'Installation et maintenance d\'infrastructures réseau pour les PME.', 'offres_count' => 1, 'logo' => 'https://picsum.photos/seed/net/400/200']
        ];
    }

    public function getAll(): array { return $this->companies; }
    public function getPage(array $companies, int $page): array { return array_slice($companies, ($page - 1) * $this->parPage, $this->parPage); }
    public function totalPages(array $companies): int { return (int) ceil(count($companies) / $this->parPage); }

    // NOUVELLE MÉTHODE : Trouver une entreprise par son ID
    public function getById(int $id): ?array
    {
        foreach ($this->companies as $company) {
            if ($company['id'] === $id) {
                return $company;
            }
        }
        return null; // Si l'entreprise n'existe pas
    }
    
}