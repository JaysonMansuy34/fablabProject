<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * 
     * @Route("/login", name="app_longin")
     * 
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {   
        /**
         *  Condition de redirection si User est déjà connecté au cas ou qu'il accèdes à la page login
         */
        
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profil');
        }

        // Obtenir le message d'erreur de connexion s'il y en a un
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
             'error' => $error
            ]);
    }

    /**
     * 
     * @Route("/logout", name="app_logout")
     * La route permet de se deconnecter gérée directement par Symfony
     */
    
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}