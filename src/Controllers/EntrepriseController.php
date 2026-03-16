<?php
namespace App\Controllers;

class EntrepriseController {
    
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    // Handles the GET request for '/entreprise'
    public function listePublic() {
        
        // DUMMY DATA updated to match your exact HTML needs!
        $entreprises = [
            [
                'id' => 1,
                'nom' => 'Tech Solutions',
                'avis' => 4, // 4 out of 5 stars
                'telephone' => '01 23 45 67 89',
                'email' => 'contact@techsolutions.fr',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel sapien eget nunc efficitur.',
                'candidats' => 6,
                'slug' => 'tech-solutions',
                'couleur' => '#5E92A6'
            ],
            [
                'id' => 2,
                'nom' => 'Data Insights',
                'avis' => 5,
                'telephone' => '01 34 56 78 90',
                'email' => 'contact@datainsights.fr',
                'description' => "Leader de l'analyse de données décisionnelles. Donec vel sapien eget nunc efficitur.",
                'candidats' => 3,
                'slug' => 'data-insights',
                'couleur' => '#4A7A8C'
            ],
            [
                'id' => 3,
                'nom' => 'SecureTech',
                'avis' => 3,
                'telephone' => '01 45 67 89 01',
                'email' => 'contact@securetech.fr',
                'description' => "Experts en cybersécurité et protection des systèmes d'information.",
                'candidats' => 4,
                'slug' => 'securetech',
                'couleur' => '#2c3e50'
            ]
        ];

        // Send the data to Twig
        echo $this->twig->render('entreprise.html.twig', [
            'entreprises' => $entreprises
        ]);
    }
}