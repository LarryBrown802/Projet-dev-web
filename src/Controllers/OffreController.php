<?php
namespace App\Controllers;

class OffreController {
    
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    // Handles the GET request for '/offres'
    public function listePublic() {
        
        // 1. Our Dummy Data
        $offres = [
            [
                'id' => 1, 'titre' => 'Développeur Web', 'entreprise' => 'Tech Solutions', 
                'lieu' => 'Lyon', 'type' => 'Stage', 'niveau' => 'Bac+2/3', 'duree' => '3 mois', 
                'remu' => '900€/mois', 'description' => 'Rejoignez notre équipe pour développer des applications web innovantes.', 
                'missions' => 'Développer des écrans (HTML/CSS/JS);Corriger des bugs', 'entreprise_desc' => 'Tech Solutions développe des plateformes web.', 'icon' => 'fa-laptop-code'
            ],
            [
                'id' => 2, 'titre' => 'Data Analyst', 'entreprise' => 'Data Insights', 
                'lieu' => 'Paris', 'type' => 'Alternance', 'niveau' => 'Bac+3', 'duree' => '12 mois', 
                'remu' => '1200€/mois', 'description' => 'Analysez des données pour aider à la décision stratégique.', 
                'missions' => 'Préparer et nettoyer les données;Créer des dashboards', 'entreprise_desc' => 'Data Insights accompagne des PME.', 'icon' => 'fa-chart-line'
            ],
            [
                'id' => 3, 'titre' => 'Spécialiste Cybersécurité', 'entreprise' => 'SecureTech', 
                'lieu' => 'Lille', 'type' => 'Stage', 'niveau' => 'Bac+4/5', 'duree' => '6 mois', 
                'remu' => '1100€/mois', 'description' => 'Protégez les systèmes d’information contre les menaces et attaques.', 
                'missions' => 'Participer à des audits;Analyser des vulnérabilités', 'entreprise_desc' => 'SecureTech audite et sécurise les SI.', 'icon' => 'fa-shield-halved'
            ]
        ];

        // 2. Look for a Search Query in the URL (e.g., ?q=Data)
        // If 'q' doesn't exist, it defaults to an empty string ''
        $searchQuery = $_GET['q'] ?? '';

        // 3. If the user typed something, we filter the array!
        if (!empty($searchQuery)) {
            $offres = array_filter($offres, function($offre) use ($searchQuery) {
                // We check if the search word is inside the Title OR the Company name (case-insensitive)
                $titreMatch = stripos($offre['titre'], $searchQuery) !== false;
                $entrepriseMatch = stripos($offre['entreprise'], $searchQuery) !== false;
                
                return $titreMatch || $entrepriseMatch;
            });
        }

        // 4. Send the data (and the search word) to Twig
        echo $this->twig->render('offres.html.twig', [
            'offres' => $offres,
            'searchQuery' => $searchQuery // We send this back so the search bar doesn't empty itself!
        ]);
    }
}