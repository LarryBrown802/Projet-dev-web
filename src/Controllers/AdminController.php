<?php
namespace App\Controllers;

class AdminController {
    
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function dashboard() {
        // SECURITY GATE: Check if the user is logged in AND is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            // If not, kick them back to the login page!
            header('Location: /connexion');
            exit;
        }

        // If they pass the security check, show them the dashboard
        echo $this->twig->render('dashboardAdmin.html.twig');
    }

    // Handles the GET request for '/admin/pilotes'
    public function gestionPilotes() {
        // SECURITY GATE
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /connexion');
            exit;
        }

        // DUMMY DATA for Pilots
        $pilotes = [
            ['id' => 1, 'nom' => 'Roussel', 'prenom' => 'Antoine', 'email' => 'aroussel@viacesi.fr', 'promotion' => 'CPI A2 Informatique', 'etudiants' => 27],
            ['id' => 2, 'nom' => 'Jeanne', 'prenom' => 'Benoît', 'email' => 'bjeanne@viacesi.fr', 'promotion' => 'CPI A2 Généraliste', 'etudiants' => 24],
            ['id' => 3, 'nom' => 'Tout', 'prenom' => 'Firas', 'email' => 'ftout@viacesi.fr', 'promotion' => 'CPI A2 BTP', 'etudiants' => 24]
        ];

        echo $this->twig->render('dashboardAGPilote.html.twig', [
            'pilotes' => $pilotes
        ]);
    }

    // ==========================================
    // GESTION DES ENTREPRISES (ADMIN)
    // ==========================================
    public function gestionEntreprises() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { header('Location: /connexion'); exit; }

        $entreprises = [
            ['nom' => 'Tech Solutions', 'email' => 'contact@techsolutions.fr', 'tel' => '01 23 45 67 89', 'offres' => 3, 'note' => 4, 'statut' => 'Active', 'badge' => 'badge--ok'],
            ['nom' => 'Data Insights', 'email' => 'contact@datainsights.fr', 'tel' => '01 34 56 78 90', 'offres' => 1, 'note' => 5, 'statut' => 'Active', 'badge' => 'badge--ok'],
            ['nom' => 'SecureTech', 'email' => 'contact@securetech.fr', 'tel' => '01 45 67 89 01', 'offres' => 0, 'note' => 3, 'statut' => 'En pause', 'badge' => 'badge--wait'],
            ['nom' => 'Cloud Solutions', 'email' => 'contact@cloudsol.fr', 'tel' => '01 56 78 90 12', 'offres' => 2, 'note' => 4, 'statut' => 'Active', 'badge' => 'badge--ok']
        ];

        echo $this->twig->render('dashboardAGEntreprise.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    // ==========================================
    // GESTION DES ÉTUDIANTS (ADMIN)
    // ==========================================
    public function gestionEtudiants() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { header('Location: /connexion'); exit; }

        $etudiants = [
            [
                'nom' => 'Boucetta', 'prenom' => 'Enzo', 'email' => 'enzo.boucetta@viacesi.fr', 'nb_candidatures' => 3, 'statut' => 'En cours', 'badge' => 'badge--wait', 'pilote' => 'Roussel Antoine',
                'candidatures' => json_encode([
                    ["offre" => "Développeur Web", "entreprise" => "Tech Solutions", "date" => "20/02/2026", "statut" => "wait"],
                    ["offre" => "Data Analyst", "entreprise" => "Data Insights", "date" => "15/02/2026", "statut" => "ok"],
                    ["offre" => "DevOps", "entreprise" => "Cloud Solutions", "date" => "10/02/2026", "statut" => "wait"]
                ])
            ],
            [
                'nom' => 'Battoktok', 'prenom' => 'Michel', 'email' => 'michel.battoktok@viacesi.fr', 'nb_candidatures' => 5, 'statut' => 'Stage trouvé', 'badge' => 'badge--ok', 'pilote' => 'Roussel Antoine',
                'candidatures' => json_encode([
                    ["offre" => "Data Analyst", "entreprise" => "Data Insights", "date" => "19/02/2026", "statut" => "ok"],
                    ["offre" => "Développeur Web", "entreprise" => "Tech Solutions", "date" => "10/02/2026", "statut" => "no"]
                ])
            ],
            [
                'nom' => 'Chefdjou', 'prenom' => 'Larry Brown', 'email' => 'larry.chefdjou@viacesi.fr', 'nb_candidatures' => 2, 'statut' => 'Non trouvé', 'badge' => 'badge--no', 'pilote' => 'Jeanne Benoît',
                'candidatures' => json_encode([
                    ["offre" => "Cybersécurité", "entreprise" => "SecureTech", "date" => "18/02/2026", "statut" => "no"]
                ])
            ],
            [
                'nom' => 'Verel', 'prenom' => 'Samuel', 'email' => 'samuel.verel@viacesi.fr', 'nb_candidatures' => 4, 'statut' => 'En cours', 'badge' => 'badge--wait', 'pilote' => 'Tout Firas',
                'candidatures' => json_encode([
                    ["offre" => "DevOps", "entreprise" => "Cloud Solutions", "date" => "17/02/2026", "statut" => "wait"],
                    ["offre" => "Ingénieur IA", "entreprise" => "AI Labs", "date" => "12/02/2026", "statut" => "wait"]
                ])
            ]
        ];

        echo $this->twig->render('dashboardAGEtudiant.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    // ==========================================
    // GESTION DES OFFRES (ADMIN)
    // ==========================================
    public function gestionOffres() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { header('Location: /connexion'); exit; }

        $offres = [
            ['titre' => 'Développeur Web', 'entreprise' => 'Tech Solutions', 'type' => 'Stage', 'badgeType' => 'tag-stage', 'niveau' => 'Bac+2/3', 'lieu' => 'Lyon', 'remu' => '900 €/mois', 'remu_brut' => '900', 'duree' => '6 mois', 'duree_brut' => '6', 'date' => '10/01/2026', 'candidatures' => 6, 'desc' => 'Rejoignez notre équipe pour développer des applications web innovantes.', 'missions' => 'Développer des interfaces;Travailler avec les APIs REST;Participer aux revues de code'],
            ['titre' => 'Data Analyst', 'entreprise' => 'Data Insights', 'type' => 'Alternance', 'badgeType' => 'tag-alt', 'niveau' => 'Bac+3', 'lieu' => 'Paris', 'remu' => '1 200 €/mois', 'remu_brut' => '1200', 'duree' => '12 mois', 'duree_brut' => '12', 'date' => '15/01/2026', 'candidatures' => 3, 'desc' => 'Analysez des données pour aider à la décision stratégique.', 'missions' => 'Analyser les données;Créer des tableaux de bord;Présenter les insights'],
            ['titre' => 'Spécialiste Cybersécurité', 'entreprise' => 'SecureTech', 'type' => 'Stage', 'badgeType' => 'tag-stage', 'niveau' => 'Bac+4/5', 'lieu' => 'Lille', 'remu' => '1 100 €/mois', 'remu_brut' => '1100', 'duree' => '6 mois', 'duree_brut' => '6', 'date' => '20/01/2026', 'candidatures' => 4, 'desc' => "Protégez les systèmes d'information contre les menaces.", 'missions' => "Audits de sécurité;Tests d'intrusion;Rédaction de rapports"],
            ['titre' => 'Ingénieur DevOps', 'entreprise' => 'Cloud Solutions', 'type' => 'Stage', 'badgeType' => 'tag-stage', 'niveau' => 'Bac+4/5', 'lieu' => 'Marseille', 'remu' => '1 300 €/mois', 'remu_brut' => '1300', 'duree' => '6 mois', 'duree_brut' => '6', 'date' => '25/01/2026', 'candidatures' => 2, 'desc' => 'Automatisez les déploiements et assurez la fiabilité des systèmes.', 'missions' => 'Automatiser les déploiements;Gérer les infra cloud;Monitoring']
        ];

        echo $this->twig->render('dashboardAGOffre.html.twig', [
            'offres' => $offres
        ]);
    }
}