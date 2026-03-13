<?php

namespace App\Controllers;

use Twig\Environment;

class WishlistController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('wishlist.html.twig', [
            'current_page' => 'wishlist',
        ]);
        
    }
}