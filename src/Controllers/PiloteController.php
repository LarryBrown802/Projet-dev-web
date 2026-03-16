<?php
namespace App\Controllers;

class PiloteController {
    
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function dashboard() {
        // SECURITY GATE: In a real app, you would check if $_SESSION['user']['role'] === 'pilote' here!
        
        // 1. DUMMY DATA: Pilote Info
        $pilote = [
            'nom' => 'M. Dupont',
            'promotion' => 'CPI A2 Informatique',
            'nb_etudiants' => 27
        ];

        // 2. DUMMY DATA: KPIs
        $kpis = [
            'candidatures_totales' => 87,
            'etudiants_postule' => 18,
            'entreprises' => 12,
            'offres_dispos' => 4
        ];

        // 3. DUMMY DATA: Recent Applications List
        $candidatures = [
            ['etudiant' => 'Enzo Boucetta', 'offre' => 'Développeur Web', 'entreprise' => 'Tech Solutions', 'date' => '20/02/2026', 'statut' => 'En attente', 'badge' => 'badge--wait'],
            ['etudiant' => 'Julien Voltz', 'offre' => 'Data Analyst', 'entreprise' => 'Data Insights', 'date' => '19/02/2026', 'statut' => 'Accepté', 'badge' => 'badge--ok'],
            ['etudiant' => 'Michel Battoktok', 'offre' => 'Cybersécurité', 'entreprise' => 'SecureTech', 'date' => '18/02/2026', 'statut' => 'Refusé', 'badge' => 'badge--no'],
            ['etudiant' => 'Samuel Verel', 'offre' => 'DevOps', 'entreprise' => 'Cloud Solutions', 'date' => '17/02/2026', 'statut' => 'En attente', 'badge' => 'badge--wait'],
            ['etudiant' => 'Larry Brown', 'offre' => 'Ingénieur IA', 'entreprise' => 'AI Labs', 'date' => '16/02/2026', 'statut' => 'En attente', 'badge' => 'badge--wait']
        ];

        // Send everything to Twig!
        echo $this->twig->render('dashboardPilote.html.twig', [
            'pilote' => $pilote,
            'kpis' => $kpis,
            'candidatures' => $candidatures
        ]);
    }

    // Handles the GET request for '/pilote/etudiants'
    public function mesEtudiants() {
        
        // DUMMY DATA matching your advanced Twig template
        $etudiants = [
            [
                'nom' => 'Boucetta',
                'prenom' => 'Enzo',
                'email' => 'enzo.boucetta@viacesi.fr',
                'nb_candidatures' => 3,
                'statut' => 'En cours',
                'badge' => 'badge--wait',
                // We use json_encode so Javascript can parse it easily in the view!
                'candidatures' => json_encode([
                    ["offre" => "Développeur Web", "entreprise" => "Tech Solutions", "date" => "20/02/2026", "statut" => "wait"],
                    ["offre" => "Data Analyst", "entreprise" => "Data Insights", "date" => "15/02/2026", "statut" => "ok"],
                    ["offre" => "DevOps", "entreprise" => "Cloud Solutions", "date" => "10/02/2026", "statut" => "wait"]
                ])
            ],
            [
                'nom' => 'Battoktok',
                'prenom' => 'Michel',
                'email' => 'michel.battoktok@viacesi.fr',
                'nb_candidatures' => 5,
                'statut' => 'Stage trouvé',
                'badge' => 'badge--ok',
                'candidatures' => json_encode([
                    ["offre" => "Data Analyst", "entreprise" => "Data Insights", "date" => "19/02/2026", "statut" => "ok"],
                    ["offre" => "Développeur Web", "entreprise" => "Tech Solutions", "date" => "10/02/2026", "statut" => "no"]
                ])
            ],
            [
                'nom' => 'Chedjou',
                'prenom' => 'Larry Brown',
                'email' => 'larry.chedjou@viacesi.fr',
                'nb_candidatures' => 2,
                'statut' => 'Non trouvé',
                'badge' => 'badge--no',
                'candidatures' => json_encode([
                    ["offre" => "Cybersécurité", "entreprise" => "SecureTech", "date" => "18/02/2026", "statut" => "no"]
                ])
            ]
        ];

        // Send the data to your specific template
        echo $this->twig->render('dashboardPGEtudiant.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    // Handles the GET request for '/pilote/entreprises'
    public function mesEntreprises() {
        $entreprises = [
            ['nom' => 'Tech Solutions', 'email' => 'contact@techsolutions.fr', 'tel' => '01 23 45 67 89', 'candidats' => 6, 'note' => 4, 'desc' => 'Entreprise spécialisée dans le développement web et mobile.'],
            ['nom' => 'Data Insights', 'email' => 'contact@datainsights.fr', 'tel' => '01 34 56 78 90', 'candidats' => 3, 'note' => 5, 'desc' => "Leader de l'analyse de données décisionnelles."],
            ['nom' => 'SecureTech', 'email' => 'contact@securetech.fr', 'tel' => '01 45 67 89 01', 'candidats' => 4, 'note' => 3, 'desc' => "Experts en cybersécurité et protection des systèmes d'information."],
            ['nom' => 'Cloud Solutions', 'email' => 'contact@cloudsol.fr', 'tel' => '01 56 78 90 12', 'candidats' => 2, 'note' => 4, 'desc' => "Solutions cloud et DevOps pour les entreprises en transformation numérique."],
            ['nom' => 'AI Labs', 'email' => 'contact@ailabs.fr', 'tel' => '01 67 89 01 23', 'candidats' => 5, 'note' => 5, 'desc' => "Développement de solutions d'intelligence artificielle pour l'industrie."]
        ];

        echo $this->twig->render('dashboardPGEntreprise.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    // Handles the GET request for '/pilote/offres'
    public function mesOffres() {
        $offres = [
            ['titre' => 'Développeur Web', 'entreprise' => 'Tech Solutions', 'type' => 'Stage', 'badge' => 'badge--wait', 'lieu' => 'Lyon', 'remu_brut' => '900', 'remu' => '900 €/mois', 'candidatures' => 6, 'date' => '10/01/2026', 'niveau' => 'Bac+2/3', 'duree_brut' => '6', 'duree' => '6 mois', 'desc' => 'Rejoignez notre équipe pour développer des applications web innovantes.', 'missions' => 'Développer des interfaces;Travailler avec les APIs REST;Participer aux revues de code'],
            ['titre' => 'Data Analyst', 'entreprise' => 'Data Insights', 'type' => 'Alternance', 'badge' => 'badge--ok', 'lieu' => 'Paris', 'remu_brut' => '1200', 'remu' => '1 200 €/mois', 'candidatures' => 3, 'date' => '15/01/2026', 'niveau' => 'Bac+3', 'duree_brut' => '12', 'duree' => '12 mois', 'desc' => 'Analysez des données pour aider à la décision stratégique.', 'missions' => 'Analyser les données;Créer des tableaux de bord;Présenter les insights'],
            ['titre' => 'Spécialiste Cybersécurité', 'entreprise' => 'SecureTech', 'type' => 'Stage', 'badge' => 'badge--wait', 'lieu' => 'Lille', 'remu_brut' => '1100', 'remu' => '1 100 €/mois', 'candidatures' => 4, 'date' => '20/01/2026', 'niveau' => 'Bac+4/5', 'duree_brut' => '6', 'duree' => '6 mois', 'desc' => "Protégez les systèmes d'information contre les menaces.", 'missions' => "Audits de sécurité;Tests d'intrusion;Rédaction de rapports"],
            ['titre' => 'Ingénieur DevOps', 'entreprise' => 'Cloud Solutions', 'type' => 'Stage', 'badge' => 'badge--wait', 'lieu' => 'Marseille', 'remu_brut' => '1300', 'remu' => '1 300 €/mois', 'candidatures' => 2, 'date' => '25/01/2026', 'niveau' => 'Bac+4/5', 'duree_brut' => '6', 'duree' => '6 mois', 'desc' => 'Automatisez les déploiements et assurez la fiabilité des systèmes.', 'missions' => 'Automatiser les déploiements;Gérer les infra cloud;Monitoring']
        ];

        echo $this->twig->render('dashboardPGOffre.html.twig', [
            'offres' => $offres
        ]);
    }
}