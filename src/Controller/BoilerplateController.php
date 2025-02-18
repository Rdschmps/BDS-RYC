<?php
// src/Controller/BoilerplateController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BoilerplateController extends AbstractController
{
    #[Route('/boilerplate')]
    public function number(): Response
    {
        return $this->render('boilerplate/boilerplate.html.twig');
    }
}
