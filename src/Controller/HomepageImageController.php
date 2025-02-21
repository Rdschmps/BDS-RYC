<?php

namespace App\Controller;

use App\Entity\HomepageImage;
use App\Form\HomepageImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/homepage')]
class HomepageImageController extends AbstractController
{
    #[Route('/edit', name: 'admin_homepage_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $homepageImage = $entityManager->getRepository(HomepageImage::class)->find(1);
        if (!$homepageImage) {
            $homepageImage = new HomepageImage();
        }

        $form = $this->createForm(HomepageImageType::class, $homepageImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image1File = $form->get('image1')->getData();
            $image2File = $form->get('image2')->getData();

            if ($image1File) {
                $newFilename1 = uniqid() . '.' . $image1File->guessExtension();
                try {
                    $image1File->move($this->getParameter('homepage_images_directory'), $newFilename1);
                    $homepageImage->setImage1($newFilename1);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image 1.');
                }
            }

            if ($image2File) {
                $newFilename2 = uniqid() . '.' . $image2File->guessExtension();
                try {
                    $image2File->move($this->getParameter('homepage_images_directory'), $newFilename2);
                    $homepageImage->setImage2($newFilename2);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image 2.');
                }
            }

            $entityManager->persist($homepageImage);
            $entityManager->flush();

            $this->addFlash('success', 'Images mises à jour avec succès.');
            return $this->redirectToRoute('admin_homepage_edit');
        }

        return $this->render('admin/homepage_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
