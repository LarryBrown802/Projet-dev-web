<?php
namespace App\Controllers;

class EtudiantController
{

    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    // Handles the GET request for '/wishlist'
    public function wishlist()
    {

        // DUMMY DATA: Offers the student has bookmarked
        $favoris = [
            [
                'titre' => 'Développeur Web',
                'entreprise' => 'Tech Solutions',
                'lieu' => 'Lyon',
                'type' => 'Stage',
                'niveau' => 'Bac+2/3',
                'remu' => '900€/mois',
                'description' => 'Rejoignez notre équipe pour développer des applications web innovantes.',
                'icon' => 'fa-laptop-code'
            ],
            [
                'titre' => 'Data Analyst',
                'entreprise' => 'Data Insights',
                'lieu' => 'Paris',
                'type' => 'Alternance',
                'niveau' => 'Bac+3',
                'remu' => '1200€/mois',
                'description' => 'Analysez des données pour aider à la décision stratégique.',
                'icon' => 'fa-chart-line'
            ],
            [
                'titre' => 'Spécialiste Cybersécurité',
                'entreprise' => 'SecureTech',
                'lieu' => 'Lille',
                'type' => 'Stage',
                'niveau' => 'Bac+4/5',
                'remu' => '1100€/mois',
                'description' => 'Protégez les systèmes d’information contre les menaces et attaques.',
                'icon' => 'fa-shield-halved'
            ]
        ];

        // Send the data to Twig
        echo $this->twig->render('wishlist.html.twig', [
            'favoris' => $favoris
        ]);
    } // <-- This closes the wishlist() method

    // Handles the GET request for '/mes-candidatures'
    public function candidatures()
    {
        // SECURITY GATE: Check if user is student (future)

        // DUMMY DATA: Applications the student has sent
        $candidatures = [
            [
                'poste' => 'Développeur Web',
                'entreprise' => 'Tech Solutions',
                'status_icon' => 'fa-paper-plane',
                'status_class' => '',
                'status_title' => 'Candidature envoyée',
                'avis' => 4,
                'email' => 'recrutement@techsolutions.fr',
                'tel' => '+33 6 00 00 00 00',
                'desc_courte' => 'Poste orienté front/back, équipe agile, bonnes pratiques, code review…',
                'lieu' => 'Lyon',
                'type' => 'Stage',
                'niveau' => 'Bac+2/3',
                'duree' => '3 mois',
                'remu' => '900€/mois',
                'entreprise_desc' => 'Tech Solutions développe des plateformes innovantes pour le web.',
                'missions' => 'Développer des écrans (HTML/CSS/JS);Corriger des bugs;Participer aux revues de code;Écrire des tests;Collaborer avec le designer',
                'icon' => 'fa-laptop-code'
            ],
            [
                'poste' => 'Data Analyst',
                'entreprise' => 'Data Insights',
                'status_icon' => 'fa-circle-check',
                'status_class' => 'status--ok',
                'status_title' => 'Réponse reçue',
                'avis' => 3,
                'email' => 'jobs@datainsights.fr',
                'tel' => '+33 1 00 00 00 00',
                'desc_courte' => 'Analyse KPI, dashboards, automatisation de rapports, restitution…',
                'lieu' => 'Paris',
                'type' => 'Alternance',
                'niveau' => 'Bac+3',
                'duree' => '12 mois',
                'remu' => '1200€/mois',
                'entreprise_desc' => 'Data Insights accompagne les grands groupes dans leur transformation Data.',
                'missions' => 'Préparer et nettoyer les données ; Créer des dashboards;Analyser des KPI;Présenter les résultats;Automatiser des rapports',
                'icon' => 'fa-chart-line'
            ],
            [
                'poste' => 'Spécialiste Cybersécurité',
                'entreprise' => 'SecureTech',
                'status_icon' => 'fa-xmark',
                'status_class' => 'status--ko',
                'status_title' => 'Candidature refusée',
                'avis' => 5,
                'email' => 'recrutement@securetech.fr',
                'tel' => '+33 3 00 00 00 00',
                'desc_courte' => 'Protection des systèmes d’information, analyse des menaces, réponse aux incidents…',
                'lieu' => 'Lille',
                'type' => 'Stage',
                'niveau' => 'Bac+4/5',
                'duree' => '6 mois',
                'remu' => '1100€/mois',
                'entreprise_desc' => 'SecureTech propose des solutions de cybersécurité pour protéger les entreprises.',
                'missions' => 'Protéger les réseaux et les systèmes d’information;Analyser les menaces et attaques;Réaliser des audits de sécurité;Élaborer des plans de réponse aux incidents;Former les collaborateurs',
                'icon' => 'fa-shield-halved'
            ],
            [
                'poste' => 'Développeur Backend',
                'entreprise' => 'CloudTech',
                'status_icon' => 'fa-hourglass-half',
                'status_class' => 'status--pending',
                'status_title' => 'En attente',
                'avis' => 5,
                'email' => 'contact@cloudtech.fr',
                'tel' => '+33 7 11 22 33 44',
                'desc_courte' => 'API REST, microservices, Docker, bonnes pratiques backend…',
                'lieu' => 'Marseille',
                'type' => 'Stage',
                'niveau' => 'Bac+3',
                'duree' => '4 mois',
                'remu' => '1000€/mois',
                'entreprise_desc' => 'CloudTech est spécialisée dans les solutions cloud et DevOps.',
                'missions' => 'Créer des API;Maintenir le backend;Optimiser les performances;Sécuriser les données',
                'icon' => 'fa-server'
            ],
            [
                'poste' => 'UX/UI Designer',
                'entreprise' => 'DesignPro',
                'status_icon' => 'fa-hourglass-half',
                'status_class' => 'status--pending',
                'status_title' => 'En attente',
                'avis' => 4,
                'email' => 'recrutement@designpro.fr',
                'tel' => '+33 7 11 22 33 44',
                'desc_courte' => 'Conception d’interfaces utilisateur, maquettes, tests utilisateurs…',
                'lieu' => 'Lyon',
                'type' => 'Emploi',
                'niveau' => 'Bac+3',
                'duree' => '6 mois',
                'remu' => '1500€/mois',
                'entreprise_desc' => 'DesignPro accompagne les startups dans leur développement produit.',
                'missions' => 'Créer des maquettes;Concevoir des interfaces;Réaliser des tests utilisateurs;Documenter les designs',
                'icon' => 'fa-paint-brush'
            ]


        ];

        echo $this->twig->render('offreDejaPostule.html.twig', [
            'candidatures' => $candidatures
        ]);
    }
} // <-- This closes the EtudiantController class