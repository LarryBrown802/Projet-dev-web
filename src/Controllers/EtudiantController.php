<?php
namespace App\Controllers;

class EtudiantController {
    
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    // Handles the GET request for '/wishlist'
    public function wishlist() {
        
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
} // <-- This closes the EtudiantController class