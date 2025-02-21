<?php

namespace App\Controller;

use App\Entity\HomepageImage;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupération des images de la page d'accueil
        $homepageImages = $entityManager->getRepository(HomepageImage::class)->find(1);

        // Récupération des articles depuis la base de données
        $articles = $entityManager->getRepository(Article::class)
        ->findBy([], ['id' => 'DESC'], 5); 
    
        return $this->render('base.html.twig', [
            'homepageImages' => $homepageImages,
            'articles' => $articles 
        ]);
    }
}
