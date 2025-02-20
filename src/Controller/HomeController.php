<?php

namespace App\Controller;

use App\Entity\HomepageImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $homepageImages = $entityManager->getRepository(HomepageImage::class)->find(1);

        return $this->render('base.html.twig', [
            'homepageImages' => $homepageImages
        ]);
    }
}
