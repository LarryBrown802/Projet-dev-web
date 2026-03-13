<?php

namespace App\Models;

class OfferModel
{
    private array $offers;

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
                'duree' => '3 mois',
                'description' => 'Rejoignez notre équipe pour développer des applications web innovantes.',
                'entrepriseDesc' => 'Tech Solutions développe des plateformes web pour le retail et l’industrie. Équipe agile, code review et bonnes pratiques.',
                'missions' => [
                    'Développer des écrans HTML/CSS/JS',
                    'Corriger des bugs',
                    'Participer aux revues de code',
                    'Écrire des tests',
                    'Collaborer avec le designer'
                ],
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
                'entrepriseDesc' => 'Data Insights accompagne des PME et grands comptes avec des dashboards et analyses décisionnelles.',
                'missions' => [
                    'Préparer et nettoyer les données',
                    'Créer des dashboards',
                    'Analyser des KPI',
                    'Présenter les résultats',
                    'Automatiser des rapports'
                ],
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
                'description' => 'Protégez les systèmes d’information contre les menaces et attaques.',
                'entrepriseDesc' => 'SecureTech audite et sécurise les SI. Culture sécurité et documentation.',
                'missions' => [
                    'Participer à des audits',
                    'Analyser des vulnérabilités',
                    'Mettre en place des recommandations',
                    'Rédiger des rapports',
                    'Suivre des incidents'
                ],
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
                'description' => 'Participez au développement d’applications mobiles innovantes.',
                'entrepriseDesc' => 'App Innovate conçoit des applications mobiles pour les secteurs de la santé et du sport.',
                'missions' => [
                    'Développer des fonctionnalités mobiles',
                    'Corriger des bugs',
                    'Participer aux revues de code',
                    'Collaborer avec le designer',
                    'Tester les applications'
                ],
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
                'description' => 'Concevez et entraînez des modèles d’intelligence artificielle.',
                'entrepriseDesc' => 'AI Labs développe des solutions d’IA pour l’industrie et les services.',
                'missions' => [
                    'Concevoir des modèles d’IA',
                    'Entraîner des réseaux de neurones',
                    'Analyser les performances',
                    'Collaborer avec les équipes produit',
                    'Rester à jour sur les avancées en IA'
                ],
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
                'entrepriseDesc' => 'Cloud Solutions accompagne les entreprises dans leur transformation cloud avec des solutions sur mesure.',
                'missions' => [
                    'Automatiser les déploiements',
                    'Gérer les infrastructures cloud',
                    'Surveiller les performances',
                    'Collaborer avec les équipes de développement',
                    'Assurer la sécurité des systèmes'
                ],
                'icon' => 'fa-cogs'
            ]
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