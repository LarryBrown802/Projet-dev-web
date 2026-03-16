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
}