<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{   
    /**
     * @Route Accueil (' / ', nom admin_)
     * Page D'admin
     */
    
    #[Route('/admin', name: 'admin_')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/machine', name: 'machine_')]
    public function machine(): Response
    {
        return $this->render('admin/machine.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}