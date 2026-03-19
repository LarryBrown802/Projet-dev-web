<?php

namespace App\Models;

class OfferModel
{
    private array $offers;
    private int $parPage = 4; 

    public function getPage(array $offers, int $page): array
    {
        $offset = ($page - 1) * $this->parPage;
        return array_slice($offers, $offset, $this->parPage);
    }

    public function totalPages(array $offers): int
    {
        return (int) ceil(count($offers) / $this->parPage);
    }



    public function __construct()
    {
        $this->offers = [
            [
            'id' => 1,
            'poste' => 'Développeur Web',
            'entreprise' => 'Tech Solutions',
            'lieu' => 'Lyon',
            'type' => 'Stage',
            'niveau' => 'Bac+2/3',
            'categorie' => 'Développement',
            'remuneration' => '900€/mois',
            'duree' => '6 mois',
            'description' => 'Rejoignez notre équipe pour développer des applications web innovantes.',
            'entrepriseDesc' => 'Tech Solutions développe des plateformes web pour le retail et l\'industrie.',
            'missions' => ['Développer des écrans HTML/CSS/JS', 'Corriger des bugs', 'Participer aux revues de code', 'Écrire des tests', 'Collaborer avec le designer'],
            'candidatures' => 6,
            'date' => '10/01/2026',
            'icon' => 'fa-laptop-code'
        ],
        [
            'id' => 2,
            'poste' => 'Data Analyst',
            'entreprise' => 'Data Insights',
            'lieu' => 'Paris',
            'type' => 'Alternance',
            'niveau' => 'Bac+3',
            'categorie' => 'Data / BI',
            'remuneration' => '1200€/mois',
            'duree' => '12 mois',
            'description' => 'Analysez des données pour aider à la décision stratégique.',
            'entrepriseDesc' => 'Data Insights accompagne des PME avec des dashboards décisionnels.',
            'missions' => ['Préparer et nettoyer les données', 'Créer des dashboards', 'Analyser des KPI', 'Présenter les résultats', 'Automatiser des rapports'],
            'candidatures' => 3,
            'date' => '15/01/2026',
            'icon' => 'fa-chart-line'
        ],
        [
            'id' => 3,
            'poste' => 'Spécialiste Cybersécurité',
            'entreprise' => 'SecureTech',
            'lieu' => 'Lille',
            'type' => 'Stage',
            'niveau' => 'Bac+4/5',
            'categorie' => 'Cybersécurité',
            'remuneration' => '1100€/mois',
            'duree' => '6 mois',
            'description' => 'Protégez les systèmes d\'information contre les menaces et attaques.',
            'entrepriseDesc' => 'SecureTech audite et sécurise les SI des grandes entreprises.',
            'missions' => ['Participer à des audits', 'Analyser des vulnérabilités', 'Mettre en place des recommandations', 'Rédiger des rapports', 'Suivre des incidents'],
            'candidatures' => 4,
            'date' => '20/01/2026',
            'icon' => 'fa-shield-halved'
        ],
        [
            'id' => 4,
            'poste' => 'Développeur Mobile',
            'entreprise' => 'App Innovate',
            'lieu' => 'Toulouse',
            'type' => 'Stage',
            'niveau' => 'Bac+3',
            'categorie' => 'Développement',
            'remuneration' => '1000€/mois',
            'duree' => '4 mois',
            'description' => 'Participez au développement d\'applications mobiles innovantes.',
            'entrepriseDesc' => 'App Innovate conçoit des applications mobiles pour la santé et le sport.',
            'missions' => ['Développer des fonctionnalités mobiles', 'Corriger des bugs', 'Participer aux revues de code', 'Collaborer avec le designer', 'Tester les applications'],
            'candidatures' => 5,
            'date' => '22/01/2026',
            'icon' => 'fa-mobile-screen-button'
        ],
        [
            'id' => 5,
            'poste' => 'Ingénieur IA',
            'entreprise' => 'AI Labs',
            'lieu' => 'Bordeaux',
            'type' => 'Alternance',
            'niveau' => 'Bac+5',
            'categorie' => 'Data / BI',
            'remuneration' => '1500€/mois',
            'duree' => '12 mois',
            'description' => 'Concevez et entraînez des modèles d\'intelligence artificielle.',
            'entrepriseDesc' => 'AI Labs développe des solutions d\'IA pour l\'industrie et les services.',
            'missions' => ['Concevoir des modèles d\'IA', 'Entraîner des réseaux de neurones', 'Analyser les performances', 'Collaborer avec les équipes produit', 'Rester à jour sur les avancées en IA'],
            'candidatures' => 8,
            'date' => '25/01/2026',
            'icon' => 'fa-robot'
        ],
        [
            'id' => 6,
            'poste' => 'Ingénieur DevOps',
            'entreprise' => 'Cloud Solutions',
            'lieu' => 'Marseille',
            'type' => 'Stage',
            'niveau' => 'Bac+4/5',
            'categorie' => 'DevOps / Cloud',
            'remuneration' => '1300€/mois',
            'duree' => '6 mois',
            'description' => 'Automatisez les déploiements et assurez la fiabilité des systèmes.',
            'entrepriseDesc' => 'Cloud Solutions accompagne les entreprises dans leur transformation cloud.',
            'missions' => ['Automatiser les déploiements', 'Gérer les infrastructures cloud', 'Surveiller les performances', 'Collaborer avec les équipes dev', 'Assurer la sécurité des systèmes'],
            'candidatures' => 2,
            'date' => '25/01/2026',
            'icon' => 'fa-cogs'
        ],
        [
            'id' => 7,
            'poste' => 'Développeur Backend PHP',
            'entreprise' => 'WebAgency',
            'lieu' => 'Nantes',
            'type' => 'Stage',
            'niveau' => 'Bac+3',
            'categorie' => 'Développement',
            'remuneration' => '950€/mois',
            'duree' => '3 mois',
            'description' => 'Développez des APIs robustes et performantes en PHP.',
            'entrepriseDesc' => 'WebAgency crée des solutions web sur mesure pour les PME françaises.',
            'missions' => ['Développer des APIs REST', 'Optimiser les requêtes SQL', 'Écrire des tests unitaires', 'Documenter le code', 'Travailler en méthode Agile'],
            'candidatures' => 7,
            'date' => '01/02/2026',
            'icon' => 'fa-server'
        ],
        [
            'id' => 8,
            'poste' => 'UX/UI Designer',
            'entreprise' => 'Creative Studio',
            'lieu' => 'Lyon',
            'type' => 'Stage',
            'niveau' => 'Bac+2/3',
            'categorie' => 'Développement',
            'remuneration' => '800€/mois',
            'duree' => '4 mois',
            'description' => 'Concevez des interfaces intuitives et esthétiques pour nos clients.',
            'entrepriseDesc' => 'Creative Studio est une agence de design spécialisée en expérience utilisateur.',
            'missions' => ['Réaliser des wireframes', 'Créer des maquettes Figma', 'Conduire des tests utilisateurs', 'Collaborer avec les développeurs', 'Présenter les designs aux clients'],
            'candidatures' => 9,
            'date' => '03/02/2026',
            'icon' => 'fa-pen-ruler'
        ],
        [
            'id' => 9,
            'poste' => 'Administrateur Réseau',
            'entreprise' => 'NetWork Pro',
            'lieu' => 'Strasbourg',
            'type' => 'Alternance',
            'niveau' => 'Bac+2/3',
            'categorie' => 'Réseau / Systèmes',
            'remuneration' => '1050€/mois',
            'duree' => '12 mois',
            'description' => 'Administrez et sécurisez l\'infrastructure réseau de l\'entreprise.',
            'entrepriseDesc' => 'NetWork Pro gère les infrastructures réseau de plus de 50 entreprises en Alsace.',
            'missions' => ['Configurer des équipements réseau', 'Surveiller le trafic', 'Gérer les incidents', 'Mettre à jour les firewalls', 'Documenter l\'infrastructure'],
            'candidatures' => 3,
            'date' => '05/02/2026',
            'icon' => 'fa-network-wired'
        ],
        [
            'id' => 10,
            'poste' => 'Chef de Projet IT',
            'entreprise' => 'Innova Group',
            'lieu' => 'Paris',
            'type' => 'Alternance',
            'niveau' => 'Bac+5',
            'categorie' => 'Gestion de projet',
            'remuneration' => '1400€/mois',
            'duree' => '24 mois',
            'description' => 'Pilotez des projets IT de A à Z en méthode Agile/Scrum.',
            'entrepriseDesc' => 'Innova Group est un cabinet de conseil en transformation digitale.',
            'missions' => ['Planifier les sprints', 'Animer les daily meetings', 'Gérer les risques', 'Communiquer avec les clients', 'Livrer dans les délais'],
            'candidatures' => 6,
            'date' => '10/02/2026',
            'icon' => 'fa-diagram-project'
        ],
        [
            'id' => 11,
            'poste' => 'Développeur Full Stack',
            'entreprise' => 'StartupX',
            'lieu' => 'Bordeaux',
            'type' => 'Stage',
            'niveau' => 'Bac+4/5',
            'categorie' => 'Développement',
            'remuneration' => '1100€/mois',
            'duree' => '6 mois',
            'description' => 'Développez des fonctionnalités end-to-end dans une startup en forte croissance.',
            'entrepriseDesc' => 'StartupX révolutionne la gestion RH avec une plateforme SaaS innovante.',
            'missions' => ['Développer le frontend en React', 'Créer des APIs Node.js', 'Optimiser les performances', 'Participer aux choix techniques', 'Déployer sur AWS'],
            'candidatures' => 11,
            'date' => '12/02/2026',
            'icon' => 'fa-code'
        ],
        [
            'id' => 12,
            'poste' => 'Technicien Support IT',
            'entreprise' => 'HelpDesk Plus',
            'lieu' => 'Rennes',
            'type' => 'Stage',
            'niveau' => 'Bac+2/3',
            'categorie' => 'Support IT',
            'remuneration' => '850€/mois',
            'duree' => '3 mois',
            'description' => 'Assistez les utilisateurs et résolvez les incidents informatiques.',
            'entrepriseDesc' => 'HelpDesk Plus fournit des services de support IT externalisé pour les entreprises.',
            'missions' => ['Répondre aux tickets', 'Diagnostiquer les pannes', 'Installer les postes de travail', 'Former les utilisateurs', 'Rédiger des procédures'],
            'candidatures' => 4,
            'date' => '15/02/2026',
            'icon' => 'fa-headset'
            ],
        ];
    }

    public function getAllOffers(): array
    {
        return $this->offers;
    }

    public function getLatestOffers(int $limit = 4): array
    {
        return array_slice($this->offers, 0, $limit);
    }

    public function searchOffers(
        ?string $search = null,
        ?string $location = null,
        array $types = [],
        array $levels = [],
        array $categories = []
    ): array {
        $filteredOffers = array_filter($this->offers, function (array $offer) use ($search, $location, $types, $levels, $categories) {
            $matchesSearch = true;
            $matchesLocation = true;
            $matchesType = true;
            $matchesLevel = true;
            $matchesCategory = true;

            if ($search !== null && $search !== '') {
                $matchesSearch =
                    stripos($offer['poste'], $search) !== false ||
                    stripos($offer['entreprise'], $search) !== false ||
                    stripos($offer['categorie'], $search) !== false;
            }

            if ($location !== null && $location !== '') {
                $matchesLocation = strcasecmp($offer['lieu'], $location) === 0;
            }

            if (!empty($types)) {
                $matchesType = in_array($offer['type'], $types, true);
            }

            if (!empty($levels)) {
                $matchesLevel = in_array($offer['niveau'], $levels, true);
            }

            if (!empty($categories)) {
                $matchesCategory = in_array($offer['categorie'], $categories, true);
            }

            return $matchesSearch
                && $matchesLocation
                && $matchesType
                && $matchesLevel
                && $matchesCategory;
        });

        return array_values($filteredOffers);
    }
}