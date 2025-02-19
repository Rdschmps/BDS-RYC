<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//#[IsGranted('ROLE_USER')] // 🔒 Seuls les utilisateurs avec ROLE_USER peuvent y accéder
class UserDashboardController extends AbstractController
{
    #[Route('/user-dashboard', name: 'app_user_dashboard')]
    public function index(): Response
    {
        return $this->render('user_dashboard/index.html.twig');
    }
}
