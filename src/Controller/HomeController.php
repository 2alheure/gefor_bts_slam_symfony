<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    #[Route("/", name: "accueil")]
    function afficherLAccueil(Request $request): Response {
        return $this->render('home.html.twig', [
            'titre' => 'Accueil',
            'date' => date('d/m/Y H:i:s'),
            'n' => rand(0, 2)
        ]);
    }
}
