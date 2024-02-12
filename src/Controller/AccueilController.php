<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AccueilController extends AbstractController
{   
    /**
     * @Route Accueil (' / ', nom app_accueil)
     * Page D'accueil
     */
    
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    /**
     * @Route accès Profil ('/profil', nom app_profil) 
     * @Login obligatoire ROLE_USER
     */

    #[Route('/profil', name: 'app_profil')]
    #[IsGranted('ROLE_USER')] // Appliquer la restriction de rôle ici
    public function profil(): Response
    {
        // Utilisez 'getUser()' pour accéder à l'utilisateur connecté dans le template
        return $this->render('accueil/profil.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}